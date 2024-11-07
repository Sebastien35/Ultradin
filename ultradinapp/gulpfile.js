'use strict';

var gulp = require('gulp'),
    shell = require('gulp-shell')
;

gulp.task('up-all', shell.task(['docker-compose -f cd/docker-compose.yaml up -d']));


gulp.task('up', gulp.series(['up-all']));

gulp.task('kill', shell.task(['docker-compose -f cd/docker-compose.yml kill']));
gulp.task('logs', shell.task(['docker-compose -f cd/docker-compose.yml logs -f']));

gulp.task('u', gulp.series(['up']));
gulp.task('k', gulp.series(['kill']));

gulp.task('default', gulp.series(['up']));