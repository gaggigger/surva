var gulp = require('gulp');

gulp.task('copy', copyToDist);

function copyToDist() {
    var files = gulp.config.copy;
    for(var i in files) {
        gulp
            .src(files[i].src)
            .pipe(gulp.dest(files[i].dest));
    }
    return gulp;
}
