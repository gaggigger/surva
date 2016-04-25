var gulp = require('gulp');
var uglify = require('gulp-uglify');

gulp.task('uglify', uglifyJs);

/**
 * Uglify all Javascript files in `dist` folder.
 * Documentation for `gulp-uglify`
 * https://www.npmjs.com/package/gulp-uglify
 */
function uglifyJs() {
    var src = gulp.config.dist + 'public/*.js';
    var dest = gulp.config.dist + 'public/';
    console.log('Uglify: ', src, dest);
    return gulp
        .src(src)
        .pipe(uglify())
        .pipe(gulp.dest(dest));
}
