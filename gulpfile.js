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
//var compressImage = require('gulp-imagemin');
var cached = require('gulp-cached');
var gap = require('gulp-append-prepend');
var sourcemaps = require('gulp-sourcemaps');
//var changed = require('gulp-changed');
//var merge2 = require('merge2');
//var ignore = require('gulp-ignore');
//var rimraf = require('gulp-rimraf');
//var clone = require('gulp-clone');
//var merge = require('gulp-merge');
//var browserSync = require('browser-sync').create();
//var del = require('del');

var theme = 'syntric';

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
	src_sass: './src/sass/',
	src_js: './src/js/',
	src_img: './src/img/',
	dest_css: './assets/css/',
	dest_js: './assets/js/',
	dest_img: './assets/images/'
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
 *
 *	Mapping schema.
 *
 *	1. Create a mapping for each domain without "www" e.g. syntric.com, clouddeep.com, etc
 *	2. Each mapping will generate dev, staging and production ccs files both formatted and minimized, and a map file
 *	3. Also, include mappings for each color scheme which generates formatted, minimized and map files the same for dev, stage and prod e.g. blue, orange, silver, etc
 *
 * 	Color schemes: cyan, blue, green, magenta, red, yellow, silver, grey, black, white
 *
 * 'blue': 'blue.min',
	'purple': 'purple.min',
	'green': 'green.min',
	'orange': 'orange.min',
	'grey': 'grey.min',
	'silver': 'silver.min',
	'teal': 'teal.min',
	'black': 'black.min',
	'white': 'white.min',
 *
 */
/*var domainMappings = {
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
};*/

/*var domains = [
	'amadorcoe.org.scss',
	'amadorhs.amadorcoe.org.scss',
	'argonauths.amadorcoe.org.scss',
	'ionejr.amadorcoe.org.scss',
	'jacksonjr.amadorcoe.org.scss',
	'shenandoah.amadorcoe.org.scss',
	'ioneel.amadorcoe.org.scss',
	'jacksonel.amadorcoe.org.scss',
	'pinegroveel.amadorcoe.org.scss',
	'pioneerel.amadorcoe.org.scss',
	'plymouthel.amadorcoe.org.scss',
	'suttercreekel.amadorcoe.org.scss',
	'northstar.amadorcoe.org.scss',
	'independent.amadorcoe.org.scss',
	'community.amadorcoe.org.scss',
	'escalonusd.org.scss',
	'escalonhigh.org.scss',
	'elportalmiddle.org.scss',
	'escaloncharteracademy.org.scss',
	'collegevilleschool.orgv',
	'dentschool.org.scss',
	'farmingtonschool.org.scss',
	'vanallenschool.org.scss',
	'vistahighschool.org.scss'
];

var colors = [
	'color.blue.scss',
	'color.white.scss',
	'color.silver.scss',
	'color.grey.scss',
	'color.black.scss',
	'color.purple.scss',
	'color.green.scss',
	'color.orange.scss',
	'color.teal.scss'
];

var base = [
	'_syntric.scss',
	'_variables.scss'
];

var admin = [
	'syntric-admin.scss'
];*/

var domains = dirs.src_sass + '*.+(org|com).scss';
var dev = dirs.src_sass + '*.+(localhost).scss';
var colors = dirs.src_sass + 'color.*.scss';
var base = dirs.src_sass + '_*.scss';
var admin = dirs.src_sass + '*-admin.scss';
var css = dirs.dest_css + '*.min.css';

var js = dirs.src_js + 'syntric.js';
var js_admin = dirs.src_js + 'syntric-admin.js';
var js_customizer = dirs.src_js + 'customizer.js';
var js_google_maps = dirs.src_js + 'google-maps.js';

/**
 * Watchers
 *
 * If a base file (starts with an underscore) is changed, rerun all it's dependents.
 * If a dependent file is changed, run only the dependent.
 *
 * Watch and run front-end files separately from admin files
 */

// File watcher
gulp.task('watch', function () {
	// SASS watchers
	// base change, run all
	gulp.watch(domains, ['compileDomain']);
	// domain or color change, run only it
	gulp.watch(dev, ['compileDev']);
	// domain or color change, run only it
	gulp.watch(colors, ['compileColor']);
	// admin change, run only it
	gulp.watch(admin, ['compileAdmin']);
	// base change, run domains and colors
	gulp.watch(base, ['compileAll']);
	// rename *.min.css in dest for staging server (schooltechpro.com)
	gulp.watch(css, ['compileCSS']);

	// Javascript watchers
	// change to front-end js - currently syntric.js
	gulp.watch(js, ['compileFrontendJS']);
	// change to admin js - currently syntric-admin.js
	gulp.watch(js_admin, ['compileAdminJS']);
	// customizer.js change
	gulp.watch(js_customizer, ['compileCustomizer']);
	// google-maps.js change
	gulp.watch(js_google_maps, ['compileGoogleMaps']);


	// Javascript watchers
	//gulp.watch([dirs.src_js + 'syntric.js'], {ignoreInitial: false}, ['compileJS']);
	//gulp.watch([dirs.src_js + '*-admin.js'], {ignoreInitial: false}, ['compileAdminJS']);
	//gulp.watch([dirs.src_js + 'customizer.js'], {ignoreInitial: false}, ['compileCustomizerJS']);
	// Image watchers
	//gulp.watch(dirs.src_img + '*.*', watcherArgs, ['compressImages']);
	//gulp.watch(dirs.src_img + '*.*', watcherArgs, ['compressAdminImages']);
});

// Dev file watcher
gulp.task('watchDev', function () {
	// SASS watchers
	gulp.watch(dev, ['compileDev']);
	gulp.watch(admin, ['compileAdmin']);
	gulp.watch(base, ['compileDev']);
	gulp.watch(css, ['compileCSS']);
	// Javascript watchers
	// change to front-end js - currently syntric.js
	gulp.watch(js, ['compileFrontendJS']);
	// change to admin js - currently syntric-admin.js
	gulp.watch(js_admin, ['compileAdminJS']);
	gulp.watch(js_customizer, ['compileCustomizer']);
	gulp.watch(js_google_maps, ['compileGoogleMaps']);
});

/*
Compile domain files
 */
gulp.task('compileDomain', function () {
		console.log('compileDomain running');
		return gulp.src(domains)
		//.pipe(changed(dirs.dest_css))
		.pipe(compileSASS())
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(rename({suffix: '.min'}))
		.pipe(minifyCSS({discardComments: {removeAll: true}}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename(function (path) {
			console.log(path.basename);
			path.basename = domains[path.basename];
		}))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename({prefix: ''}))
		.pipe(gulp.dest(dirs.dest_css));
});
gulp.task('compileDev', function () {
	console.log('compileDev running');
	return gulp.src(dev)
	//.pipe(changed(dirs.dest_css))
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename(function (path) {
		console.log(path.basename);
		path.basename = domains[path.basename];
	}))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename({prefix: ''}))
	.pipe(gulp.dest(dirs.dest_css));
});
gulp.task('compileCSS', function () {
	console.log('compileCSS running');
	return gulp.src(css)
	//.pipe(changed(dirs.dest_css))
	//.pipe(compileSASS())
	//.pipe(gulp.dest(dirs.dest_css))
	//.pipe(plumber())
	//.pipe(sourcemaps.init({loadMaps: true}))
	//.pipe(rename({suffix: '.min'}))
	//.pipe(minifyCSS({discardComments: {removeAll: true}}))
	//.pipe(sourcemaps.write('./'))
	//.pipe(gulp.dest(dirs.dest_css))
	//.pipe(plumber())
	.pipe(rename(function (path) {
		//console.log(path.basename);
		//path.basename = domains[path.basename];
		var pathArr = path.basename.split('.');
		path.basename = pathArr[0] + '.schooltechpro.com.min';
	}))
	.pipe(gulp.dest(dirs.dest_css));
	//.pipe(plumber())
	//.pipe(rename({prefix: ''}))
	//.pipe(gulp.dest(dirs.dest_css));
});
gulp.task('compileColor', function () {
		console.log('compileColor running');
		return gulp.src(colors)
		//.pipe(changed(dirs.dest_css))
		.pipe(compileSASS())
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(rename({suffix: '.min'}))
		.pipe(minifyCSS({discardComments: {removeAll: true}}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename(function (path) {
			console.log(path.basename);
			path.basename = domains[path.basename];
		}))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename({prefix: ''}))
		.pipe(gulp.dest(dirs.dest_css));
});
gulp.task('compileAdmin', function () {
		console.log('compileAdmin running');
		return gulp.src(admin)
		//.pipe(changed(dirs.dest_css))
		.pipe(compileSASS())
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(rename({suffix: '.min'}))
		.pipe(minifyCSS({discardComments: {removeAll: true}}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename(function (path) {
			console.log(path.basename);
			path.basename = domains[path.basename];
		}))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename({prefix: ''}))
		.pipe(gulp.dest(dirs.dest_css));
});
gulp.task('compileAll', function () {
		console.log('compileAll running');
	return gulp.src([domains, colors, dev])
		//.pipe(changed(dirs.dest_css))
		.pipe(compileSASS())
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(rename({suffix: '.min'}))
		.pipe(minifyCSS({discardComments: {removeAll: true}}))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename(function (path) {
			console.log(path.basename);
			path.basename = domains[path.basename];
		}))
		.pipe(gulp.dest(dirs.dest_css))
		.pipe(plumber())
		.pipe(rename({prefix: ''}))
		.pipe(gulp.dest(dirs.dest_css));
});
/*gulp.task('compileDomain', function () {
	//var dependents = domains.concat(colors);
	return gulp.src(domains)
	// cache files so they are only compiled if they have changed
	//.pipe(cached('sassFiles'))
	//.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename(function (path) {
		console.log(path);
		path.basename = domains[path.basename];
		console.log(path);
	}))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename({prefix: ''}))
	.pipe(gulp.dest(dirs.dest_css));
});

gulp.task('compileColor', function () {
	//var dependents = domains.concat(colors);
	return gulp.src(domains)
	// cache files so they are only compiled if they have changed
	//.pipe(cached('sassFiles'))
	//.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename(function (path) {
		console.log(path);
		path.basename = domains[path.basename];
		console.log(path);
	}))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename({prefix: ''}))
	.pipe(gulp.dest(dirs.dest_css));
});*/

gulp.task('compileSASS', function () {
	return gulp.src([dirs.src_sass + '*.scss', '!' + dirs.src_sass + '*-admin.scss'])
	// cache files so they are only compiled if they have changed
	.pipe(cached('sassFiles'))
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(plumber())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(sourcemaps.write('./'))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename(function (path) {
		console.log(path.basename);
		path.basename = domainMappings[path.basename];
	}))
	.pipe(gulp.dest(dirs.dest_css))
	.pipe(plumber())
	.pipe(rename({prefix: ''}))
	.pipe(gulp.dest(dirs.dest_css));
});

gulp.task('compileAdminSASS', function () {
	return gulp.src([dirs.src_sass + '*-admin.scss'])
	.pipe(cached('sassAdminFiles'))
	.pipe(plumber())
	.pipe(compileSASS())
	.pipe(rename({suffix: '.min'}))
	.pipe(minifyCSS({discardComments: {removeAll: true}}))
	.pipe(gulp.dest(dirs.dest_css));
});

gulp.task('compileFrontendJS', function () {
	console.log('Compiling front end js');
	return gulp.src([dirs.src_js + 'syntric.js'])
	.pipe(rename({suffix: '.min'}))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dest_js));
});

gulp.task('compileAdminJS', function () {
	return gulp.src(js_admin)
	.pipe(cached('jsAdminFiles'))
	.pipe(plumber())
	.pipe(concat(theme + '-admin.js'))
	.pipe(gulp.dest(dirs.dest_js))
	.pipe(plumber())
	.pipe(rename(theme + '-admin.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dest_js));
});
gulp.task('compileCustomizer', function () {
	return gulp.src(js_customizer)
	.pipe(cached('jsCustomizerFiles'))
	.pipe(plumber())
	.pipe(gulp.dest(dirs.dest_js))
	.pipe(plumber())
	.pipe(rename('customizer.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dest_js));
});
gulp.task('compileGoogleMaps', function () {
	return gulp.src(js_google_maps)
	.pipe(cached('jsGoogleMapsFiles'))
	.pipe(plumber())
	.pipe(gulp.dest(dirs.dest_js))
	.pipe(plumber())
	.pipe(rename('google-maps.min.js'))
	.pipe(uglify())
	.pipe(gulp.dest(dirs.dest_js));
});

gulp.task('compressImages', function () {
	gulp.src([dirs.src_img + '*.jpg', dirs.src_img + '*.gif', dirs.src_img + '*.png', '!' + dirs.src_img + '_*.*'])
	.pipe(compressImage())
	.pipe(gulp.dest(dirs.dest_img));
});

gulp.task('compressAdminImages', function () {
	gulp.src([dirs.src_img + '*.jpg', dirs.src_img + '*.gif', dirs.src_img + '*.png'])
	.pipe(compressImage())
	.pipe(gulp.dest(dirs.dest_img));
});