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
                ->arrayNode('promotions')
                    ->prototype('array')
                        ->children()
                            // If 3 for the price of 2, then this number would be set to 3
                            // Buy one get one free, this would be 2
                            ->integerNode('apply_discount_on')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->min(2)
                                ->end()
                            // Which category of products to apply the discount for
                            ->scalarNode('category')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->end()
                            // Is the cheapest item free or half-price
                            ->enumNode('type')
                                ->cannotBeEmpty()
                                ->values(array('CHEAPEST_FREE', 'CHEAPEST_HALF_PRICE'))
                                ->defaultValue('CHEAPEST_FREE')
                                ->end()
                            // Enable the promotion
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
