import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            port: 5173,
            host: 'localhost'
        }
    },
    plugins: [
        vueDevTools({
            appendTo: 'resources/js/app.js'
        }),
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
