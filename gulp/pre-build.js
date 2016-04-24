var gulp = require('gulp');
var del = require('del');

gulp.task('pre-build', preBuild);

function preBuild() {
    return del.sync(['./dist/**']);
}