import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
                    primary: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        200: '#bfdbfe',
                        300: '#93c5fd',
                        400: '#60a5fa',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                        800: '#1e40af',   // Your exact color
                        900: '#1e3a8a',
                        950: '#172554',
                    },
                    rose: {
                        50: '#fef2f3',
                        100: '#fde6e8',
                        200: '#fbd0d6',
                        300: '#f8aab5',
                        400: '#f3798e', // Your base color #fb7185 adjusted for consistency
                        500: '#e95a76', // Slightly darker
                        600: '#d63c65',
                        700: '#b42d55',
                        800: '#97274f',
                        900: '#812449',
                        950: '#480f24',
                    }
                }
    },

    plugins: [
        forms,
        require('flowbite/plugin'),
    ],
};
