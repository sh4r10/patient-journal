import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                yellow: {
                    '50': '#f8f7f4',
                    '100': '#efede5',
                    '200': '#ded8ca',
                    '300': '#cbc0aa',
                    '400': '#b3a084',
                    '500': '#a48b6b',
                    '600': '#977b5f',
                    '700': '#7e6550',
                    '800': '#675345',
                    '900': '#54453a',
                    '950': '#2c231e',
                },
                blue: {
                    '50': '#eff3ff',
                    '100': '#dbe3fe',
                    '200': '#bfcdfe',
                    '300': '#93abfd',
                    '400': '#6083fa',
                    '500': '#3b66f6',
                    '600': '#2552eb',
                    '700': '#1d48d8',
                    '800': '#1e3faf',
                    '900': '#1e378a',
                    '950': '#172554',
                },
            },
        },
    },
    plugins: [forms],
};
