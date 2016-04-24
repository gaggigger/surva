var gulp = require('gulp');
var gulpUseRef = require('gulp-useref');

gulp.task('useref', compileUseRef);

function compileUseRef() {
    var opts = gulp.config.useRef;
    return gulp
        .src(opts.src)
        .pipe(gulpUseRef())
        .pipe(gulp.dest(opts.dest));
}
