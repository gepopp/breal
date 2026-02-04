import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'app/Filament/**/*.php',
                'app/Livewire/**/*.php',
                'app/Models/**/*.php',
            ],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        hmr: {
            host: 'localhost',
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
        }
    }
});