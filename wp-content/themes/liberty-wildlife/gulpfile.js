var gulp = require("gulp");
var sass = require("gulp-sass");
var autoprefixer = require("gulp-autoprefixer");
var jshint = require("gulp-jshint");
var imagemin = require("gulp-imagemin");
var notify = require("gulp-notify");
var plumber = require("gulp-plumber");
var rename = require("gulp-rename");
var uglify = require("gulp-uglify");
var browserSync = require("browser-sync").create();

// Event handler message - the variable has been added to all tasks to show an error in any one via the Terminal
var plumberErrorHandler = {
  errorHandler: notify.onError({
    title: "Gulp",
    message: "Error: <%= error.message %>"
  })
};

gulp.task("serve", function() {
  var files = ["./*.php", "./sass/*.scss", "./js/*.js", "./includes/*.php"];

  browserSync.init(files, {
    proxy: "http://test.libertywildlife.org"
  });

  gulp.watch("./sass/*.scss", ["sass"]);
  gulp.watch(files).on("change", browserSync.reload);
});

gulp.task("sass", function() {
  gulp
    .src("./sass/*.scss")
    .pipe(plumber(plumberErrorHandler))
    .pipe(sass())
    .pipe(
      autoprefixer({
        overrideBrowserslist: ["last 2 versions", "> 5%", "Firefox <= 20"],
        cascade: false
      })
    )
    .pipe(gulp.dest("./"))
    .pipe(browserSync.stream())
    .pipe(notify({ message: 'TASK: "sass" 👍', onLast: true }));
});

gulp.task("js", function() {
  gulp
    .src("./js/*.js", { base: process.cwd() })
    .pipe(plumber(plumberErrorHandler))
    .pipe(jshint())
    .pipe(
      uglify().on("error", function(e) {
        console.log(e);
      })
    )
    .pipe(
      rename({
        dirname: "",
        basename: "scripts",
        suffix: ".min",
        extname: ".js"
      })
    )
    .pipe(gulp.dest("./js/dist"))
    .pipe(notify({ message: 'TASK: "js" 👍', onLast: true }));
});

gulp.task("img", function() {
  gulp
    .src("./img/*.{png,jpg,gif}")
    .pipe(plumber(plumberErrorHandler))
    .pipe(
      imagemin({
        optimizationLevel: 7,
        progressive: true
      })
    )

    .pipe(gulp.dest("./img/dist"))
    .pipe(notify({ message: 'TASK: "img" 👍', onLast: true }));
});

gulp.task("watch", function() {
  gulp.watch("./sass/*.scss", ["sass"]);
  gulp.watch("./js/*.js", ["js"]);
  gulp.watch("./img/*.{png,jpg,gif}", ["img"]);
  gulp.watch("./*.php");
});

gulp.task("default", ["serve", "sass", "js", "img", "watch"]);
