<?php

/*
 * This file is part of the pvrEzComment package.
 *
 * (c) Philippe Vincent-Royol <vincent.royol@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace pvr\EzCommentBundle\Comment;

use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\Core\Repository\Values\User\User as EzUser;
use eZ\Publish\Core\MVC\Symfony\Locale\LocaleConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Form\Form;

interface pvrEzCommentManagerInterface
{
    public function __construct( $anonymous_access = false, $moderating = false,
                                 $moderate_subject, $moderate_from, $moderate_to, $moderate_template,
                                 $isNotify,
                                 ContainerInterface $container );

    /**
     * @param $connection Get connection to eZ Publish Database
     * @param $contentId Get content Id to fetch comments
     * @param int $status
     * @return mixed Array or false
     * @throws \Exception
     */
    public function getComments( $connection, $contentId, $status = self::COMMENT_ACCEPT );

    /**
     * @param $connection
     * @param Request $request
     * @param EzUser $currentUser
     * @param LocaleConverter $localeService
     * @param array $data
     * @param null $contentId
     * @throws \InvalidArgumentException
     */
    public function addComment( $connection, Request $request, EzUser $currentUser,
                                LocaleConverter $localeService, $data = array(), $contentId = null );

    public function addAnonymousComment( $connection, Request $request, LocaleConverter $localeService,
                                         array $data, $contentId );

    public function createAnonymousForm();

    public function createUserForm();

    /**
     * Get validation error from form
     *
     * @param \Symfony\Component\Form\Form $form the form
     * @return array errors messages
     */
    public function getErrorMessages( Form $form );

    /**
     * Send message to admin(s)
     */
    public function sendMessage( $data, $user, $contentId, $sessionId, $commentId );

    public function canUpdate( $contentId, $sessionHash, $connection, $commentId );

    public function updateStatus( $connection, $commentId, $status = self::COMMENT_ACCEPT );

    /**
     * @return bool
     */
    public function hasAnonymousAccess();

    /**
     * @return bool
     */
    public function hasModeration();
}