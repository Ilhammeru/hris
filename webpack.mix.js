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

mix.less('resources/css/employee.less', 'public/css')
    .js('resources/js/attendant.js', 'dist/js')
    .js('resources/js/event.js', 'dist/js')
    .js('resources/js/guestbook.js', 'dist/js')
    .js('resources/js/master.js', 'dist/js')
    .setPublicPath('public')
    .webpackConfig({
        mode: 'production',
        optimization: {
            sideEffects: false
        }
    })
    // .browserSync('http://hris.test')
    .version();
