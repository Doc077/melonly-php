const { join, resolve } = require('path')

const framework = 'react'

module.exports = {
  mode: 'development',

  entry: join(__dirname, 'frontend', framework, 'index.js'),

  output: {
    path: resolve(__dirname, 'public', 'js'),
    filename: 'index.js',
  },

  module: {
    rules: [
      {
        test: /\.s[ac]ss$/,
        exclude: /node_modules/,
        use: [
          'sass-loader',
        ],
      },

      // React.js config
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

      // Vue.js config
      // {
      //   test: /\.vue$/,
      //   exclude: /node_modules/,
      //   loader: 'vue-loader',
      // },
    ],
  },
}
