# CraftCMS Laravel Mix

A [CraftCMS](https://craftcms.com/) plugin that enables the use of [Laravel Mix](https://laravel.com/docs/5.4/mix) for managing asset builds.

## Requirements

- PHP 7+
- Node.js 6+
- CraftCMS 2.5+

## Installation

### Manual

Download and install this plugin to your `CRAFT_PLUGINS_PATH`. Rename the directory to `mix` to correspond with the plugin handle. For example, if your `CRAFT_PLUGINS_PATH` is `craft/plugins`, you could run the following from the root of your project:

```bash
cd craft/plugins
curl -Lo mix.zip https://api.github.com/repos/dnunez24/craft-laravel-mix/zipball
unzip mix.zip
```

Then install the plugin from the CraftCMS administrative panel.

### Composer

If you use Composer for managing dependencies, you can install this plugin by requiring it from your `composer.json` file. This plugin's composer package type is `craft-plugin` so Composer can install it directly into your Craft plugins directory. However, before installing the dependency, you must add an extra configuration option to rename the destination directory of the plugin. For example, if your `CRAFT_PLUGINS_PATH` is `craft/plugins`, then you would add the following configuration to your `composer.json`:

```json
...
    "extra": {
        "installer-paths": {
            "craft/plugins/mix/": [
                "dnunez24/craft-laravel-mix"
            ]
        }
    }
...
```

_For more information about configuring the destination of the package during installation, see the [Composer Installers](https://github.com/composer/installers)._

Then simply require the package to ad it to your `composer.json` file and install it:

```
composer require dnunez24/craft-laravel-mix
```

Create a `package.json` file with the following contents to install Laravel Mix dependencies and configure asset build tasks.

```json
{
    "private": "true",
    "scripts": {
      "dev": "npm run development",
      "development": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
      "watch": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --watch --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
      "watch-poll": "npm run watch -- --watch-poll",
      "hot": "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --config=node_modules/laravel-mix/setup/webpack.config.js",
      "prod": "npm run production",
      "production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js"
    },
    "devDependencies": {
        "cross-env": "^5.0.0",
        "laravel-mix": "^1.0.0"
    }
}
```

Install the Node.js dependencies using `npm` or `yarn`.

```bash
npm install # OR yarn install
```

## Configuration

To demonstrate usage of the plugin, let's imagine a project with the following directory structure.

```
...
src/
  assets/
    js/
      main.js
    css/
      main.scss
public/
  js/
  css/
...
```

Create a `webpack.mix.js` file at the root of your project to configure Laravel Mix for building your assets. See the [Laravel Mix](https://laravel.com/docs/5.4/mix) documentation for configuration details and more options. Be sure to configure the `publicPath` option to point at the directory from which you will serve static assets (images, fonts, javascript and CSS). Here's an example configuration as a starting point that would work with the previously described project structure:

```js
const { mix } = require('laravel-mix');

// this must be the relative path from the webpack.mix.js
// file to your public web directory
mix.setPublicPath('public')
  // outputs built SCSS files to the public/css directory
  .sass('src/assets/css/main.scss', 'public/css')
  // outputs built JS files to the public/js directory
  .js('src/assets/js/main.js', 'public/js');

if (mix.inProduction()) {
  mix.version();
}
```

This plugin also provides a CraftCMS configuration value to set the public directory that it uses to locate and read from the Mix manifest file. You may want to override the setting if your path differs from the default  (`CRAFT_BASE_PATH/public`). You can do this by creating a file at `CRAFT_CONFIG_PATH/mix.php` with the following contents:

```php
<?php

return [
    // set "build" to the name of your public web directory
    'publicDir' => 'build'
];
```

## Usage

The primary purpose of this plugin is to provide template helpers that translate between a known path to your build assets and the real path to the assets after they have been built (which varies depending on the build mode). There are three main ways you can use Mix from Twig templates in CraftCMS:

```twig
{# Twig Filter #}
<script type="text/javascript" src="{{ 'js/main.js' | mix }}"></script>

{# Twig Function #}
<script type="text/javascript" src="{{ mix('js/main.js') }}"></script>

{# CraftCMS Variable #}
<script type="text/javascript" src="{{ craft.mix.getAssetPath('js/main.js') }}"></script>
```

There are a handful of different modes in which you can run Mix and the plugin will work differently in each mode, as described in the following sections.

### Dev Mode

Dev mode will build your assets to target a development environment. Depending on how you've configured Mix, this may bypass certain build instructions intended only for the production environment. In the example `webpack.mix.js` file, we are only versioning assets in production mode for cache busting or similar use cases. You can build the assets for developer mode by using the `npm` script we added in our `package.json` file:

```bash
npm run dev
```

This will generate the following files in our example project structure:

```
public/
  mix.js
  mix-manifest.json
  css/
    main.css
  js/
    main.js
```

You can then use the Twig helpers from this plugin in your templates to load the assets from the `mix-manifest.json` file:
 
```twig
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
...
<script type="text/javascript" src="{{ mix('js/main.js') }}"></script>
```

Yields

```twig
<link rel="stylesheet" href="/css/main.css">
...
<script type="text/javascript" src="/js/main.js"></script>
```

### Watch Mode

Functions just like Dev Mode except Mix will continue running as a foreground process through NodeJS and building assets as changes to the source files are detected.

```bash
npm run watch
```

### Hot Module Replacement Mode

Builds your assets and runs the Webpack dev server to allow [Hot Module Replacement](https://webpack.js.org/concepts/hot-module-replacement/). It works very similarly to what is described in the [Laravel Mix](https://github.com/JeffreyWay/laravel-mix/blob/master/docs/hot-module-replacement.md) documentation. To run in HMR mode, run the following command:

```bash
npm run hot
```

You can then use the Twig helpers from this plugin in your templates to load the assets from the Webpack dev server (running at `//localhost:8080`):
 
```twig
<link rel="stylesheet" href="{{ mix('css/main.css') }}">
...
<script type="text/javascript" src="{{ mix('js/main.js') }}"></script>
```

Yields

```twig
<link rel="stylesheet" href="//localhost:8080/css/main.css">
...
<script type="text/javascript" src="//localhost:8080/js/main.js"></script>
```

### Production Mode

or bundle your assets for production

```bash
npm run production
```

This will generate the following files in our example project structure:

```
public/
  mix-manifest.json
  css/
    main.css
  js/
    main.js
```

You can then use the Twig helpers from this plugin in your templates to load the assets from the `mix-manifest.json` file:
 
```twig
<link rel="stylesheet" href="{{ mix('css/main.css') }}">
...
<script type="text/javascript" src="{{ mix('js/main.js') }}"></script>
```

Yields

```twig
<link rel="stylesheet" href="/css/main.css?id=3b3bff1760a5005737de">
...
<script type="text/javascript" src="/js/main.js?id=5f474c7493fb1b375dca"></script>
```

## Acknowledgements

TODO

## License

Craft Laravel Mix is open source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
