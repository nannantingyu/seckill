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
            name: 'shopper',
            path: '/shopper',
            component: resolve=>void(require(['./pages/shopper'], resolve))
        },
        {
            name: 'seckill',
            path: '/seckill',
            component: resolve=>void(require(['./pages/seckill'], resolve))
        }
    ]
});