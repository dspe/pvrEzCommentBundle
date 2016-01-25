<?php

/*
 * This file is part of the pvrEzComment package.
 *
 * (c) Philippe Vincent-Royol <vincent.royol@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace pvr\EzCommentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root( 'pvr_ez_comment' );

        $rootNode
            ->children()
                ->booleanNode( 'anonymous' )
                    ->defaultFalse()
                ->end()
                ->booleanNode( 'moderating' )
                    ->defaultFalse()
                ->end()
                ->booleanNode( 'comment_reply' )
                    ->defaultFalse()
                ->end()
                ->arrayNode( 'moderate_mail' )
                    ->children()
                        ->scalarNode( 'subject' )
                            ->defaultValue( 'New Notification' )
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode( 'from' )
                            ->defaultValue( 'noreply@example.com' )
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode( 'to' )
                            ->cannotBeEmpty()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode( 'template' )
                            ->defaultValue( 'PvrEzCommentBundle:mail:email_moderate.txt.twig' )
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode( 'notify_mail' )
                    ->children()
                        ->booleanNode( 'enabled' )
                            ->defaultFalse()
                        ->end()
                        ->scalarNode( 'subject' )
                            ->defaultValue( 'Your comment' )
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode( 'from' )
                            ->defaultValue('noreply@example.com')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode( 'template' )
                            ->defaultValue( 'PvrEzCommentBundle:mail:email.txt.twig' )
                            ->cannotBeEmpty()
                        ->end()
                     ->end()
                ->end()
            ->end();




        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
