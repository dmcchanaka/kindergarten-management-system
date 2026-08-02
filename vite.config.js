import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import path from "path"

export default defineConfig({
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel([
            'resources/css/app.css',
            'resources/ts/app.ts',
        ]),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/ts/src/'),
            '~': path.resolve(__dirname, 'node_modules/')
        }
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost'
        },
        watch: {
            usePolling: true
        }
    }
});