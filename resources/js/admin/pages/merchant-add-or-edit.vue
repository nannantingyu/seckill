<template>
    <div>
        <el-form ref="form" :model="new_merchant" label-position="right" label-width="120px" :rules="merchant_rule">
            <el-form-item label="账户名" prop="account_name">
                <el-input v-model="new_merchant.account_name"></el-input>
            </el-form-item>
            <el-form-item label="商户名" prop="nick_name">
                <el-input v-model="new_merchant.nick_name"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" prop="email">
                <el-input v-model="new_merchant.email"></el-input>
            </el-form-item>
            <el-form-item label="经营范围" prop="scope">
                <el-input v-model="new_merchant.scope"></el-input>
            </el-form-item>
            <el-form-item label="图片" prop="avatar">
                <el-upload
                    class="avatar-uploader"
                    :limit="1"
                    action="/ad/uploads_avatar"
                    name="avatar"
                    :headers="headers"
                    :file-list="avatar_src"
                    list-type="picture-card"
                    :on-success="handleSuccess"
                    :on-remove="handleRemove">
                    <i class="el-icon-plus"></i>
                </el-upload>
            </el-form-item>
            <el-row>
                <el-col :span="4" :offset="10">
                    <el-button @click="commit">添加</el-button>
                </el-col>
            </el-row>
        </el-form>
    </div>
</template>

<script>
    import Vue from "vue";
    import {mapState, mapMutations} from "vuex";
    import {Upload, Button, Form, Input, FormItem} from "element-ui";
    import tool from "../tool";
    Vue.use(Upload);
    Vue.use(Button);
    Vue.use(Form);
    Vue.use(FormItem);
    Vue.use(Input);

    export default {
        name: "index",
        computed: {
            ...mapState({
                "new_merchant": state=>state.merchant.new_merchant,
                "merchant_rule": state=>state.merchant.merchant_rule,
                "image_url": state=>state.image_url,
                "headers": state=>state.headers
            }),
            avatar_src() {
                if (this.new_merchant.avatar) {
                    return [{name:this.new_merchant.avatar, url:tool.transfer_img_src(this.new_merchant.avatar)}]
                }

                return [];
            }
        },
        methods: {
            ...mapMutations({
                update_new_merchant: "merchant/set_new_merchant"
            }),
            commit() {
                const _this = this;
                this.$refs['form'].validate(valid=>{
                    if (valid) {
                        if (! _this.new_merchant.id) {
                            _this.$store.dispatch('merchant/add_merchant').then((id)=>{
                                _this.$success('添加成功！');
                                _this.$store.commit('merchant/set_new_merchant', {id: id});
                                _this.$store.commit('merchant/add_new_merchant', _this.new_merchant);

                                this.$store.commit("merchant/set_visible", {key: "add", value: false});
                            }).catch(error=>{
                                _this.$error(error);
                            });
                        }
                        else {
                            _this.$store.dispatch('merchant/update_merchant').then(()=>{
                                _this.$success('修改成功！');
                                _this.$store.commit('merchant/update_merchant_by_key', {
                                    key: "id",
                                    key_val: _this.new_merchant.id,
                                    updates: _this.new_merchant
                                });

                                this.$store.commit("merchant/set_visible", {key: "update", value: false});
                            }).catch(error=>{
                                _this.$error(error);
                            });
                        }
                    }
                    else this.$error('请检查您填写的内容！');
                });
            },
            handleSuccess(img_src) {
                this.update_new_merchant({avatar: img_src['avatar']});
            },
            handleRemove() {

            },
        }
    }
</script>

<style scoped>

</style>