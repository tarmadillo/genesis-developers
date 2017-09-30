'use strict';

var gulp = require('gulp'),
    bourbon = require('bourbon').includePaths,
    neat = require('bourbon-neat').includePaths,
    sass = require('gulp-sass'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    cssMinify = require('gulp-cssnano'),
    sassLint = require('gulp-sass-lint'),

    // Utilities
    rename = require('gulp-rename'),
    notify = require('gulp-notify'),
    plumber = require('gulp-plumber');

/************
 * Utilities
 ************/

/**
 * Error Handling
 *
 */

function handleErrors() {
    var args = Array.prototype.slice.call(arguments);
    
    notify.onError({
        title: 'Task Failed [<%= error.message %>',
        message: 'See Console',
        sound: 'Sosumi'  
    }).apply(this, args);
    
    //gutil.beep();
    
    this.emit('end');    
}

/************
 * CSS Tasks
 ************
/**
 * PostCSS Task Handler
 */
gulp.task('postcss', function(){
   
    return gulp.src('sass/style.scss')
    
        // Error Handling
        .pipe(plumber({
            errorHandler: handleErrors
        }))
    
        .pipe( sourcemaps.init())    
    
        .pipe( sass({
            includePaths: [].concat( bourbon, neat),
            errLogToConsole: true,
            outputStyle: 'expanded' //options: nested, expanded, compact, compressed
        }))
    
        .pipe( postcss([
            autoprefixer({
                browsers: ['last 2 versions']
            })        
        ]))
        
        .pipe(sourcemaps.write())
        
        
              
        .pipe(gulp.dest('./'))
    
        .pipe(notify({
            message: 'Styles are built.'      
        }));
              
        
});

gulp.task('css:minify', ['postcss'], function() {
    return gulp.src('style.css')
        .pipe( cssMinify ({
            safe: true
    }))
        .pipe(rename('style.min.css'))
        .pipe(gulp.dest('./'));
});

gulp.task('sass:lint', ['css:minify'], function(){
    gulp.src([
        'sass/style.scss',
        '!sass/html5-reset/_baseline-normalize',
        '!sass/utilities/animate/**/*.*'
    ])
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError())
});

/************
 * All Task Callers
 ************
 */
gulp.task('watch', function() {
    gulp.watch('sass/**/*.scss', ['styles']);
});

gulp.task('styles', ['sass:lint']);