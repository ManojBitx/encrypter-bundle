<?php

namespace ManojX\EncrypterBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class EncrypterBundle extends AbstractBundle
{
    /**
     * Configures the bundle's configuration.
     *
     * @param DefinitionConfigurator $definition The definition configurator to set up the configuration structure.
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        // Define the root node of the configuration
        $definition->rootNode()
            ->children()
            // Define a required scalar node for the secret
            ->scalarNode('secret')->isRequired()->end()
            ->end();
    }

    /**
     * Loads the bundle's services into the container.
     *
     * @param array $config The configuration array.
     * @param ContainerConfigurator $container The container configurator.
     * @param ContainerBuilder $builder The container builder.
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Import services from the YAML configuration file
        $container->import('../config/services.yaml');

        // Set the secret argument for the encrypter service
        $container->services()
            ->get('manojx.encrypter')
            ->arg('$secret', $config['secret']);
    }
}
