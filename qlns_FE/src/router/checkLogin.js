import axios from "axios";
import { createToaster } from "@meforma/vue-toaster";
<<<<<<< HEAD
import authService from "../services/authService";

const toaster = createToaster({ position: "top-right" });

/**
 * Authentication Check Guard
 * Kiểm tra xem user có token hợp lệ hay không
 * Lưu thông tin user và role vào localStorage
 */
export default function checkLogin(to, from, next) {
  const token = localStorage.getItem("tk_nhan_vien");

  // Nếu không có token, redirect sang login
  if (!token) {
    toaster.error("Vui lòng đăng nhập");
    next("/admin/dang-nhap");
    return;
  }

  // Kiểm tra token với backend
  axios
    .get("http://127.0.0.1:8000/api/admin/check-login", {
      headers: {
        Authorization: "Bearer " + token,
=======
const toaster = createToaster({ position: "top-right" });
export default function (to, from, next) {
  axios
    .get("http://127.0.0.1:8000/api/admin/check-login", {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("tk_nhan_vien"),
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
      },
    })
    .then((res) => {
      if (res.data.status) {
<<<<<<< HEAD
        // Lưu thông tin user và role
        localStorage.setItem("user_role", res.data.role);
        localStorage.setItem("user_data", JSON.stringify(res.data.user));

        // Setup axios default header với token
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        next();
      } else {
        // Token không hợp lệ
        localStorage.removeItem("tk_nhan_vien");
        localStorage.removeItem("user_role");
        localStorage.removeItem("user_data");
        next("/admin/dang-nhap");
        toaster.error(res.data.message || "Token không hợp lệ");
      }
    })
    .catch((error) => {
      // Lỗi khi gọi API
      console.error("CheckLogin error:", error);
      localStorage.removeItem("tk_nhan_vien");
      localStorage.removeItem("user_role");
      localStorage.removeItem("user_data");
      next("/admin/dang-nhap");
      toaster.error("Lỗi kiểm tra đăng nhập");
=======
        next();
      } else {
        next("/admin/dang-nhap");
        toaster.error(res.data.message);
      }
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
    });
}
