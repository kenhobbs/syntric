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
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var compressImage = require('gulp-imagemin');
var cached = require('gulp-cached');
var gap = require('gulp-append-prepend');
var sourcemaps = require('gulp-sourcemaps');
//var merge2 = require('merge2');
//var ignore = require('gulp-ignore');
//var rimraf = require('gulp-rimraf');
//var clone = require('gulp-clone');
//var merge = require('gulp-merge');
//var browserSync = require('browser-sync').create();
//var del = require('del');

var theme = 'syntric';
var src_dir = './src/';
var lib_dir = './libs/';
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
 *        /dist (compiled, other files and folders likely to co-reside here)
 *            /css
 *            /js
 *            /img
 *            /admin
 *                /css
 *                /js
 *                /img
 *        /src (precompiled)
 *            /sass
 *            /js
 *            /img
 *            /admin
 *                /sass
 *                /js
 *                /img
 *
 * Note that either /src or /dist can have other files and folders co-residing
 * within them (bootstrap, jquery, vendor, json, etc).
 *
 * Also note that both /src and /dist are set as variables above so they can
 * be aliased
 */
var dirs = {
	src_dir: './src/',

	src_sass: './src/sass/',
	src_js: './src/js/',
	src_img: './src/img/',

	src_admin_sass: './src/sass/',
	src_admin_js: './src/js/',
	src_admin_img: './src/img/',

	lib_dir: './libs/',

	dist_dir: './assets/',

	dist_css: './assets/css/',
	dist_js: './assets/js/',
	dist_img: './assets/images/',

	dist_admin_css: './assets/css/',
	dist_admin_js: './assets/js/',
	dist_admin_img: './assets/images/'
};

/**
 *  Domain mappings.  This needs to be updated whenever a new site is added
 *
 *  Configure domainMappings such that a local scss file will be duplicated into x min.css files...
 *
 *  domainMappings = {
 *  	'green.blue': ['master.localhost','master.syntric.com','another.org'],
 *  	'teal.blue': ['escalonusd.org','amadorcoe.org'],
 *  	etc...
 *  }
 */
var domainMappings = {
	/*'amadorcoe.syntric.com.min': 'amadorcoe.syntric.school.min',
	'amadoradulted.syntric.com.min': 'amadoradulted.syntric.school.min',
	'amadorhs.syntric.com.min': 'amadorhs.syntric.school.min',
	'argonauths.syntric.com.min': 'argonauths.syntric.school.min',
	'ionejr.syntric.com.min': 'ionejr.syntric.school.min',
	'jacksonjr.syntric.com.min': 'jacksonjr.syntric.school.min',
	'shenandoah.syntric.com.min': 'shenandoah.syntric.school.min',
	'ione.syntric.com.min': 'ioneel.syntric.school.min',
	'jackson.syntric.com.min': 'jacksonel.syntric.school.min',
	'pinegrove.syntric.com.min': 'pinegroveel.syntric.school.min',
	'pioneer.syntric.com.min': 'pioneerel.syntric.school.min',
	'plymouth.syntric.com.min': 'plymouthel.syntric.school.min',
	'suttercreek.syntric.com.min': 'suttercreekel.syntric.school.min',
	'northstar.syntric.com.min': 'northstar.syntric.school.min',
	'independent.syntric.com.min': 'independent.syntric.school.min',
	'community.syntric.com.min': 'community.syntric.school.min',
	'escalonusd.syntric.com.min': 'escalonusd.syntric.school.min',
	'escalonhs.syntric.com.min': 'escalonhigh.syntric.school.min',
	'elportal.syntric.com.min': 'elportalmiddle.syntric.school.min',
	'collegeville.syntric.com.min': 'collegevilleschool.syntric.school.min',
	'dent.syntric.com.min': 'dentschool.syntric.school.min',
	'farmington.syntric.com.min': 'farmingtonschool.syntric.school.min',
	'eca.syntric.com.min': 'escaloncharteracademy.syntric.school.min',
	'vista.syntric.com.min': 'vistahighschool.syntric.school.min',*/
	
	'amadorcoe.syntric.com.min': 'www.amadorcoe.org.min',
	'amadoradulted.syntric.com.min': 'www.amadoradulted.org.min',
	'amadorhs.syntric.com.min': 'amadorhs.amadorcoe.org.min',
	'argonauths.syntric.com.min': 'argonauths.amadorcoe.org.min',
	'ionejr.syntric.com.min': 'ionejr.amadorcoe.org.min',
	'jacksonjr.syntric.com.min': 'jacksonjr.amadorcoe.org.min',
	'shenandoah.syntric.com.min': 'shenandoah.amadorcoe.org.min',
	'ione.syntric.com.min': 'ioneel.amadorcoe.org.min',
	'jackson.syntric.com.min': 'jacksonel.amadorcoe.org.min',
	'pinegrove.syntric.com.min': 'pinegroveel.amadorcoe.org.min',
	'pioneer.syntric.com.min': 'pioneerel.amadorcoe.org.min',
	'plymouth.syntric.com.min': 'plymouthel.amadorcoe.org.min',
	'suttercreek.syntric.com.min': 'suttercreekel.amadorcoe.org.min',
	'northstar.syntric.com.min': 'northstar.amadorcoe.org.min',
	'independent.syntric.com.min': 'independent.amadorcoe.org.min',
	'community.syntric.com.min': 'community.amadorcoe.org.min',
	'escalonusd.syntric.com.min': 'www.escalonusd.org.min',
	'escalonhs.syntric.com.min': 'www.escalonhigh.org.min',
	'elportal.syntric.com.min': 'www.elportalmiddle.org.min',
	'collegeville.syntric.com.min': 'www.collegevilleschool.org.min',
	'dent.syntric.com.min': 'www.dentschool.org.min',
	'farmington.syntric.com.min': 'www.farmingtonschool.org.min',
	'vanallen.syntric.com.min': 'www.vanallenschool.org.min',
	'eca.syntric.com.min': 'www.escaloncharteracademy.org.min',
	'vista.syntric.com.min': 'www.vistahighschool.org.min',
	
	'master.localhost.min': 'master.syntric.com.min',
	'syntric.localhost.min': 'www.syntric.com.min',
	'blue': 'blue.min',
	'purple': 'purple.min',
	'green': 'green.min',
	'orange': 'orange.min',
	'grey': 'grey.min',
	'silver': 'silver.min',
	'teal': 'teal.min',
	'black': 'black.min',
	'white': 'white.min',

	'www.vanallenschool.org.min': 'vanallenel.syntric.school.min'
};
// Gulp watcher args
var watcherArgs = {
	ignoreInitial: false
};
// File watcher
gulp.task('watch', function () {

	// SASS watchers   , '!' + dirs.src_sass + '_*.scss'
	gulp.watch([dirs.src_sass + '*.scss', '!' + dirs.src_sass + '*-admin.scss'], watcherArgs, ['compileSASS']);
	gulp.watch([dirs.src_admin_sass + '*-admin.scss'], watcherArgs, ['compileAdminSASS']);

	// Javascript watchers
	gulp.watch([dirs.src_js + 'syntric.js'], watcherArgs, ['compileJS']);
	gulp.watch(dirs.src_admin_js + '*-admin.js', watcherArgs, ['compileAdminJS']);
	gulp.watch(dirs.src_admin_js + 'customizer.js', watcherArgs, ['compileCustomizerJS']);

	// Image watchers
	//gulp.watch(dirs.src_img + '*.*', watcherArgs, ['compressImages']);
	//gulp.watch(dirs.src_admin_img + '*.*', watcherArgs, ['compressAdminImages']);
});

gulp.task('compileSASS', function () {
	return gulp.src([dirs.src_sass + '*.scss', '!' + dirs.src_sass + '*-admin.scss'])
	// cache files so they are only compiled if they have changed
	.pipe(cached('sassFiles'))
	.pipe(plumber())

	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dist_css))
	.pipe(plumber())

	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())

	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dist_css))
	.pipe(plumber())

	.pipe(rename(function (path) {
		//console.log(path);
		path.basename = domainMappings[path.basename];
		console.log(path.basename);
	}))
	.pipe(gulp.dest(dirs.dist_css))
	.pipe(plumber())

	.pipe(rename({prefix: ''}))
	.pipe(gulp.dest(dirs.dist_css));
});
/*
gulp.task('minifyCSS', function () {
 gulp.src(dirs.dist_css + theme + '*.css')
 .pipe(sourcemaps.init({loadMaps: true}))
 .pipe(plumber())
 .pipe(rename({suffix: '.min'}))
 .pipe(minifyCSS({discardComments: {removeAll: true}}))
 .pipe(sourcemaps.write('./'))
 .pipe(gulp.dest(dirs.dist_css));
 });
 */

gulp.task('compileAdminSASS', function () {
	return gulp.src([dirs.src_admin_sass + '*-admin.scss'])
	.pipe(cached('sassAdminFiles'))
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(gulp.dest(dirs.dist_admin_css));
});
//, dirs.lib_dir + 'jquery-3.2.1/jquery.js', dirs.lib_dir + 'bootstrap-4.0.0-beta.2/dist/js/bootstrap.bundle.js', dirs.lib_dir + 'fullcalendar-3.6.1/lib/moment.min.js', dirs.lib_dir + 'fullcalendar-3.6.1/fullcalendar.js'
gulp.task('compileJS', function () {
	return gulp.src([dirs.src_js + 'syntric.js'])
	//.pipe(cached('jsFiles'))
	//.pipe(concat(theme + '.js'))
	//.pipe(gap.prependText('(function($) {'))
	//.pipe(gap.appendText('})(jQuery);'))
	.pipe(rename({suffix: '.min'}))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_js));
});

gulp.task('compileAdminJS', function () {
	return gulp.src(dirs.src_admin_js + '*-admin.js')
	.pipe(cached('jsAdminFiles'))
	.pipe(plumber())
	.pipe(concat(theme + '-admin.js'))
	.pipe(gulp.dest(dirs.dist_admin_js))
	.pipe(plumber())
	.pipe(rename(theme + '-admin.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_admin_js));
});
gulp.task('compileCustomizerJS', function () {
	return gulp.src(dirs.src_js + 'customizer.js')
	.pipe(cached('jsCustomizerFiles'))
	.pipe(plumber())
	.pipe(gulp.dest(dirs.dist_js))
	.pipe(plumber())
	.pipe(rename('customizer.min'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_js));
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