<template>
    <div :class="['wrapper', isSidebarOpen ? 'sidebar-open' : 'sidebar-mini']">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <MenuRocker ref="sidebarMenu" @sidebar-change="onSidebarChange"></MenuRocker>
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <TopRocker @toggle-sidebar="toggleSidebar"></TopRocker>
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
import authService from "../../services/authService";

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
import "../../assets/css/design-system.css";

export default {
    name: "admin-wrapper",
    components: {
        TopRocker, MenuRocker, BotRocker
    },
    data() {
        return {
            contentStyle: {
                marginLeft: '260px'
            },
            isSidebarOpen: true,
            currentUser: null,
            userRole: null
        }
    },
    mounted() {
        this.currentUser = authService.getCurrentUser();
        this.userRole = authService.getCurrentRole();
    },
    methods: {
        onSidebarChange(isOpen) {
            this.isSidebarOpen = isOpen;
            this.contentStyle.marginLeft = isOpen ? '260px' : '68px';
        },
        toggleSidebar() {
            this.$refs.sidebarMenu?.toggleSidebar();
        }
    }
}
</script>
<style>
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap");
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css");

.wrapper.sidebar-open .page-wrapper {
    margin-left: 260px !important;
    transition: margin-left 0.25s cubic-bezier(.4, 0, .2, 1);
}

.wrapper.sidebar-mini .page-wrapper {
    margin-left: 68px !important;
    transition: margin-left 0.25s cubic-bezier(.4, 0, .2, 1);
}

@media (max-width: 991.98px) {
    .wrapper.sidebar-open .page-wrapper,
    .wrapper.sidebar-mini .page-wrapper {
        margin-left: 0 !important;
    }
}
</style>
