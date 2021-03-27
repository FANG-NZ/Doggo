const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

const src_path = "app/reactclient/"
const dist_path = "public/"

module.exports = {
    mode: 'development',

    watch: true,
    watchOptions: {
        ignored: /node_modules/,
    },

    //entry: path.resolve(__dirname, src_path, 'app.js'),
    entry: {
        app:[
            path.resolve(__dirname, src_path, 'js/app.js'),
            path.resolve(__dirname, src_path, 'scss/app.scss')
        ]
    },

    output: {
        path: path.resolve(__dirname, dist_path + "/js"),
        filename: 'app.js',
    },

    devServer: {
        contentBase: path.resolve(__dirname, dist_path + "/js"),
        open: true,
        clientLogLevel: 'silent',
        port: 9000
    },

    module: {
        rules: [
            // {
            //     test: /\.js$/,
            //     enforce: "pre",
            //     use: ["source-map-loader"],
            // },
            {
                test: /\.(jsx|js)$/,
                include: path.resolve(__dirname, src_path),
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: [
                                ['@babel/preset-env', {
                                    "targets": "defaults" 
                                }],
                            '@babel/preset-react'
                            ]
                        }
                    }
                ]
            },

            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false
                        }
                    },
                    {
                      loader: 'sass-loader'
                    }
                ]
            },

            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false
                        }
                    }
                ]
            },

            {
                test: /\.(jpg|png|svg)$/,
                use: {
                    loader: 'url-loader',
                    options: {
                        limit: 25000
                    }
                }
            }

        ]
    },

    resolve: {
        alias: {
            node_modules: 'node_modules',
        },
        extensions: ['.js', '.jsx', '.ts', '.tsx'],
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: '../css/[name].css'
        }),
    ],
}