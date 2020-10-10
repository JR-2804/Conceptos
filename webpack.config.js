var path = require("path");

module.exports = [
    {
        entry: {
            app: "./assets/new_scripts/app.js",
            home: "./assets/new_scripts/home.js",
            store: "./assets/new_scripts/store.js",
            construction: "./assets/new_scripts/construction.js",
            construction_work: "./assets/new_scripts/construction_work.js",
            construction_service: "./assets/new_scripts/construction_service.js",
            design: "./assets/new_scripts/design.js",
            design_work: "./assets/new_scripts/design_work.js",
            design_service: "./assets/new_scripts/design_service.js",
            store_section: "./assets/new_scripts/store_section.js",
            product_details: "./assets/new_scripts/product_details.js",

            // app: "./assets/scripts/app.js",
            // product: "./assets/scripts/product.js",
            // products: "./assets/scripts/products.js",
            // shop_cart: "./assets/scripts/shop_cart.js",
            // blog: "./assets/scripts/blog.js"
        },
        output: {
            path: path.resolve(
                __dirname,
                "./public_html/bundles/conceptos/14ndy15/new_scripts"
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
        mode: "development"
        // mode: 'production',
    },
    {
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
        mode: "development"
        // mode: 'production',
    }
];