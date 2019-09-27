const mix = require('laravel-mix')

mix.browserSync({
   proxy: {
      target: "k12i.space:8000"
   },
   open: false,
   reloadOnRestart: true
})
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .version()