<?php


namespace FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Configuration\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MongoDbProfilingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition("profiler")) {
            $container->setAlias("database", "database.real");
        }else{
            $container->setAlias("database", "database.profiling");
        }
    }
}