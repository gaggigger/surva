var gulp = require('gulp');
var runSequence = require('run-sequence');
var shell = require('gulp-shell');

var phpServeUrl = 'localhost:8000';

var dir = './gulp/';
require(dir + 'config.js');
require(dir + 'pre-build.js');
require(dir + 'post-build.js');
require(dir + 'sass.js');
require(dir + 'cache.js');
require(dir + 'useref.js');
require(dir + 'copy.js');
require(dir + 'uglify.js');

gulp.task('build', function(callback) {
    runSequence(
        'pre-build',
        ['sass', 'cache'],
        'useref',
        'copy',
        'uglify',
        'post-build',
        callback
    );
    return gulp;
});

gulp.task('watch', function() {
    gulp.watch(gulp.config.watch, ['serve']);
});

gulp.task('serve', ['build'], shell.task([
    'php -S ' + phpServeUrl + ' -t dist/public/'
]));
