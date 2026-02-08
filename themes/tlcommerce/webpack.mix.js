const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('public'); // Tells Mix that 'public' is the base inside this folder

mix.js('resources/js/main.js', 'js') // Outputs to themes/tlcommerce/public/js/main.js
    .vue();

mix.webpackConfig({
    output: {
        publicPath: '/themes/tlcommerce/',
        chunkFilename: 'public/js/[name].js?id=[chunkhash]',
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
        },
        minimize: true
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
});

// This now writes to themes/tlcommerce/public/js/main.js
// Which is automatically visible at public/themes/tlcommerce/js/main.js
// mix.js('resources/js/main.js', 'js')
//     .vue();

// mix.js('resources/js/main.js', 'public/themes/tlcommerce/js')
//     .vue();
// mix.js('resources/js/main.js', 'public/js')
//     .vue();

// mix.js('resources/js/main.js', '../../public/themes/tlcommerce/public/js')
//     .vue();