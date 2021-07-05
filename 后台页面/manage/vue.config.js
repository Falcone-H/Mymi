const target = 'http://106.55.165.218/'
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
    }
}