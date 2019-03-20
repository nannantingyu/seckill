import Vue from "vue"
import VueRouter from "vue-router"
Vue.use(VueRouter);

export default new VueRouter ({
    routes: [
        {
            name: 'index',
            path: '/index',
            component: resolve=>void(require(['./pages/index'], resolve))
        },
        {
            name: 'merchant',
            path: '/merchant',
            component: resolve=>void(require(['./pages/merchant'], resolve))
        },
        {
            name: 'flash_sale',
            path: '/flash_sale',
            component: resolve=>void(require(['./pages/flash_sale'], resolve))
        }
    ]
});