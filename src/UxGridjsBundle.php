<?php

namespace Alexain\UxGridjs;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class UxGridjsBundle extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (!class_exists(\Symfony\Component\AssetMapper\AssetMapper::class)) {
            return;
        }


        $builder->prependExtensionConfig('framework', [
            'asset_mapper' => [
                'paths' => [
                    __DIR__.'/../assets' => '@alexain/ux-gridjs',
                ],
            ],
        ]);
    }
}
