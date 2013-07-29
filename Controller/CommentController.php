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
     * @param $contentId id from current content
     * @return Response
     */
    public function getCommentsAction( $contentId )
    {
        $pvrEzCommentManager = $this->container->get( 'pvr_ezcomment.manager' );
        $connection = $this->container->get( 'ezpublish.connection' );

        $comments = $pvrEzCommentManager->getComments( $connection, $contentId );

        return $this->render(
            'pvrEzCommentBundle:blog:list_comments.html.twig',
            array(
                'comments'  => $comments,
                'contentId' => $contentId
            )
        );
    }

    /**
     * This function get comment form depends of configuration
     *
     * @param $contentId id of content
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getFormCommentAction( $contentId )
    {
        $pvrEzCommentManager = $this->container->get( 'pvr_ezcomment.manager' );
        if ( $pvrEzCommentManager->hasAnonymousAccess() )
        {
            $form = $pvrEzCommentManager->createAnonymousForm();
        }
        else
        {
            $form = $pvrEzCommentManager->createUserForm();
        }

        return $this->render(
            'pvrEzCommentBundle:blog:form_comments.html.twig',
            array(
                'form' => $form->createView(),
                'contentId' => $contentId
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
                    $pvrEzCommentManager->addAnonymousComment(
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

                    $pvrEzCommentManager->addComment(
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
                        $this->getRequest()->getSession()->getId()
                    );
                    $response = new Response( 'Your comment should be moderate before publishing' );
                    return $response;
                }
                else
                {
                    $response = new Response( 'Your comment has been added correctly' );
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
        return new Response( 'Something goes wrong !', 400 );
    }

    /**
     * After receiving email choose if you would like to approve it or not
     *
     * @param $contentId id of content
     * @param $sessionHash hash session do decrypt for transaction
     * @param $action approve|reject value
     * @return Response
     */
    public function commentModerateAction( $contentId, $sessionHash, $action )
    {
        $pvrEzCommentManager = $this->container->get( 'pvr_ezcomment.manager' );
        $connection = $this->container->get( 'ezpublish.connection' );

        // Check if comment has waiting status..
        $commentId = $pvrEzCommentManager->canUpdate( $contentId, $sessionHash, $connection );

        if ( $commentId )
        {
            if ( $action == "approve" )
            {
                // Update status
                if ( $pvrEzCommentManager->updateStatus( $connection, $commentId ) )
                {
                    return new Response( "Comment publish :)" );
                }
            }
            else
            {
                // Update status
                if ( $pvrEzCommentManager->updateStatus( $connection, $commentId, $pvrEzCommentManager::COMMENT_REJECTED ) )
                {
                    return new Response( "Comment rejected :(" );
                }
            }

        }
        return new Response( "An unexpected error has occurred. please contact the webmaster :(", 406 );
    }

}
