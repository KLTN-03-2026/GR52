<template>
    <div class="wrapper">
        <!--start header -->
        <header>
            <TopRocker @toggle-sidebar="toggleSidebar"></TopRocker>
        </header>
        <!--end header -->

        <!-- Sidebar Staff -->
        <StaffMenuRocker ref="sidebarMenu" @sidebar-change="onSidebarChange"></StaffMenuRocker>

        <!--start page wrapper -->
        <div class="page-wrapper" :style="{ marginLeft: sidebarOpen ? '260px' : '68px' }">
            <div class="page-content">
                <router-view></router-view>
            </div>
        </div>
        <!--end page wrapper -->
        <BotRocker></BotRocker>
    </div>
</template>

<script>
import TopRocker from "../components/TopRocker.vue";
import BotRocker from "../components/BotRocker.vue";
import StaffMenuRocker from "../components/StaffMenuRocker.vue";

// Import các file JS và CSS cần thiết cho layout
import "../../assets/js/bootstrap.bundle.min.js";
import "../../assets/js/jquery.min.js";
import "../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js";
import "../../assets/js/index.js";
import "../../assets/js/app.js";
import "../../assets/js/pace.min.js";

import "../../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css";
import "../../assets/css/pace.min.css";
import "../../assets/css/bootstrap.min.css";
import "../../assets/css/bootstrap-extended.css";
import "../../assets/css/app.css";
import "../../assets/css/icons.css";
import "../../assets/css/design-system.css";

export default {
    name: "staff-wrapper",
    components: {
        TopRocker, BotRocker, StaffMenuRocker
    },
    data() {
        return {
            sidebarOpen: true,
        }
    },
    methods: {
        onSidebarChange(isOpen) {
            this.sidebarOpen = isOpen;
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

.page-wrapper {
    transition: margin-left 0.25s cubic-bezier(.4, 0, .2, 1);
}
</style>
