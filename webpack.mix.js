const mix = require('laravel-mix');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

mix.js('resources/js/admin/app.js', 'public/js/admin')
    .sass('resources/sass/app.scss', 'public/css/admin')
    .sass('resources/sass/admin.scss', 'public/css/admin');

mix.sass('resources/sass/page.scss', 'public/css')
    .js('resources/js/common.js', 'public/js').version();

mix.webpackConfig({
    plugins: [
        new UglifyJsPlugin(),
    ]
});
