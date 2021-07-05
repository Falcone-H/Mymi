import {
    createApp
} from 'vue'
import App from './App.vue'
import router from './router'
import ElementPlus from 'element-plus'
import 'element-plus/lib/theme-chalk/index.css';
import Global from './Global'
import Axios from 'axios'
import store from './store'
import MakeitCaptcha from 'makeit-captcha'
import 'makeit-captcha/dist/captcha.min.css'

var app = createApp(App);
app.use(router);
app.use(ElementPlus);
app.use(Global);
app.use(store);
app.use(MakeitCaptcha);
app.config.globalProperties.$axios = Axios;

// 全局请求拦截器
Axios.interceptors.request.use(
    config => {
        return config;
    },
    error => {
        return Promise.reject(error);
    }
)

// 全局响应拦截器
Axios.interceptors.response.use(
    res => {
        if (res.data.code === "401") {
            // 401 表示没有登录
            app.config.globalProperties.notifyError(res.data.msg);
            // 修改 vuex 的 showLogin 状态，显示登录组件
            // store.dispatch("setShowLogin", true);
        }
        if (res.data.code === "500") {
            // 500 表示服务器异常
            // 跳转 error 页面
            // router.push({ path: "/error" });
        }
        return res;
    },
    error => {
        // 跳转 error 页面
        // router.push({ path: "/error" });
        return Promise.reject(error);
    }

)

// 全局拦截器，在进入需要用户权限的页面前校验是否已经登录
router.beforeResolve((to, from, next) => {
    const loginUser = store.state.user.user;
    // 判断路由是否设置响应校验用户权限
    if (to.meta.requireAuth) {
        if (!loginUser) {
            // 没有登录
            store.dispatch("setShowLogin", true);
            if (from.name == null) {
                // 在页面没有加载，直接在地址栏输入链接的情况，需要进入登录验证页面
                next("/");
                return;
            }
            // 终止导航
            next(false);
            return;
        }
    }
    next();
});

// 全局组件
import MyMenu from './components/MyMenu.vue';
app.component(MyMenu.name, MyMenu);
import MyList from './components/MyList.vue';
app.component(MyList.name, MyList);
import MyLogin from './components/MyLogin.vue';
app.component(MyLogin.name, MyLogin);
import MyRegister from './components/MyRegister.vue';
app.component(MyRegister.name, MyRegister);
import MyComment from './components/MyComment.vue';
app.component(MyComment.name, MyComment);

app.mount('#app');