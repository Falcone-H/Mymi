const target = 'http://106.55.165.218/';
const path = require('path');
const resolve = dir => path.join(__dirname, dir);
module.exports = {
    publicPath: '/',
    devServer: {
        open: true,
        proxy: {
            '/api': {
                target: target, // 线上后端地址
                changeOrigin: true, //允许跨域
                pathRewrite: {
                    '^/api': ''
                }
            }
        }
    },
    chainWebpack: config => {
        config.resolve.alias
            .set('@', resolve('src'))
    }
}