const { series, parallel } = require('gulp');
const gulp        = require('gulp');
const concat      = require('gulp-concat');  // Concatenate files
const cssnano     = require('gulp-cssnano');
const minify      = require('gulp-minify');
const rename      = require('gulp-rename');
const sourcemaps  = require('gulp-sourcemaps');
const cleanCSS    = require('gulp-clean-css');

function clean(cb) {
    // body omitted
    cb();
}

function cssGenerate(cb) {
    gulp.src('./web/css/style.css')
        .pipe(rename('style.min.css'))


        .pipe(sourcemaps.init())
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(cssnano({
            mergeRules: true,
            discardUnused: true,
            discardDuplicates: true,
            discardEmpty:true,
            discardComments: {removeAll: true},
            minimize: true,
            zindex: false,
            minifyFontValues:false,
            preferredQuote: 'single',
            normalizeString: true
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./web/css/'));
    cb();
}

function concatenation(cb) {
    gulp.src( [
        './node_modules/@fortawesome/fontawesome-free/css/all.css',
        './web/css/src/reset.css',
        './web/css/src/colors-definition.css',
        './web/css/src/font-definition.css',
        './web/css/src/sidebar-definition.css',
        './web/css/src/general.css',
        './web/css/src/header.css',
        './web/css/src/navigation.css',
        './web/css/src/grid.css',
        './web/css/src/forms.css',
        './web/css/src/badger.css',
        './web/css/src/card.css',
        './web/css/src/buttons.css',
        './web/css/src/toggle.css',
        './web/css/src/widget.css',
        './web/css/src/footer.css',

])

.pipe(concat('style.css'))
        .pipe(gulp.dest('./web/css'));
    cb()
}

function cssLoginGenerate(cb) {
    return gulp.src('./web/css/login.css')
        .pipe(rename('login.min.css'))
        .pipe(sourcemaps.init())
        .pipe(cssnano({
            discardComments: {removeAll: true},
            minimize: true,
            zindex: false,
            discardDuplicates: true,
            discardEmpty:true,
            minifyFontValues:false,
            normalizeString: true
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./web/css/'));
}
function javascript(cb) {
    gulp.src([
          './node_modules/@fortawesome/fontawesome-free/js/all.js',
          './web/js/custom.js',
    ])

    .pipe(concat('javascript-distr.min.js'))
    .pipe(rename('javascript-distr.min.js'))
    .pipe(sourcemaps.init())
    .pipe(gulp.dest('./web/js/'))
    .pipe(minify())
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest('./web/js/'))
    cb();
}

exports.build = series(clean, parallel(cssGenerate, cssLoginGenerate, javascript));
exports.default = series(clean, concatenation, parallel(cssGenerate, javascript));
