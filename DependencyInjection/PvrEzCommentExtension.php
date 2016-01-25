<?php

namespace pvr\EzCommentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PvrEzCommentExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $config = array_merge(
            array(
                'anonymous' => false,
                'moderating' => false,
                'comment_reply' => true,
                'moderate_mail' => array(
                    'subject' => "Notify mail",
                    'from' => "no-reply@example.com",
                    'to' => "me@example.com",
                    'template' => "PvrEzCommentBundle:mail:email_moderate.txt.twig"
                ),
                'notify_mail' => array(
                    'enabled' => false,
                    'subject' => "Notify mail",
                    'from' => "noreply@example.com",
                    'template' => "pvrEzCommentbundle:mail:email.txt.twig"
                ),
            ),
            $config
        );

        $container->setParameter( 'pvr_ezcomment.config',  array( 
            "anonymous"         => $config['anonymous'],
            "moderating"        => $config['moderating'],
            "comment_reply"     => $config['comment_reply'],
            "moderate_subject"  => $config['moderate_mail']['subject'],
            "moderate_from"     => $config['moderate_mail']['from'],
            "moderate_to"       => $config['moderate_mail']['to'],
            "moderate_template" => $config['moderate_mail']['template'],
            "notify_enabled"    => $config['notify_mail']['enabled']
        ));
    }
}
