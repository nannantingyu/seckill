import Vue from "vue";
import Vuex from "vuex";
import shopper from "./shopper";
import seckill from "./seckill";

Vue.use(Vuex);
export default new Vuex.Store({
    modules: {
        shopper,
        seckill
    },
    state: {
        site_url: window.site_url,
        image_url: window.image_url,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
        },
    },
    getters: {
        retrieve_images() {

        }
    }
});