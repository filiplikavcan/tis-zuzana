'use strict';

// *******************************
// *       REQUIREMENTS          *
// *******************************

const gulp = require('gulp');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
// const postcss = require('gulp-postcss');
const autoprefixer = require('gulp-autoprefixer');
// const cssnano = require('cssnano');
// const rename = require('gulp-rename')
// const runSequence = require('run-sequence');
const minifyCss = require('gulp-minify-css');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const del = require('del');

// *******************************
// *         SASS TASKS          *
// *******************************

gulp.task('sass', function() {
  return gulp.src('./frontend/sass/styles.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('styles.css'))
    .pipe(autoprefixer({
        browsers: 'last 5 versions',
        cascade: false
    }))
    .pipe(gulp.dest('./static/css'))
    .pipe(rename({ extname: '.min.css' }))
    .pipe(minifyCss({ keepSpecialComments: 1, keepBreaks: false, aggressiveMerging: false }))
    .pipe(gulp.dest('./static/css'));
});

// *******************************
// *         JS TASKS          *
// *******************************

gulp.task('js', function() {
  return gulp.src('./frontend/js/*.js')
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('./static/js'))
    .pipe(rename({ extname: '.min.js' }))
    .pipe(uglify())
    .pipe(gulp.dest('./static/js'));
});

// *******************************
// *         ASSETS TASKS          *
// *******************************

gulp.task('assets', function() {
    del(['./static/assets/**']);

    return gulp.src('frontend/assets/**/*.*')
        .pipe(gulp.dest('./static/assets'));
});

// *******************************
// *         WATCH TASKS         *
// *******************************

const sassFiles = [
  './frontend/sass/**/*.scss'
];

const jsFiles = [
  './frontend/js/**/*.scss'
];

const assetsFiles = [
  './frontend/assets/**/*.*'
];

gulp.task('watch', function() {
  gulp.watch(sassFiles, ['sass']);
  gulp.watch(jsFiles, ['js']);
  gulp.watch(assetsFiles, ['assets']);
});

// *******************************
// *         MAIN TASKS          *
// *******************************

gulp.task('default', ['sass', 'js', 'assets', 'watch']);
