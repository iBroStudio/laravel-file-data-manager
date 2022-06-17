/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
'./resources/views/**/*.blade.php',
'NewValue'
],
    important: 'tata',
    darkMode: 'class',
    theme: {
      extend: {},
    },
    plugins: [
require('@tailwindcss/forms'),
require('@tailwindcss/typography'),
require('@tailwindcss/line-clamp')
],
    corePlugins: {
      preflight: false,
preflight: true,
    }
  }
