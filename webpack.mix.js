const mix = require('laravel-mix');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

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
