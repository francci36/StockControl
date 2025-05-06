import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/css/**/*.css',  // ✅ Ajout des fichiers CSS
        './resources/js/**/*.js',    // ✅ Ajout des fichiers JS
    ],

    darkMode: 'class', // Active le mode sombre basé sur la classe

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Ajoutez des couleurs personnalisées pour le mode sombre
                dark: {
                    100: '#1a202c', // Couleur de fond sombre
                    200: '#2d3748', // Couleur de fond secondaire
                    300: '#4a5568', // Couleur de fond tertiaire
                },
            },
        },
    },

    plugins: [forms],
};