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
//var sourcemaps = require('gulp-sourcemaps');
//var browserSync = require('browser-sync').create();
//var del = require('del');
var cached = require('gulp-cached');

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

	src_sass: './src/sass/',
	src_js: './src/js/',
	src_img: './src/img/',

	src_admin_sass: './src/sass/',
	src_admin_js: './src/js/',
	src_admin_img: './src/img/',

	dist_dir: dist_dir,

	dist_css: './assets/css/',
	dist_js: './assets/js/',
	dist_img: './assets/images/',

	dist_admin_css: './assets/css/',
	dist_admin_js: './assets/js/',
	dist_admin_img: './assets/images/'
};
/** Domain mappings.  This needs to be updated whenever a new site is added **/
var domainMappings = {
	'amadorcoe.syntric.com.min': 'www.amadorcoe.org.min',
	'amadorhs.syntric.com.min': 'www.amadorcoe.org.amadorhs.min',
	'argonauths.syntric.com.min': 'www.amadorcoe.org.argonauths.min',
	'ionejr.syntric.com.min': 'www.amadorcoe.org.ionejr.min',
	'jacksonjr.syntric.com.min': 'www.amadorcoe.org.jacksonjr.min',
	'shenandoah.syntric.com.min': 'www.amadorcoe.org.shenandoah.min',
	'ione.syntric.com.min': 'www.amadorcoe.org.ione.min',
	'jackson.syntric.com.min': 'www.amadorcoe.org.jackson.min',
	'pinegrove.syntric.com.min': 'www.amadorcoe.org.pinegrove.min',
	'pioneer.syntric.com.min': 'www.amadorcoe.org.pioneer.min',
	'plymouth.syntric.com.min': 'www.amadorcoe.org.plymouth.min',
	'suttercreek.syntric.com.min': 'www.amadorcoe.org.suttercreek.min',
	'scprimary.syntric.com.min': 'www.amadorcoe.org.scprimary.min',
	'northstar.syntric.com.min': 'www.amadorcoe.org.northstar.min',
	'independent.syntric.com.min': 'www.amadorcoe.org.independent.min',
	'escalonusd.syntric.com.min': 'www.escalonusd.org.min',
	'escalonhs.syntric.com.min': 'www.escalonhigh.org.min',
	'elportal.syntric.com.min': 'www.elportalmiddle.org.min',
	'collegeville.syntric.com.min': 'www.collegevilleschool.org.min',
	'dent.syntric.com.min': 'www.dentschool.org.min',
	'farmington.syntric.com.min': 'www.farmingtonschool.org.min',
	'vanallen.syntric.com.min': 'www.vanallenschool.org.min',
	'eca.syntric.com.min': 'www.escaloncharteracademy.org.min',
	'vista.syntric.com.min': 'www.vistahighschool.org.min',
}
// Gulp watcher args
var watcherArgs = {
	ignoreInitial: true
};
// File watcher
gulp.task('watch', function () {
	// SCSS watchers
	gulp.watch([dirs.src_sass + '*.scss', '!' + dirs.src_sass + '*-admin.scss', '!' + dirs.src_sass + '_*.scss'], watcherArgs, ['compileSASS']);
	gulp.watch([dirs.src_admin_sass + '*-admin.scss'], watcherArgs, ['compileAdminSASS']);
	// Javascript watchers
	gulp.watch([dirs.src_js + '*.js', '!' + dirs.src_js + '*-admin.js', '!' + dirs.src_js + '_*.js'], watcherArgs, ['compileJS']);
	gulp.watch(dirs.src_admin_js + '*-admin.js', watcherArgs, ['compileAdminJS']);
	// Image watchers
	//gulp.watch(dirs.src_img + '*.*', watcherArgs, ['compressImages']);
	////gulp.watch(dirs.src_admin_img + '*.*', watcherArgs, ['compressAdminImages']);
	// Copy/rename for domains
	////gulp.watch(dirs.dist_css + '*.com.min.css', watcherArgs, ['generateDomainCSS']);
	////gulp.watch(dirs.dist_css + '*.com.min.css.map', watcherArgs, ['generateDomainCSSMap']);
});

gulp.task('compileSASS', function () {
	return gulp.src([dirs.src_sass + '*.scss', '!' + dirs.src_sass + '*-admin.scss', '!' + dirs.src_sass + '_*.scss' ])
	.pipe(cached('sassFiles'))
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dist_css))
	//.pipe(sourcemaps.init({loadMaps: true}))
	//.pipe(sourcemaps.init())
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	//.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dist_css))
	.pipe(plumber())
	.pipe(rename(function(path) {
		path.basename = domainMappings[path.basename];
	}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(gulp.dest(dirs.dist_css));
});

gulp.task('compileAdminSASS', function () {
	return gulp.src([dirs.src_admin_sass + '*-admin.scss'])
	.pipe(cached('sassAdminFiles'))
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dist_admin_css))
	//.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	//.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dist_admin_css));
	console.log('compileAdminSASS completed');
});

gulp.task('compileJS', function () {
	return gulp.src([dirs.src_js + '*.js', '!' + dirs.src_js + '_*.js' ])
	.pipe(cached('jsFiles'))
	.pipe(plumber())
	.pipe(gulp.dest(dirs.dist_js))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dist_js));
});

gulp.task('compileAdminJS', function () {
	return gulp.src([dirs.src_admin_js + '*.js', '!' + dirs.src_admin_js + '_*.js' ])
	.pipe(cached('jsAdminFiles'))
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

gulp.task('generateDomainCSS', function () {
	gulp.src([dirs.dist_css + '*.com.min.css'])
	.pipe(rename(function(path) {
		console.log(path);
		path.basename = domainMappings[path.basename];
		console.log(path);
	}))
	.pipe(gulp.dest(dirs.dist_css));
})

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

