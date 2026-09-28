import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
                mono: ['"Space Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                paper: {
                    50: '#FDFCF9',
                    100: '#FAF6EE',
                    200: '#F4ECE1',
                    300: '#E7DCcb',
                    900: '#231F1A',
                },
                ink: {
                    950: '#121211',
                    900: '#1A1918',
                    800: '#2B2927',
                },
            },
        },
    },

    plugins: [forms],
};
