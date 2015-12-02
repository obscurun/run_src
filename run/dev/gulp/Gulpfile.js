var gulp 	= require('gulp'),
configs     = Array(),
colors      = require('colors');

startGulp();

var gutil   = require('gulp-util'),
ignore      = require('gulp-ignore'),
gulpif      = require('gulp-if'),
//phpunit     = require('gulp-phpunit'),
sourcemaps  = require('gulp-sourcemaps'), // compiles less to CSS
less        = require('gulp-less'), // compiles less to CSS
//sass        = require('gulp-sass'), // compiles sass to CSS
concat      = require('gulp-concat'),
jshint 		= require('gulp-jshint'),
uglify 		= require('gulp-uglify'),
minifyCSS 	= require('gulp-minify-css'),
rename 		= require('gulp-rename'),
clean 		= require('gulp-clean'),
imagemin 	= require('gulp-imagemin'),
optipng 	= require('imagemin-optipng'),
advpng 		= require('imagemin-advpng'),
size        = require('gulp-size2'),
prettyBytes = require('pretty-bytes');
//notify      = require('gulp-notify'),
changed     = require('gulp-changed'),
browserSync = require('browser-sync');
//livereload  = require('gulp-livereload');

//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
/**
 * Atenção, para desenvolver use a task deploy ou dev(com watch)
 * Para publicar no servidor de testes ou aplicação use a task publish
 * A task publish usa o uglify e o minify
 * Para debugar os source-maps no chrome
 * Para não juntar arquivos css ou js, use o sufixo -apart.js ou -apart.css
 */
function startGulp(){
    getConfigsPack();
    console.log( colors.bold.gray.bgBlack(setWidthText('Iniciando '+configs['name']) ));
    console.log( colors.bold.gray.bgWhite(setWidthText('-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -') ));
}
























//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
var ignore_files = {
    tmps: '**/*.tmp',
    tmp: '**/*-tmp',
    apart: '**/*-apart.*',
    bkps: '**/*bkp.*',
    copias: '**/*Copia.*',
    js: '**/*.js',
    css: '**/*.css',
    less: '**/*.less',
    lesses: '**/less/',
    ai: '**/*.ai',
}
var ignore_principals = [
    ignore_files.tmps,
    ignore_files.tmp,
    ignore_files.bkps,
    ignore_files.copias,
    ignore_files.ai,
];
var ignore_js_css = ["!"+ignore_files.js,"!"+ignore_files.css];
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
var bootstrap_files = {
    js:     './bower_components/bootstrap/dist/js/bootstrap.js',
    less:   './bower_components/bootstrap/less/*.less',
    css:    './bower_components/bootstrap/dist/js/bootstrap.css',
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
var run_files = {
    root: {
        src:    '_src/run/',
        dest:   './_deploy/run/'
    },
    core_root: {
        src:    '_src/run/core/',
        dest:   './_deploy/run/core/'
    },
    core_control: {
        src:    '_src/run/core/control/',
        dest:   './_deploy/run/core/control/'
    },
    core_error: {
        src:    '_src/run/core/error/',
        dest:   './_deploy/run/core/error/'
    },
    core_form: {
        src:    '_src/run/core/modelForm/',
        dest:   './_deploy/run/core/modelForm/'
    },
    core_log: {
        src:    '_src/run/core/log/',
        dest:   './_deploy/run/core/log/'
    },
    core_model: {
        src:    '_src/run/core/model/',
        dest:   './_deploy/run/core/model/'
    },
    core_view: {
        src:    '_src/run/core/view/',
        dest:   './_deploy/run/core/view/'
    },
    helpers: {
        src:    '_src/run/helpers/',
        dest:   './_deploy/run/helpers/'
    },
    libraries: {
        src:    '_src/run/libraries/',
        dest:   './_deploy/run/libraries/'
    },
    'plugins': {
        src:    '_src/run/plugins/',
        dest:   './_deploy/run/plugins/'
    },
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
var app_files	=  {
    root: {
        src:    '_src/',
        dest:   './_deploy/'
    },
    images: {
        src:    '_src/run-pags/view/img/',
        dest:   './_deploy/run-pags/view/img/'
    },
    scripts: {
        src:    '_src/run-pags/view/js/*.js',
        dest:   './_deploy/run-pags/view/js/'
    },
    less: {
        src:    '_src/run-pags/view/less/main.less',
        srcs:   '_src/run-pags/view/less/*.less',
        dest:   './_deploy/run-pags/view/less/'
    },
    styles: {
        src:    '_src/run-pags/view/css/*.css',
        dest:   './_deploy/run-pags/view/css/'
    },
    root_php: {
        src:    '_src/*.php',
        dest:   './_deploy/'
    },
    run_pags: {
        src:    '_src/run-pags/',
        dest:   './_deploy/run-pags/'
    },
    run_files: {
        src:    '_src/run-files/',
        dest:   './_deploy/run-files/'
    },
    run_config: {
        src:    '_src/run-config/',
        dest:   './_deploy/run-config/'
    },
    run_pags_control: {
        src:    '_src/run-pags/control/',
        dest:   './_deploy/run-pags/control/'
    },
    run_pags_model: {
        src:    '_src/run-pags/model/',
        dest:   './_deploy/run-pags/model/'
    },
    run_pags_view: {
        src:    '_src/run-pags/view/',
        dest:   './_deploy/run-pags/view/'
    }
};
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
var libs_js_path = {
    all: {
        src:    '_libs/**/*.js',
        dest:   './_deploy/run-pags/view/js/min/'
    },
    root: {
        src:    '_libs/js/*.js',
        dest:   './_deploy/run-pags/view/js/min/'
    },
    jquery: {
        src:    '_libs/js/jquery/v1/*.js',
        dest:   './_deploy/run-pags/view/js/min/'
    },
    jquery_plugins: {
        src:    '_libs/js/jquery/plugins/*.js',
        dest:   './_deploy/run-pags/view/js/min/'
    },
    jquery_custom: {
        src:    '_libs/js/jquery/custom/*.js',
        dest:   './_deploy/run-pags/view/js/min/'
    },
    modernizr: {
        src:    '_libs/js/modernizr/*.js',
        dest:   './_deploy/run-pags/view/js/min/'
    },
    spin: {
        src:    '_libs/js/spin/*.js',
        dest:   './_deploy/run-pags/view/js/min/'
    },
};
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
var libs_css_path = {
    root: {
        src:    '_libs/css/*.css',
        dest:   './_deploy/run-pags/view/css/min/'
    }
};
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function getIgnoredsPath(path){
    ignores = ignore_principals.concat([]);
    c = ignores.length;
    path = path.split("/");
    path.pop();
    path = path.join("/");
    console.log( colors.yellow("getIgnoredsPath: "+path));
    for(i=0;i<c;i++){
        ignores[i] = ignores[i].replace("!**/","");
        ignores[i] = "!"+path+"/**/"+ignores[i];
    }
    ignores[c] = path+"/.*";
    return ignores;
}



































//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('clean_deploy', function () {
    console.log( colors.bgRed.white(setWidthText('CLEAN DEPLOY')));
    return gulp.src([
        app_files.images.dest,
        app_files.scripts.dest,
        app_files.styles.dest,
        app_files.run_pags_view.dest,
        app_files.run_pags_model.dest,
        app_files.run_pags_control.dest,
        app_files.run_pags.dest,
        app_files.run_config.dest,
        app_files.root_php.dest,

        run_files.core_root.dest,
        run_files.core_control.dest,
        run_files.core_error.dest,
        run_files.core_log.dest,
        run_files.core_model.dest,
        run_files.core_view.dest,
        run_files.helpers.dest,
        run_files.libraries.dest,
        run_files.root.dest,
        app_files.root.dest
    ], {read: false})
    .pipe(clean({force: true})).on('finish', function(){
    console.log( colors.bgRed.white(setWidthText('CLEAN DEPLOY-END')));
    });
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -


































gulp.task('browser-sync', function() {
    browserSync({
        browser: "google chrome",
        logLevel: "silent",
        proxy: "dev/run/_deploy/"
    });
});
var packageJSON  = require('./package.json');
var jshintConfig = packageJSON.jshintConfig;
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('checklibs', function(){
    return gulp.src([libs_js_path.all.src]).pipe(jshint({ maxerr:50 }))
    .pipe(jshint.reporter('jshint-stylish'));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('checkjs', function(){
    return gulp.src([app_files.scripts.src]).pipe(jshint({ maxerr:50 }))
    .pipe(jshint.reporter('jshint-stylish'));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('js_hint_libs', function(){   
    return gulp.src([libs_js_path.jquery_custom.src])
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('jshint-stylish', {
      boss: false,
      wordWrap: false
    }));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('js_hint', [],function(){
	return gulp.src([app_files.scripts.src])
	.pipe(jshint())
	.pipe(jshint.reporter('jshint-stylish'));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function prepareJS(){
    return gulp.src([
        libs_js_path.jquery.src, 
        libs_js_path.jquery_plugins.src, 
        libs_js_path.modernizr.src,
        bootstrap_files.js,
        app_files.scripts.src 
    ]).pipe(sourcemaps.init());
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('deployJS', function(){
    console.log( colors.bgGreen.white(setWidthText('deployJS')) );
    prepareJS().pipe(changed(app_files.scripts.dest))
                .pipe(ignore.exclude(ignore_files.copias))
                .pipe(ignore.include([ignore_files.apart]))
                .pipe(getSize("js|src", "green"))
                .pipe(sourcemaps.write("./"))
                .pipe(gulp.dest(app_files.scripts.dest))
                .pipe(getSize("js|cop", "green"));
    return prepareJS().pipe(ignore.exclude([ignore_files.apart, ignore_files.copias]))
                    .pipe(concat('./'))
                    .pipe(rename(configs['name']+'.js'))
                    .pipe(getSize("js|src", "green"))
                    .pipe(sourcemaps.write("./")).on('error', handleError)
                    .pipe(gulp.dest(app_files.scripts.dest)).on('finish', function(){
                    console.log( colors.bgGreen.white(setWidthText('deployJS-END')));
                    })
                    .pipe(getSize("js|cop", "green"));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('publishJS', function(){
    console.log( colors.bgGreen.white(setWidthText('PUBLISH JS')) );
    prepareJS().pipe(ignore.include(ignore_files.apart))
                .pipe(sourcemaps.write("./"))
                .pipe(getSize("js|src", "green"))
                .pipe(gulp.dest(app_files.scripts.dest))
                .pipe(getSize("js|cop", "green"));
    return prepareJS().pipe(ignore.exclude(ignore_files.apart)).pipe(concat('./')).pipe(rename(configs['name']+'.js'))
                      .pipe(getSize("js|src", "green"))
                      .pipe(uglify())
                      .pipe(sourcemaps.write("./"))
                      .on('error', handleError)
                      .pipe(gulp.dest(app_files.scripts.dest))
                      .pipe(getSize("js|cop", "green"));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('publishJavascript', function(){
    console.log( colors.bgGreen.white(setWidthText('PUBLISH JS')) );
    /*prepareJS().pipe(ignore.include(ignore_files.apart))
                .pipe(sourcemaps.write("./"))
                .pipe(getSize("js|src", "green"))
                .pipe(gulp.dest(app_files.scripts.dest))
                .pipe(getSize("js|cop", "green"));*/
    return gulp.src('_src/run-pags/view/js/**/*.js')
    .pipe(getSize("js|src", "green"))
    .pipe(gulpif('*.js', uglify()))
    .on('error', handleError)
    .pipe(gulp.dest('./_deploy/run-pags/view/js/'))
    .pipe(getSize("js|src", "green"));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function prepareCSS(){
    return gulp.src([
        app_files.less.src,
        libs_css_path.root.src,
        app_files.styles.src
    ])
    .pipe(sourcemaps.init()).pipe(less()).on('error', handleError).on('finish', function(){  });
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('deployCSS', function(){
    console.log( colors.bgMagenta.white(setWidthText('deployCSS')) );
    prepareCSS().pipe(changed(app_files.styles.dest))
                .pipe(getSize("css|src", "magenta"))
                .pipe(ignore.include(ignore_files.apart))
                .pipe(sourcemaps.write("./"))
                .pipe(getSize("css|cop", "magenta"))                
                .pipe(gulp.dest(app_files.styles.dest));
    return prepareCSS().pipe(ignore.exclude(ignore_files.apart)).pipe(concat('./')).pipe(rename(configs['name']+'.css'))
                       .pipe(sourcemaps.write("./"))
                       .pipe(getSize("css|src", "magenta"))
                       .on('error', handleError)
                       .pipe(gulp.dest(app_files.styles.dest)).pipe(getSize("css|cop", "magenta"))
                       .on('finish', function(){
                            console.log( colors.bgMagenta.white(setWidthText('deployCSS-END')));
                        });
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('publishCSS', function () {
    console.log( colors.bgMagenta.white(setWidthText('PUBLISH CSS')) );
    prepareCSS().pipe(ignore.include(ignore_files.apart)).pipe(getSize("css|src", "magenta"))
    .pipe(sourcemaps.write("./")).pipe(gulp.dest(app_files.styles.dest)).pipe(getSize("css|cop", "magenta"));
    return prepareCSS().pipe(ignore.exclude(ignore_files.apart)).pipe(concat('./')).pipe(rename(configs['name']+'.css'))
                       .pipe(minifyCSS({root: "", benchmark:false}))
                       .pipe(sourcemaps.write("./")).pipe(getSize("css|src", "magenta").on('error', handleError))
                      // .pipe(changed(app_files.styles.dest))
                      .pipe(gulp.dest(app_files.styles.dest)).pipe(getSize("css|cop", "magenta"))
                       .on('finish', function(){
                            console.log( colors.bgMagenta.white(setWidthText('PUBLISH CSS-END')));
                        });
});


































//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('img-base', function () {
    console.log( colors.bgYellow.black(setWidthText('img-base')));
    return gulp.src(['!'+app_files.images.src+"*.png", '!'+app_files.images.src+"**/*.png", app_files.images.src+"**"])
    .pipe(changed(app_files.images.dest))
    .pipe(ignore.exclude([ignore_files.ai, ignore_files.tmps]))
    .pipe(getSize("img-base|src", "yellow"))
    .pipe(imagemin({
        progressive: true,
        svgoPlugins: [{removeViewBox: false}]
    }))
    .pipe(gulp.dest(app_files.images.dest))
    .pipe(getSize("img-base|cop", "yellow"));
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('img-compress', ['img-base'], function () {
    console.log( colors.bgYellow.black(setWidthText('img-compress-INI')));
    return gulp.src([app_files.images.src+"*.png",app_files.images.src+"**/*.png" ]).pipe(changed(app_files.images.dest))
    .pipe(ignore.exclude([ignore_files.ai, ignore_files.tmps]))
    .pipe(getSize("img|src", "yellow"))
    .pipe(imagemin({
        use: [optipng({  optimizationLevel: 7 })]
    }))
    .pipe(gulp.dest(app_files.images.dest)).pipe(getSize("img|cop", "yellow")).on('finish', function(){
        console.log( colors.bgYellow.black(setWidthText('img-compress-END')));
    });
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -



































//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('deploy-base', ['clean_deploy'], function(){
    gulp.src([app_files.root.src+"**", '!**/less/', '!**/specimen_files/', '!**/*_original', '!**/fonts/**/*.html', '!**/fonts/**/*.txt'])
        .pipe(ignore.exclude([
            ignore_files.tmp, ignore_files.js, ignore_files.ai, ignore_files.css, ignore_files.copias, ignore_files.bkps, ignore_files.less
        ])).pipe(gulp.dest(app_files.root.dest));

    gulp.src(app_files.root.src+".htaccess").pipe(gulp.dest(app_files.root.dest));
    gulp.src(app_files.run_pags.src+".htaccess").pipe(gulp.dest(app_files.run_pags.dest));
    gulp.src(app_files.run_config.src+".htaccess").pipe(gulp.dest(app_files.run_config.dest));
    gulp.src(app_files.run_files.src+".htaccess").pipe(gulp.dest(app_files.run_files.dest));
    console.log( colors.bgWhite.green( setWidthText('DEPLOY-BASE-END') ));
    return gulp.src(run_files.root.src+".htaccess").pipe(gulp.dest(run_files.root.dest));
});
deploy_ready = false;
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('deploy', ['deploy-base','js_hint' ], function(){
    //gulp.src([ app_files.less.dest ], {read: false}).pipe(clean({force: true}));
    packGenerator(true);
    return gulp.start(['img-compress', 'deployJS', 'deployCSS'], function(){
        gulp.src([ app_files.less.dest ], {read: false}).pipe(clean({force: true}));
        if(deploy_ready!==false) return;
        deploy_ready = true;
        //console.log( colors.bgWhite.green(setWidthText()));
        console.log( colors.bgWhite.green(setWidthText('-  -  -  -  -  -  -  -  -  -  -  -  -  -  -')));
        console.log( colors.bgWhite.green( setWidthText('DEPLOY-END') ));
        console.log( colors.bgWhite.green(setWidthText('-  -  -  -  -  -  -  -  -  -  -  -  -  -  -')));
        gulp.start("browser-sync");
        return true;
        //console.log( colors.bgWhite.green(setWidthText()));
    });
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('publish', ['deploy-base'], function(){ 
    gulp.start(['img-compress', 'publishCSS','publishJS'], function(){
        gulp.src([ app_files.less.dest ], {read: false}).pipe(clean({force: true}));
        console.log( colors.bgGreen.white(setWidthText()));
        //console.log( colors.bgGreen.white(setWidthText('-  -  -  -  -  -  -  -  -  -  -  -  -  -  -')));
        console.log( colors.bgGreen.white(setWidthText('PUBLISH-END!')));
        console.log( colors.bgGreen.white(setWidthText('-  -  -  -  -  -  -  -  -  -  -  -  -  -  -')));
        //console.log( colors.bgGreen.white(setWidthText()));
    });
    packGenerator(false);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('default', function() {
	gulp.start('deploy');
});




































//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('app_root', function(){
    gulp.src(app_files.root.src+"**").pipe(changed(app_files.root.dest))
    .pipe(ignore.exclude(ignore_principals))
    .pipe(gulp.dest(app_files.root.dest)).on('error', handleError)
    .pipe(getSize("app_root|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.black.bgWhite(setWidthText('app-root-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_pags_imgs', function(){
    gulp.start("img-compress", function(){
        console.log(colors.black.bgYellow(setWidthText('run_pags_imgs-END')));
    });
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_pags_view', function(){
    gulp.src(['!**/less/*.less', '!**/specimen_files/**', '!**/*_original', '!**/fonts/**/*.css', '!**/fonts/**/*.html', '!**/fonts/**/*.txt', "!"+app_files.styles.src+"**",  "!"+app_files.less.src+"**",  "!"+app_files.images.src+"**", app_files.run_pags_view.src+"**"])
    .pipe(changed(app_files.run_pags_view.dest)).pipe(ignore.exclude(ignore_principals))
    .pipe(gulp.dest(app_files.run_pags_view.dest)).on('error', handleError)
    .pipe(getSize("run_pags_view|cop", "yellow"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.black.bgYellow(setWidthText('run_pags_view-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_pags_model', function(){
    gulp.src(app_files.run_pags_model.src+"**").pipe(changed(app_files.run_pags_model.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(app_files.run_pags_model.dest))
    .pipe(getSize("run_pags_model|cop", "yellow"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.black.bgYellow(setWidthText('run_pags_model-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_pags_control', function(){
    gulp.src(app_files.run_pags_control.src+"**").pipe(changed(app_files.run_pags_control.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(app_files.run_pags_control.dest))
    .pipe(getSize("run_pags_control|cop", "yellow"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.black.bgYellow(setWidthText('run_pags_control-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_config', function(){
    gulp.src(app_files.run_config.src+"**").pipe(changed(app_files.run_config.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(app_files.run_config.dest))
    .pipe(getSize("run_config|cop", "cyan"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.black.bgCyan(setWidthText('run_config-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_root', function(){
    gulp.src(run_files.root.src+"*.php").pipe(changed(app_files.root.dest))
    .pipe(gulp.dest(run_files.root.dest))
    .pipe(getSize("run_root|cop", "cyan"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.black.bgCyan(setWidthText('run_core_root-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_core_root', function(){
    gulp.src(run_files.core_root.src+"*.php").pipe(changed(run_files.core_root.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.core_root.dest))
    .pipe(getSize("run_core_root|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
       console.log(colors.cyan.bgWhite(setWidthText('run_core_root-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_core_control', function(){
    gulp.src(run_files.core_control.src+"**").pipe(changed(run_files.core_control.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.core_control.dest))
    .pipe(getSize("run_core_control|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.cyan.bgWhite(setWidthText('run_core_control-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_core_error', function(){
    gulp.src(run_files.core_error.src+"**").pipe(changed(run_files.core_error.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.core_error.dest))
    .pipe(getSize("run_core_error|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.cyan.bgWhite(setWidthText('core_error-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_core_form', function(){
    gulp.src(run_files.core_form.src+"**").pipe(changed(run_files.core_form.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.core_form.dest))
    .pipe(getSize("run_core_form|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.cyan.bgWhite(setWidthText('core_form-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_core_log', function(){
    gulp.src(run_files.core_log.src+"**").pipe(changed(run_files.core_log.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.core_log.dest))
    .pipe(getSize("run_core_log|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.cyan.bgWhite(setWidthText('core_log-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_core_model', function(){
    gulp.src(run_files.core_model.src+"**").pipe(changed(run_files.core_model.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.core_model.dest))
    .pipe(getSize("run_core_model|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.cyan.bgWhite(setWidthText('core_model-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_core_view', function(){
    gulp.src(run_files.core_view.src+"**").pipe(changed(run_files.core_view.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.core_view.dest))
    .pipe(getSize("run_core_view|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.cyan.bgWhite(setWidthText('core_view-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_helpers', function(){
    gulp.src(run_files.helpers.src+"**").pipe(changed(run_files.helpers.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.helpers.dest))
    .pipe(getSize("run_helpers|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.grey.bgWhite(setWidthText('helpers-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_libraries', function(){
    gulp.src(run_files.libraries.src+"**").pipe(changed(run_files.libraries.dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files.libraries.dest))
    .pipe(getSize("run_libraries|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.blue.bgWhite(setWidthText('libraries-END')));
    });
    updateGenerator(true);
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('run_plugins', function(){
    gulp.src(run_files['plugins'].src+"**").pipe(changed(run_files['plugins'].dest))
    .pipe(ignore.exclude(ignore_principals)).pipe(gulp.dest(run_files['plugins'].dest))
    .pipe(getSize("run_plugins|cop", "black"))
    .pipe(browserSync.reload({stream:true}))
    .on('finish', function(){
        console.log(colors.magenta.bgWhite(setWidthText('plugins-END')));
    });
    updateGenerator(true);
});





































//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('dev', ["deploy"], function() {
    gulp.start('dev-watches');
});
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('dev-watches', function(){
     console.log( colors.bold.yellow.bgCyan(setWidthText('DEV-INI > DEPLOY TASK > WATCH FILES')));

    //livereload.listen();
    //gulp.start(, function(){
    //});
    // watch javascripts from libs e app
    path = [libs_js_path.root.src, app_files.scripts.src].concat(getIgnoredsPath(app_files.scripts.src));
    gulp.watch(path, function(evt){ 
        //console.log( colors.green('start-publishJS') ); 
        gulp.start(['deployJS', 'checkjs']);     
    });
    gulp.watch(
        [libs_css_path.root.src, app_files.less.srcs, app_files.styles.src].concat(getIgnoredsPath(app_files.less.srcs)), 
        function(evt){ 
            //console.log( colors.magenta('start-publishCSS') ); 
            gulp.start('deployCSS'); 
    });
    // watch run-pags view/model/control
    gulp.watch([
            "!"+app_files.images.src+"*.gif", 
            "!"+app_files.images.src+"*.jpg", 
            "!"+app_files.images.src+"*.png", 
            "!"+app_files.scripts.src,
            "!"+app_files.run_pags_view.src+"*.css", 
            "!"+app_files.run_pags_view.src+"*.less", 
            "!"+app_files.less.srcs, 
            "!"+app_files.styles.src, 
            app_files.run_pags_view.src+"**"
        ]
        .concat(getIgnoredsPath(app_files.run_pags_view.src)),      function(evt){ gulp.start('run_pags_view'); });

    gulp.watch([
            app_files.images.src+"*.jpg", 
            app_files.images.src+"*.gif", 
            app_files.images.src+"*.png",
            app_files.images.src+"**/*.jpg", 
            app_files.images.src+"**/*.gif", 
            app_files.images.src+"**/*.png"
        ],   
        function(evt){ console.log("ini img"); gulp.start('run_pags_imgs'); });

    gulp.watch([app_files.run_pags_model.src+"**"].concat(getIgnoredsPath(app_files.run_pags_model.src)),    function(evt){ gulp.start('run_pags_model'); });
    gulp.watch([app_files.run_pags_control.src+"**"].concat(getIgnoredsPath(app_files.run_pags_control.src)),function(evt){ gulp.start('run_pags_control'); });
    // watch run
    gulp.watch([app_files.run_config.src+"**"].concat(getIgnoredsPath(app_files.run_config.src)),            function(evt){ gulp.start('run_config'); });
    gulp.watch([run_files.core_root.src+"*.php"].concat(getIgnoredsPath(run_files.core_root.src)),           function(evt){ gulp.start('run_core_root'); });
    gulp.watch([run_files.core_control.src+"**"].concat(getIgnoredsPath(run_files.core_control.src)),        function(evt){ gulp.start('run_core_control'); });
    gulp.watch([run_files.core_error.src+"**"].concat(getIgnoredsPath(run_files.core_error.src)),            function(evt){ gulp.start('run_core_error'); });
    gulp.watch([run_files.core_form.src+"**"].concat(getIgnoredsPath(run_files.core_form.src)),              function(evt){ gulp.start('run_core_form'); });
    gulp.watch([run_files.core_log.src+"**"].concat(getIgnoredsPath(run_files.core_log.src)),                function(evt){ gulp.start('run_core_log'); });
    gulp.watch([run_files.core_model.src+"**"].concat(getIgnoredsPath(run_files.core_model.src)),            function(evt){ gulp.start('run_core_model'); });
    gulp.watch([run_files.core_view.src+"**"].concat(getIgnoredsPath(run_files.core_view.src)),              function(evt){ gulp.start('run_core_view'); });
    gulp.watch([run_files.helpers.src+"**"].concat(getIgnoredsPath(run_files.helpers.src)),                  function(evt){ gulp.start('run_helpers'); });
    gulp.watch([run_files.libraries.src+"**"].concat(getIgnoredsPath(run_files.libraries.src)),              function(evt){ gulp.start('run_libraries'); });
    gulp.watch([run_files['plugins'].src+"**"].concat(getIgnoredsPath(run_files['plugins'].src)),            function(evt){ gulp.start('run_plugins'); });
    gulp.watch([run_files.root.src+"*.*"].concat(getIgnoredsPath(run_files.root.src)),                       function(evt){ gulp.start('run_root'); });
    gulp.watch([app_files.root.src+".*"].concat(getIgnoredsPath(app_files.root.src)),                        function(evt){ 
        console.log(colors.black.bgWhite(setWidthText('Não é recomendado alterar arquivos na raiz')));
        gulp.start('app_root'); 
    });

    /**/
    console.log( colors.bold.yellow.bgCyan(setWidthText('DESENVOLVIMENTO EM ANDAMENTO - Use CTRL+C para parar')));
    setTimeout(function(){
     }, 8000)
});





































//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
gulp.task('getConfigs', function () {
  return getConfigsPack();
});
function getConfigsPack(){
  var pkg = require('./package.json')
  configs['name'] = pkg.name;
  configs['version'] = pkg.version;
  configs['description'] = pkg.description;
  configs['keywords'] = pkg.keywords;
  return true;    
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function updateGenerator(em_desenvolvimento) {
  var content = "";
  content += " projeto          = "   +configs['name'];
  content += "\r\n versão       = "   +configs['version'];
  content += "\r\n descrição    = "   +configs['description'];
  content += "\r\n chaves       = "   +configs['keywords'];
  content += "\r\n data         = "   +Date();

  nome = "UPDATE";

  string_src(nome+".txt", content).pipe(gulp.dest('_deploy/'));
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function packGenerator(em_desenvolvimento) {
  var content = "";
  content += " projeto      = "   +configs['name'];
  content += "\r\n versão       = "   +configs['version'];
  content += "\r\n descrição    = "   +configs['description'];
  content += "\r\n chaves       = "   +configs['keywords'];
  content += "\r\n data         = "   +Date();
  d = new Date();
  h = d.getHours();
  m = d.getMinutes();
  s = d.getSeconds();
  if(h < 10) h = "0"+h;
  if(m < 10) m = "0"+m;
  if(s < 10) s = "0"+s;

  nome = (em_desenvolvimento) ? "DESENVOLVIMENTO" : "PUBLISHED";

  string_src(nome+"_"+ h +""+ m +""+ s +".txt", content).pipe(gulp.dest('_deploy/'));
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function string_src(filename, string) {
  var src = require('stream').Readable({ objectMode: true })
  src._read = function () {
    this.push(new gutil.File({ cwd: "", base: "", path: filename, contents: new Buffer(string) }))
    this.push(null)
  }
  return src
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function handleError(err) {
  console.log(colors.bold(err.toString()));
  this.emit('end');
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function setWidthText(txt){
    if(!txt) txt = "";
    txt = txt.toString().toUpperCase();
    padrao = "                                                                                                       ";
    c = padrao.length-txt.length;
    padrao = padrao.substring(1, c);
    //console.log("TESTE "+c);
    return padrao+txt+"         ";
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function setWidthTextSize(txt){
    if(!txt) txt = "";
    txt = txt.toString().toLowerCase();
    padrao = "                      ";
    c = padrao.length-txt.length;
    padrao = padrao.substring(1, c);
    //console.log("TESTE "+c);
    return padrao+txt+":";
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function sizeLog(title, what, size, gzip){
    if(!title) title = "";
    if(!what) what = "";
    if(!size) size = 0;
    if(!gzip) gzip = "";
    console.log(colors.gray(title), colors.yellow(what), colors.cyan(prettyBytes(size)), colors.grey(gzip));
}
//  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function getSize(_title, _cor, _gzip){
   if(!_cor) _cor = 'gray';
   // _title = colors[_cor](setWidthTextSize(_title));
   _contra_cor = "white";
   if(_cor == "black"){
        _cor = "white";
        _contra_cor = "black";
    }
   _cor = "bg"+_cor.substr(0,1).toUpperCase() + _cor.substr(1).toLowerCase();
   _title = colors[_cor][_contra_cor](setWidthTextSize(_title.toLowerCase()));
   return size({showFiles:true, title: _title, gzip:_gzip, log:sizeLog});
}

