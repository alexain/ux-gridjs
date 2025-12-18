# UX GridJs

Symfony bundle to integrate [Grid.js](https://gridjs.io/) tables with Symfony 7.x/8.x using AssetMapper + Stimulus.
Turbo Drive is optional.

Grid.js is MIT-licensed. See Credits section.

## Requirements

- PHP >= 8.2
- Symfony 7.x/8.0
- symfony/asset-mapper (AssetMapper / importmap)
- symfony/stimulus-bundle (Stimulus)

Turbo Drive is optional

## Installation

Install the bundle:

```console
composer require alexain/ux-gridjs
```

Install Grid.js via importmap in the host Symfony app:

php bin/console importmap:require gridjs
php bin/console importmap:install


importmap:require is the recommended way to add third-party JS packages with AssetMapper.
Symfony

Register/use the Stimulus controller grid_controller.js shipped by this bundle.

## Usage:

Twig rendering:

```console
{{ gridjs_render(grid) }}
```

Where grid is the array produced by GridPresenter::toArray().

Backend-driven toolbar

Configure toolbar buttons from Symfony (no Twig overrides).

## Credits & Licenses

Grid.js: MIT license

© 2025 - Alessandro Capano