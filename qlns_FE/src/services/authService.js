// Service quản lý đăng nhập nội bộ
import axios from 'axios';
import { getDashboardByRole, getLayoutByRole } from './rbac';

class AuthService {
    // Lấy thông tin user hiện tại từ localStorage
    getCurrentUser() {
        // Kiểm tra candidate đầu tiên
        const candidateData = localStorage.getItem('candidate_data');
        if (candidateData) {
            try {
                return JSON.parse(candidateData);
            } catch {
                return null;
            }
        }
        
        // Sau đó kiểm tra employee
        const userData = localStorage.getItem('user_data');
        try {
            return userData ? JSON.parse(userData) : null;
        } catch {
            return null;
        }
    }

    // Lấy role hiện tại
    getCurrentRole() {
        const candidateRole = localStorage.getItem('candidate_role');
        if (candidateRole) {
            return candidateRole;
        }
        return localStorage.getItem('user_role');
    }

    // Lấy token (hỗ trợ cả employee và candidate)
    getToken() {
        const candidateToken = localStorage.getItem('token_ung_vien');
        if (candidateToken) {
            return candidateToken;
        }
        return localStorage.getItem('tk_nhan_vien');
    }

    // Kiểm tra xem user có được authenticated không
    isAuthenticated() {
        return !!this.getToken();
    }

    // Kiểm tra xem là candidate hay không
    isCandidate() {
        return !!localStorage.getItem('token_ung_vien');
    }

    // Kiểm tra xem là employee hay không
    isEmployee() {
        return !!localStorage.getItem('tk_nhan_vien');
    }

    hasRole(roles) {
        const currentRole = this.getCurrentRole();
        const allowedRoles = Array.isArray(roles) ? roles : [roles];
        return allowedRoles.includes(currentRole);
    }

    // Đăng xuất
    logout() {
        localStorage.removeItem('tk_nhan_vien');
        localStorage.removeItem('user_role');
        localStorage.removeItem('user_data');
        localStorage.removeItem('token_ung_vien');
        localStorage.removeItem('candidate_data');
        localStorage.removeItem('candidate_role');
        delete axios.defaults.headers.common['Authorization'];
    }

    // Đăng xuất candidate
    logoutCandidate() {
        localStorage.removeItem('token_ung_vien');
        localStorage.removeItem('candidate_data');
        localStorage.removeItem('candidate_role');
        delete axios.defaults.headers.common['Authorization'];
    }

    // Lấy dashboard URL dựa trên role
    getDashboardUrl() {
        return getDashboardByRole(this.getCurrentRole());
    }

    // Lấy thông tin layout dựa trên role
    getLayoutByRole() {
        return getLayoutByRole(this.getCurrentRole());
    }
}

export default new AuthService();
