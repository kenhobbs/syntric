// Load required
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var compileSASS = require('gulp-sass');
var watch = require('gulp-watch');
var minifyCSS = require('gulp-cssnano');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var merge2 = require('merge2');
var compressImage = require('gulp-imagemin');
var ignore = require('gulp-ignore');
var rimraf = require('gulp-rimraf');
var clone = require('gulp-clone');
var merge = require('gulp-merge');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
var del = require('del');

var theme = 'syntric';
var base = {
	node: './node_modules/',
	src_sass: './src/sass/',
	src_js: './src/js/',
	src_img: './src/images/',
	build_css: './assets/css/',
	build_js: './assets/js/',
	build_img: './assets/images/'
};

// File watcher
gulp.task('watch', function () {
	gulp.watch(base.src_sass + '**.scss', ['compileSASS']);
	gulp.watch(base.src_js + '**.js', ['compileScripts']);
	gulp.watch(base.src_img + '*.*', ['compressImages']);
});

gulp.task('compileSASS', function () {
	gulp.src(base.src_sass + '**.scss')
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(base.build_css))
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(base.build_css));
});

gulp.task('compileScripts', function () {
	gulp.src(base.src_js + '**.js')
	.pipe(plumber())
	.pipe(gulp.dest(base.build_js))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(uglify())
	.pipe(gulp.dest(base.build_js));
});

gulp.task('compileAdminScripts', function () {
	return gulp.src(base.src_js + '_' + theme + '-admin.js')
	.pipe(plumber())
	.pipe(rename(theme + '-admin.js'))
	.pipe(gulp.dest(base.build_js));
});

gulp.task('uglifyAdminScripts', function () {
	return gulp.src(base.build_js + theme + '-admin.js')
	.pipe(plumber())
	.pipe(rename(theme + '-admin.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(base.build_js));
});

gulp.task('compileCustomizerScripts', function () {
	return gulp.src(base.src_js + '_' + 'customizer.js')
	.pipe(plumber())
	.pipe(rename('customizer.js'))
	.pipe(gulp.dest(base.build_js));
});

gulp.task('uglifyCustomizerScripts', function () {
	return gulp.src(base.build_js + 'customizer.js')
	.pipe(plumber())
	.pipe(rename('customizer.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(base.build_js));
});

gulp.task('compressImages', function () {
	gulp.src([base.src_img + '*.jpg', base.src_img + '*.gif', base.src_img + '*.png'])
	.pipe(compressImage())
	.pipe(gulp.dest(base.build_img));
});

//
//
// Bone yard
//
//

/*gulp.task('minifyCSS', function () {
 gulp.src(base.build_css + theme + '*.css')
 .pipe(sourcemaps.init({loadMaps: true}))
 .pipe(plumber())
 .pipe(rename({suffix: '.min'}))
 .pipe(minifyCSS({discardComments: {removeAll: true}}))
 .pipe(sourcemaps.write('./'))
 .pipe(gulp.dest(base.build_css));
 });*/

/*gulp.task('compileAdminSASS', function () {
 return gulp.src(base.src_sass + theme + '-admin.scss')
 .pipe(plumber())
 .pipe(compileSASS())
 .pipe(gulp.dest(base.build_css));
 });*/

/*gulp.task('minifyAdminCSS', function () {
 return gulp.src(base.build_css + theme + '-admin.css')
 .pipe(sourcemaps.init({loadMaps: true}))
 .pipe(plumber())
 .pipe(rename(theme + '-admin.min.css'))
 .pipe(minifyCSS({discardComments: {removeAll: true}}))
 .pipe(sourcemaps.write('./'))
 .pipe(gulp.dest(base.build_css));
 });*/

/*gulp.task('uglifyScripts', function () {
 return gulp.src(base.build_js + theme + '.js')
 .pipe(plumber())
 .pipe(rename(theme + '.min.js'))
 .pipe(uglify())
 .pipe(gulp.dest(base.build_js));
 });*/

// Uglifies and concat all JS files into one
/*gulp.task('admin-scripts', function () {
 var scripts = [
 base.js + '_syntric-admin.js'
 ];
 gulp.src(scripts)
 .pipe(concat(theme + '-admin.min.js'))
 .pipe(uglify())
 .pipe(gulp.dest(base.js));

 gulp.src(scripts)
 .pipe(concat(theme + '-admin.js'))
 .pipe(gulp.dest(base.js));
 });*/

