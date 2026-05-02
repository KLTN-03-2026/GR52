import axios from "axios";
import { createToaster } from "@meforma/vue-toaster";
import authService from "../services/authService";

const toaster = createToaster({ position: "top-right" });

/**
 * Candidate Authentication Check Guard
 * Kiểm tra xem candidate có token hợp lệ hay không
 * Lưu thông tin candidate vào localStorage
 */
export default function candidateCheckLogin(to, from, next) {
  const token = localStorage.getItem("token_ung_vien");

  // Nếu không có token, redirect sang login
  if (!token) {
    toaster.error("Vui lòng đăng nhập để tiếp tục");
    next("/ung-vien/dang-nhap");
    return;
  }

  // Kiểm tra token với backend
  axios
    .get("http://127.0.0.1:8000/api/ung-vien/check-login", {
      headers: {
        Authorization: "Bearer " + token,
      },
    })
    .then((res) => {
      if (res.data.status) {
        // Lưu thông tin candidate
        const candidateData = res.data.data || res.data;
        localStorage.setItem("candidate_role", "candidate");
        localStorage.setItem("candidate_data", JSON.stringify(candidateData));

        // Setup axios default header với token
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        next();
      } else {
        // Token không hợp lệ
        localStorage.removeItem("token_ung_vien");
        localStorage.removeItem("candidate_data");
        localStorage.removeItem("candidate_role");
        next("/ung-vien/dang-nhap");
        toaster.error(res.data.message || "Token không hợp lệ");
      }
    })
    .catch((error) => {
      // Lỗi khi gọi API
      console.error("Candidate CheckLogin error:", error);
      localStorage.removeItem("token_ung_vien");
      localStorage.removeItem("candidate_data");
      localStorage.removeItem("candidate_role");
      next("/ung-vien/dang-nhap");
      toaster.error("Lỗi kiểm tra đăng nhập");
    });
}
