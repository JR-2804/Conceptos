var gulp = require('gulp'),
del = require('del'),
usemin = require('usemin'),
rev = require('gulp-rev'),
cssnano = require('gulp-cssnano'),
uglify = require('gulp-uglify'),
imagemin = require('gulp-imagemin');

gulp.task('deleteDistFolder', function(){
    return del('./dist');
});

gulp.task('optimizeImage', ['deleteDistFolder'], function(){
    return gulp.src('./app/assets/images/icons/**/*')
        .pipe(imagemin({
            progressive: true,
            interlace: true,
            multiplass: true
        }))
        .pipe(gulp.dest('./dist/assets/images/icons/'));
});

gulp.task('usemin', ['deleteDistFolder'], function () { 
    return gulp.src('./app/index.html')
                .pipe(usemin({
                    css: [function() { return rev() }, function () { return cssnano() }],
                    js:  [function() { return rev() }, function () { return uglify() }]
                }))
                .pipe(gulp.dest('./dist'));
 });

gulp.task('build', ['deleteDistFolder', 'usemin']);