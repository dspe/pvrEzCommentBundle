<?php

namespace pvr\EzCommentBundle\DependencyInjection;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PvrEzCommentExtension extends Extension implements PrependExtensionInterface
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

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // make sure Assetic can handle the assets
        $container->prependExtensionConfig('assetic', ['bundles' => ['PvrEzCommentBundle']]);

        $this->prependYui( $container );
        $this->prependCss( $container );
    }

    public function prependYui( ContainerBuilder $container )
    {
        $container->setParameter('pvr_ezcomment.public_dir', 'bundles/pvrezcomment');

        $yuiConfigFile = __DIR__ . '/../Resources/config/yui.yml';
        $config = Yaml::parse( file_get_contents( $yuiConfigFile) );
        $container->prependExtensionConfig( 'ez_platformui', $config );
        $container->addResource( new FileResource( $yuiConfigFile ) );
    }

    public function prependCss( ContainerBuilder $container )
    {
        $container->setParameter('pvr_ezcomment.css_dir', 'bundles/pvrezcomment/css');

        $cssConfigFile = __DIR__ . '/../Resources/config/css.yml';
        $config = Yaml::parse( file_get_contents( $cssConfigFile ) );
        $container->prependExtensionConfig( 'ez_platformui', $config );
        $container->addResource( new FileResource( $cssConfigFile ) );
    }
}
