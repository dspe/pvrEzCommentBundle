<?php


namespace pvr\EzCommentBundle\Twig;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use pvr\EzCommentBundle\Comment\PvrEzCommentManager;

class PvrEzCommentExtension extends \Twig_Extension
{
    /**
     * @var PvrEzCommentManager
     */
    protected $commentManager;
    /**
     * @var ContentService
     */
    protected $contentService;
    /**
     * @var EzcDbHandler
     */
    protected $handler;
    /**
     * @var LocationService
     */
    protected $locationService;
    /**
     * @var \eZ\Publish\Core\Helper\TranslationHelper
     */
    protected $translationHelper;

    public function __construct( PvrEzCommentManager $commentManager, EzcDbHandler $handler,
                                 ContentService $contentService, TranslationHelper $translationHelper,
                                 LocationService $locationService )
    {
        $this->commentManager    = $commentManager;
        $this->contentService    = $contentService;
        $this->handler           = $handler;
        $this->locationService   = $locationService;
        $this->translationHelper = $translationHelper;
    }

    /**
     * Create filters for Twig templates
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'getCountComments' => new \Twig_Filter_Method( $this, 'countCommentsFilter' ),
            'getContentName' => new \Twig_Filter_Method( $this, 'getContentName' ),
            'getMainLocation' => new \Twig_Filter_Method( $this, 'getMainLocation' ),
        );
    }

    /**
     * Get number of comments on a content
     *
     * @param $contentId
     * @return int
     */
    public function countCommentsFilter( $contentId )
    {
        return $this->commentManager->getCountComments( $contentId, $this->handler );
    }

    /**
     * Get the content name
     *
     * @param $contentId
     * @return string
     */
    public function getContentName( $contentId )
    {
        $contentInfo = $this->contentService->loadContentInfo( $contentId );
        return $this->translationHelper->getTranslatedContentNameByContentInfo( $contentInfo );
    }

    /**
     * Get the main location Id
     *
     * @param $contentId
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     */
    public function getMainLocation( $contentId )
    {
        $mainLocationId = $this->contentService->loadContentInfo( $contentId )->mainLocationId;
        return $this->locationService->loadLocation( $mainLocationId );
    }

    public function getName()
    {
        return 'pvrezcomment_extension';
    }
}