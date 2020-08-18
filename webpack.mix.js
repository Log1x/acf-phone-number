const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .setPublicPath('./dist')
  .js('assets/js/field.js', 'dist/js')
  .sass('assets/css/field.scss', 'dist/css')
  .options({
    processCssUrls: false,
  })
  .autoload({
    jquery: ['$', 'window.jQuery'],
  })
  .version();
