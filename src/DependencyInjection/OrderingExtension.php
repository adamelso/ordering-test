<?php

namespace FeelUnique\Ordering\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class OrderingExtension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'feelunique_ordering';
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('offers', $config['offers']);
    }

    /**
     * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'http://feelunique.com/schema/dic/'.$this->getAlias();
    }
}
