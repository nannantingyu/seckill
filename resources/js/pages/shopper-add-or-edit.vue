<template>
    <div>
        <el-form ref="form" :model="new_shopper" label-position="right" label-width="120px" :rules="shopper_rule">
            <el-form-item label="账户名" prop="account_name">
                <el-input v-model="new_shopper.account_name"></el-input>
            </el-form-item>
            <el-form-item label="商户名" prop="nick_name">
                <el-input v-model="new_shopper.nick_name"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" prop="email">
                <el-input v-model="new_shopper.email"></el-input>
            </el-form-item>
            <el-form-item label="经营范围" prop="scope">
                <el-input v-model="new_shopper.scope"></el-input>
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
                "new_shopper": state=>state.shopper.new_shopper,
                "shopper_rule": state=>state.shopper.shopper_rule,
                "image_url": state=>state.image_url,
                "headers": state=>state.headers
            }),
            avatar_src() {
                if (this.new_shopper.avatar) {
                    return [{name:this.new_shopper.avatar, url:tool.transfer_img_src(this.new_shopper.avatar)}]
                }

                return [];
            }
        },
        methods: {
            ...mapMutations({
                update_new_shopper: "shopper/set_new_shopper"
            }),
            commit() {
                const _this = this;
                this.$refs['form'].validate(valid=>{
                    if (valid) {
                        if (! _this.new_shopper.id) {
                            _this.$store.dispatch('shopper/add_shopper').then((id)=>{
                                _this.$success('添加成功！');
                                _this.$store.commit('shopper/set_new_shopper', {id: id});
                                _this.$store.commit('shopper/add_new_shopper', _this.new_shopper);

                                this.$store.commit("shopper/set_visible", {key: "add", value: false});
                            }).catch(error=>{
                                _this.$error(error);
                            });
                        }
                        else {
                            _this.$store.dispatch('shopper/update_shopper').then(()=>{
                                _this.$success('修改成功！');
                                _this.$store.commit('shopper/update_shopper_by_key', {
                                    key: "id",
                                    key_val: _this.new_shopper.id,
                                    updates: _this.new_shopper
                                });

                                this.$store.commit("shopper/set_visible", {key: "update", value: false});
                            }).catch(error=>{
                                _this.$error(error);
                            });
                        }
                    }
                    else this.$error('请检查您填写的内容！');
                });
            },
            handleSuccess(img_src) {
                this.update_new_shopper({avatar: img_src['avatar']});
            },
            handleRemove() {

            },
        }
    }
</script>

<style scoped>

</style>