var gulp = require('gulp');
var del = require('del');

gulp.task('post-build', postBuild);

function postBuild() {
    return del(gulp.config.postBuild);
}