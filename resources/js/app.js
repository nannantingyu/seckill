require('./bootstrap');
window.Vue = require('vue');

Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

import router from "./router";
import App from "./Container.vue";
import store from "./store";
import "./app.scss";
import 'element-ui/lib/theme-chalk/index.css';
import {Message} from "element-ui";
import tool from "./tool";
Vue.prototype.$message = Message;
Vue.prototype.tool = tool;
const show_message = function(message_type) {
    return function (message){
        Message({
            type: message_type,
            dangerouslyUseHTMLString: true,
            message: message
        });
    }
};
Vue.prototype.$error = show_message('error');
Vue.prototype.$info = show_message('info');
Vue.prototype.$success = show_message('success');

const app = new Vue({
    el: "#app",
    router,
    store,
    render: h=>h(App)
});
