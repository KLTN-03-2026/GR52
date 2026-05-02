import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
<<<<<<< HEAD
import './assets/css/design-system.css'
import Default from './layout/wrapper/index.vue'
import Admin from './layout/wrapper/admin-wrapper.vue'
import Staff from './layout/wrapper/staff-wrapper.vue'
import Candi from './layout/wrapper/index_candi.vue'
import Blank from './layout/wrapper/index_blank.vue'
import Toaster from "@meforma/vue-toaster";
import authPlugin from './services/authPlugin'

=======
import Default from './layout/wrapper/index.vue'
import Candi from './layout/wrapper/index_candi.vue'
import Blank from './layout/wrapper/index_blank.vue'
import Toaster from "@meforma/vue-toaster";
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
const app = createApp(App)
app.use(router)
app.use(Toaster, {
    position: "top-right",
});
<<<<<<< HEAD
app.use(authPlugin);

app.component("default-layout", Default);
app.component("admin-layout", Admin);
app.component("staff-layout", Staff);
app.component("blank-layout", Blank);
app.component("candi-layout", Candi);
app.mount("#app")
=======
app.component("default-layout", Default);
app.component("blank-layout", Blank);
app.component("candi-layout", Candi);
app.mount("#app")
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
