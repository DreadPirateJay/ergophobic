var gulp    = require('gulp');
var bower   = require('bower');
var sh      = require('shelljs');
var wiredep = require('wiredep');
var $       = require('gulp-load-plugins')();

var paths = {
  sass: ['./scss/**/*.scss'],
  jsFiles: ['www/js/**/*.js']
};

gulp.task('default', ['sass']);

gulp.task('sass', function(done) {
  gulp.src('./scss/ionic.app.scss')
    .pipe($.sass())
    .on('error', $.sass.logError)
    .pipe(gulp.dest('./www/css/'))
    .pipe($.minifyCss({
      keepSpecialComments: 0
    }))
    .pipe($.rename({ extname: '.min.css' }))
    .pipe(gulp.dest('./www/css/'))
    .on('end', done);
});

gulp.task('watch', function() {
  gulp.watch(paths.sass, ['sass']);
});

gulp.task('install', ['git-check'], function() {
  return bower.commands.install()
    .on('log', function(data) {
      $.util.log('bower', $.util.colors.cyan(data.id), data.message);
    });
});

gulp.task('git-check', function(done) {
  if (!sh.which('git')) {
    console.log(
      '  ' + gutil.colors.red('Git is not installed.'),
      '\n  Git, the version control system, is required to download Ionic.',
      '\n  Download git here:', gutil.colors.cyan('http://git-scm.com/downloads') + '.',
      '\n  Once git is installed, run \'' + gutil.colors.cyan('gulp install') + '\' again.'
    );
    process.exit(1);
  }
  done();
});

gulp.task('wiredep', function () {

  return gulp.src('www/index.html')
    // exclude ionic scss since we're using ionic sass
    .pipe(wiredep.stream({exclude: ['www/lib/ionic/css']}))
    .pipe(gulp.dest('www/'));
});

gulp.task('inject', ['wiredep'], function () {

  return gulp.src('www/index.html')
    .pipe(
      $.inject( // www/js/**/*.js files
        gulp.src(paths.jsFiles)
          .pipe($.plumber()) // use plumber so watch can start despite js errors
          .pipe($.naturalSort())
          .pipe($.angularFilesort()),
        {relative: true}))
    .pipe(gulp.dest('www'));
});
