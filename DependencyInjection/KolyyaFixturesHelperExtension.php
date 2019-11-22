<?php

namespace Kolyya\FixturesHelperBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class KolyyaFixturesHelperExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['load'] as $configName => $configItem) {
            foreach ($configItem as $commandName => $attributes) {
                $config['load'][$configName][$commandName] = array_merge(
                    Configuration::$defaultConfig[$commandName],
                    $config['load'][$configName][$commandName]
                );
            }
        }

        $container->setParameter('kolyya_fixtures_helper.load', $config['load']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // FixturesLoadCommand
        $container->getDefinition('Kolyya\FixturesHelperBundle\Command\FixturesLoadCommand');

////        // UploadFileFixtures
////        $container->getDefinition('Kolyya\FixturesHelperBundle\DataFixtures\UploadFileFixtures');
//
//        $this->addAnnotatedClassesToCompile([
//            'Kolyya\FixturesHelperBundle\DataFixtures\UploadFileFixtures',
//        ]);
    }
}