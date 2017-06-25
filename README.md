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

## Usage

Create a `package.json` file with the following configuration to install Laravel Mix dependencies and configure asset build tasks.

```json
{
    "private": "true",
    "scripts": {
        "dev": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
        "watch": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --watch --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
        "watch-poll": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --watch --watch-poll --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
        "hot": "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --config=node_modules/laravel-mix/setup/webpack.config.js",
        "production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js"
    },
    "devDependencies": {
        "cross-env": "^5.0.0",
        "laravel-mix": "^0.12.0"
    }
}
```

Install the Node.js dependencies using `npm` or `yarn`

```bash
npm install
# OR
yarn install
```

Create a `webpack.mix.js` file at the root of your project to configure Laravel Mix to build your assets. See the [Laravel Mix](https://laravel.com/docs/5.4/mix) documentation for configuration details and more options. Be sure to configure the `publicPath` option for Mix to point at the directory from which you will serve static assets (images, fonts, javascript and CSS).

```js
const { mix } = require('laravel-mix');

mix.options({
    // this must be the relative path from the webpack.mix.js
    // file to your public web directory
    publicPath: 'public',
  })
  .sass('src/assets/styles/main.scss', 'public/assets/styles')
  .js('src/assets/scripts/main.js', 'public/assets/scripts')
  .sourceMaps()
  .browserSync('www.mysite.com');

if (mix.config.inProduction) {
  mix.version();
}
```

Given a project with asset files organized as follows

```
...
src/
  assets/
    scripts/
      main.js
    styles/
      main.scss
...
```

You can use Mix from Twig templates in the three following ways:

```twig
{# Twig Filter #}
<script type="text/javascript" src="{{ 'assets/scripts/main.js' | mix }}"></script>

{# Twig Function #}
<script type="text/javascript" src="{{ mix('assets/scripts/main.js') }}"></script>

{# Twig Variable #}
<script type="text/javascript" src="{{ craft.mix.getAssetPath('assets/scripts/main.js') }}"></script>
```


### Dev Mode

```bash
npm run dev
```

### Hot Module Replacement Mode

Build the assets and run the Webpack dev server (Hot Module Replacement mode):

```bash
npm run hot
```

A `mix-manifest.json` file will be generated in your `public` directory with the following content

```json
{
  "/mix.js": "/mix.js",
  "/assets/styles/main.css": "/assets/styles/main.css",
  "/assets/scripts/main.js": "/assets/scripts/main.js"
}
```

Then use the Twig function in your Craft templates like so

```twig
<script type="text/javascript" src="{{ mix('assets/scripts/main.js') }}"></script>
{# Outputs: <script type="text/javascript" src="//localhost:8080/assets/scripts/main.js"></script> #}
```

### Production Mode

or bundle your assets for production

```bash
npm run production
```

A `mix-manifest.json` file will be generated in your `public` directory with the following content

```json
{
  "/mix.js": "/mix.49fd152cead1b55e6d10.js",
  "/assets/styles/main.css": "/assets/styles/main.11dbed27c16369bf55bc7e36fb2cf415.css",
  "/assets/scripts/main.js": "/assets/scripts/main.2761134756d7f7719ae8.js"
}
```

Using the Twig filters and functions as in the examples below will output the appropriate URLs for either Hot Module Replacement or Production modes.


```twig
<script type="text/javascript" src="{{ mix('assets/scripts/main.js') }}"></script>
{# Outputs: <script type="text/javascript" src="https://www.site.com/assets/scripts/main.2761134756d7f7719ae8.js"></script> #}
```


## Acknowledgements

TODO

## License
