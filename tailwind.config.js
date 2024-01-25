import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/primevue/**/*.{vue,js,ts,jsx,tsx}",
        "./resources/assets/**/*.{vue,js,ts,jsx,tsx}",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    default: '#8BC34A',
                    50: '#E3F1D4',
                    100: '#DAECC5',
                    200: '#C6E1A6',
                    300: '#B2D787',
                    400: '#9FCD69',
                    500: '#8BC34A',
                    600: '#6EA035',
                    700: '#517627',
                    800: '#344C19',
                    900: '#17210B',
                    950: '#090C04'
                },
                some: {
                    50: 'rgb(var(--primary-50))',
                    100: 'rgb(var(--primary-100))',
                    200: 'rgb(var(--primary-200))',
                    300: 'rgb(var(--primary-300))',
                    400: 'rgb(var(--primary-400))',
                    500: 'rgb(var(--primary-500))',
                    600: 'rgb(var(--primary-600))',
                    700: 'rgb(var(--primary-700))',
                    800: 'rgb(var(--primary-800))',
                    900: 'rgb(var(--primary-900))',
                    950: 'rgb(var(--primary-950))',
                },
                surface: {
                    0: 'var(--surface-0)',
                    50: 'var(--surface-50)',
                    100: 'var(--surface-100)',
                    200: 'var(--surface-200)',
                    300: 'var(--surface-300)',
                    400: 'var(--surface-400)',
                    500: 'var(--surface-500)',
                    600: 'var(--surface-600)',
                    700: 'var(--surface-700)',
                    800: 'var(--surface-800)',
                    900: 'var(--surface-900)',
                    950: 'var(--surface-950)'
                }

            }
        },
    },

    plugins: [forms, typography],
};
