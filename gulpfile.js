const {
    series,
    parallel,
    src,
    dest,
    watch: gulpWatch
} = require('gulp');

// Include Our Plugins
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minifycss = require('gulp-minify-css');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var plumber = require('gulp-plumber');

function sassDist() {
    const modularity = src('source/sass/modularity.scss')
    .pipe(plumber())
    .pipe(sass())
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .pipe(dest('dist/css'));

    const thickbox = src('source/sass/modularity-thickbox-edit.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss())
        .pipe(dest('dist/css'));

    const assets = src('source/php/Module/*/assets/*.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss())
        .pipe(dest('dist/css'));

    return Promise.all([modularity, thickbox, assets]);
}

function sassDev() {
    const modularity = src('source/sass/modularity.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(rename({suffix: '.dev'}))
        .pipe(dest('dist/css'));

    const thickbox = src('source/sass/modularity-thickbox-edit.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1'))
        .pipe(rename({suffix: '.dev'}))
        .pipe(dest('dist/css'));

    return Promise.all([modularity, thickbox]);
}

function scriptsDist() {
    const modularity = src([
        'source/js/**/*.js',
        '!source/js/modularity-editor-modal.js'
    ])
        .pipe(concat('modularity.dev.js'))
        .pipe(dest('dist/js'))
        .pipe(rename('modularity.min.js'))
        .pipe(uglify())
        .pipe(dest('dist/js'));

    const assets = src('source/php/Module/*/assets/*.js')
        .pipe(uglify())
        .pipe(dest('dist/js'));

    const editor = src('source/js/modularity-editor-modal.js')
        .pipe(concat('modularity-editor-modal.dev.js'))
        .pipe(dest('dist/js'))
        .pipe(rename('modularity-editor-modal.min.js'))
        .pipe(uglify())
        .pipe(dest('dist/js'));

    return Promise.all([modularity, assets, editor]);
}

function watch() {
    gulpWatch(['Source/js/**/*.js', 'source/php/Module/*/assets/*.js'], series(scriptsDist));
    gulpWatch('Source/sass/**/*.scss', series(sassDist, sassDev));
    gulpWatch('source/php/Module/*/assets/*.scss', series(sassDist, sassDev));
}

const build = parallel(sassDist, sassDev, scriptsDist);

exports.watch = watch;
exports.build = build;
exports.default = series(build, watch);
