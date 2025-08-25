/** @type {import('tailwindcss').Config} */

  module.exports = {
  darkMode: 'class',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/Http/Controllers/**/*.php',
    './routes/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
          'dark-blue': '#161A23', // Nama warna kustom
        },
    },
  },
  plugins: [],
};