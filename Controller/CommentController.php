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
    public function getCommentsAction( $contentId, $locationId, $params = array() )
    {
        $response = new Response();
        $response->setMaxAge( $this->container->getParameter( 'pvr_ezcomment.maxage' ) );
        $response->headers->set( 'X-Location-Id', $locationId );

        $data = $this->container->get( 'pvr_ezcomment.service' )->getComments( $locationId, $contentId );
        $data += array( 'params' => $params );

        $template = isset( $params['template'] ) ? $params['template'] : 'pvrEzCommentBundle:blog:list_comments.html.twig';

        return $this->render( $template, $data, $response );
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
        $form = $this->container->get( 'pvr_ezcomment.service' )->generateForm();

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
        if ( $request->isXmlHttpRequest() ) {
            return $this->container->get( 'pvr_ezcomment.service' )->addComments( $contentId );
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
