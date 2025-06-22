import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    server: {
        host: '0.0.0.0',  // Esto permite que el servidor escuche en todas las interfaces
        port: 5173,        // Puedes cambiar el puerto si lo deseas
        hmr: {
            host: '192.168.18.32',  // Reemplaza con tu IP local
        },
    },
});
