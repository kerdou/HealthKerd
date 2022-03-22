const path = require('path');

module.exports = {
    mode: "development",
    watch: true,
    watchOptions: {
        ignored: ['./node_modules/', './src/']
    },
    entry: './assets/JS output/index.js',
    output: {
        filename: 'script.js',
        path: path.resolve(__dirname, './public/js'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                enforce: 'pre',
                use: ['source-map-loader']
            }
        ]
    }
};