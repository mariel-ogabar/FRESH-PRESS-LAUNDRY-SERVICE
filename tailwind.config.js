import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/View/Resources/Views/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                'fp-primary': '#000000',
                'fp-soft': '#eff6ff',
                'fp-dark': '#1f2937',
                'fp-accent': '#7c3aed',
            },
            borderRadius: {
                'fp': '1.5rem',
            },
        },
    },

    plugins: [forms],
};