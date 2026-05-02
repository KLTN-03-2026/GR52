<template>
<<<<<<< HEAD
    <div :class="['wrapper', isSidebarOpen ? 'sidebar-open' : 'sidebar-mini']">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <MenuRocker ref="sidebarMenu" @sidebar-change="onSidebarChange"></MenuRocker>
=======
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <MenuRocker @sidebar-change="onSidebarChange"></MenuRocker>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
<<<<<<< HEAD
            <TopRocker @toggle-sidebar="toggleSidebar"></TopRocker>
=======
            <TopRocker></TopRocker>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
        </header>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper" :style="contentStyle">
            <div class="page-content">
                <router-view> </router-view>
            </div>
        </div>
        <!--end page wrapper -->
        <BotRocker></BotRocker>
    </div>
</template>
<script>
import TopRocker from "../components/TopRocker.vue";
import BotRocker from "../components/BotRocker.vue";
import MenuRocker from "../components/MenuRocker.vue";

// Import các file JS và CSS cần thiết cho layout
import "../../assets/js/bootstrap.bundle.min.js";
import "../../assets/js/jquery.min.js";
import "../../assets/plugins/simplebar/js/simplebar.min.js";
import "../../assets/plugins/metismenu/js/metisMenu.min.js";
import "../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js";
import "../../assets/js/index.js";
import "../../assets/js/app.js";
import "../../assets/js/pace.min.js";

import "../../assets/plugins/simplebar/css/simplebar.css";
import "../../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css";
import "../../assets/plugins/metismenu/css/metisMenu.min.css";
import "../../assets/css/pace.min.css";
import "../../assets/css/bootstrap.min.css";
import "../../assets/css/bootstrap-extended.css";
import "../../assets/css/app.css";
import "../../assets/css/icons.css";
<<<<<<< HEAD
import "../../assets/css/design-system.css";
=======
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe

export default {
    name: "app",
    components: {
        TopRocker, MenuRocker, BotRocker
<<<<<<< HEAD
    },
    data() {
        return {
            isSidebarOpen: true,
            contentStyle: {
                marginLeft: '260px'
            }
        }
    },
    methods: {
        onSidebarChange(isOpen) {
            this.isSidebarOpen = isOpen;
            this.contentStyle.marginLeft = isOpen ? '260px' : '68px';
        },
        toggleSidebar() {
            this.$refs.sidebarMenu?.toggleSidebar();
        }
=======
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
    }
}
</script>
<style>
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap");
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css");
<<<<<<< HEAD
</style>
=======
</style>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
