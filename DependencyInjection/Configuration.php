<?php

namespace Kolyya\FixturesHelperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

class Configuration implements ConfigurationInterface
{
    const PREFIX = 'kolyya_fixtures_helper';
    const DEFAULT_NAME = 'default';

    public static $defaultConfig = [
        'drop' => [
            '--connection' => self::DEFAULT_NAME,
//            '--if-exists' => true,
            '--force' => true,
        ],
        'create' => [
            '--connection' => self::DEFAULT_NAME,
        ],
        'update' => [
            '--em' => self::DEFAULT_NAME,
            '--force' => true,
        ],
        'load' => [
            '--em' => self::DEFAULT_NAME,
            '--append' => true,
        ],
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (Kernel::VERSION_ID >= 40200) {
            $treeBuilder = new TreeBuilder(self::PREFIX);
            $root = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $root = $treeBuilder->root(self::PREFIX);
        }

        $this->addLoadSection($root);

        return $treeBuilder;
    }

    protected function addLoadSection(ArrayNodeDefinition $node): void
    {
        $node->children()
            ->arrayNode('load')
            ->defaultValue([self::DEFAULT_NAME => self::$defaultConfig])
            ->info('load configs')
            ->useAttributeAsKey('id')
            ->prototype('array')
            ->children()
            ->arrayNode('drop')->defaultValue(self::$defaultConfig['drop'])->normalizeKeys(false)->scalarPrototype()->end()->end()
            ->arrayNode('create')->defaultValue(self::$defaultConfig['create'])->normalizeKeys(false)->scalarPrototype()->end()->end()
            ->arrayNode('update')->defaultValue(self::$defaultConfig['update'])->normalizeKeys(false)->scalarPrototype()->end()->end()
            ->arrayNode('load')->defaultValue(self::$defaultConfig['load'])->normalizeKeys(false)->scalarPrototype()->end()->end()
            ->end()
            ->end();
    }
}