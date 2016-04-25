var gulp = require('gulp');

// Must have trailing slashes.
var src = './src/';
var dist = './dist/';

gulp.config = {

    // Root of all files, prefixed to all paths
    root: src,

    // After running 'gulp dist', you can directy copy the contents of
    // dist folder onto your server's root.
    dist: dist,

    // Build on changes to watch files
    watch: [
        // PHP Files
        src+'api/**/*.php',
        // SASS files
        src+'public/sass/**/*.scss',
        // JS files
        src+'public/app/**/*.js',
        // Main files
        src+'public/index.php',
        src+'public/.htaccess',
        // Templates
        src+'public/templates/**/*.html'
    ],

    // SASS files to compile
    sass: {
        src: src+'public/sass/master.scss',
        options: {
            // For full list of options
            // visit https://github.com/sass/node-sass
            outputStyle: 'compressed',
            includePaths: [
                src+'public/vendor/bootstrap-sass/assets/stylesheets/',
                src+'public/vendor/reset-css/'
            ],
        },
        dest: src+'public/'
    },

    // Compile Template Cache
    templates: {
        src: src+'public/templates/**/*.html',
        htmlOptions: {
            // For full list of options
            // visit: https://github.com/kangax/html-minifier
            caseSensitive: true,
            collapseWhitespace: true,
            conservativeCollapse: false,
            removeComments: true
        },
        options: {
            filename: 'app.cache.js',
            module: 'app.cache',
            moduleSystem: 'IIFE',
            standalone: true,
            templateHeader: 'angular' +
                '.module(\'<%= module %>\'<%= standalone %>)' +
                '.run([\'$templateCache\', function($templateCache) {',
            templateBody: '$templateCache' +
                '.put(\'<%= url %>\',\'<%= contents %>\');'
        },
        dest: src+'public/'
    },

    // Parse JS files in "index.php"
    useRef: {
        src: src+'public/index.php',
        dest: dist+'public/'
    },

    // Copy these files directly into dist into correponding folder
    copy: [
        {
            src: src+'*.php',
            dest: dist+''
        },
        {
            src: src+'api/**/*',
            dest: dist+'api/'
        },
        {
            src: src+'data/**/*.json',
            dest: dist+'data/'
        },
        {
            src: [
                src+'public/master.js',
                src+'public/master.css',
                src+'public/.htaccess'
            ],
            dest: dist+'public/'
        },
        {
            src: src+'public/api/**/*.php',
            dest: dist+'public/api/'
        },
        {
            src: src+'public/media/**/*.{jpg,gif,png,pdf,svg}',
            dest: dist+'public/media/'
        }
    ],

    // Delete following files after build
    postBuild: [
        src+'public/*.js',
        src+'public/*.css'
    ]
};
