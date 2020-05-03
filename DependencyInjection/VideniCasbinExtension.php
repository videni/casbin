<?php

namespace Videni\Bundle\CasbinBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Casbin\Enforcer;
use Symfony\Component\DependencyInjection\Definition;
use Videni\Bundle\CasbinBundle\EnforcerManager;

class VideniCasbinExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->configureEnforcers($container, $config);
    }

    protected function configureEnforcers(ContainerBuilder $container, $config)
    {
        $enforcers = [];

        foreach($config['enforcers'] as $name => $enforcerConfiguration) {
            list($class, $path, $options) = $enforcerConfiguration;
            $adapter = new $class($options);

            $enforcerDef = (new Definition(Enforcer::class, [
                    $path,
                    $adapter,
                ]))
                ->setPublic(true);
            $enforcerId = sprintf('videni_casbin.%s_enforcer', $name);
            $enforcers[$name] = $enforcerId;
            $container->setDefinition($enforcerId, $enforcerDef);
        }

        $container->setAlias(
            'videni_casbin.default_enforcer',
            sprintf('videni_casbin.%s_enforcer', $config['default'])
        );

        $container->register('videni_casbin.enforce_manager', EnforcerManager::class)
            ->addArgument($config['default'])
            ->addArgument($enforcers)
            ->addArgument($container);
    }
}
