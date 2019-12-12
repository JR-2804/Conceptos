var path = require('path');

module.exports = {
    entry: {
        app: "./assets/js/app.js",
    },
    output: {
        path: path.resolve(__dirname, "./public_html/static/js/"),
        filename: "[name].js"
    },
    module: {
        rules: [
            {
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                },
                test: /\.js$/,
                exclude: /node_modules/
            }
        ]
    },
    mode: 'development',
    // mode: 'production',
};
