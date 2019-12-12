let gulp = require('gulp'),
postcss = require('gulp-postcss'),
sass = require('gulp-sass'),
autoprefixer = require('autoprefixer'),
cssImport = require('postcss-import'),
hexrgba =require('postcss-hexrgba');


gulp.task('styles', function(){
    return gulp.src('./assets/css/**/*.scss')
        .pipe(sass())
        .on('error', sass.logError)
        .pipe(postcss([cssImport, autoprefixer, hexrgba]))
        .on('error', function (errorInfo) {
            console.log(errorInfo.toString());
            this.emit('end');
         })
        .pipe(gulp.dest('./public_html/static/css/'));
});
