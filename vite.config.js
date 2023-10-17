import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const refreshPaths = [
    'resources/css/app.css',
    'resources/js/app.js',
];

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament.css',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
});
