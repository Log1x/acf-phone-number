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
  .setPublicPath('./public')
  .js('assets/js/field.js', 'public/js')
  .sass('assets/css/field.scss', 'public/css')
  .options({
    processCssUrls: false,
  })
  .autoload({
    jquery: ['$', 'window.jQuery'],
  })
  .version();
