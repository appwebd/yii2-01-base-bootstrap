'use strict';
var gulp        = require('gulp'),
    concat      = require('gulp-concat'),  // Concatenate files
    cssnano     = require('gulp-cssnano'), // minifier of css projaso
    uncss       = require('gulp-uncss'),
    uglify      = require('gulp-uglify'),  //Minify javascript
    rename      = require('gulp-rename'),
    sourcemaps  = require('gulp-sourcemaps');


    gulp.task('default', [
                          'concatenation',
                          'creating_file_public_css',
                          'creating_public_file_login_css',
                          'public-minimize-custom.css',
                          'concat_and_minify_javascript',
//                          'jquery-kumbiaphp_map_minify',
//                          'copy-jquery'
                          ], function () {});




gulp.task('concatenation',function(){

  return gulp.src( [
                    './vendor/semantic/ui/dist/components/button.css',
                    './vendor/semantic/ui/dist/components/breadcrumb.css',
                    './vendor/semantic/ui/dist/components/checkbox.css',
                    './vendor/semantic/ui/dist/components/divider.css',
                    './vendor/semantic/ui/dist/components/dropdown.css',
                    './vendor/semantic/ui/dist/components/grid.css',
                    './vendor/semantic/ui/dist/components/header.css',
                    './vendor/semantic/ui/dist/components/form.css',
                    './vendor/semantic/ui/dist/components/icon.css',
                    './vendor/semantic/ui/dist/components/input.css',
                    './vendor/semantic/ui/dist/components/label.css',
                    './vendor/semantic/ui/dist/components/menu.css',
                    './vendor/semantic/ui/dist/components/message.css',
                    './vendor/semantic/ui/dist/components/popup.css',
                    './vendor/semantic/ui/dist/components/site.css',
                    './vendor/semantic/ui/dist/components/sidebar.css',
                    './vendor/semantic/ui/dist/components/segment.css',
                    './vendor/semantic/ui/dist/components/table.css',
                    './vendor/semantic/ui/dist/components/transition.css',
                    './web/css/custom.css',
                  ])

      .pipe(concat('style.css'))
      .pipe(gulp.dest('./web/css'))
      .on('error', function(err) { console.error('Error in script task', err.toString()); })
});

gulp.task('creating_file_public_css', function () {
    return gulp.src('./web/css/style.css')
        .pipe(rename('style.min.css'))
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
});

gulp.task('creating_public_file_login_css', function () {
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
});



gulp.task('public-minimize-custom.css', function () {
    return gulp.src('./web/css/custom.css')
        .pipe(rename('custom.min.css'))
        .pipe(sourcemaps.init())
        .pipe(cssnano({
            discardComments: {removeAll: true},
            minimize: true,
            zindex: false,
            discardDuplicates: true,
            discardEmpty:true,
            minifyFontValues:false,
            normalizeString: true,



        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./web/css/'));
});

gulp.task('uncss', function() {
  return gulp.src([
      './dev/css/temp/style.css'
    ])
    .pipe(uncss({
      volt: [
        'default/app/views/**/*.phtml',
      ]
    }))
    .pipe(gulp.dest('./dev/css/temp/uncss.css'));
});

gulp.task('concat_and_minify_javascript',function(){

  return gulp.src([
      './vendor/components/jquery/jquery.js',
      './vendor/semantic/ui/dist/components/checkbox.js',
      './vendor/semantic/ui/dist/components/dropdown.js',
      './vendor/semantic/ui/dist/components/form.js',
      './vendor/semantic/ui/dist/components/popup.js',
      './vendor/semantic/ui/dist/components/site.js',
      './vendor/semantic/ui/dist/components/sidebar.js',
      './vendor/semantic/ui/dist/components/transition.js',

      './web/js/custom.js',

    ])

  .pipe(concat('javascript-distr.min.js'))
  .pipe(rename('javascript-distr.min.js'))
  .pipe(sourcemaps.init())
  .pipe(gulp.dest('./web/js/'))
  .pipe(uglify())
  .pipe(sourcemaps.write("."))
  .pipe(gulp.dest('./web/js/'))

  .on('error', function(err) { console.error('Error in script task', err.toString()); })
});


gulp.task('copy-jquery', function(){
    gulp.src('./vendor/components/jquery/jquery.min.*')
    .pipe(gulp.dest('./default/public/javascript/jquery/'));
});
