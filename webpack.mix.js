const mix = require('laravel-mix');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/page.js', 'public/js')
    .sass('resources/sass/page.scss', 'public/css')
    .sass('resources/sass/admin.scss', 'public/css')
    .js('resources/js/ad.js', 'public/js').version()
    .js('resources/js/common.js', 'public/js').version();

mix.webpackConfig({
    plugins: [
        new UglifyJsPlugin(),
    ]
});
