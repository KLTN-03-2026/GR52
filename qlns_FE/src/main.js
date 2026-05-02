import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/css/design-system.css'
import Default from './layout/wrapper/index.vue'
import Admin from './layout/wrapper/admin-wrapper.vue'
import Staff from './layout/wrapper/staff-wrapper.vue'
import Candi from './layout/wrapper/index_candi.vue'
import Blank from './layout/wrapper/index_blank.vue'
import Toaster from "@meforma/vue-toaster";
import authPlugin from './services/authPlugin'

const app = createApp(App)
app.use(router)
app.use(Toaster, {
    position: "top-right",
});
app.use(authPlugin);

app.component("default-layout", Default);
app.component("admin-layout", Admin);
app.component("staff-layout", Staff);
app.component("blank-layout", Blank);
app.component("candi-layout", Candi);
app.mount("#app")
