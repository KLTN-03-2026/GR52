import { createRouter, createWebHistory } from "vue-router";
import checkLogin from "./checkLogin";
import roleGuard from "./roleGuard";
import candidateCheckLogin from "./candidateCheckLogin";
import { ROLES } from "../services/rbac";

const routes = [
  // Public career portal routes (no login required)
  {
    path: "/career",
    component: () => import("../components/Public/CareerPortal/index.vue"),
  },
  {
    path: "/career/:id",
    component: () => import("../components/Public/CareerPortal/JobDetail.vue"),
  },

  // Login routes (no auth required)
  {
    path: "/admin/dang-nhap",
    component: () => import("../components/Admin/DangNhap/index.vue"),
    meta: { layout: "blank" },
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

  // Protected routes - chỉ yêu cầu đăng nhập
  {
    path: "/",
    redirect: "/admin/nhan-vien",
  },
  {
    path: "/admin/nhan-vien",
    component: () => import("../components/Admin/NhanVien/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/chuc-vu",
    component: () => import("../components/Admin/ChucVu/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/phong-ban",
    component: () => import("../components/Admin/PhongBan/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/hop-dong",
    component: () => import("../components/Admin/HopDong/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/tuyen-dung",
    component: () => import("../components/Admin/TuyenDung/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN, ROLES.HR],
    },
  },
  {
    path: "/admin/nghi-phep",
    component: () => import("../components/Admin/NghiPhep/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/cham-cong",
    component: () => import("../components/Admin/ChamCong/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/tuyen-dung/:id/ung-tuyen",
    component: () => import("../components/Admin/HoSoUngTuyen/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/trang-chu",
    component: () => import("../components/Admin/TrangChu/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/kpi",
    component: () => import("../components/Admin/KPI/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/kpi-nhan-vien",
    component: () => import("../components/Admin/KPINhanVien/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/thuong-phat",
    component: () => import("../components/Admin/ThuongPhat/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/bang-luong",
    component: () => import("../components/Admin/BangLuong/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN],
    },
  },
  {
    path: "/admin/quan-ly-ho-so-ung-tuyen",
    component: () => import("../components/Admin/QuanLyHoSoUngTuyen/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "admin",
      requiredRoles: [ROLES.ADMIN, ROLES.HR],
    },
  },

  {
    path: "/dashboard-staff",
    component: () => import("../components/Staff/DashboardStaff.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "staff",
      requiredRoles: [ROLES.STAFF],
    },
  },
  {
    path: "/nhan-vien/cham-cong",
    component: () => import("../components/Staff/ChamCong/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "staff",
      requiredRoles: [ROLES.STAFF],
    },
  },
  {
    path: "/nhan-vien/nghi-phep",
    component: () => import("../components/Staff/NghiPhep/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "staff",
      requiredRoles: [ROLES.STAFF],
    },
  },
  {
    path: "/nhan-vien/ho-so",
    component: () => import("../components/Staff/HoSo/index.vue"),
    beforeEnter: [checkLogin, roleGuard],
    meta: {
      layout: "staff",
      requiredRoles: [ROLES.STAFF],
    },
  },

  // Candidate routes
  {
    path: "/ung-vien/trang-chu",
    component: () => import("../components/Candidate/DanhSachTuyenDung/index.vue"),
    meta: { layout: "candi" },
  },
  {
    path: "/ung-vien/cong-viec/:id",
    component: () => import("../components/Candidate/CongViec/index.vue"),
    beforeEnter: [candidateCheckLogin],
    meta: { layout: "candi" },
  },
  {
    path: "/ung-vien/ho-so-ung-tuyen",
    component: () => import("../components/Candidate/HoSoUngTuyen/index.vue"),
    beforeEnter: [candidateCheckLogin],
    meta: { layout: "candi" },
  },
  {
    path: "/vi-tri/:id",
    component: () => import("../components/Public/CareerPortal/JobDetail.vue"),
  },


  //TEST
  {
    PATH: "/test",
    component: () => import("../components/CVAnalysis.vue"),
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
});

export default router
