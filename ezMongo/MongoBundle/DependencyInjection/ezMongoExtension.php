<?php

namespace ezMongo\MongoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ezMongoExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Connection Factory
        $factoryDetinition = new Definition('ezMongo\MongoBundle\Connection\ConnectionFactory');
        // Manager
        $managerDefinition = new Definition('ezMongo\MongoBundle\Manager\MongoManager');
        $managerDefinition->addMethodCall('setConnectionFactory', array($factoryDetinition));
        $managerDefinition->addMethodCall('setConfig', array($config));
        $managerDefinition->addMethodCall('setBundleAliases', array($container->getParameter('kernel.bundles')));
        $container->setDefinition('ez.mongo.manager', $managerDefinition);
    }
}
