<template>
    <div class="career-portal" style="padding: 1.5rem; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient shadow-lg border-0">
                    <div class="card-body text-white">
                        <h2 class="mb-2">
                            <i class="fa-solid fa-briefcase me-2"></i>Danh Sách Cơ Hội Việc Làm
                        </h2>
                        <p class="mb-0 lead">Khám phá các vị trí tuyệt vời chờ đón bạn</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-2 align-items-end">
                            <!-- Search -->
                            <div class="col-12 col-md-8">
                                <label class="form-label fw-semibold mb-2">Tìm kiếm vị trí</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text border-0"><i class="fa-solid fa-search text-primary"></i></span>
                                    <input v-model="searchText" type="text" class="form-control border-0 shadow-sm"
                                        placeholder="Nhập tên vị trí (VD: Lập trình, Kế toán...)"
                                        @keyup.enter="loadData">
                                </div>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-12 col-md-4">
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="stat-box text-center">
                    <div class="stat-number text-primary">{{ totalJobs }}</div>
                    <div class="stat-label">Vị Trí Mở</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="stat-box text-center">
                    <div class="stat-number text-success">{{ totalPositions }}</div>
                    <div class="stat-label">Tổng Chỉ Tiêu</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="stat-box text-center">
                    <div class="stat-number text-info">{{ filteredJobs.length }}</div>
                    <div class="stat-label">Kết Quả Tìm</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="stat-box text-center">
                    <div class="stat-number text-warning">{{ departments.size }}</div>
                    <div class="stat-label">Phòng Ban</div>
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
                        <div class="job-header">
                            <div class="job-title">{{ job.tieu_de }}</div>
                            <div class="job-quota">
                                <span class="badge bg-primary">{{ job.so_luong }} vị trí</span>
                            </div>
                        </div>

                        <div class="job-body">
                            <div class="job-info-row">
                                <i class="fa-solid fa-building text-muted"></i>
                                <span>{{ getPhongBan(job) }}</span>
                            </div>
                            <div class="job-info-row">
                                <i class="fa-solid fa-briefcase text-muted"></i>
                                <span>{{ getChucVu(job) }}</span>
                            </div>
                            <div class="job-info-row">
                                <i class="fa-solid fa-calendar text-muted"></i>
                                <span>{{ formatDate(job.ngay_bat_dau) }} → {{ formatDate(job.ngay_ket_thuc) }}</span>
                            </div>
                        </div>

                        <div class="job-footer">
                            <button class="btn btn-primary btn-sm w-100" @click="viewJobDetail(job)">
                                <i class="fa-solid fa-eye me-1"></i>Xem Chi Tiết
                            </button>
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
    name: 'CareerPortal',
    data() {
        return {
            jobs: [],
            phongBanMap: {},
            chucVuMap: {},
            searchText: '',
            loading: false,
        };
    },
    computed: {
        filteredJobs() {
            if (!this.searchText.trim()) return this.jobs;
            const search = this.searchText.toLowerCase();
            return this.jobs.filter(job =>
                job.tieu_de.toLowerCase().includes(search) ||
                this.getPhongBan(job).toLowerCase().includes(search) ||
                this.getChucVu(job).toLowerCase().includes(search)
            );
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
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                const params = {};
                if (this.searchText.trim()) {
                    params.search = this.searchText.trim();
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
        resetSearch() {
            this.searchText = '';
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
        viewJobDetail(job) {
            this.$router.push(`/vi-tri/${job.id}`);
        },
    },
};
</script>

<style scoped>
.career-portal {
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
