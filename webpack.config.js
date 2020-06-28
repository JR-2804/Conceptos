var path = require("path");

module.exports = {
  entry: {
    app: "./assets/scripts/app.js",
    product: "./assets/scripts/product.js",
    products: "./assets/scripts/products.js",
    shop_cart: "./assets/scripts/shop_cart.js",
    blog: "./assets/scripts/blog.js"
  },
  output: {
    path: path.resolve(
      __dirname,
      "./public_html/bundles/conceptos/14ndy15/scripts"
    ),
    filename: "[name].js"
  },
  module: {
    rules: [
      {
        loader: "babel-loader",
        query: {
          presets: ["es2015"]
        },
        test: /\.js$/,
        exclude: /node_modules/
      }
    ]
  },
  // mode: "development"
  mode: 'production',
};
