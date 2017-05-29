'use strict';

// Include gulp
var gulp = require('gulp');

// Include our plugins
var sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require('gulp-rename'),
    minify = require('gulp-minify'),
    concat = require('gulp-concat');

// Configuration
var config = {
    sassPath: './src/CustoMood/Bundle/AppBundle/Resources/css/sass',
    mainCSSFileName: 'main',
    resultSassPath: './src/CustoMood/Bundle/AppBundle/Resources/css/result',
    distSassPath: './web/bundles/customoodapp/css',
    mapFolder: './maps',
    minSuffix: '.min',
    srcJSPath: './src/CustoMood/Bundle/AppBundle/Resources/js/',
    libJSPath: './src/CustoMood/Bundle/AppBundle/Resources/js/lib',
    distJSPath: './web/bundles/customoodapp/js'
};

// Compile Sass + create source map + rename task
gulp.task('sass', function() {
    // Uncompressed version
    return gulp.src(config.sassPath + '/' + config.mainCSSFileName + '.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(config.resultSassPath));
});

// Minification task
gulp.task('minification', ['sass'], function() {
    // Compressed version (renamed + sourcemaps included)
    var minifyCss = require('gulp-minify-css');

    return gulp.src(config.resultSassPath + '/' + config.mainCSSFileName + '.css')
        .pipe(sourcemaps.init())
        .pipe(minifyCss({}))
        .pipe(sourcemaps.write(config.mapFolder, {
            mapFile: function(mapFilePath) {
                // source map files are named *.map instead of *.js.map
                return mapFilePath.replace('.css.map', '.css.map');
            }
        }))
        .pipe(gulp.dest(config.distSassPath));
});

// Minify and concatenate allscripts.js
gulp.task('js-minify', function () {
    gulp.src([
        config.libJSPath + '/jquery-3.2.1.min.js',
        config.libJSPath + '/Chart.bundle.min.js',
        config.libJSPath + '/kube.min.js',
        config.srcJSPath + '/_chart.js',
        config.srcJSPath + '/main.js'
    ])
        // .pipe(minify({
        //     ext: {
        //         min: '.min.js'
        //     },
        //     noSource: true,
        //     ignoreFiles: ['.min.js']
        // }))
        .pipe(concat('allscripts.min.js'))
        .pipe(gulp.dest(config.distJSPath))
});

// Watch files for changes task
gulp.task('watch', function() {
    gulp.watch(config.sassPath + '/**/*.scss',
        ['sass', 'minification']
    );
    gulp.watch(config.srcJSPath + '/**/*.js',
        ['js-minify']
    );
});

// Default tasks
gulp.task('default', ['watch']);
gulp.task('build', ['sass', 'minification', 'js-minify']);
