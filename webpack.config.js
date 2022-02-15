const path = require('path')

module.exports = {
   mode: 'development',

   // entry: path.join(__dirname, 'frontend', 'react', 'index.js'),

   // output: {
   //    path: path.resolve(__dirname, 'public', 'js'),
   //    filename: 'index.js',
   // },

   module: {
      rules: [
         {
            test: /\.s[ac]ss$/,
            exclude: /node_modules/,
            use: [
               'sass-loader',
            ],
         },
         // {
         //    test: /\.?js$/,
         //    exclude: /node_modules/,
         //    use: {
         //       loader: 'babel-loader',
         //       options: {
         //          presets: [
         //             '@babel/preset-env',
         //             '@babel/preset-react',
         //          ],
         //       },
         //    },
         // },
         // {
         //    test: /\.vue$/,
         //    loader: 'vue-loader',
         // },
      ],
   },
}
