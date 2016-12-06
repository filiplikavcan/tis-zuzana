'use strict';

// *******************************
// *       REQUIREMENTS          *
// *******************************

const gulp = require('gulp');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
// const postcss = require('gulp-postcss');
// const autoprefixer = require('autoprefixer');
// const cssnano = require('cssnano');
// const rename = require('gulp-rename')
// const runSequence = require('run-sequence');

// *******************************
// *         SASS TASKS          *
// *******************************

gulp.task('sass', function() {
  return gulp.src('./sass/styles.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(concat('styles.css'))
    .pipe(gulp.dest('./css'));
});

// *******************************
// *         WATCH TASKS         *
// *******************************

const sassFiles = [
  './sass/**/*.scss',
];

gulp.task('watch', function() {
  gulp.watch(sassFiles, ['sass']);
});

// *******************************
// *         MAIN TASKS          *
// *******************************

gulp.task('default', ['sass', 'watch']);
