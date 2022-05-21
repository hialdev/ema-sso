var gulp = require('gulp');
var gulpMinifyCss = require('gulp-minify-css');
var gulpConcat = require('gulp-concat');
var gulpUglify = require('gulp-uglify');
var rev = require('gulp-rev');
var clean = require('gulp-clean');
var baseDest = './www/web/assets/';
var jsonManifest = 'rev-manifest.json';

// clean
gulp.task('clean-asset', function() {
    return gulp
        .src([
            baseDest + 'fonts/',
            baseDest + 'images/',
            baseDest + 'icon/',
            baseDest + 'css/fonts/',
        ],{
            read: false,
            allowEmpty: true
        })
        .pipe(clean());
});

gulp.task('clean-css', function() {
    return gulp
        .src([
            baseDest + 'css/*.css',
        ],{
            read: false,
            allowEmpty: true
        })
        .pipe(clean());
});

gulp.task('clean-js', function() {
    return gulp
        .src([
            baseDest + 'js/*.js',
        ],{
            read: false,
            allowEmpty: true
        })
        .pipe(clean());
});

// vendors/plugins assets bundles
var srcVendorCSS = [
    './webassets/css/roboto.css',
    './webassets/css/opensans.css',
    './webassets/css/icons/icomoon/styles.min.css',
    './webassets/css/icons/fontawesome/styles.min.css',

    './webassets/templates/material/css/bootstrap.min.css',
    './webassets/templates/material/css/bootstrap_limitless.min.css',
    './webassets/templates/material/css/layout.min.css',
    './webassets/templates/material/css/components.min.css',
    './webassets/templates/material/css/colors.min.css',
    './webassets/plugins/editors/trumbowyg/plugins/table/ui/trumbowyg.table.min.css',
    './webassets/plugins/pickers/bootstrap-datepicker/bootstrap-datepicker3.min.css',
    /*'./webassets/plugins/pickers/bootstrap-datetimepicker.min.css' */
];

var srcAppCSS = [
    './webassets/css/custom.css',
];

var srcVendorJS = [
    './webassets/js/main/jquery.min.js',
    './webassets/js/main/bootstrap.bundle.min.js',
    './webassets/plugins/loaders/blockui.min.js',
    './webassets/plugins/ui/ripple.min.js',
    './webassets/plugins/ui/perfect_scrollbar.min.js',
    './webassets/plugins/ui/moment/moment.min.js',
    './webassets/plugins/editors/trumbowyg/trumbowyg.min.js',
    './webassets/plugins/editors/trumbowyg/plugins/pasteembed/trumbowyg.pasteembed.min.js',
    './webassets/plugins/editors/trumbowyg/plugins/noembed/trumbowyg.noembed.js',
    './webassets/plugins/editors/trumbowyg/plugins/fontsize/trumbowyg.fontsize.min.js',
    './webassets/plugins/editors/trumbowyg/plugins/table/trumbowyg.table.min.js',
    './webassets/plugins/pickers/bootstrap-datepicker/bootstrap-datepicker.min.js',
    './webassets/plugins/pickers/bootstrap-datepicker/bootstrap-datepicker.id.js',
    './webassets/plugins/notifications/noty.min.js',
    './webassets/plugins/forms/selects/select2.min.js',
    './webassets/plugins/forms/selects/select2.id.min.js',
    './webassets/plugins/forms/validation/validate.min.js',
    './webassets/plugins/ui/jquery.ba-hashchange.js',
    './webassets/plugins/tables/datatables/datatables.min.js',
    './webassets/plugins/tables/datatables/extensions/input.js',
    './webassets/plugins/tables/datatables/extensions/select.min.js',
    './webassets/plugins/forms/styling/uniform.min.js',
    './webassets/plugins/forms/styling/switch.min.js',
    './webassets/plugins/forms/styling/switchery.min.js',
    './webassets/plugins/forms/autosize.min.js',

    './webassets/templates/material/js/app.js',
];

var srcMapJS = [
    './webassets/js/main/bootstrap.bundle.min.js.map',
    './webassets/plugins/notifications/noty.min.js.map',
];

var srcAppJS = [
    './webassets/js/apps/application.js',
    './webassets/js/apps/upload.management.js',
    './webassets/js/apps/appstart.js',

    './webassets/js/apps/taskman/taskman.js',
    './webassets/js/apps/taskman/taskman.templates.js',

    './webassets/js/apps/workspace.js',
    './webassets/js/apps/project.js',
    './webassets/js/apps/section.js',
    './webassets/js/apps/task.js',

    './webassets/js/apps/task.home.js',
    './webassets/js/apps/my.task.js',
    './webassets/js/apps/task.project.js',

    './webassets/js/apps/workspace.setting.js',
    './webassets/js/apps/project.setting.js',
];

gulp.task('vendor-fonts', function () {
    return gulp
        .src(['./webassets/fonts/**/*'])
        .pipe(gulp.dest(baseDest + 'fonts'));
});

gulp.task('icon-fonts', function () {
    return gulp
        .src([
            './webassets/css/icons/icomoon/fonts/*',
            './webassets/css/icons/fontawesome/fonts/*'
        ])
        .pipe(gulp.dest(baseDest + 'css/fonts'));
});

gulp.task('app-images', function () {
    return gulp
        .src([
            './webassets/images/placeholder.png',
            './webassets/images/user.png',
            './webassets/images/logo.png',
            './webassets/images/logo_only.png',
            './webassets/images/logo_bg.png',
            './webassets/images/logo_bg_dark.png',
            './webassets/plugins/editors/trumbowyg/ui/icons.svg',
            './webassets/images/loading-spinner.gif',
        ])
        .pipe(gulp.dest(baseDest + 'images'));
});

gulp.task('vendor-images', function () {
    return gulp
        .src([
            './webassets/plugins/editors/trumbowyg/ui/*',
        ])
        .pipe(gulp.dest(baseDest + 'js/ui'));
});

gulp.task('app-icons', function () {
    return gulp
        .src(['./webassets/icon/*',])
        .pipe(gulp.dest(baseDest + 'icon'));
});

gulp.task('vendor-js-map', function () {
    return gulp
        .src(srcMapJS)
        .pipe(gulp.dest(baseDest + 'js'));
});

gulp.task('vendor-css', function () {
    return gulp
        .src(srcVendorCSS)
        .pipe(gulpConcat('vendor.css'))
        .pipe(gulpMinifyCss({
            compatibility: 'ie8'
        }))
        .pipe(rev())
        .pipe(gulp.dest(baseDest + 'css'))
        .pipe(rev.manifest(jsonManifest,{
			merge: true
		}))
        .pipe(gulp.dest('./'));
});
gulp.task('vendor-js', function () {
    return gulp
        .src(srcVendorJS)
        .pipe(gulpConcat('vendor.js'))
        //.pipe(gulpUglify())
        .pipe(rev())
        .pipe(gulp.dest(baseDest + 'js'))
        .pipe(rev.manifest(jsonManifest,{
			merge: true
		}))
        .pipe(gulp.dest('./'));
});

gulp.task('app-css', function () {
    return gulp
        .src(srcAppCSS)
        .pipe(gulpConcat('app.css'))
        .pipe(gulpMinifyCss({
            compatibility: 'ie8'
        }))
        .pipe(rev())
        .pipe(gulp.dest(baseDest + 'css'))
        .pipe(rev.manifest(jsonManifest,{
			merge: true
		}))
        .pipe(gulp.dest('./'));
});
gulp.task('app-js',function () {
    return gulp
        .src(srcAppJS)
        .pipe(gulpConcat('app.js'))
        //.pipe(gulpUglify())
        .pipe(rev())
        .pipe(gulp.dest(baseDest + 'js'))
        .pipe(rev.manifest(jsonManifest,{
			merge: true
		}))
        .pipe(gulp.dest('./'));
});

gulp.task('clean', gulp.series('clean-asset', 'clean-css', 'clean-js'));
gulp.task('asset', gulp.series('vendor-fonts',  /* 'vendor-images', */'icon-fonts', 'app-images', 'app-icons'));
gulp.task('css', gulp.series('clean-css', 'vendor-css', 'app-css'));
gulp.task('js', gulp.series('clean-js', 'vendor-js', 'vendor-js-map', 'app-js'));
gulp.task('default', gulp.series('css', 'js'));
gulp.task('all', gulp.series('asset','css', 'js'));