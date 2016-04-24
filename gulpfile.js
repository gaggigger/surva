var gulp = require('gulp');
var runSequence = require('run-sequence');
var shell = require('gulp-shell');

var dir = './gulp/';
require(dir + 'config.js');
require(dir + 'pre-build.js');
require(dir + 'post-build.js');
require(dir + 'sass.js');
require(dir + 'cache.js');
require(dir + 'useref.js');
require(dir + 'copy.js');

gulp.task('build', function(callback) {
    runSequence(
        'pre-build',
        ['sass', 'cache'],
        'useref',
        'copy',
        'post-build',
        callback
    );
    return gulp;
});

gulp.task('watch', function() {
    gulp.watch(gulp.config.watch, ['serve']);
});

gulp.task('serve', ['build'], shell.task([
    'php -S localhost:8000 -t dist/public/'
]));