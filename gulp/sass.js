var gulp = require('gulp');
var gulpSass = require('gulp-sass');

gulp.task('sass', compileSass);

function compileSass() {
    var opts = gulp.config.sass;
    return gulp
        .src(opts.src)
        .pipe(
            gulpSass(opts.options)
                .on('error', gulpSass.logError)
        )
        .pipe(gulp.dest(opts.dest));
}
