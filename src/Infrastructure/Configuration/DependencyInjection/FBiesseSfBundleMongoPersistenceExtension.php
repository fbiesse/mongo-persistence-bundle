<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Configuration\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FBiesseSfBundleMongoPersistenceExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../../../Resources/config'));
        $container->setParameter('mongo.server_uri', $config['server_uri']);
        $container->setParameter('mongo.authmechanism', $config['authmechanism']);
        $container->setParameter('mongo.db_name', $config['db_name']);
        $loader->load('mongo.xml');
    }
}