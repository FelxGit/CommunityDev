const mix = require('laravel-mix');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

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
 const cleanPatterns = [
    '**assets/*'
 ];

 mix.webpackConfig({
    plugins: [
       new CleanWebpackPlugin({
          cleanOnceBeforeBuildPatterns: cleanPatterns
       })
    ],
 });

mix.setPublicPath('public');
mix.copyDirectory('node_modules/trumbowyg/plugins/upload', 'public/assets/trumbowyg/plugins/upload');
mix.copyDirectory('node_modules/trumbowyg/plugins/pasteimage', 'public/assets/trumbowyg/plugins/pasteimage');
mix.copyDirectory('node_modules/trumbowyg/plugins/base64', 'public/assets/trumbowyg/plugins/base64');
mix.copyDirectory('node_modules/trumbowyg/plugins/pasteembed', 'public/assets/trumbowyg/plugins/pasteembed');
mix.copyDirectory('node_modules/trumbowyg/plugins/resizimg', 'public/assets/trumbowyg/plugins/resizimg');
mix.copyDirectory('node_modules/trumbowyg/plugins/fontsize', 'public/assets/trumbowyg/plugins/fontsize');
mix.copyDirectory('node_modules/trumbowyg/plugins/fontfamily', 'public/assets/trumbowyg/plugins/fontfamily');
mix.copyDirectory('node_modules/trumbowyg/plugins/colors', 'public/assets/trumbowyg/plugins/colors');


mix.js('resources/js/app.js', 'public/js').vue()
   .sass('resources/sass/app.scss', 'public/css');
