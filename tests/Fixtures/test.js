/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      './resources/views/**/*.blade.php'
    ],
    important: '.{{ plugin_name }}',
    darkMode: 'class',
    theme: {
      extend: {},
    },
    plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
    ],
    corePlugins: {
      preflight: false,
    }
  }
