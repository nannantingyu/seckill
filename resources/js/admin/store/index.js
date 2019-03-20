import Vue from "vue";
import Vuex from "vuex";
import merchant from "./merchant";
import flash_sale from "./flash_sale";

Vue.use(Vuex);
export default new Vuex.Store({
    modules: {
        merchant,
        flash_sale
    },
    state: {
        site_url: window.site_url,
        image_url: window.image_url,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
        },
    }
});