<?php

namespace UPCPaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use UPCPaymentBundle\Service\UPCPayService;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('upc_payment');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
            ->arrayNode('configs')->isRequired()
            ->children()
            ->scalarNode('payment_host')->isRequired()->end()
            ->scalarNode('merchant_id')->isRequired()->end()
            ->scalarNode('terminal_id')->isRequired()->end()
            ->scalarNode('version')->defaultValue(UPCPayService::DEFAULT_VERSION)->end()
            ->scalarNode('currency')->defaultValue(UPCPayService::DEFAULT_CURRENCY)->end()
            ->end()
            ->end()
            ->arrayNode('keys')->isRequired()
            ->children()
            ->scalarNode('connection_key')->isRequired()->end()
            ->scalarNode('signature_key')->isRequired()->end()
            ->scalarNode('key_in_file')->defaultValue(false)->end()
            ->end()
            ->end()
//                ->arrayNode('imagine')
//                    ->children()
//                        ->booleanNode('messenger_enable')
//                            ->defaultFalse()
//                            ->info('Enables integration with messenger if set true. Allows resolve image caches in background by sending messages to MQ.')
//                        ->end()
//                        ->arrayNode('messenger_image_filters')
//                            ->prototype('scalar')->end()
//                        ->end()
//                        ->arrayNode('messenger_video_filters')
//                            ->prototype('scalar')->end()
//                        ->end()
//                    ->end()
//                ->end()
            ->end();

        return $treeBuilder;
    }
}
