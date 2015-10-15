<?php

namespace KCH\Bundle\SmsApiBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kch_sms_api');

        // Config tree
        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('client_login')->end()
                            ->scalarNode('client_password_hash')->end()
                        ->end()
                    ->end() // prototype
                ->end()// sms_factories
            ->end()
        ;

        return $treeBuilder;
    }
}
