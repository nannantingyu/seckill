import tool from "../tool";
const state = {
    page: {
        page_index: 1,
        per_page: 3,
        total: 0
    },
    shopper_list: [],
    list_back: [],
    new_shopper: {
        id: null,
        account_name: '',
        avatar: '',
        nick_name: '',
        email: '',
        scope: ''
    },
    visible: {
        update: false,
        add: false
    },
    shopper_rule: {
        account_name: [
            { required: true, message: '请输入账户名', trigger: 'blur' },
            { min: 2, max: 32, message: '账户名长度在 2 到 32 个字符', trigger: 'blur' }
        ],
        nick_name: [
            { required: true, message: '请输入商家名', trigger: 'blur' },
            { min: 2, max: 32, message: '商家名长度在 2 到 32 个字符', trigger: 'blur' }
        ],
        scope: [
            { required: true, message: '请输入经营范围', trigger: 'blur' },
            { min: 2, max: 32, message: '经营范围长度在 2 到 32 个字符', trigger: 'blur' }
        ],
        avatar: [
            { required: true, message: '请上传照片', trigger: 'blur' },
        ],
        email: [
            { required: true, message: '请填写邮箱', trigger: 'blur' },
            { type: 'email', message: '邮箱格式不正确', trigger: 'blur' },
        ]
    }
};

const mutations = {
    set_page(state, page) {
        Object.assign(state.page, page);
    },
    set_shopper_list(state, shopper_list) {
        state.shopper_list = shopper_list;
    },
    set_back_data(state, back_data) {
        state.back_data = back_data;
    },
    reset_shopper_list(state) {
        state.shopper_list = tool.deepClone(state.back_data);
    },
    delete_shopper_from_list(state, id) {
        tool.removeListByKey(state.shopper_list, "id", id);
        tool.removeListByKey(state.back_data, "id", id);
    },
    update_shopper_by_index(state, {index, updates}) {
        tool.updateListByIndex(state.shopper_list, index, updates);
        state.back_data = tool.deepClone(state.shopper_list);
    },
    update_shopper_by_key(state, {key, key_val, updates}) {
        tool.updateListByKey(state.shopper_list, key, key_val, updates);
        tool.updateListByKey(state.back_data, key, key_val, updates);
    },
    add_new_shopper(state, shopper) {
        state.shopper_list.unshift(shopper);
        state.back_data.unshift(shopper);
    },
    set_new_shopper(state, new_shopper) {
        state.new_shopper = Object.assign(state.new_shopper, new_shopper);
    },
    clear_new_shopper(state) {
        state.new_shopper = {
            id: null,
            account_name: '',
            avatar: '',
            nick_name: '',
            email: '',
            scope: ''
        };
    },
    set_visible(state, {key, value}) {
        if (state.visible.hasOwnProperty(key)) {
            state.visible[key] = value;
        }
    },
};

const actions = {
    get_shopper_list({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.get('/ad/shopper').then(result=>{
                commit('set_shopper_list', result.data.data);
                commit('set_back_data', tool.deepClone(result.data.data));
                commit('set_page', {
                    total: result.data.data.length
                });
                resolve(result.data.data);
            });
        })
    },
    update_shopper({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.post('/ad/shopper/update', state.new_shopper).then(result=>{
                resolve(result.data.data.shopper_id);
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    add_shopper({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.post('/ad/shopper/store', state.new_shopper).then(result=>{
                resolve(result.data.data.shopper_id);
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    delete_shopper({commit, state, dispatch}, id) {
        return new Promise((resolve, reject)=> {
            axios.post('/ad/shopper/delete', {id: id}).then(()=>{
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