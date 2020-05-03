<?php

namespace Videni\Bundle\CasbinBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('videni_casbin')
            ->children()
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
            ->prototype('array');

        $enforcerNode
            ->children()
                ->scalarNode('class')
                    ->default(FileAdapter::class)
                    ->validate()
                        ->ifFalse(function($v){
                            return \class_exists($v);
                        })
                        ->thenInvalid('The %s adapter class doesn\'t exist')
                    ->end()
                ->end()
                ->arrayNode('options')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('model_path')
                    ->isRequired()
                    ->validate()
                        ->ifFalse(function($v){
                            return \file_exists($v) || \is_readable($v);
                        })
                        ->thenInvalid('Please make sure the model file %s exists and readable')
                    ->end()
                ->end()
            ->end();

        return $enforcerNode;
    }
}
