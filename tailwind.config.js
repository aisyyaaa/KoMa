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
            },
            colors: {
                // Palet Warna KOMA Market
                'koma-primary': '#f06977', // Merah Muda/Coral (Utama/Tombol)
                'koma-danger': '#ef444d',  // Merah (Sale/Peringatan)
                'koma-accent': '#6f95ea',  // Biru (Aksen/Navigasi)
                'koma-bg-light': '#dde0e7', // Abu Muda (Latar Belakang Ringan)
                'koma-hover-light': '#cad7fa', // Biru Muda (Hover/Aksen Ringan)
                'koma-text-dark': '#5d5e62', // Abu Tua (Teks Utama)
                'koma-nav': '#99aedd',     // Biru Sedang (Navigasi Sekunder)
            },
        },
    },

    plugins: [forms],
};
