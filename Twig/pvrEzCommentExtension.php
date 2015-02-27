<?php

namespace pvr\EzCommentBundle\Twig;

use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use pvr\EzCommentBundle\Comment\pvrEzCommentManager;

class pvrEzCommentExtension extends \Twig_Extension
{
    protected $commentManager;
    protected $handler;

    public function __construct( pvrEzCommentManager $commentManager, EzcDbHandler $handler )
    {
        $this->commentManager = $commentManager;
        $this->handler = $handler;
    }

    public function getFilters()
    {
        return array(
            'getCountComments' => new \Twig_Filter_Method( $this, 'countCommentsFilter' ),
        );
    }

    public function countCommentsFilter( $contentId )
    {
        return $this->commentManager->getCountComments( $contentId, $this->handler );
    }

    public function getName()
    {
        return 'pvrezcomment_extension';
    }
}

