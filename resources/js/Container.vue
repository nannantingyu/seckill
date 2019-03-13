<template>
    <div style="height: 100%">
        <aside width="*" :class="aside_class">
            <el-menu :default-active="$route.path"
                     class="el-menu-vertical-demo"
                     router
                     background-color="#333"
                     text-color="#fff"
                     :collapse="false">
                <el-menu-item
                    index="/">
                    <i class="el-icon-tickets"></i>
                    <span slot="title">首页</span>
                </el-menu-item>
                <el-submenu
                    index="/shopper">
                    <template slot="title">
                        <i class="fa fa-user-o"></i>
                        <span slot="title">商家管理</span>
                    </template>
                    <el-menu-item-group>
                        <el-menu-item
                            index="/shopper">
                            <i class="fa fa-user-o"></i>
                            <span slot="title">商家列表</span>
                        </el-menu-item>
                    </el-menu-item-group>
                </el-submenu>
                <el-submenu
                    index="/seckill">
                    <template slot="title">
                        <i class="fa fa-user-o"></i>
                        <span slot="title">秒杀管理</span>
                    </template>
                    <el-menu-item-group>
                        <el-menu-item
                            index="/seckill">
                            <i class="fa fa-user-o"></i>
                            <span slot="title">秒杀申请列表</span>
                        </el-menu-item>
                    </el-menu-item-group>
                </el-submenu>
                <el-submenu
                    index="/permission">
                    <template slot="title">
                        <i class="fa fa-institution"></i>
                        <span slot="title">权限管理</span>
                    </template>
                    <el-menu-item-group>
                        <el-menu-item
                            index="/role-user">
                            <i class="fa fa-drivers-license-o"></i>
                            <span slot="title">角色分配</span>
                        </el-menu-item>
                        <el-menu-item
                            index="/module">
                            <i class="fa fa-medium"></i>
                            <span slot="title">模块管理</span>
                        </el-menu-item>
                        <el-menu-item
                            index="/permission">
                            <i class="fa fa-flash"></i>
                            <span slot="title">权限管理</span>
                        </el-menu-item>
                    </el-menu-item-group>
                </el-submenu>
                <el-submenu
                    index="/api-list">
                    <template slot="title">
                        <i class="fa fa-institution"></i>
                        <span slot="title">API管理</span>
                    </template>
                    <el-menu-item-group>
                        <el-menu-item
                            index="/api-list">
                            <i class="fa fa-drivers-license-o"></i>
                            <span slot="title">API列表</span>
                        </el-menu-item>
                        <el-menu-item
                            index="/api-user-list">
                            <i class="fa fa-drivers-license-o"></i>
                            <span slot="title">API客户列表</span>
                        </el-menu-item>
                        <el-menu-item
                            index="/user-api">
                            <i class="fa fa-drivers-license-o"></i>
                            <span slot="title">客户API分配</span>
                        </el-menu-item>
                    </el-menu-item-group>
                </el-submenu>
            </el-menu>
        </aside>
        <div @click="switch_menu" class="menu-switch" :class="menu_class"></div>
        <div class="container" :class="container_class">
            <el-main>
                <router-view></router-view>
            </el-main>
        </div>
    </div>
</template>

<script>
    import {Container, Aside, Menu, MenuItem, Main, Submenu, MenuItemGroup} from "element-ui";
    import Vue from "vue";
    Vue.use(Container);
    Vue.use(Aside);
    Vue.use(Menu);
    Vue.use(MenuItem);
    Vue.use(Main);
    Vue.use(Submenu);
    Vue.use(MenuItemGroup);
    export default {
        name: "Container",
        data() {
            return {
                "menu_class": "menu-switch-close",
                "container_class": "container-close-size",
                "aside_class": "",
                "aside_show": true
            }
        },
        methods: {
            switch_menu() {
                this.menu_class = this.aside_show?"menu-switch-open":"menu-switch-close";
                this.aside_class = this.aside_show?"slide-out-left":"slide-in-left";
                this.container_class = this.aside_show?"container-open-size":"container-close-size";
                this.aside_show = !this.aside_show;
            }
        }
    }
</script>

<style scoped>

</style>