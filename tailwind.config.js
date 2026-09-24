import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // INET SOLUTIONS brand palette
                brand: {
                    DEFAULT: '#0B5ED7',
                    dark: '#071A33',
                    light: '#F4F8FC',
                    50: '#EFF5FF',
                    100: '#D9E7FD',
                    200: '#BCD6FA',
                    300: '#8EBDF6',
                    400: '#599AF0',
                    500: '#3378EA',
                    600: '#0B5ED7',
                    700: '#0A4CB0',
                    800: '#0C3F90',
                    900: '#0D3776',
                    950: '#071A33',
                },
                accent: {
                    DEFAULT: '#00AEEF',
                    50: '#EFFCFF',
                    100: '#C8F5FF',
                    200: '#A3E9FF',
                    300: '#5FD4FF',
                    400: '#18BCFF',
                    500: '#00AEEF',
                    600: '#008CCC',
                    700: '#006FA3',
                    800: '#005982',
                    900: '#004B6E',
                },
                ink: '#172033',
            },
        },
    },

    plugins: [forms],
};
