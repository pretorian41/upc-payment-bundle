<?php

namespace UPCPaymentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use UPCPaymentBundle\Service\ConnectionsConfig;
use UPCPaymentBundle\Service\ConnectionsConfigInterface;

/**
 * Class UPCPaymentExtension
 */
class UPCPaymentExtension extends ConfigurableExtension
{
    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $config = new Definition(
            ConnectionsConfig::class,
            [
                $mergedConfig['keys']['connection_key'],
                $mergedConfig['keys']['signature_key'],
                $mergedConfig['configs']['payment_host'],
                [
                    'Version' => $mergedConfig['configs']['version'],
                    'MerchantID' => $mergedConfig['configs']['merchant_id'],
                    'TerminalID' => $mergedConfig['configs']['terminal_id'],
                    'Currency' => $mergedConfig['configs']['currency'],
                ],
                $mergedConfig['keys']['key_in_file'],
            ]
        );
        $container->setDefinition(ConnectionsConfigInterface::class, $config);

        $loader->load('sevices.yaml');
    }
}
