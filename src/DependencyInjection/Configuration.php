<?php

namespace FeelUnique\Ordering\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('feelunique_ordering');
        $rootNode
            ->children()
                ->arrayNode('offers')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('offer')
                                ->cannotBeEmpty()
                                ->end()
                            ->booleanNode('enabled')
                                ->defaultTrue()
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
