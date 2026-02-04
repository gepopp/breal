export default {
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'logo': {
                    '50': '#e1f2ff',
                    '100': '#c2e3ff',
                    '200': '#79cbff',
                    '300': '#00a3ff',
                    '400': '#004e79',
                    '500': '#002641',
                    '600': '#001e39',
                    '700': '#00182e',
                    '800': '#001426',
                    '900': '#00101f',
                    '950': '#000811',
                },
            },
            fontFamily: {
                sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                logo: ['EmBauhaus W00 Bold', 'Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
}
