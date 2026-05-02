<template>
    <div class="candidate-recruitment"
        style="padding: 1.5rem; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient shadow-lg border-0">
                    <div class="card-body text-white">
                        <h2 class="mb-2">
                            <i class="fa-solid fa-briefcase me-2"></i>Danh Sách Vị Trí Tuyển Dụng
                        </h2>
                        <p class="mb-0 lead">Khám phá các cơ hội việc làm tuyệt vời chờ đón bạn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Search Row -->
                        <div class="row g-2 align-items-end mb-3">
                            <!-- Search -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold mb-2">Tìm kiếm vị trí</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text border-0"><i
                                            class="fa-solid fa-search text-primary"></i></span>
                                    <input v-model="searchText" type="text" class="form-control border-0 shadow-sm"
                                        placeholder="Nhập tên vị trí (VD: Lập trình, Kế toán...)"
                                        @keyup.enter="loadData">
                                </div>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-12 col-md-6">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-lg" @click="loadData" :disabled="loading">
                                        <i class="fa-solid fa-search me-1"></i>
                                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                                        Tìm kiếm
                                    </button>
                                    <button class="btn btn-outline-secondary btn-lg" @click="resetSearch">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Row -->
                        <div class="row g-2">
                            <!-- Position Filter -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold mb-2">Chức Vụ</label>
                                <select v-model="selectedPosition" class="form-select form-select-lg shadow-sm"
                                    @change="loadData">
                                    <option value="">-- Tất Cả Chức Vụ --</option>
                                    <option v-for="pos in uniquePositions" :key="pos.id" :value="pos.id">
                                        {{ pos.ten_chuc_vu }}
                                    </option>
                                </select>
                            </div>

                            <!-- Department Filter -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold mb-2">Phòng Ban</label>
                                <select v-model="selectedDepartment" class="form-select form-select-lg shadow-sm"
                                    @change="loadData">
                                    <option value="">-- Tất Cả Phòng Ban --</option>
                                    <option v-for="dept in uniqueDepartments" :key="dept.id" :value="dept.id">
                                        {{ dept.ten_phong_ban }}
                                    </option>
                                </select>
                            </div>

                            <!-- Province Filter -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold mb-2">Tỉnh Thành</label>
                                <select v-model="selectedProvince" class="form-select form-select-lg shadow-sm"
                                    @change="loadData">
                                    <option value="">-- Tất Cả Tỉnh Thành --</option>
                                    <option value="HN">Hà Nội</option>
                                    <option value="SG">TP. Hồ Chí Minh</option>
                                    <option value="DN">Đà Nẵng</option>
                                    <option value="HUE">Huế</option>
                                    <option value="QN">Quảng Ninh</option>
                                    <option value="HP">Hải Phòng</option>
                                    <option value="CT">Cần Thơ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4" v-if="isCandidateLoggedIn">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="mb-1">
                                <i class="fa-solid fa-wand-magic-sparkles me-2 text-primary"></i>Gợi Ý Theo CV
                            </h5>
                            <small class="text-muted">Dựa trên kết quả AI đã phân tích từ CV bạn đã gửi</small>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" :disabled="suggestionLoading" @click="refreshSuggestions">
                            <span v-if="suggestionLoading" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-rotate me-1"></i>Làm mới
                        </button>
                    </div>
                    <div class="card-body">
                        <div v-if="suggestionLoading" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm me-2"></div>Đang tải gợi ý...
                        </div>
                        <div v-else-if="suggestions.length === 0" class="text-muted py-2">
                            Chưa có gợi ý. Hệ thống sẽ tự tạo sau khi HR phân tích CV của bạn.
                        </div>
                        <div v-else class="row g-3">
                            <div class="col-lg-4 col-md-6" v-for="item in suggestions" :key="item.id">
                                <div class="suggestion-card">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div>
                                            <div class="job-title">{{ item.vi_tri_tuyen_dung?.tieu_de || 'Vị trí tuyển dụng' }}</div>
                                            <div class="text-muted small mt-1">
                                                {{ getPhongBan(item.vi_tri_tuyen_dung || {}) }} · {{ getChucVu(item.vi_tri_tuyen_dung || {}) }}
                                            </div>
                                        </div>
                                        <span class="match-score">{{ item.diem_phu_hop }}%</span>
                                    </div>
                                    <p class="small text-muted mt-3 mb-3">{{ item.ly_do }}</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-primary btn-sm flex-fill"
                                            @click="applyJob(item.vi_tri_tuyen_dung)">
                                            <i class="fa-solid fa-paper-plane me-1"></i>Ứng tuyển
                                        </button>
                                        <button class="btn btn-primary btn-sm"
                                            @click="$router.push(`/ung-vien/cong-viec/${item.vi_tri_tuyen_dung_id}`)">
                                            Chi tiết
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jobs Grid -->
        <div class="row g-3">
            <template v-if="loading">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Đang tải danh sách vị trí...</p>
                </div>
            </template>

            <template v-else-if="filteredJobs.length > 0">
                <div class="col-lg-4 col-md-6" v-for="job in filteredJobs" :key="job.id">
                    <div class="job-card">
                        <div class="job-card-top d-flex align-items-start gap-3">
                            <div class="company-logo">{{ getPhongBan(job).slice(0, 2).toUpperCase() }}</div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="job-title">{{ job.tieu_de }}</div>
                                        <div class="text-muted small mt-1">{{ getPhongBan(job) }} · {{ getChucVu(job) }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="salary-chip">{{ formatSalary(job.luong) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="job-body">
                            <div class="job-info-row">
                                <i class="fa-solid fa-calendar text-muted"></i>
                                <span>{{ formatDate(job.ngay_bat_dau) }} → {{ formatDate(job.ngay_ket_thuc) }}</span>
                            </div>
                            <div class="job-info-row">
                                <i class="fa-solid fa-users text-muted"></i>
                                <span>Số lượng: {{ job.so_luong }}</span>
                            </div>
                        </div>

                        <div class="job-footer">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm flex-fill" @click="applyJob(job)">
                                    <i class="fa-solid fa-paper-plane me-1"></i>Ứng Tuyển
                                </button>
                                <button class="btn btn-primary btn-sm"
                                    @click="$router.push(`/ung-vien/cong-viec/${job.id}`)">
                                    Chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="col-12">
                    <div class="card border-0 text-center shadow-sm">
                        <div class="card-body py-5">
                            <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không tìm thấy vị trí phù hợp</h5>
                            <p class="text-muted mb-3">Hãy thử tìm kiếm với từ khóa khác hoặc quay lại sau</p>
                            <button class="btn btn-outline-primary" @click="resetSearch">
                                <i class="fa-solid fa-rotate-left me-1"></i>Đặt lại tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { createToaster } from '@meforma/vue-toaster';

const toaster = createToaster({ position: 'top-right' });
const API = 'http://127.0.0.1:8000/api';

export default {
    name: 'CandidateDanhSachTuyenDung',
    data() {
        return {
            jobs: [],
            phongBanMap: {},
            chucVuMap: {},
            searchText: '',
            selectedPosition: '',
            selectedDepartment: '',
            selectedProvince: '',
            loading: false,
            suggestions: [],
            suggestionLoading: false,
        };
    },
    computed: {
        isCandidateLoggedIn() {
            return !!localStorage.getItem('tk_ung_vien');
        },
        filteredJobs() {
            return this.jobs.filter(job => {
                const searchMatch = !this.searchText.trim() ||
                    job.tieu_de.toLowerCase().includes(this.searchText.toLowerCase()) ||
                    this.getPhongBan(job).toLowerCase().includes(this.searchText.toLowerCase()) ||
                    this.getChucVu(job).toLowerCase().includes(this.searchText.toLowerCase());

                const positionMatch = !this.selectedPosition || job.id_chuc_vu == this.selectedPosition;
                const deptMatch = !this.selectedDepartment || job.id_phong_ban == this.selectedDepartment;

                return searchMatch && positionMatch && deptMatch;
            });
        },
        uniquePositions() {
            const positions = new Map();
            this.jobs.forEach(job => {
                if (job.chuc_vu && job.id_chuc_vu) {
                    positions.set(job.id_chuc_vu, job.chuc_vu);
                }
            });
            return Array.from(positions.values());
        },
        uniqueDepartments() {
            const departments = new Map();
            this.jobs.forEach(job => {
                if (job.phong_ban && job.id_phong_ban) {
                    departments.set(job.id_phong_ban, job.phong_ban);
                }
            });
            return Array.from(departments.values());
        },
        totalJobs() {
            return this.jobs.length;
        },
        totalPositions() {
            return this.jobs.reduce((sum, job) => sum + (job.so_luong || 0), 0);
        },
        departments() {
            const depts = new Set();
            this.jobs.forEach(job => {
                const dept = this.getPhongBan(job);
                if (dept) depts.add(dept);
            });
            return depts;
        },
    },
    mounted() {
        this.loadData();
        this.loadSuggestions();
    },
    methods: {
        authHeaders() {
            return {
                Authorization: `Bearer ${localStorage.getItem('tk_ung_vien')}`,
            };
        },
        async loadData() {
            this.loading = true;
            try {
                const params = {};
                if (this.searchText.trim()) {
                    params.search = this.searchText.trim();
                }
                if (this.selectedPosition) {
                    params.id_chuc_vu = this.selectedPosition;
                }
                if (this.selectedDepartment) {
                    params.id_phong_ban = this.selectedDepartment;
                }

                const response = await axios.get(`${API}/vi-tri/open`, { params });
                this.jobs = Array.isArray(response.data) ? response.data : (response.data.data || []);

                // Extract phong ban and chuc vu info
                this.jobs.forEach(job => {
                    if (job.phong_ban) {
                        this.phongBanMap[job.id_phong_ban] = job.phong_ban.ten_phong_ban || job.phong_ban;
                    }
                    if (job.chuc_vu) {
                        this.chucVuMap[job.id_chuc_vu] = job.chuc_vu.ten_chuc_vu || job.chuc_vu;
                    }
                });
            } catch (error) {
                console.error(error);
                toaster.error('Lỗi khi tải danh sách vị trí');
            } finally {
                this.loading = false;
            }
        },
        async loadSuggestions() {
            if (!this.isCandidateLoggedIn) return;

            this.suggestionLoading = true;
            try {
                const response = await axios.get(`${API}/ung-vien/goi-y-viec-lam`, {
                    headers: this.authHeaders(),
                });
                this.suggestions = response.data.data || [];
            } catch (error) {
                this.suggestions = [];
            } finally {
                this.suggestionLoading = false;
            }
        },
        async refreshSuggestions() {
            if (!this.isCandidateLoggedIn) return;

            this.suggestionLoading = true;
            try {
                const response = await axios.post(`${API}/ung-vien/goi-y-viec-lam/refresh`, {}, {
                    headers: this.authHeaders(),
                });
                this.suggestions = response.data.data || [];
                toaster.success('Đã cập nhật gợi ý công việc.');
            } catch (error) {
                this.suggestions = [];
                toaster.error(error.response?.data?.message || 'Chưa có kết quả AI để gợi ý.');
            } finally {
                this.suggestionLoading = false;
            }
        },
        formatSalary(value) {
            if (!value) return '';
            // Support numbers or strings like '2.500.000' or numeric
            const num = typeof value === 'number' ? value : parseFloat(String(value).replace(/[^0-9.-]+/g, '')) || 0;
            return new Intl.NumberFormat('vi-VN').format(num) + ' đ';
        },
        resetSearch() {
            this.searchText = '';
            this.selectedPosition = '';
            this.selectedDepartment = '';
            this.selectedProvince = '';
            this.loadData();
        },
        getPhongBan(job) {
            if (job.phong_ban?.ten_phong_ban) return job.phong_ban.ten_phong_ban;
            if (this.phongBanMap[job.id_phong_ban]) return this.phongBanMap[job.id_phong_ban];
            return job.id_phong_ban ? `Phòng Ban ${job.id_phong_ban}` : 'Không xác định';
        },
        getChucVu(job) {
            if (job.chuc_vu?.ten_chuc_vu) return job.chuc_vu.ten_chuc_vu;
            if (this.chucVuMap[job.id_chuc_vu]) return this.chucVuMap[job.id_chuc_vu];
            return job.id_chuc_vu ? `Chức Vụ ${job.id_chuc_vu}` : 'Không xác định';
        },
        formatDate(date) {
            if (!date) return '-';
            const d = new Date(date);
            return d.toLocaleDateString('vi-VN');
        },
        applyJob(job) {
            this.$router.push(`/ung-vien/cong-viec/${job.id}`);
        },
    },
};
</script>

<style scoped>
.candidate-recruitment {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-box {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.job-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.suggestion-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    height: 100%;
    padding: 1rem;
}

.match-score {
    align-self: flex-start;
    background: #d1e7dd;
    border-radius: 999px;
    color: #0f5132;
    font-weight: 800;
    min-width: 56px;
    padding: 0.3rem 0.6rem;
    text-align: center;
}

.job-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.job-header {
    padding: 1.25rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
}

.job-title {
    font-size: 1.1rem;
    font-weight: 700;
    flex: 1;
}

.job-title {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 1.05rem;
}

.salary-chip {
    display: inline-block;
    background: linear-gradient(90deg, #eef2ff, #e6f7ff);
    color: #1f4f8b;
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.95rem;
    box-shadow: 0 2px 6px rgba(33, 82, 154, 0.06);
}

.job-card-top {
    background: #ffffff;
    border-bottom: 1px solid #eef2f6;
}

.job-card {
    transition: transform 0.35s cubic-bezier(.2, .8, .2, 1), box-shadow 0.35s;
}

.job-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(32, 45, 92, 0.08);
}

.job-card-top {
    padding: 1rem 1.25rem;
}

.company-logo {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    background: linear-gradient(135deg, #eef2ff, #dbeafe);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    color: #344e86;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(51, 64, 85, 0.06);
}

.job-quota {
    flex-shrink: 0;
}

.job-body {
    padding: 1.25rem;
    flex: 1;
}

.job-info-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
    color: #495057;
}

.job-info-row:last-child {
    margin-bottom: 0;
}

.job-info-row i {
    width: 18px;
    text-align: center;
    flex-shrink: 0;
}

.job-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a4299 100%);
}

@media (max-width: 768px) {
    .job-card {
        margin-bottom: 1rem;
    }

    .stat-box {
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 1.5rem;
    }
}
</style>
