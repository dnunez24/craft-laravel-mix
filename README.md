# CraftCMS Laravel Mix

Plugin that incorporates [Laravel Mix](https://laravel.com/docs/5.4/mix) to CraftCMS.

## Requirements

- PHP 7+
- Node.js 6+
- CraftCMS 2.5+

## Installation

Download and install this plugin to your `CRAFT_PLUGINS_PATH`. You can do this manually, or you can use Composer if you've set your project up to use Composer for managing dependencies. Since this plugin's `composer.json` configures this package as a `craft-plugin` type, Composer should install it in your Craft plugins directory automatically. If you have trouble with that, see the [Composer Installers](https://github.com/composer/installers) repo for configuration options.

```
composer require dnunez24/craft-laravel-mix
```

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

## Usage

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
