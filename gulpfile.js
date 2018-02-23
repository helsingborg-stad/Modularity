// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minifycss = require('gulp-minify-css');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var plumber = require('gulp-plumber');

// Compile Our Sass
gulp.task('sass-dist', function() {
    gulp.src('source/sass/modularity.scss')
            .pipe(plumber())
            .pipe(sass())
            .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
            .pipe(rename({suffix: '.min'}))
            .pipe(minifycss())
            .pipe(gulp.dest('dist/css'));

    gulp.src('source/sass/modularity-thickbox-edit.scss')
            .pipe(plumber())
            .pipe(sass())
            .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
            .pipe(rename({suffix: '.min'}))
            .pipe(minifycss())
            .pipe(gulp.dest('dist/css'));

    gulp.src('source/php/Module/*/assets/*.scss')
            .pipe(plumber())
            .pipe(sass())
            .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
            .pipe(rename({suffix: '.min'}))
            .pipe(minifycss())
            .pipe(gulp.dest('dist/css'));
});

gulp.task('sass-dev', function() {
    gulp.src('source/sass/modularity.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(rename({suffix: '.dev'}))
        .pipe(gulp.dest('dist/css'));

    gulp.src('source/sass/modularity-thickbox-edit.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(rename({suffix: '.dev'}))
        .pipe(gulp.dest('dist/css'));
});

// Concatenate & Minify JS
gulp.task('scripts-dist', function() {
    gulp.src([
            'source/js/**/*.js',
            '!source/js/modularity-editor-modal.js'
        ])
        .pipe(concat('modularity.dev.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(rename('modularity.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dist/js'));

    gulp.src('source/php/Module/*/assets/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('dist/js'));

    gulp.src('source/js/modularity-editor-modal.js')
        .pipe(concat('modularity-editor-modal.dev.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(rename('modularity-editor-modal.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dist/js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch(['Source/js/**/*.js', 'source/php/Module/*/assets/*.js'], ['scripts-dist']);
    gulp.watch('Source/sass/**/*.scss', ['sass-dist', 'sass-dev']);
    gulp.watch('source/php/Module/*/assets/*.scss', ['sass-dist', 'sass-dev']);
});

// Build Task
gulp.task('build', ['sass-dist', 'sass-dev', 'scripts-dist']);

// Default Task
gulp.task('default', ['build', 'watch']);
