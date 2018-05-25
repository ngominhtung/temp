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

mix.js(['resources/assets/js/app.js', 'resources/assets/js/admin.js'], 'public/js')
    .js(['resources/assets/js/register_confirm/register_confirm.js'], 'public/js/register_confirm.js')
    .js(['resources/assets/js/contacts/contact.js'], 'public/js/contact.js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.styles(['resources/assets/css/site.css'], 'public/css/site.css');

mix.copyDirectory('resources/assets/img', 'public/img');

mix.version();
