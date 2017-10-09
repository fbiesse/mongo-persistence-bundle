<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Configuration\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('f_biesse_sf_bundle_mongo_persistence');
        $rootNode
            ->children()
                ->scalarNode('server_uri')
                    ->isRequired()
                ->end()
                ->enumNode('authmechanism')
                    ->values(['SCRAM-SHA-1', 'MONGODB-CR'])
                    ->defaultValue('SCRAM-SHA-1')
                ->end()
                ->scalarNode('db_name')
                    ->isRequired()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}