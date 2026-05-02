<template>
    <div class="staff-dashboard">
        <div class="container-fluid mt-4">
            <!-- Welcome Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient">
                        <div class="card-body">
                            <h2 class="card-title">Chào mừng, {{ userName }}!</h2>
                            <p class="card-text">Role: <span class="badge bg-info">{{ userRole }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="row">
                <!-- Chấm công Card -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card card-hover">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Chấm Công</h5>
                            <p class="card-text">Xem lịch sử chấm công của bạn</p>
                            <router-link to="/nhan-vien/cham-cong" class="btn btn-primary btn-sm">
                                Xem Chi Tiết
                            </router-link>
                        </div>
                    </div>
                </div>

                <!-- Nghỉ Phép Card -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card card-hover">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Nghỉ Phép</h5>
                            <p class="card-text">Quản lý đơn xin nghỉ phép</p>
                            <router-link to="/nhan-vien/nghi-phep" class="btn btn-warning btn-sm">
                                Xem Chi Tiết
                            </router-link>
                        </div>
                    </div>
                </div>

                <!-- Hồ Sơ Cá Nhân Card -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card card-hover">
                        <div class="card-body text-center">
                            <i class="fas fa-user fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Hồ Sơ</h5>
                            <p class="card-text">Xem và cập nhật thông tin cá nhân</p>
                            <router-link to="/nhan-vien/ho-so" class="btn btn-info btn-sm">
                                Chỉnh Sửa
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin Nhân viên -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Thông tin Nhân viên</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <p><strong>Họ và tên:</strong> {{ userInfo?.ho_va_ten }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p><strong>Email:</strong> {{ userInfo?.email }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p><strong>Số điện thoại:</strong> {{ userInfo?.so_dien_thoai || 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p><strong>Phòng ban:</strong>
                                       
                                    {{ userInfo?.phong_ban?.ten_phong_ban || userInfo?.phongBan?.ten_phong_ban ||
                                        userInfo?.ten_phong_ban || userInfo?.id_phong_ban || 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p><strong>Chức vụ:</strong>
                                        
                                    {{ userInfo?.chuc_vu?.ten_chuc_vu || userInfo?.chucVu?.ten_chuc_vu ||
                                        userInfo?.ten_chuc_vu || userInfo?.id_chuc_vu || 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <p><strong>Ngày sinh:</strong> {{ formatDate(userInfo?.ngay_sinh) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê nhanh -->
            <div class="row mt-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="stat-card text-center">
                        <h3 class="stat-number">{{ attendanceCount }}</h3>
                        <p class="stat-label">Chấm công tháng này</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="stat-card text-center">
                        <h3 class="stat-number">{{ leaveCount }}</h3>
                        <p class="stat-label">Đơn xin nghỉ</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="stat-card text-center">
                        <h3 class="stat-number">{{ bonusCount }}</h3>
                        <p class="stat-label">Thưởng/Phạt</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="stat-card text-center">
                        <h3 class="stat-number">{{ salaryInfo }}</h3>
                        <p class="stat-label">Lương cơ bản</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Staff Tracking Tabs -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item"><a href="#" class="nav-link" :class="{ active: activeTab === 'luong' }"
                                    @click.prevent="changeTab('luong')">Bảng Lương</a></li>
                            <li class="nav-item"><a href="#" class="nav-link" :class="{ active: activeTab === 'kpi' }"
                                    @click.prevent="changeTab('kpi')">KPI</a></li>
                            <li class="nav-item"><a href="#" class="nav-link"
                                    :class="{ active: activeTab === 'thuong' }"
                                    @click.prevent="changeTab('thuong')">Thưởng/Phạt</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div v-if="activeTab === 'luong'">
                            <div v-if="loading">Đang tải...</div>
                            <div v-else>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Năm</th>
                                            <th>Thưởng</th>
                                            <th>Phạt</th>
                                            <th>Lương thực nhận</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in luongData" :key="item.id">
                                            <td>{{ item.thang }}</td>
                                            <td>{{ item.nam }}</td>
                                            <td>{{ formatCurrency(item.tong_thuong) }}</td>
                                            <td>{{ formatCurrency(item.tong_phat) }}</td>
                                            <td>{{ formatCurrency(item.luong_thuc_nhan) }}</td>
                                            <td>{{ item.trang_thai }}</td>
                                        </tr>
                                        <tr v-if="luongData.length === 0">
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div v-if="activeTab === 'kpi'">
                            <div v-if="loading">Đang tải...</div>
                            <div v-else>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tiêu chí</th>
                                            <th>Mục tiêu</th>
                                            <th>Kết quả</th>
                                            <th>% HT</th>
                                            <th>Điểm</th>
                                            <th>Xếp loại</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="k in kpiData" :key="k.id">
                                            <td>{{ k.tieu_chi?.ten_tieu_chi || '-' }}</td>
                                            <td>{{ k.muc_tieu || '-' }}</td>
                                            <td>{{ k.ket_qua_thuc_te || '-' }}</td>
                                            <td>{{ k.phan_tram_hoan_thanh || '-' }}</td>
                                            <td>{{ k.diem_kpi || '-' }}</td>
                                            <td>{{ k.xep_loai || '-' }}</td>
                                        </tr>
                                        <tr v-if="kpiData.length === 0">
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div v-if="activeTab === 'thuong'">
                            <div v-if="loading">Đang tải...</div>
                            <div v-else>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Năm</th>
                                            <th>Loại</th>
                                            <th>Số tiền</th>
                                            <th>Lý do</th>
                                            <th>Người tạo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="t in thuongData" :key="t.id">
                                            <td>{{ t.thang }}</td>
                                            <td>{{ t.nam }}</td>
                                            <td>{{ t.loai }}</td>
                                            <td>{{ formatCurrency(t.so_tien) }}</td>
                                            <td>{{ t.ly_do || '-' }}</td>
                                            <td>{{ t.nguoi_tao?.ho_va_ten || '-' }}</td>
                                        </tr>
                                        <tr v-if="thuongData.length === 0">
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-2">
                                    <strong>Tổng thưởng:</strong> {{
                                        formatCurrency(thuongData.filter(x => x.loai ===
                                            'thuong').reduce((s, x) => s + (x.so_tien || 0), 0))
                                    }} &nbsp;
                                    <strong>Tổng phạt:</strong> {{
                                        formatCurrency(thuongData.filter(x => x.loai ===
                                            'phat').reduce((s, x) => s + (x.so_tien || 0), 0))
                                    }} &nbsp;
                                    <strong>Net:</strong> {{
                                        formatCurrency(thuongData.filter(x => x.loai === 'thuong').reduce((s, x) => s +
                                            (x.so_tien || 0), 0)
                                            - thuongData.filter(x => x.loai === 'phat').reduce((s, x) => s + (x.so_tien || 0),
                                                0))
                                    }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import authService from '../../services/authService';

export default {
    name: "StaffDashboard",
    data() {
        return {
            attendanceCount: 0,
            leaveCount: 0,
            bonusCount: 0,
            userInfo: null,

            // Staff tracking data
            activeTab: 'luong', // 'luong' | 'kpi' | 'thuong'
            luongData: [],
            kpiData: [],
            thuongData: [],
            loading: false,
            year: new Date().getFullYear(),
            month: new Date().getMonth() + 1,
        }
    },
    mounted() {
        this.loadUserInfo();
        this.loadStatistics();
        this.fetchAll();
    },
    computed: {
        userName() {
            return this.$auth.user()?.ho_va_ten || 'Nhân viên';
        },
        userRole() {
            return this.$auth.role() || 'staff';
        },
        salaryInfo() {
            const salary = this.$auth.user()?.luong_co_ban;
            return salary ? this.formatCurrency(salary) : 'N/A';
        }
    },
    methods: {
        loadUserInfo() {
            this.userInfo = this.$auth.user();

            // If userInfo doesn't include relations (either snake_case or camelCase), try fetching from backend
            const hasPhong = this.userInfo && (this.userInfo.phongBan || this.userInfo.phong_ban || this.userInfo.ten_phong_ban);
            const hasChuc = this.userInfo && (this.userInfo.chucVu || this.userInfo.chuc_vu || this.userInfo.ten_chuc_vu);
            if (this.userInfo && (!hasPhong || !hasChuc)) {
                const token = authService.getToken();
                if (token) {
                    axios.get('/api/admin/user-info', { headers: { Authorization: 'Bearer ' + token } })
                        .then(res => {
                            if (res?.data?.status && res.data.data) {
                                // backend returns full user object
                                const full = res.data.data;
                                this.userInfo = full;
                                try { localStorage.setItem('user_data', JSON.stringify(full)); } catch (e) { }
                            }
                        })
                        .catch(err => console.error('fetch user-info', err));
                }
            }
        },
        loadStatistics() {
            // Minimal stats using existing user data; consider replacing with API call
            this.attendanceCount = 22;
            this.leaveCount = 1;
            this.bonusCount = 2;
        },

        // Fetch all three resources
        async fetchAll() {
            await Promise.all([
                this.fetchLuong(this.month, this.year),
                this.fetchKpi(this.month, this.year),
                this.fetchThuongVaPhat(this.month, this.year),
            ]);
        },

        // Helper to build axios headers with token
        authHeaders() {
            const token = authService.getToken();
            return token ? { Authorization: `Bearer ${token}` } : {};
        },

        async fetchLuong(thang = null, nam = null) {
            this.loading = true;
            try {
                const params = {};
                if (thang) params.thang = thang;
                if (nam) params.nam = nam;
                const res = await axios.get('/api/nhan-vien/luong/xem', { params, headers: this.authHeaders() });
                if (res?.data?.status) {
                    this.luongData = res.data.data || [];
                }
            } catch (e) {
                console.error('fetchLuong error', e);
            } finally { this.loading = false; }
        },

        async fetchKpi(thang = null, nam = null) {
            this.loading = true;
            try {
                const params = {};
                if (thang) params.thang = thang;
                if (nam) params.nam = nam;
                const res = await axios.get('/api/nhan-vien/kpi/xem', { params, headers: this.authHeaders() });
                if (res?.data?.status) {
                    this.kpiData = res.data.data || [];
                }
            } catch (e) {
                console.error('fetchKpi error', e);
            } finally { this.loading = false; }
        },

        async fetchThuongVaPhat(thang = null, nam = null) {
            this.loading = true;
            try {
                const params = {};
                if (thang) params.thang = thang;
                if (nam) params.nam = nam;
                const res = await axios.get('/api/nhan-vien/thuong-va-phat/xem', { params, headers: this.authHeaders() });
                if (res?.data?.status) {
                    this.thuongData = res.data.data || [];
                }
            } catch (e) {
                console.error('fetchThuongVaPhat error', e);
            } finally { this.loading = false; }
        },

        // UI helpers
        changeTab(tab) {
            this.activeTab = tab;
        },

        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('vi-VN');
        },
        formatCurrency(value) {
            if (value === null || value === undefined) return '0 ₫';
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
    }
}
</script>

<style scoped>
.staff-dashboard {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: calc(100vh - 100px);
    padding: 20px 0;
}

.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.card-hover {
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #667eea;
    margin: 10px 0;
}

.stat-label {
    color: #666;
    font-size: 14px;
    margin: 0;
}

.btn-sm {
    font-weight: 600;
}

.badge {
    font-size: 12px;
    padding: 5px 10px;
}
</style>
