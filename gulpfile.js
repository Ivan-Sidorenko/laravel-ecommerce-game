var gulp = require('gulp');
var elixir = require('laravel-elixir');
var minifyCss = require('gulp-minify-css');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    var paths = {
        'bootstrap': 'node_modules/bootstrap/dist/',
        'jquery': 'node_modules/jquery/dist/',
        'fontawesome': 'node_modules/font-awesome/',
    };

    mix.copy(paths.bootstrap+'js/bootstrap.min.js', 'public/assets/js/bootstrap.min.js');
    mix.copy(paths.bootstrap+'fonts/*.*', 'public/build/assets/fonts');
    mix.copy(paths.fontawesome+'fonts/*.*', 'public/build/assets/fonts');
    mix.copy(paths.jquery+'*.*', 'public/assets/js');

    mix.copy('resources/assets/js/vendor.js', 'public/assets/js/vendor.js');
    mix.copy('resources/assets/css/**/*.*', 'public/assets/css/');

    // Compile LESS
    elixir(function(mix) {
        mix.less([
            'style.less'
        ],
        'public/assets/css/less_compiled.css');
    });

    // Compile plain CSS
    elixir(function(mix) {
        mix.styles([
            'less_compiled.css',
            'bootstrap-customization.css',
        ],
        'public/assets/css/all.css',
        'public/assets/css'
        );
    });

    // Compile JS scripts
    elixir(function(mix) {
        mix.scripts([
            'jquery.min.js',
            'bootstrap.min.js',
            'vendor.js'
        ],
        'public/assets/js/app.js',
        'public/assets/js'
        );
    });

    // Run CSS compress task
    mix.task('minify-css');

    // Versioning / Cache Busting
    elixir(function(mix) {
        mix.version([
            'public/assets/css/all.css',
            'public/assets/js/app.js'
        ]);
    });

});

gulp.task('minify-css', function() {
    return gulp.src('public/assets/css/all.css')
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('public/assets/css/'));
});