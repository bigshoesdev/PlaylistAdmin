let mix = require('laravel-mix');

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
.js('resources/js/app/app.js', 'public/js')
.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve('resources/scss/app')
    }
  }
})
.sass('resources/scss/app/app.scss', 'public/css');
