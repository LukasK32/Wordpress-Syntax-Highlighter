const gulp = require('gulp');
const del = require('del');
const babel = require('gulp-babel');

const prism_repository = './node_modules/prismjs/';

/*
|--------------------------------------------
|   Clears build
|--------------------------------------------
*/
gulp.task('clear', function(){
    return del([
        './dist/**'
    ]);
});


/*
|--------------------------------------------
|   Copying and compiling source
|--------------------------------------------
*/
gulp.task('php', function(){
    return gulp.src('./src/php/**')
               .pipe(gulp.dest('./dist/'));
});

gulp.task('assets', function(){
    return gulp.src('./src/assets/**')
               .pipe(gulp.dest('./dist/assets/'));
});

gulp.task('javascript', function(){

    return gulp.src('./src/js/*')
               .pipe(babel())
               .pipe(gulp.dest('./dist/assets/js/'))

});

gulp.task('extras', function(){
    return gulp.src('./src/extras/**')
               .pipe(gulp.dest('./dist/'));
});


/*
|--------------------------------------------
|   Prism related stuff
|--------------------------------------------
*/
gulp.task('prism:clear', function(){
    return del([
        './dist/assets/prism/'
    ]);
});

gulp.task('prism:copy:themes', function(){
    return gulp.src(prism_repository+'themes/*')
               .pipe(gulp.dest('./dist/assets/prism/css/'));
});

gulp.task('prism:copy:json', function(){
    return gulp.src(prism_repository+'components.json')
               .pipe(gulp.dest('./dist/assets/prism/'));
});

gulp.task('prism:copy:javascript', function(){
    return gulp.src(prism_repository+'components/*.min.js')
               .pipe(gulp.dest('./dist/assets/prism/js/'));
});

gulp.task('prism:copy', gulp.series(
    'prism:clear',
    'prism:copy:javascript',
    'prism:copy:themes',
    'prism:copy:json'
));


/*
|--------------------------------------------
|   Wordpress related stuff
|--------------------------------------------
*/
gulp.task('wordpress:copy', function(){
    return gulp.src('./dist/**')
               .pipe(gulp.dest('../plugins/pmd-syntax-highlighter/'))
});

gulp.task('wordpress:clear', function(){
    return del([
        '../plugins/pmd-syntax-highlighter/**'
    ],
    {
        force: true
    });
});


/*
|--------------------------------------------
|   Build tasks
|--------------------------------------------
*/
gulp.task('build', gulp.series(
    'clear',
    'assets',
    'prism:copy',
    'php',
    'javascript',
    'extras'
));

gulp.task('build:wordpress', gulp.series(
    'build',
    'wordpress:clear',
    'wordpress:copy'
));


/*
|--------------------------------------------
|   Watch tasks
|--------------------------------------------
*/
gulp.task('watch', function(){
    gulp.watch('./src/**', gulp.series('build'));
});

gulp.task('watch:wordpress', function(){
    gulp.watch('./src/**', gulp.series('build:wordpress'));
});


/*
|--------------------------------------------
|   Default
|--------------------------------------------
*/
gulp.task('default', gulp.series(
    'build'
));