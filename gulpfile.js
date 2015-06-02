var gulp = require("gulp"),
    elixir = require('laravel-elixir'),
    config = elixir.config,
    shell = require("gulp-shell"),
    livereload = require('gulp-livereload'),
    watch = require('gulp-watch');


/*
 |--------------------------------------------------------------------------
 | Add Livereload
 |--------------------------------------------------------------------------
*/
livereload.listen();
elixir.extend("livereload", function(prev, cur) {
    
    gulp.task("refresh", function() {
        watch('resources/**/*').pipe(livereload());
    });
    return this.queueTask("refresh");
});

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('app.less');
});

elixir(function(mix) {
    mix.coffee('', null, {bare: true});
});

elixir(function(mix) {
    mix.version("css/all.css");
});

elixir(function(mix) {
    mix.livereload();
});