<?php

namespace pvr\EzCommentBundle\Service;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\Core\MVC\Symfony\Locale\LocaleConverter;
use pvr\EzCommentBundle\Comment\PvrEzCommentManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Translation\TranslatorInterface;


class Comment
{
    protected $requestStack;
    protected $contentManager;
    protected $connection;
    protected $locale;
    protected $translator;
    protected $repository;
    protected $security;

    /**
     * @param PvrEzCommentManager $contentManager
     * @param $connection
     * @param LocaleConverter $locale
     * @param TranslatorInterface $translator
     * @param Repository $repository
     */
    public function __construct(
        PvrEzCommentManager $contentManager, $connection,
        LocaleConverter $locale, TranslatorInterface $translator,
        Repository $repository
    )
    {
        $this->contentManager = $contentManager;
        $this->connection = $connection;
        $this->locale = $locale;
        $this->translator = $translator;
        $this->repository = $repository;
    }

    /**
     * RequestStack Dependency Injection
     * @param RequestStack $request
     */
    public function setRequest( RequestStack $request )
    {
        $this->requestStack = $request;
    }

    /**
     * SecurityContext Dependency Injection
     * @param SecurityContext $security
     */
    public function setSecurity( SecurityContext $security )
    {
        $this->security = $security;
    }

    /**
     * Fetch contents from a content Id
     * @param integer $contentId
     * @return array
     */
    public function getComments( $contentId )
    {
        $viewParameters = $this->requestStack->getCurrentRequest()->attributes->get( 'viewParameters' );
        $comments = $this->contentManager->getComments( $this->connection, $contentId, $viewParameters );

        return
            array(
                'comments'  => $comments,
                'contentId' => $contentId,
                'reply'     => $this->contentManager->canReply(),

            );
    }

    /**
     * @param $contentId
     * @return Response return a json message
     */
    public function addComments( $contentId )
    {
        // Check if user is anonymous or not and generate correct form
        $isAnonymous = false;
        if ( $this->contentManager->hasAnonymousAccess() ) {
            $form = $this->contentManager->createAnonymousForm();
            $isAnonymous = true;
        } else {
            $form = $this->contentManager->createUserForm();
        }

        $form->bind( $this->requestStack->getCurrentRequest() );
        if ( $form->isValid() ) {

            // Save data depending of user (anonymous or ezuser)
            if ( $isAnonymous ) {
                $commentId = $this->contentManager->addAnonymousComment(
                    $this->connection,
                    $this->requestStack->getCurrentRequest(),
                    $this->locale,
                    $form->getData(),
                    $contentId,
                    $this->requestStack->getCurrentRequest()->getSession()->getId()
                );
            } else {
                $currentUser = $this->repository->getCurrentUser();

                $commentId = $this->contentManager->addComment(
                    $this->connection,
                    $this->requestStack->getCurrentRequest(),
                    $currentUser,
                    $this->locale,
                    $form->getData(),
                    $contentId,
                    $this->requestStack->getCurrentRequest()->getSession()->getId()
                );
            }

            // Check if you need to moderate comment or not
            if ( $this->contentManager->hasModeration() ) {
                if (!isset( $currentUser )) $currentUser = null;

                $this->contentManager->sendMessage(
                    $form->getData(),
                    $currentUser,
                    $contentId,
                    $this->requestStack->getCurrentRequest()->getSession()->getId(),
                    $commentId
                );
                $response = new Response(
                    $this->translator->trans( 'Your comment should be moderate before publishing' )
                );
                return $response;
            } else {
                $response = new Response(
                    $this->translator->trans( 'Your comment has been added correctly' )
                );
                return $response;
            }
        } else {
            $errors = $this->contentManager->getErrorMessages( $form );

            $response = new Response( json_encode( $errors ), 406 );
            $response->headers->set( 'Content-Type', 'application/json' );
            return $response;
        }
    }

    /**
     * @return mixed|null
     */
    public function generateForm()
    {
        // Case: configuration set to anonymous
        if ( $this->contentManager->hasAnonymousAccess() ) {
            // if user is connected
            if( $this->security->isGranted('IS_AUTHENTICATED_FULLY') ) {
                $form = $this->contentManager->createUserForm();
            } else {
                // else
                $form = $this->contentManager->createAnonymousForm();
            }
        }
        // Case: Configuration set to connected user
        else {
            // If user has right to add comment
            if ( $this->repository->hasAccess( 'comment', 'add' ) ) {
                $form = $this->contentManager->createUserForm();
            } else {
                $form = null;
            }
        }
        return $form;
    }
}
