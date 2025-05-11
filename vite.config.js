import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Generate manifest.json in the build directory
        manifest: true,
        // Use relative paths for assets to work with any domain
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
