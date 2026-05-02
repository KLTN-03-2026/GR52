import { createRouter, createWebHistory } from "vue-router"; // cài vue-router: npm install vue-router@next --save
import checkLogin from "./checkLogin";

const routes = [
  // Public career portal routes (no login)
  {
    path: "/career",
    component: () => import("../components/Public/CareerPortal/index.vue"),
  },
  {
    path: "/career/:id",
    component: () => import("../components/Public/CareerPortal/JobDetail.vue"),
  },
  {
    path: "/",
    component: () => import("../layout/wrapper/index.vue"),
    beforeEnter: checkLogin,
  },
  {
    path: "/admin/dang-nhap",
    component: () => import("../components/Admin/DangNhap/index.vue"),
    meta: { layout: "blank" },
  },
  {
    path: "/admin/nhan-vien",
    component: () => import("../components/Admin/NhanVien/index.vue"),
    beforeEnter: checkLogin,
  },
  {
    path: "/admin/chuc-vu",
     component: () => import("../components/Admin/ChucVu/index.vue"),
    beforeEnter: checkLogin,
  },
  {
    path: "/admin/phong-ban",
    component: () => import("../components/Admin/PhongBan/index.vue"),
    beforeEnter: checkLogin,
  },
  {
    path: "/admin/tuyen-dung",
    component: () => import("../components/Admin/TuyenDung/index.vue"),
    beforeEnter: checkLogin,
  },
  {
    path: "/ung-vien/dang-ky",
    component: () => import("../components/Candidate/DangKi/index.vue"),
      meta: { layout: "blank" },
  },
  {
    path: "/ung-vien/dang-nhap",
    component: () => import("../components/Candidate/DangNhap/index.vue"),
      meta: { layout: "blank" },
  },
  {
    path: "/ung-vien/trang-chu",
    component: () => import("../components/Candidate/DanhSachTuyenDung/index.vue"),
    meta: { layout: "candi" },
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});

export default router