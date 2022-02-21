const { src, dest, parallel, watch } = require("gulp");
const concat = require('gulp-concat');

// TS: concatenation
function jsConcat() {
    return src(['./assets/JS output/*.js', './assets/JS output/**/*.js'])
        .pipe(concat('script.js'))
        .pipe(dest('public/js/'))
    ;
}

exports.default = parallel(jsConcat);

exports.jsWatcher = function () {
    watch(['./assets/JS output/*.js', './assets/JS output/**/*.js'], jsConcat);
};