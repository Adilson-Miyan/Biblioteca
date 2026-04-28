import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                gray: colors.stone,
                indigo: colors.amber,
            },
            fontFamily: {
                sans: ['"Merriweather"', ...defaultTheme.fontFamily.serif],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms, typography, require('daisyui')],

    daisyui: {
        themes: [
            {
                biblioteca: {
                    "primary": "#3e2b1e",
                    "primary-content": "#ffffff",
                    "secondary": "#b58f5c",
                    "secondary-content": "#ffffff",
                    "accent": "#f59e0b",
                    "neutral": "#2a2522",
                    "base-100": "#f3f4f6",
                    "base-200": "#e5e7eb",
                    "base-300": "#d1d5db",
                    "base-content": "#1f2937",
                },
            },
            "light",
            "dark",
        ],
    },
};
