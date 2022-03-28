<?php

namespace Videni\Casbin\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Casbin\Persist\Adapters\FileAdapter;

class Configuration implements ConfigurationInterface
{
   /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('videni_casbin');
         /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('default')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->append($this->getEnforcersNode())
            ->end();

        return $treeBuilder;
    }

    private function getEnforcersNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('enforcers');

        $node = $treeBuilder->getRootNode();

        $enforcerNode = $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->arrayPrototype();

        $enforcerNode
            ->children()
                ->scalarNode('class')
                    ->defaultValue(FileAdapter::class)
                    ->validate()
                        ->ifTrue(function($v){
                            return !\class_exists($v);
                        })
                        ->thenInvalid('The %s adapter class doesn\'t exist')
                    ->end()
                ->end()
                ->arrayNode('options')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('path')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function($v){
                            return !\file_exists($v) || !\is_readable($v);
                        })
                        ->thenInvalid('Please make sure the model file %s exists and readable')
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
