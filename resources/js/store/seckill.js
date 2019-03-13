import tool from "../tool";
const state = {
    page: {
        page_index: 1,
        per_page: 3,
        total: 0
    },
    seckill_list: [],
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
    set_seckill_list(state, seckill_list) {
        state.seckill_list = seckill_list;
    },
    set_back_data(state, back_data) {
        state.back_data = back_data;
    },
    reset_seckill_list(state) {
        state.seckill_list = tool.deepClone(state.back_data);
    },
    delete_seckill_from_list(state, id) {
        tool.removeListByKey(state.seckill_list, "id", id);
        tool.removeListByKey(state.back_data, "id", id);
    },
    update_seckill_by_index(state, {index, updates}) {
        tool.updateListByIndex(state.seckill_list, index, updates);
        state.back_data = tool.deepClone(state.seckill_list);
    },
    update_seckill_by_key(state, {key, key_val, updates}) {
        tool.updateListByKey(state.seckill_list, key, key_val, updates);
        tool.updateListByKey(state.back_data, key, key_val, updates);
    },
    set_visible(state, {key, value}) {
        if (state.visible.hasOwnProperty(key)) {
            state.visible[key] = value;
        }
    },
};

const actions = {
    get_seckill_list({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.get('/ad/seckill').then(result=>{
                commit('set_seckill_list', result.data.data);
                commit('set_back_data', tool.deepClone(result.data.data));
                commit('set_page', {
                    total: result.data.data.length
                });
                resolve(result.data.data);
            });
        })
    },
    check_seckill({commit, state, dispatch}, {id, new_state}) {
        return new Promise((resolve, reject)=> {
            axios.post('/ad/seckill/check', {id:id, state:new_state}).then(result=>{
                resolve();
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    add_seckill({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.post('/ad/seckill/store', state.new_seckill).then(result=>{
                resolve(result.data.data.seckill_id);
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    delete_seckill({commit, state, dispatch}, id) {
        return new Promise((resolve, reject)=> {
            axios.post('/ad/seckill/delete', {id: id}).then(()=>{
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