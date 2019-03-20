<template>
    <div>
        <el-row>
            <el-col :span="3">
                <el-input v-model="search_key" placeholder="搜索(回车执行)" @blur="search" @keyup.enter.native="search"></el-input>
            </el-col>
            <el-col :span="3" :offset="18">
                <el-button size="small" @click="add_merchant">添加商家</el-button>
            </el-col>
        </el-row>
        <el-row>
            <el-table :data="table_data" style="width: 100%; height: 100%;" size="mini" @sort-change="change_sort">
                <el-table-column prop="id" label="ID" width="70" sortable></el-table-column>
                <el-table-column prop="nick_name" label="商家名" width="100"></el-table-column>
                <el-table-column prop="avatar" width="120" label="头像">
                    <template slot-scope="scope">
                        <img :src="'/'+scope.row.avatar" :alt="scope.row.nick_name" style="width: 100px; height: 100px;">
                    </template>
                </el-table-column>
                <el-table-column prop="account_name" label="用户名" width="100"></el-table-column>
                <el-table-column prop="email" width="*" label="邮箱"></el-table-column>
                <el-table-column prop="scope" width="200" label="经营范围"></el-table-column>
                <el-table-column width="200">
                    <template slot-scope="scope">
                        <el-button size="small" type="danger" @click="update_merchant(scope.row)">编辑</el-button>
                        <el-button size="small" type="danger" @click="delete_merchant(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>
        <el-row>
            <el-pagination
                :current-page.sync="page.page_index"
                :page-size="page.per_page"
                @size-change="size_change"
                :page-sizes="[3, 5, 10, 20, 30, 40, 50, 100]"
                class="align-center"
                layout="sizes, total, prev, pager, next"
                :total="merchant_list.length">
            </el-pagination>
        </el-row>

        <el-dialog :title="visible_add?'添加商家':'修改商家'" :visible.sync="visible">
            <Addmerchant></Addmerchant>
        </el-dialog>
    </div>
</template>

<script>
    import Vue from "vue";
    import {mapState} from "vuex";
    import {Row, Col, Table, TableColumn, Button, Dialog, Pagination, Input} from "element-ui";
    import Addmerchant from "./merchant-add-or-edit";
    Vue.use(Row);
    Vue.use(Col);
    Vue.use(Table);
    Vue.use(TableColumn);
    Vue.use(Button);
    Vue.use(Dialog);
    Vue.use(Input);
    Vue.use(Pagination);

    export default {
        name: "index",
        components: {Addmerchant},
        data() {return {
            search_key: ''
        }},
        computed: {
            ...mapState({
                "merchant_list": state=>state.merchant.merchant_list,
                "visible_add": state=>state.merchant.visible.add,
                "page": state=>state.merchant.page
            }),
            visible: {
                get() {
                    return this.$store.state.merchant.visible.update || this.$store.state.merchant.visible.add;
                },
                set(value) {
                    this.$store.commit("merchant/set_visible", {key: "update", value: value});
                    this.$store.commit("merchant/set_visible", {key: "add", value: value});
                }
            },
            table_data() {
                return this.merchant_list.slice((this.page.page_index-1)*this.page.per_page, this.page.page_index*this.page.per_page)
            }
        },
        methods: {
            size_change(size) {
                this.$store.commit("merchant/set_page", {
                    per_page: size
                });
            },
            add_merchant() {
                this.$store.commit("merchant/clear_new_merchant");
                this.$store.commit("merchant/set_visible", {key: "add", value: true});
            },
            update_merchant(merchant) {
                this.$store.commit("merchant/set_visible", {key: "update", value: true});
                this.$store.commit('merchant/set_new_merchant', merchant);
            },
            change_sort({column, prop, order}) {
                this.$store.commit("merchant/set_merchant_list", this.tool.sorted_table_data(this.merchant_list, prop, order));
            },
            search() {
                if (!this.search_key) {
                    this.$store.commit("merchant/reset_merchant_list");
                }else {
                    this.$store.commit("merchant/set_merchant_list", this.tool.search_table_data(this.$store.state.merchant.back_data, this.search_key));
                }
            },
            delete_merchant(id) {
                const _this = this;
                this.$store.dispatch("merchant/delete_merchant", id).then(()=>{
                    _this.$store.commit("merchant/delete_merchant_from_list", id);
                    _this.$success("删除成功！");
                }).catch(error=>_this.$error(error));
            }
        },
        created() {
            const _this = this;
            if (this.merchant_list.length === 0) {
                this.$store.dispatch('merchant/get_merchant_list').then(x=>{
                    _this.$success("成功加载商家信息！");
                });
            }
        }
    }
</script>