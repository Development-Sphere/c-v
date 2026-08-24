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
            colors: {
                paper: '#FAF9F4',
                'paper-dim': '#F2F0E8',
                ink: '#1C1B22',
                'ink-soft': '#59575F',
                cobalt: '#2A3EFF',
                'cobalt-dim': '#EDEFFF',
                seal: '#C89B3C',
                'seal-dim': '#F7EEDA',
                rule: '#DAD6C9',
            },
            fontFamily: {
                sans: ['"IBM Plex Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['"IBM Plex Serif"', ...defaultTheme.fontFamily.serif],
                mono: ['"IBM Plex Mono"', ...defaultTheme.fontFamily.mono],
            },
            keyframes: {
                stamp: {
                    '0%': { transform: 'scale(2.2) rotate(-14deg)', opacity: '0' },
                    '55%': { transform: 'scale(0.92) rotate(-8deg)', opacity: '1' },
                    '75%': { transform: 'scale(1.06) rotate(-10deg)' },
                    '100%': { transform: 'scale(1) rotate(-8deg)', opacity: '1' },
                },
            },
            animation: {
                stamp: 'stamp 0.5s cubic-bezier(.2,.8,.3,1.1) both',
            },
        },
    },

    plugins: [forms],
};
