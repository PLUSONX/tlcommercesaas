const mix = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');

// Bump version in asset-version.json on every storefront deploy (Blade reads the same file).
const ASSET_VERSION = String(require('./asset-version.json').version);

// mix.setPublicPath('public'); // Tells Mix that 'public' is the base inside this folder

mix.setPublicPath('../../public/themes/tlcommerce'); // Output directly to where blade loads from

mix.js('resources/js/main.js', 'js') // Outputs to themes/tlcommerce/public/js/main.js
    .vue();

mix.webpackConfig({
    plugins: [
        new webpack.DefinePlugin({
            __VUE_I18N_FULL_INSTALL__: JSON.stringify(true),
            __VUE_I18N_LEGACY_API__: JSON.stringify(true), // Set to true since you're likely using this.$t
            __INTLIFY_PROD_DEVTOOLS__: JSON.stringify(false),
            __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
            __TLC_ASSET_VERSION__: JSON.stringify(ASSET_VERSION),
        }),
    ],
    output: {
        publicPath: '/themes/tlcommerce/',
        // Query-string bust on every chunk URL (Instagram caches named chunks aggressively)
        chunkFilename: `js/[name].js?v=${ASSET_VERSION}`,
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
