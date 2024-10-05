<?php

namespace ManojX\EncrypterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('encrypter');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode->children()
            ->scalarNode('secret')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
