import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', 

  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],

  theme: {
    extend: {
      // 2. Palet Warna Brand (Pink Estetik)
      colors: {
        brand: {
            50: '#fdf2f7',   
            100: '#fce7f3',
            200: '#fbcfe8',
            300: '#f9a8d4',
            400: '#f472b6',
            500: '#D55F8E',  // Warna Utama
            600: '#be3f73',  
            700: '#9f2f5b',
            800: '#83274b',
            900: '#6d243f',
        },
        'brand-bg': '#FAFAFC', 
      },

      // 3. Lengkungan Sudut (Rounded Corners)
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
        '3xl': '2rem', 
      },

      // 4. Font Family 
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },

      boxShadow: {
        'soft': '0 10px 40px -10px rgba(0,0,0,0.08)',
        'glow': '0 0 20px rgba(213, 95, 142, 0.3)', 
      }
    },
  },

  plugins: [],
}