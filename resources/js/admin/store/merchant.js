import tool from "../tool";
const state = {
    page: {
        page_index: 1,
        per_page: 3,
        total: 0
    },
    merchant_list: [],
    list_back: [],
    new_merchant: {
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
    merchant_rule: {
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
    set_merchant_list(state, merchant_list) {
        state.merchant_list = merchant_list;
    },
    set_back_data(state, back_data) {
        state.back_data = back_data;
    },
    reset_merchant_list(state) {
        state.merchant_list = tool.deepClone(state.back_data);
    },
    delete_merchant_from_list(state, id) {
        tool.removeListByKey(state.merchant_list, "id", id);
        tool.removeListByKey(state.back_data, "id", id);
    },
    update_merchant_by_index(state, {index, updates}) {
        tool.updateListByIndex(state.merchant_list, index, updates);
        state.back_data = tool.deepClone(state.merchant_list);
    },
    update_merchant_by_key(state, {key, key_val, updates}) {
        tool.updateListByKey(state.merchant_list, key, key_val, updates);
        tool.updateListByKey(state.back_data, key, key_val, updates);
    },
    add_new_merchant(state, merchant) {
        state.merchant_list.unshift(merchant);
        state.back_data.unshift(merchant);
    },
    set_new_merchant(state, new_merchant) {
        state.new_merchant = Object.assign(state.new_merchant, new_merchant);
    },
    clear_new_merchant(state) {
        state.new_merchant = {
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
    get_merchant_list({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.get('/admin/merchant').then(result=>{
                commit('set_merchant_list', result.data.data);
                commit('set_back_data', tool.deepClone(result.data.data));
                commit('set_page', {
                    total: result.data.data.length
                });
                resolve(result.data.data);
            });
        })
    },
    update_merchant({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.post('/admin/merchantUpdate', state.new_merchant).then(result=>{
                resolve(result.data.data.merchant_id);
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    add_merchant({commit, state, dispatch}) {
        return new Promise((resolve, reject)=> {
            axios.post('/admin/merchant', state.new_merchant).then(result=>{
                resolve(result.data.data.merchant_id);
            }).catch(error=>{
                reject(tool.handle_laravel_errors(error.response.data.errors));
            });
        });
    },
    delete_merchant({commit, state, dispatch}, id) {
        return new Promise((resolve, reject)=> {
            axios.post('/admin/merchantDelete', {id: id}).then(()=>{
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