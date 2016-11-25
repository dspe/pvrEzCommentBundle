<?php

namespace pvr\EzCommentBundle;

use pvr\EzCommentBundle\Security\CommentProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PvrEzCommentBundle extends Bundle
{
    protected $name = 'PvrEzCommentBundle';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $pvrEzCommentExtension = $container->getExtension('ezpublish');
        $pvrEzCommentExtension->addPolicyProvider(new CommentProvider());
    }
}
