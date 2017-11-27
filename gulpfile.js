/**
 * Node directory relative to this file.  Reference this file's root
 * using './' notation.
 *
 * This is not currently in use...
 *
 * @type {string}
 */
var node_dir = './node_modules/';

// Load required node modules
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var compileSASS = require('gulp-sass');
var watch = require('gulp-watch');
var minifyCSS = require('gulp-cssnano');
var rename = require('gulp-rename');
//var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
//var merge2 = require('merge2');
var compressImage = require('gulp-imagemin');
var ignore = require('gulp-ignore');
//var rimraf = require('gulp-rimraf');
//var clone = require('gulp-clone');
//var merge = require('gulp-merge');
var sourcemaps = require('gulp-sourcemaps');
//var browserSync = require('browser-sync').create();
//var del = require('del');

var theme = 'syntric';
var src_dir = './src/';
var dist_dir = './assets/';

/**
 * Directories for source and build stylesheets, scripts and images.  
 * 
 * It is important to keep build directory structure unchanged because
 * these magic values are also used in the Wordpress PHP to enqueue 
 * the sheets and scripts.
 * 
 * Syntric theme structure:
 * 
 * All preprocessed files are in /src (aka source) and processed files are
 * in /dist (aka build or distribute).  The node_modules is also in
 * the theme root directory (ie /node_modules).
 * 
 * Within each of source and build directories there are /css or /sass,
 * /js and /img folders. All three folders are mirrored in /admin for
 * each of the /src and /assets folders.
 *
 * The result...
 *
 * /theme root
 * 		/dist (compiled, other files and folders likely to co-reside here)
 * 			/css
 * 			/js
 * 			/img
 * 			/admin
 * 				/css
 * 				/js
 * 				/img
 * 		/src (precompiled)
 * 			/sass
 * 			/js
 * 			/img
 * 			/admin
 * 				/sass
 * 				/js
 * 				/img
 *
 * Note that either /src or /dist can have other files and folders co-residing
 * within them (bootstrap, jquery, vendor, json, etc).
 *
 * Also note that both /src and /dist are set as variables above so they can
 * be aliased
 */
var dirs = {
	src_dir: src_dir,

	src_sass: src_dir + 'sass/',
	src_js: src_dir + 'js/',
	src_img: src_dir + 'img/',

	src_admin_sass: src_dir + 'admin/sass/',
	src_admin_js: src_dir + 'admin/js/',
	src_admin_img: src_dir + 'admin/img/',

	dist_dir: dist_dir,

	dist_css: dist_dir + 'css/',
	dist_js: dist_dir + 'js/',
	dist_img: dist_dir + 'img/',

	dist_admin_css: dist_dir + 'admin/css/',
	dist_admin_js: dist_dir + 'admin/js/',
	dist_admin_img: dist_dir + 'admin/img/'
};
// Gulp watcher args
var watcherArgs = {
	ignoreInitial: true, // true


};
// File watcher
gulp.task('watch', function () {
	// SCSS watchers
	gulp.watch(dirs.src_sass + '*.scss', ['compileSASS']);
	gulp.watch(dirs.src_admin_sass + '*.scss', ['compileAdminSASS']);
	// Javascript watchers
	gulp.watch(dirs.src_js + '*.js', ['compileJS']);
	gulp.watch(dirs.src_admin_js + '*.js', ['compileAdminJS']);
	// Image watchers
	gulp.watch(dirs.src_img + '*.*', ['compressImages']);
	gulp.watch(dirs.src_admin_img + '*.*', ['compressAdminImages']);
});

gulp.task('compileSASS', function () {
	gulp.src([dirs.src_sass + '*.scss', '!' + dirs.src_sass + '_*.scss' ])
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dist_css))
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dist_css));
});

gulp.task('compileAdminSASS', function () {
	gulp.src([dirs.src_admin_sass + '*.scss', '!' + dirs.src_admin_sass + '_*.scss' ])
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dist_admin_css))
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dist_admin_css));
});

gulp.task('compileJS', function () {
	gulp.src([dirs.src_js + '*.js', '!' + dirs.src_js + '_*.js' ])
	.pipe(plumber())
	.pipe(gulp.dest(dirs.dist_js))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_js));
});

gulp.task('compileAdminJS', function () {
	return gulp.src([dirs.src_admin_js + '*.js', '!' + dirs.src_admin_js + '_*.js' ])
	.pipe(plumber())
	.pipe(gulp.dest(dirs.dist_admin_js))
	.pipe(plumber())
	.pipe(rename(theme + '.min'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_admin_js));
});

gulp.task('compressImages', function () {
	gulp.src([dirs.src_img + '*.jpg', dirs.src_img + '*.gif', dirs.src_img + '*.png', '!' + dirs.src_img + '_*.*'])
	.pipe(compressImage())
	.pipe(gulp.dest(dirs.dist_img));
});

gulp.task('compressAdminImages', function () {
	gulp.src([dirs.src_admin_img + '*.jpg', dirs.src_admin_img + '*.gif', dirs.src_admin_img + '*.png'])
	.pipe(compressImage())
	.pipe(gulp.dest(dirs.dist_admin_img));
});

//
//
// Bone yard
//
//

/*

gulp.task('uglifyAdminScripts', function () {
	return gulp.src(dirs.dist_js + '*-admin.js')
	.pipe(plumber())
	.pipe(rename(theme + '-admin.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_js));
});

gulp.task('compileCustomizerScripts', function () {
	return gulp.src(dirs.src_js + '_' + 'customizer.js')
	.pipe(plumber())
	.pipe(rename('customizer.js'))
	.pipe(gulp.dest(dirs.dist_js));
});

gulp.task('uglifyCustomizerScripts', function () {
	return gulp.src(dirs.dist_js + 'customizer.js')
	.pipe(plumber())
	.pipe(rename('customizer.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_js));
});

gulp.task('minifyCSS', function () {
 gulp.src(dirs.dist_css + theme + '*.css')
 .pipe(sourcemaps.init({loadMaps: true}))
 .pipe(plumber())
 .pipe(rename({suffix: '.min'}))
 .pipe(minifyCSS({discardComments: {removeAll: true}}))
 .pipe(sourcemaps.write('./'))
 .pipe(gulp.dest(dirs.dist_css));
 });*/

/*gulp.task('compileAdminSASS', function () {
 return gulp.src(dirs.src_sass + theme + '-admin.scss')
 .pipe(plumber())
 .pipe(compileSASS())
 .pipe(gulp.dest(dirs.dist_css));
 });*/

/*gulp.task('minifyAdminCSS', function () {
 return gulp.src(dirs.dist_css + theme + '-admin.css')
 .pipe(sourcemaps.init({loadMaps: true}))
 .pipe(plumber())
 .pipe(rename(theme + '-admin.min.css'))
 .pipe(minifyCSS({discardComments: {removeAll: true}}))
 .pipe(sourcemaps.write('./'))
 .pipe(gulp.dest(dirs.dist_css));
 });*/

/*gulp.task('uglifyScripts', function () {
 return gulp.src(dirs.dist_js + theme + '.js')
 .pipe(plumber())
 .pipe(rename(theme + '.min.js'))
 .pipe(uglify())
 .pipe(gulp.dest(dirs.dist_js));
 });*/

// Uglifies and concat all JS files into one
/*gulp.task('admin-scripts', function () {
 var scripts = [
 dirs.js + '_syntric-admin.js'
 ];
 gulp.src(scripts)
 .pipe(concat(theme + '-admin.min.js'))
 .pipe(uglify())
 .pipe(gulp.dest(dirs.js));

 gulp.src(scripts)
 .pipe(concat(theme + '-admin.js'))
 .pipe(gulp.dest(dirs.js));
 });*/

