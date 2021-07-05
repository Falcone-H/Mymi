import {
    createRouter,
    createWebHistory
} from "vue-router";
import Home from "../views/Home.vue";

const routes = [{
    path: '/',
    redirect: '/dashboard'
}, {
    path: "/",
    name: "Home",
    component: Home,
    children: [{
        path: "/dashboard",
        name: "dashboard",
        meta: {
            title: '系统首页'
        },
        component: () => import(
            "../views/Dashboard.vue")
    }, {
        path: "/product",
        name: "product",
        meta: {
            title: '商品管理'
        },
        component: () => import(
            "../views/Product.vue")
    }, {
        path: "/order",
        name: "order",
        meta: {
            title: '订单管理'
        },
        component: () => import(
            "../views/Order.vue")
    }, {
        path: "/upload",
        name: "upload",
        meta: {
            title: '上传插件'
        },
        component: () => import(
            "../views/Upload.vue")
    }, {
        path: '/404',
        name: '404',
        meta: {
            title: '找不到页面'
        },
        component: () => import(
            '../views/404.vue')
    }, {
        path: '/403',
        name: '403',
        meta: {
            title: '没有权限'
        },
        component: () => import(
            '../views/403.vue')
    }]
}, {
    path: "/login",
    name: "Login",
    meta: {
        title: '登录'
    },
    component: () => import(
        "../views/Login.vue")
}];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
});

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} | vue-manage-system`;
    const role = localStorage.getItem('ms_username');
    if (!role && to.path !== '/login') {
        next('/login');
    } else if (to.meta.permission) {
        // 如果是管理员权限则可进入，这里只是简单的模拟管理员权限而已
        role === 'admin' ?
            next() :
            next('/403');
    } else {
        next();
    }
});

export default router;