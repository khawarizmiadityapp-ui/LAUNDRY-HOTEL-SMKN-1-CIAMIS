// tailwind.config.js
// Tailwind CSS v3.4+ config untuk LaundryPro



/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui'],
            },

            // Warna custom untuk badge dan aksen laundry
            colors: {
                laundry: {
                    sky:     '#0ea5e9',
                    cyan:    '#06b6d4',
                    mint:    '#10b981',
                    violet:  '#7c3aed',
                    amber:   '#f59e0b',
                    orange:  '#f97316',
                    red:     '#ef4444',
                    slate:   '#64748b',
                },
            },

            // Border radius custom
            borderRadius: {
                '4xl': '2rem',
            },

            // Box shadow lebih halus
            boxShadow: {
                'sm-colored': '0 1px 3px 0 rgb(0 0 0 / 0.08)',
                'md-colored': '0 4px 12px 0 rgb(0 0 0 / 0.08)',
            },

            // Animasi custom
            keyframes: {
                'slide-in-up': {
                    '0%': { transform: 'translateY(8px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
            animation: {
                'slide-in-up': 'slide-in-up 0.2s ease-out',
            },
        },
    },

    plugins: [
        // Plugin untuk form styling (opsional)
        // require('@tailwindcss/forms'),
    ],
}
