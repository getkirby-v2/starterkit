var gulp   = require('gulp');
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var cssmin = require('gulp-cssmin');
var sass   = require('gulp-sass');
var image  = require('gulp-image');

gulp.task('panel-js', function() {

  return gulp.src(
    [
      'assets/js/src/plugins/*.js',
      'assets/js/src/jquery/jquery.js',
      'assets/js/src/jquery/plugins/*.js',
      'assets/js/src/components/*.js'
    ]) 
    .pipe(concat('panel.js')) 
    .pipe(gulp.dest('assets/js/dist'))
    .pipe(rename('panel.min.js'))
    .pipe(uglify()) 
    .pipe(gulp.dest('assets/js/dist'));

});

gulp.task('app-js', function() {

  return gulp.src('assets/js/src/app.js')
    .pipe(gulp.dest('assets/js/dist'))
    .pipe(rename('app.min.js'))
    .pipe(uglify()) 
    .pipe(gulp.dest('assets/js/dist'));

});

gulp.task('form-js', function() {

  return gulp.src('app/fields/*/assets/js/*.js')
    .pipe(jshint())
    .pipe(jshint.reporter('default'))
    .pipe(concat('form.js')) 
    .pipe(gulp.dest('assets/js/dist'))
    .pipe(rename('form.min.js'))
    .pipe(uglify()) 
    .pipe(gulp.dest('assets/js/dist'));

});

gulp.task('panel-css', function() {

  return gulp.src('assets/scss/panel.scss')
    .pipe(sass().on('error', sass.logError)) 
    .pipe(gulp.dest('assets/css'))
    .pipe(rename('panel.min.css'))
    .pipe(cssmin()) 
    .pipe(gulp.dest('assets/css'));    

});

gulp.task('form-css', function() {

  return gulp.src('app/fields/*/assets/css/*.css')
    .pipe(concat('form.css')) 
    .pipe(gulp.dest('assets/css'))
    .pipe(rename('form.min.css'))
    .pipe(cssmin()) 
    .pipe(gulp.dest('assets/css'));

});

gulp.task('image', function() {
  gulp.src('assets/images/*')
    .pipe(image())
    .pipe(gulp.dest('assets/images'));
});

gulp.task('watch', function() {

  gulp.watch('assets/scss/**/*.scss', ['panel-css']);
  gulp.watch('assets/js/src/**/*.js', ['panel-js', 'app-js']);
  gulp.watch('app/fields/*/assets/js/*.js', ['form-js']);
  gulp.watch('app/fields/*/assets/css/*.css', ['form-css']);

});

gulp.task('default', [
  'panel-css', 
  'panel-js', 
  'app-js', 
  'form-js', 
  'form-css',
  'image'
]);