let gulp = require('gulp'),
watch = require('gulp-watch');
// browserSync = require('browser-sync').create();


gulp.task('watch', function(){
    gulp.start('styles');
    // browserSync.init({
    //     notify: false,
    //     server: {
    //         baseDir: "app"
    //     }
    // });


    // watch('app/index.html', function(){
    //      browserSync.reload();
    // });

    // watch('./assets/styles/**/*.scss', function(){
    //     gulp.start('cssInject');
    // });

    watch('./assets/styles/**/*.scss', function(){
        gulp.start('styles');
    });

    watch('./assets/scripts/**/*.js', function(){
        gulp.start('scripts');
    });

    // watch('./assets/scripts/**/*.js', function(){
    //     gulp.start('scriptsRefresh');
    // })

 });

//  gulp.task('cssInject', ['styles'],  function(){
//     return gulp.src('./app/styles/styles.css')
//     .pipe(browserSync.stream());
// });

// gulp.task('scriptsRefresh', ['scripts'], function(){
//     browserSync.reload();
// });