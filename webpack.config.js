const path = require('path');

module.exports = [
    {
        name: 'dev',
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
    },
    {
        name: 'prod',
        mode: "production",
        watch: false,
        entry: './assets/JS output/index.js',
        output: {
            filename: 'script.js',
            path: path.resolve(__dirname, './public/js'),
            clean: true
        }
    }
];