var gulp = require('gulp');
var uglify = require('gulp-uglify');

gulp.task('uglify', uglifyJs);

/**
 * Uglify all Javascript files in `dist` folder.
 * Documentation for `gulp-uglify`
 * https://www.npmjs.com/package/gulp-uglify
 */
function uglifyJs() {
    return gulp
        .src(gulp.config.dist+'*.js')
        .pipe(uglify())
        .pipe(gulp.dest(gulp.config.dist));
}
