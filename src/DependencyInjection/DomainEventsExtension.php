<?php

declare(strict_types=1);

namespace Phauthentic\Symfony\DomainEvents\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 * @codeCoverageIgnore
 */
class DomainEventsExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $yamlLoader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $yamlLoader->load('services.yaml');
    }
}
