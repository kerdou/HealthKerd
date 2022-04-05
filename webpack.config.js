const path = require('path');

module.exports = {
    mode: "development",
    watch: true,
    watchOptions: {
        ignored: [
            './assets',
            './dev',
            './node_modules/',
            './src/',
            './templates',
            './tests',
            './vendor'
        ]
    },
    entry: './assets/JS output/index.js',
    output: {
        filename: 'script.js',
        path: path.resolve(__dirname, './public/js'),
        clean: true
    }
};