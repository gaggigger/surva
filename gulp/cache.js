var gulp = require('gulp');
var gulpTpl = require('gulp-angular-templatecache');
var gulpHtmlMin = require('gulp-htmlmin');

gulp.task('cache', compileTemplateCache);

function compileTemplateCache() {
    var opts = gulp.config.templates;
    var stream = gulp.src(opts.src);
    if(opts.htmlOptions) {
        stream = stream.pipe(gulpHtmlMin(opts.htmlOptions));
    }
    return stream
        .pipe(gulpTpl(opts.options))
        .pipe(gulp.dest(opts.dest));
}
