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
                sans: ['"Plus Jakarta Sans"', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // INET SOLUTIONS palette, taken from the logo:
                // blue = the slash & globe, accent red = "SOLUTIONS" and its dots,
                // violet → red = the signal arcs, ink = the "iNet" wordmark.
                brand: {
                    DEFAULT: '#1E57D6',
                    dark: '#0E1B3D',
                    light: '#F5F7FB',
                    50: '#EEF3FE',
                    100: '#DCE6FC',
                    200: '#BACDF8',
                    300: '#8AAAF1',
                    400: '#5A85E8',
                    500: '#386BDF',
                    600: '#1E57D6',
                    700: '#1845B0',
                    800: '#17398C',
                    900: '#162F6E',
                    950: '#0E1B3D',
                },
                accent: {
                    DEFAULT: '#E2202C',
                    50: '#FFF1F2',
                    100: '#FFE0E2',
                    200: '#FFC5CA',
                    300: '#FF9AA2',
                    400: '#F75F6C',
                    500: '#EC3240',
                    600: '#E2202C',
                    700: '#BE1620',
                    800: '#9C151D',
                    900: '#81171E',
                },
                signal: {
                    violet: '#463CA5',
                    plum: '#9B2C7E',
                    red: '#E2202C',
                },
                // Footer / dark surfaces
                night: {
                    DEFAULT: '#0B1020',
                    950: '#070A14',
                    900: '#0B1020',
                    800: '#111830',
                    700: '#18213F',
                },
                ink: '#0F1115',
            },
            maxWidth: {
                '8xl': '88rem',
            },
            keyframes: {
                marquee: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-50%)' },
                },
                'pulse-ring': {
                    '0%': { transform: 'scale(0.9)', opacity: '0.7' },
                    '100%': { transform: 'scale(2.2)', opacity: '0' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
            animation: {
                marquee: 'marquee 40s linear infinite',
                'pulse-ring': 'pulse-ring 2s cubic-bezier(0.2, 0.6, 0.4, 1) infinite',
                float: 'float 6s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
