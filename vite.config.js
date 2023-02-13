import path from "path";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    build: {
        // generate manifest.json in outDir
        manifest: true,
        rollupOptions: {
            // overwrite default .html entry
            input: 'public/main.js'
        }
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        },
        modules: [path.resolve("./"), path.resolve("./node_modules")]
    },
});
