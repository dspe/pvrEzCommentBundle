<?php

/*
 * This file is part of the pvrEzComment package.
 *
 * (c) Philippe Vincent-Royol <vincent.royol@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace pvr\EzCommentBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class CommentController extends Controller
{
    /**
     * List comments from a certain contentId
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $contentId id from current content
     * @param $locationId
     * @param array $params
     * @return Response
     */
    public function getCommentsAction( Request $request, $contentId, $locationId, $params = array() )
    {
        $response = new Response();
        $response->setMaxAge( $this->container->getParameter( 'pvr_ezcomment.maxage' ) );
        $response->headers->set( 'X-Location-Id', $locationId );

        $pvrEzCommentManager = $this->container->get( 'pvr_ezcomment.manager' );
        $connection = $this->container->get( 'ezpublish.connection' );

        $viewParameters = $request->get( 'viewParameters' );
        $comments = $pvrEzCommentManager->getComments( $connection, $contentId, $viewParameters );

        $template = isset( $params['template'] ) ? $params['template'] : 'pvrEzCommentBundle:blog:list_comments.html.twig';

        return $this->render(
            $template,
            array(
                'comments'  => $comments,
                'contentId' => $contentId,
                'params'    => $params
            ),
            $response
        );
    }

    /**
     * This function get comment form depends of configuration
     *
     * @param $contentId id of content
     * @param array $params
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getFormCommentAction( $contentId, $params = array() )
    {
        $pvrEzCommentManager = $this->container->get( 'pvr_ezcomment.manager' );

        // Case: configuration set to anonymous
        if ( $pvrEzCommentManager->hasAnonymousAccess() )
        {
            // if user is connected
            if ( $this->get( 'security.context' )->getToken()->isAuthenticated() )
            {
                $form = $pvrEzCommentManager->createUserForm();
            }
            else
            {
                // else
                $form = $pvrEzCommentManager->createAnonymousForm();
            }
        }
        // Case: Configuration set to connected user
        else
        {
            // If user has right to add comment
            if ( $this->getRepository()->hasAccess( 'comment', 'add' ) )
            {
                $form = $pvrEzCommentManager->createUserForm();
            }
            else
            {
                $form = null;
            }
        }

        $template = isset( $params['template'] ) ? $params['template'] : 'pvrEzCommentBundle:blog:form_comments.html.twig';

        return $this->render(
            $template,
            array(
                'form' => $form ? $form->createView() : null,
                'contentId' => $contentId,
                'params'    => $params
            )
        );
    }

    /**
     * Add a comment via ajax call
     *
     * @param Request $request
     * @param $contentId id of content to insert comment
     * @return Response
     */
    public function addCommentAction( Request $request, $contentId )
    {
        $pvrEzCommentManager = $this->container->get( 'pvr_ezcomment.manager' );
        if ( $request->isXmlHttpRequest() )
        {
            // Check if user is anonymous or not and generate correct form
            $isAnonymous = false;
            if ( $pvrEzCommentManager->hasAnonymousAccess() )
            {
                $form = $pvrEzCommentManager->createAnonymousForm();
                $isAnonymous = true;
            }
            else
            {
                $form = $pvrEzCommentManager->createUserForm();
            }

            $form->bind( $request );
            if ( $form->isValid() )
            {
                $connection = $this->container->get( 'ezpublish.connection' );
                $localeService = $this->container->get( 'ezpublish.locale.converter' );

                // Save data depending of user (anonymous or ezuser)
                if ( $isAnonymous )
                {
                    $commentId = $pvrEzCommentManager->addAnonymousComment(
                        $connection,
                        $request,
                        $localeService,
                        $form->getData(),
                        $contentId,
                        $this->getRequest()->getSession()->getId()
                    );
                }
                else
                {
                    $currentUser = $this->getRepository()->getCurrentUser();

                    $commentId = $pvrEzCommentManager->addComment(
                        $connection,
                        $request,
                        $currentUser,
                        $localeService,
                        $form->getData(),
                        $contentId,
                        $this->getRequest()->getSession()->getId()
                    );
                }

                // Check if you need to moderate comment or not
                if ( $pvrEzCommentManager->hasModeration() )
                {
                    if (!isset( $currentUser )) $currentUser = null;

                    $pvrEzCommentManager->sendMessage(
                        $form->getData(),
                        $currentUser,
                        $contentId,
                        $this->getRequest()->getSession()->getId(),
                        $commentId
                    );
                    $response = new Response(
                        $this->container->get( 'translator' )->trans( 'Your comment should be moderate before publishing' )
                    );
                    return $response;
                }
                else
                {
                    $response = new Response(
                        $this->container->get( 'translator' )->trans( 'Your comment has been added correctly' )
                    );
                    return $response;
                }
            }
            else
            {
                $errors = $pvrEzCommentManager->getErrorMessages( $form );

                $response = new Response( json_encode( $errors ), 406 );
                $response->headers->set( 'Content-Type', 'application/json' );
                return $response;
            }
        }
        return new Response(
            $this->container->get( 'translator' )->trans( 'Something goes wrong !' ), 400
        );
    }

    /**
     * After receiving email choose if you would like to approve it or not
     *
     * @param $contentId id of content
     * @param $sessionHash hash session do decrypt for transaction
     * @param $action approve|reject value
     * @return Response
     */
    public function commentModerateAction( $contentId, $sessionHash, $action, $commentId )
    {
        $pvrEzCommentManager = $this->container->get( 'pvr_ezcomment.manager' );
        $connection = $this->container->get( 'ezpublish.connection' );

        // Check if comment has waiting status..
        $canUpdate = $pvrEzCommentManager->canUpdate( $contentId, $sessionHash, $connection, $commentId );

        if ( $canUpdate )
        {
            if ( $action == "approve" )
            {
                // Update status
                if ( $pvrEzCommentManager->updateStatus( $connection, $commentId ) )
                {
                    return new Response(
                        $this->container->get( 'translator' )->trans( "Comment publish !" )
                    );
                }
            }
            else
            {
                // Update status
                if ( $pvrEzCommentManager->updateStatus( $connection, $commentId, $pvrEzCommentManager::COMMENT_REJECTED ) )
                {
                    return new Response(
                        $this->container->get( 'translator' )->trans( "Comment rejected !" )
                    );
                }
            }

        }
        return new Response(
            $this->container->get( 'translator' )
                ->trans( "An unexpected error has occurred, please contact the webmaster !" ),
            406
        );
    }

}
