<template>
    <div>
        <el-row>
            <el-col :span="3">
                <el-input v-model="search_key" placeholder="搜索(回车执行)" @blur="search" @keyup.enter.native="search"></el-input>
            </el-col>
            <el-col :span="3" :offset="18">
                <el-button size="small" @click="add_shopper">添加商家</el-button>
            </el-col>
        </el-row>
        <el-row>
            <el-table :data="table_data" style="width: 100%; height: 100%;" size="mini" @sort-change="change_sort">
                <el-table-column prop="id" label="ID" width="70" sortable></el-table-column>
                <el-table-column prop="nick_name" label="商家名" width="100"></el-table-column>
                <el-table-column prop="avatar" width="120" label="头像">
                    <template slot-scope="scope">
                        <img :src="/scope.row.avatar" :alt="scope.row.nick_name" style="width: 100px; height: 100px;">
                    </template>
                </el-table-column>
                <el-table-column prop="account_name" label="用户名" width="100"></el-table-column>
                <el-table-column prop="email" width="*" label="邮箱"></el-table-column>
                <el-table-column prop="scope" width="200" label="经营范围"></el-table-column>
                <el-table-column width="200">
                    <template slot-scope="scope">
                        <el-button size="small" type="danger" @click="update_shopper(scope.row)">编辑</el-button>
                        <el-button size="small" type="danger" @click="delete_shopper(scope.row.id)">删除</el-button>
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
                :total="shopper_list.length">
            </el-pagination>
        </el-row>

        <el-dialog :title="visible_add?'添加商家':'修改商家'" :visible.sync="visible">
            <AddShopper></AddShopper>
        </el-dialog>
    </div>
</template>

<script>
    import Vue from "vue";
    import {mapState} from "vuex";
    import {Row, Col, Table, TableColumn, Button, Dialog, Pagination, Input} from "element-ui";
    import AddShopper from "./shopper-add-or-edit";
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
        components: {AddShopper},
        data() {return {
            search_key: ''
        }},
        computed: {
            ...mapState({
                "shopper_list": state=>state.shopper.shopper_list,
                "visible_add": state=>state.shopper.visible.add,
                "page": state=>state.shopper.page
            }),
            visible: {
                get() {
                    return this.$store.state.shopper.visible.update || this.$store.state.shopper.visible.add;
                },
                set(value) {
                    this.$store.commit("shopper/set_visible", {key: "update", value: value});
                    this.$store.commit("shopper/set_visible", {key: "add", value: value});
                }
            },
            table_data() {
                return this.shopper_list.slice((this.page.page_index-1)*this.page.per_page, this.page.page_index*this.page.per_page)
            }
        },
        methods: {
            size_change(size) {
                this.$store.commit("shopper/set_page", {
                    per_page: size
                });
            },
            add_shopper() {
                this.$store.commit("shopper/clear_new_shopper");
                this.$store.commit("shopper/set_visible", {key: "add", value: true});
            },
            update_shopper(shopper) {
                this.$store.commit("shopper/set_visible", {key: "update", value: true});
                this.$store.commit('shopper/set_new_shopper', shopper);
            },
            change_sort({column, prop, order}) {
                this.$store.commit("shopper/set_shopper_list", this.tool.sorted_table_data(this.shopper_list, prop, order));
            },
            search() {
                if (!this.search_key) {
                    this.$store.commit("shopper/reset_shopper_list");
                }else {
                    this.$store.commit("shopper/set_shopper_list", this.tool.search_table_data(this.$store.state.shopper.back_data, this.search_key));
                }
            },
            delete_shopper(id) {
                const _this = this;
                this.$store.dispatch("shopper/delete_shopper", id).then(()=>{
                    _this.$store.commit("shopper/delete_shopper_from_list", id);
                    _this.$success("删除成功！");
                }).catch(error=>_this.$error(error));
            }
        },
        created() {
            const _this = this;
            if (this.shopper_list.length === 0) {
                this.$store.dispatch('shopper/get_shopper_list').then(x=>{
                    _this.$success("成功加载商家信息！");
                });
            }
        }
    }
</script>

<style scoped>

</style>