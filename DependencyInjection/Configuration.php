<?php
namespace Wa72\AdaptimageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('wa72_adaptimage');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('classes')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('widths')->scalarPrototype()->end()->end()
                            ->scalarNode('sizes_attribute')->end()
                        ->end() // children
                    ->end() // arrayPrototype
                ->end() // classes
            ->end() // children
        ;

        return $treeBuilder;
    }
}