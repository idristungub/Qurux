import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/Pages/**/*.vue'
    ],

    theme: {

        screens: {
            'sm': '640px',
            // => @media (min-width: 640px) { ... }

            'md': '768px',
            // => @media (min-width: 768px) { ... }

            'lg': '1024px',
            // => @media (min-width: 1024px) { ... }

            'xl': '1280px',
            // => @media (min-width: 1280px) { ... }

            '2xl': '1536px',
            // => @media (min-width: 1536px) { ... }
        },


        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],

                colors: {
                    black: {
                        dark: '#1D1E18'
                    },

                    green : {
                        dark: '#6B8F71',
                        light: '#AAD2BA',
                        mint: '#D9FFF5'
                    },

                    white: {
                        dark: '#FFFFFF'

                    },

                },
            },
        },


    },

    plugins: [
        forms,
        require('tailwind-scrollbar')

    ],
};
