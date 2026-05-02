<template>
    <div class="wrapper">
        <!--start header -->
        <header>
            <TopRocker></TopRocker>
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
        TopRocker, BotRocker
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
