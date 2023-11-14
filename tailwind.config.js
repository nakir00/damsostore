import defaultTheme from 'tailwindcss/defaultTheme';
import preset from './vendor/filament/support/tailwind.config.preset'
import forms from '@tailwindcss/forms';

const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/awcodes/filament-curator/resources/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    corePlugins: {
        aspectRatio: false,
      },
    plugins: [
        require('@tailwindcss/aspect-ratio'),
        forms,
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
