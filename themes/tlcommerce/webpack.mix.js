const mix = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');

mix.setPublicPath('public'); // Tells Mix that 'public' is the base inside this folder

mix.js('resources/js/main.js', 'js') // Outputs to themes/tlcommerce/public/js/main.js
    .vue();

mix.webpackConfig({
    plugins: [
        new webpack.DefinePlugin({
            __VUE_I18N_FULL_INSTALL__: JSON.stringify(true),
            __VUE_I18N_LEGACY_API__: JSON.stringify(true), // Set to true since you're likely using this.$t
            __INTLIFY_PROD_DEVTOOLS__: JSON.stringify(false),
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
        }),
    ],
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