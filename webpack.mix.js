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


mix.sass('resources/sass/dashforge.scss', 'public/css');
mix.sass('resources/sass/custom.scss', 'public/css');
mix.copy('node_modules/axios/dist/axios.min.js', 'public/lib/axios/axios.min.js');
mix.copy('node_modules/datatables.net/js/jquery.dataTables.min.js', 'public/lib/dataTables/dataTables.dataTables.min.js');
mix.copy('node_modules/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js', 'public/lib/dataTables/dataTables.fixedHeader.js');
mix.copy('node_modules/datatables.net-searchbuilder/js/dataTables.searchBuilder.min.js', 'public/lib/dataTables/dataTables.searchBuilder.min.js');
mix.copy('node_modules/datatables.net-searchbuilder-dt/js/searchBuilder.dataTables.min.js', 'public/lib/dataTables/searchBuilder.dataTables.min.js');
mix.copy('node_modules/datatables.net-searchbuilder-dt/css/searchBuilder.dataTables.min.css', 'public/lib/dataTables/searchBuilder.dataTables.min.css');
mix.copy('node_modules/datatables.net-dt/css/jquery.dataTables.min.css', 'public/lib/dataTables/jquery.dataTables.min.css');
mix.copyDirectory('node_modules/datatables.net-dt/images', 'public/lib/images/');
mix.copy('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css', 'public/lib/dataTables/dataTables.bootstrap4.css');
mix.copy('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js', 'public/lib/dataTables/dataTables.bootstrap4.js');
mix.copy('node_modules/sweetalert2/dist/sweetalert2.css', 'public/lib/sweetalert2/sweetalert2.css');
mix.copy('node_modules/sweetalert2/dist/sweetalert2.js', 'public/lib/sweetalert2/sweetalert2.js');


mix.babel('resources/js/income.js', 'public/js/income.js');
