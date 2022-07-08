import gulp from 'gulp';
import cleanCSS from 'gulp-clean-css';
import newer from 'gulp-newer';
import imagemin from 'gulp-imagemin';
import jpegtran from 'imagemin-jpegtran';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import gulpSass from "gulp-sass";
import nodeSass from "node-sass";
const sass = gulpSass(nodeSass);


// SASS files convert to CSS
gulp.task('pack-sass', function() {
    return gulp.src('./dev/sass/style.scss')
        .pipe(sass())
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('../themes/urbak/css/'))
});

// JS MINIFY
gulp.task('pack-js', function () {
    return gulp.src('./dev/js/*.js')
        .pipe(concat('bundle.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('../themes/urbak/js/'))
});


// Minify Images
gulp.task('pack-images', function() {
    return gulp.src('./dev/pre-images/*')
        .pipe(newer('../themes/urbak/images/'))
        .pipe(imagemin({
            interlaced: true,
            progressive: true,
            optimizationLevel: 5,
            svgoPlugins: [{removeViewBox: true}],
            use: [jpegtran()]
        }))
        .pipe(gulp.dest('../themes/urbak/images'));
});

// Watch when any code is changed
gulp.task('watch', function(){
    gulp.watch('./dev/sass/*.scss', gulp.parallel(['pack-sass']))
    gulp.watch('./dev/js/*.js', gulp.parallel(['pack-js']))
    gulp.watch('./dev/pre-images/*', gulp.parallel(['pack-images']))
});



gulp.task('default',  gulp.series(['pack-sass', 'pack-images', 'pack-js', 'watch']));