<?php

namespace Alexain\UxGridjs;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class UxGridjsBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Registra i servizi del bundle (GridFactory, Twig extension, ecc.)
        $container->import(__DIR__ . '/Resources/config/services.yaml');
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (!class_exists(\Symfony\Component\AssetMapper\AssetMapper::class)) {
            return;
        }

        $builder->prependExtensionConfig('framework', [
            'asset_mapper' => [
                'paths' => [
                    __DIR__ . '/../assets' => '@alexain/ux-gridjs',
                ],
            ],
        ]);
    }
}
