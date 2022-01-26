const path = require('path')

module.exports = {
    entry: path.join(__dirname, 'frontend', 'react', 'index.js'),

    mode: 'development',

    output: {
        path: path.resolve(__dirname, 'public', 'js'),
        filename: 'index.js',
    },

    module: {
        rules: [
            {
                test: /\.?js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            '@babel/preset-env',
                            '@babel/preset-react',
                        ],
                    },
                },
            },
            {
                test: /\.s[ac]ss$/,
                exclude: /node_modules/,
                use: [
                    'sass-loader',
                ],
            },
        ],
    },
}
