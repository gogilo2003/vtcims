/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin');
export default {
    content: [
        "./resources/views/pdf/**/*.blade.php",
        "./resources/views/students/download/**/*.blade.php",
        "./resources/views/layout/**/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins'],
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
                gray: {
                    50: '#F8F8F8',
                    100: '#EDEDED',
                    200: '#D6D6D6',
                    300: '#BFBFBF',
                    400: '#A8A8A8',
                    500: '#919191',
                    600: '#7A7A7A',
                    700: '#636363',
                    800: '#4C4C4C',
                    900: '#353535',
                    950: '#292929'
                },
            }
        },
    },
    plugins: [
        plugin(function ({ addUtilities, theme, e }) {
            const colors = theme('colors');
            const utilities = {};

            Object.keys(colors).forEach(colorName => {
                const colorShades = colors[colorName];

                Object.keys(colorShades).forEach(shadeName => {
                    const colorHex = colorShades[shadeName];

                    utilities[`.${e(`bg-${colorName}-${shadeName}`)}`] = {
                        'background-color': colorHex,
                    };
                    utilities[`.${e(`border-${colorName}-${shadeName}`)}`] = {
                        'border-color': colorHex,
                    };
                    utilities[`.${e(`text-${colorName}-${shadeName}`)}`] = {
                        'color': colorHex,
                    };
                });
            });

            addUtilities(utilities, ['responsive', 'hover']);
        }),
    ],
}

