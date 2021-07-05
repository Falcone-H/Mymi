exports.install = function (app) {
    app.config.globalProperties.$target = "http://121.196.165.207/"; // 后端地址

    app.config.globalProperties.notifySucceed = function (msg) { // 封装响应成功的函数
        this.$notify({
            title: "Success",
            message: msg,
            type: "success",
            offset: 100
        });
    };

    app.config.globalProperties.notifyError = function (msg) { // 封装响应失败的函数
        this.$notify.error({
            title: "Error",
            message: msg,
            offset: 100
        })
    }
}