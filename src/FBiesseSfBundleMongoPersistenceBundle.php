<?php
namespace FBiesse\Sf\Bundle\MongoPersistenceBundle;

use FBiesse\Sf\Bundle\MongoPersistenceBundle\Infrastructure\Configuration\DependencyInjection\MongoDbProfilingCompilerPass;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FBiesseSfBundleMongoPersistenceBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MongoDbProfilingCompilerPass());
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $basename = preg_replace('/Bundle$/', '', $this->getName());
            $class = $this->getNamespace() . '\\Infrastructure\\Configuration\\DependencyInjection\\' . $basename . 'Extension';
            if (class_exists($class)) {
                $extension = new $class();
                // check naming convention
                $expectedAlias = Container::underscore($basename);
                if ($expectedAlias != $extension->getAlias()) {
                    throw new \LogicException(sprintf('The extension alias for the default extension of a ' . 'bundle must be the underscored version of the ' . 'bundle name ("%s" instead of "%s")', $expectedAlias, $extension->getAlias()));
                }
                $this->extension = $extension;
            } else {
                $this->extension = false;
            }
        }
        if ($this->extension) {
            return $this->extension;
        }
    }

}