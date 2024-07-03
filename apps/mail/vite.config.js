import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import babel from 'vite-plugin-babel'
import path from 'node:path'

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '')
    return {
        plugins: [
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false
                    }
                }
            }),
            babel({
                babelConfig: {
                    babelrc: false,
                    configFile: false
                }
            }),
            liveReload([
                // edit live reload paths according to your source code
                // for example:
                //__dirname + '/(apps|config|core|inc)/**/*.php',
                // using this for our example:
                __dirname + '/../public/*.php'
            ])
            //splitVendorChunkPlugin(),
        ],

        // config
        root: '',
        base: process.env.APP_ENV === 'development'
            ? '/'
            : '/dist/',

        build: {
            // output dir for production build
            outDir: './public/dist',
            emptyOutDir: true,

            // emit manifest so PHP can find the hashed files
            manifest: true,
            assetsDir: 'assets',
            // our entry
            rollupOptions: {
                input: {
                    mail: path.resolve(__dirname, 'resources/js/mail.js')
                },
                output: {
                    manualChunks: {},
                    entryFileNames: '[name]-[hash].js',
                    assetFileNames: 'assets/[name]-[hash][extname]'
                }
            }
        },

        server: {
            // we need a strict port to match on PHP side
            // change freely, but update on PHP to match the same port
            // tip: choose a different port per project to run them at the same time
            strictPort: true,
            port: env.VITE_APP_PORT
        },

        // required for in-browser template compilation
        // https://vuejs.org/guide/scaling-up/tooling.html#note-on-in-browser-template-compilation
        resolve: {
            alias: {
                vue: 'vue/dist/vue.esm-bundler.js',
                '@': path.resolve(__dirname, '/resources')
            }
        }
    }
})
