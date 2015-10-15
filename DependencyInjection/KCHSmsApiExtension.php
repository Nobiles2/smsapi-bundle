<?php

namespace KCH\Bundle\SmsApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KCHSmsApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Services registration
        if(count($config['clients'])) {
            foreach($config['clients'] as $clientName => $clientData) {
                $service = $container->setDefinition(
                    sprintf('kch_sms_api.client.%s', $clientName),
                    new Definition(
                        '%kch_sms_api.client.class%',
                        array($clientData['client_login'])
                    )
                )->addMethodCall('setPasswordHash', array($clientData['client_password_hash']));

                $this->registerFactories($container, $service, $clientName);
            }
        }
    }

    /**
     * Method register all library factories for Client.
     *
     * @param ContainerBuilder $container
     * @param Definition $clientService
     * @param $clientName
     */
    private function registerFactories(ContainerBuilder $container, Definition $clientService, $clientName)
    {
        // SmsFactory
        $container->setDefinition(
            sprintf('kch_sms_api.sms_factory.%s', $clientName),
            new Definition('%kch_sms_api.sms_factory.class%')
        )->addMethodCall('setClient', array($clientService));

        // MmsFactory
        $container->setDefinition(
            sprintf('kch_sms_api.mms_factory.%s', $clientName),
            new Definition('%kch_sms_api.mms_factory.class%')
        )->addMethodCall('setClient', array($clientService));

        // VmsFactory
        $container->setDefinition(
            sprintf('kch_sms_api.vms_factory.%s', $clientName),
            new Definition('%kch_sms_api.vms_factory.class%')
        )->addMethodCall('setClient', array($clientService));

        // SenderFactory
        $container->setDefinition(
            sprintf('kch_sms_api.sender_factory.%s', $clientName),
            new Definition('%kch_sms_api.sender_factory.class%')
        )->addMethodCall('setClient', array($clientService));

        // ContactsFactory
        $container->setDefinition(
            sprintf('kch_sms_api.contacts_factory.%s', $clientName),
            new Definition('%kch_sms_api.contacts_factory.class%')
        )->addMethodCall('setClient', array($clientService));
    }
}
