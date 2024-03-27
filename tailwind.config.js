import forms from "@tailwindcss/forms";
import plugin from "tailwindcss";
import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],
    safelist: [
        {
            pattern: /(text|bg|border)-(red|lime|green|yellow|orange)-(400|500|700|800)/,
        },
    ],
    theme: {
        extend: {
            colors: {
                dark: {
                    DEFAULT: "#141414",
                    light: "#252525",
                    lighter: "#2B2B2B",
                },
                primary: {
                    DEFAULT: "#58c3f8",
                    hover: "rgb(96 165 250)"
                },
            },
            fontSize: {
                "2xs": ["0.625rem", "1rem"],
                "3xs": [".5rem", "1rem"],
            },
            textShadow: {
                sm: "0 1px 2px var(--tw-shadow-color)",
                DEFAULT: "0 2px 4px var(--tw-shadow-color)",
                lg: "0 8px 16px var(--tw-shadow-color)",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        plugin(function ({ matchUtilities, theme }) {
            matchUtilities(
                {
                    "text-shadow": (value) => ({
                        textShadow: value,
                    }),
                },
                { values: theme("textShadow") }
            );
        }),
    ],
};
