<?php

namespace pvr\EzCommentBundle\Rest\Controller;

use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\Core\REST\Server\Controller as BaseController;
use eZ\Publish\Core\REST\Server\Values;
use eZ\Publish\Core\REST\Common\Exceptions;
use pvr\EzCommentBundle\Comment\PvrEzCommentManager;

class CommentController extends BaseController
{
    /**
     * Delete a comment from Platform UI
     *
     * @param $commentId
     * @return Exceptions\NotFoundException|Values\NoContent
     */
    public function delete( $commentId )
    {
        $manager = $this->container->get( 'pvr_ezcomment.manager' );
        try {
            $manager->deleteById( $commentId, $this->container->get( 'ezpublish.connection' ) );
        } catch (NotFoundException $e) {
            return new Exceptions\NotFoundException($e->getMessage());
        }
        return new Values\NoContent();
    }

    /**
     * Update a status on a comment from Platform UI
     *
     * @param $commentId
     * @param $statusId
     * @return Exceptions\NotFoundException|Values\NoContent
     */
    public function status( $commentId, $statusId )
    {
        $manager = $this->container->get( 'pvr_ezcomment.manager' );
        try {
            $connection = $this->container->get( 'ezpublish.connection' );
            if (
                $manager->commentExists($commentId, $connection) &&
                in_array($statusId, [PvrEzCommentManager::COMMENT_WAITING, PvrEzCommentManager::COMMENT_ACCEPT, PvrEzCommentManager::COMMENT_REJECTED] )
            ) {
                $manager->updateStatusFromUI( $connection, $commentId, $statusId );
            } else {
                return new Exceptions\NotFoundException("Comment or Status wasn't found on server !");
            }
        } catch (NotFoundException $e) {
            return new Exceptions\NotFoundException($e->getMessage());
        }
        return new Values\NoContent();
    }
}
