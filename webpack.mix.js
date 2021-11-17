let mix = require('laravel-mix');

mix.js('resources/js/liquid.js', 'resources/assets/js')
    .vue()
    .sass('resources/sass/liquid.scss', 'resources/assets/css');
    // .sass('resources/sass/coreui.scss', 'resources/assets/coreui/css');