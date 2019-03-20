import tool from "../tool";
const state = {
    page: {
        page_index: 1,
        per_page: 3,
        total: 0
    },
    flash_sale_list: [],
    list_back: [],
    visible: {
        update: false,
        add: false
    },
};

const mutations = {
    set_page(state, page) {
        Object.assign(state.page, page);
    },
    set_flash_sale_list(state, flash_sale_list) {
        state.flash_sale_list = flash_sale_list;
    },
    set_back_data(state, back_data) {
        state.back_data = back_data;
    },
    reset_flash_sale_list(state) {
        state.flash_sale_list = tool.deepClone(state.back_data);
    },
    delete_flash_sale_from_list(state, id) {
        tool.removeListByKey(state.flash_sale_list, "id", id);
        tool.removeListByKey(state.back_data, "id", id);
    },
    update_flash_sale_by_index(state, {index, updates}) {
        tool.updateListByIndex(state.flash_sale_list, index, updates);
        state.back_data = tool.deepClone(state.flash_sale_list);
    },
    update_flash_sale_by_key(state, {key, key_val, updates}) {
        tool.updateListByKey(state.flash_sale_list, key, key_val, updates);
        tool.updateListByKey(state.back_data, key, key_val, updates);
    },
    set_visible(state, {key, value}) {
        if (state.visible.hasOwnProperty(key)) {
            state.visible[key] = value;
        }
    },
};

const actions = {
    get_flash_sale_list({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.get('/admin/flashSale').then(result=>{
                commit('set_flash_sale_list', result.data.data);
                commit('set_back_data', tool.deepClone(result.data.data));
                commit('set_page', {
                    total: result.data.data.length
                });
                resolve(result.data.data);
            });
        })
    },
    check_flash_sale({commit, state, dispatch}, {id, new_state}) {
        return new Promise((resolve, reject)=> {
            axios.post('/admin/flashSaleCheck', {id:id, state:new_state}).then(result=>{
                resolve();
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    add_flash_sale({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.post('/admin/flashSale', state.new_flash_sale).then(result=>{
                resolve(result.data.data.flash_sale_id);
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    delete_flash_sale({commit, state, dispatch}, id) {
        return new Promise((resolve, reject)=> {
            axios.post('/admin/flashSaleDelete', {id: id}).then(()=>{
                resolve();
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    }
};

export default {
    namespaced: true,
    state: state,
    mutations: mutations,
    actions: actions
}