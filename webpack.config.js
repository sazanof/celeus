require('dotenv').config()
const webpack = require('webpack')
const path = require('path')
const { VueLoaderPlugin } = require('vue-loader')
const ESLintPlugin = require('eslint-webpack-plugin')

module.exports = {
    target: 'web',
    entry: {
        l10n: '/resources/js/l10n.js',
        emitter: '/resources/js/emitter.js',
        login: '/resources/js/login.js',
        main: '/resources/js/main.js',
        install: '/resources/js/install.js',
        settings: '/apps/settings/resources/js/settings.js',
        mail: '/apps/mail/resources/js/mail.js',
    },
    performance: { hints: false },
    output: {
        path: path.resolve('./public/dist/'),
    },
    plugins: [
        new VueLoaderPlugin(),
        new ESLintPlugin(),
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: true,
            __VUE_PROD_DEVTOOLS__: true
        }),
    ],
    devServer: {
        allowedHosts: [
            process.env.APP_HOST,
        ],
        liveReload: true,
        proxy: {
            '/': process.env.APP_WEBPACK_PROXY_HOST,
        },
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/,
                use: [ 'style-loader', 'css-loader' ],
            },
            {
                test: /\.s[ac]ss$/i,
                use: [ 'style-loader', 'css-loader', 'sass-loader' ],
            },
            {
                test: /\.js$/,
                loader: 'babel-loader'
            },
            {
                test: /\.svg/,
                type: 'asset/resource'
            },
        ]
    },
}