<?php

namespace Videni\Casbin\Bridge\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Casbin\Enforcer;
use Symfony\Component\DependencyInjection\Definition;
use Videni\Casbin\EnforcerManager;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;

class VideniCasbinExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $this->configureEnforcers($container, $config);
    }

    protected function configureEnforcers(ContainerBuilder $container, $config)
    {
        $enforcers = [];

        foreach($config['enforcers'] as $name => $enforcerConfiguration) {
            $class = $enforcerConfiguration['class'];

            $adapterDefinition = (new Definition($class))
                ->addArgument($enforcerConfiguration['options']);

            $enforcerDef = (new Definition(Enforcer::class, [
                    $enforcerConfiguration['path'],
                    $adapterDefinition,
                ]))
                ->setPublic(true);

            $enforcerId = sprintf('videni_casbin.%s_enforcer', $name);
            $enforcers[$name] = $enforcerId;
            $container
                ->setDefinition($enforcerId, $enforcerDef);
        }

        $container->setAlias(
            'videni_casbin.default_enforcer',
            sprintf('videni_casbin.%s_enforcer', $config['default'])
        );

        if (!in_array($config['default'], array_keys($enforcers))) {
            throw new \Exception(sprintf(
                'Enforcer %s is not configured, available enforcers are %s',
                $config['default'],
                implode(',', array_keys($enforcers))
            ));
        }

        $container
            ->register('videni_casbin.enforce_manager', EnforcerManager::class)
            ->addArgument($config['default'])
            ->addArgument($enforcers)
            ->addArgument(new Reference('service_container'));

        $container->setAlias(EnforcerManager::class, 'videni_casbin.enforce_manager');
    }
}
