<template>
    <div class="job-detail-page"
        style="padding: 1.5rem; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh;">
        <div class="container">
            <!-- Back Button -->
            <div class="row mb-4">
                <div class="col-12">
                    <button class="btn btn-outline-secondary" @click="$router.go(-1)">
                        <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách
                    </button>
                </div>
            </div>

            <template v-if="loading">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Đang tải chi tiết công việc...</p>
                </div>
            </template>

            <template v-else-if="job">
                <div class="row">
                    <!-- Job Details Card -->
                    <div class="col-lg-8 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="card-title text-primary mb-3">{{ job.tieu_de }}</h3>
                                <div class="job-meta mb-3">
                                    <span class="badge bg-info me-2">
                                        <i class="fa-solid fa-users me-1"></i>Số lượng: {{ job.so_luong }}
                                    </span>
                                    <span class="badge bg-secondary me-2">
                                        <i class="fa-solid fa-building me-1"></i>{{ job.phong_ban?.ten_phong_ban ||
                                        'N/A' }}
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="fa-solid fa-briefcase me-1"></i>{{ job.chuc_vu?.ten_chuc_vu || 'N/A'
                                        }}
                                    </span>
                                </div>
                                <p class="text-muted mb-4">
                                    <i class="fa-solid fa-calendar me-2 text-primary"></i>
                                    Thời hạn: {{ formatDate(job.ngay_bat_dau) }} → {{ formatDate(job.ngay_ket_thuc) }}
                                </p>

                                <h5 class="mt-4 mb-2">
                                    <i class="fa-solid fa-briefcase me-2 text-primary"></i>Mô tả công việc
                                </h5>
                                <div class="job-description" v-html="job.mo_ta"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Card -->
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-4">
                                    <i class="fa-solid fa-star me-2 text-warning"></i>Quan Tâm Công Việc Này
                                </h5>

                                <div class="alert alert-info" role="alert">
                                    <i class="fa-solid fa-circle-info me-2"></i>
                                    <strong>Đã đăng nhập?</strong> Hãy <router-link to="/ung-vien/cong-viec">xem chi
                                        tiết</router-link> để nộp CV.
                                </div>

                                <p class="text-center text-muted mb-3">
                                    <i class="fa-solid fa-lock me-1"></i>
                                    Bạn cần đăng nhập để nộp hồ sơ ứng tuyển.
                                </p>

                                <div class="d-grid gap-2">
                                    <router-link to="/ung-vien/dang-nhap" class="btn btn-primary btn-lg">
                                        <i class="fa-solid fa-sign-in-alt me-2"></i>Đăng Nhập
                                    </router-link>
                                    <router-link to="/ung-vien/dang-ki" class="btn btn-outline-primary btn-lg">
                                        <i class="fa-solid fa-user-plus me-2"></i>Đăng Ký Ngay
                                    </router-link>
                                </div>

                                <hr class="my-3">

                                <div class="job-info">
                                    <div class="info-item mb-2">
                                        <strong class="text-dark">📍 Phòng Ban:</strong>
                                        <p class="text-muted mb-0">{{ job.phong_ban?.ten_phong_ban || 'N/A' }}</p>
                                    </div>
                                    <div class="info-item mb-2">
                                        <strong class="text-dark">💼 Chức Vụ:</strong>
                                        <p class="text-muted mb-0">{{ job.chuc_vu?.ten_chuc_vu || 'N/A' }}</p>
                                    </div>
                                    <div class="info-item">
                                        <strong class="text-dark">👥 Số Vị Trí:</strong>
                                        <p class="text-muted mb-0">{{ job.so_luong }} vị trí</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="card border-0 text-center shadow-sm">
                    <div class="card-body py-5">
                        <i class="fa-solid fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="text-muted">Không tìm thấy thông tin công việc</h5>
                        <p class="text-muted mb-3">Vị trí tuyển dụng này có thể không tồn tại hoặc đã bị đóng.</p>
                        <button class="btn btn-outline-primary" @click="$router.push('/tuyen-dung')">
                            <i class="fa-solid fa-arrow-left me-1"></i>Quay lại danh sách
                        </button>
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
    name: 'PublicJobDetail',
    data() {
        return {
            job: null,
            loading: false,
            isLoggedIn: false,
        };
    },
    async created() {
        await this.checkLoginStatus();
        // Nếu user đã đăng nhập, redirect đến trang ứng viên xem chi tiết
        if (this.isLoggedIn) {
            const jobId = this.$route.params.id;
            if (!jobId || jobId === 'undefined') {
                toaster.error('ID vị trí không hợp lệ.');
                this.$router.replace('/career');
                return;
            }
            this.$router.replace(`/ung-vien/cong-viec/${jobId}`);
            return;
        }
        await this.loadJobDetails();
    },
    methods: {
        async checkLoginStatus() {
            try {
                const token = localStorage.getItem('token_ung_vien');
                if (!token) {
                    this.isLoggedIn = false;
                    return;
                }
                const response = await axios.get(`${API}/ung-vien/check-login`, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.isLoggedIn = response.data.status;
            } catch (error) {
                this.isLoggedIn = false;
                console.error("Error checking login status:", error);
            }
        },
        async loadJobDetails() {
            this.loading = true;
            try {
                const jobId = this.$route.params.id;
                if (!jobId || jobId === 'undefined') {
                    toaster.error('ID vị trí không hợp lệ.');
                    this.job = null;
                    this.loading = false;
                    this.$router.replace('/career');
                    return;
                }
                const response = await axios.get(`${API}/vi-tri/${jobId}`);
                this.job = response.data;
            } catch (error) {
                console.error("Error loading job details:", error);
                toaster.error('Không thể tải chi tiết công việc.');
                this.job = null;
            } finally {
                this.loading = false;
            }
        },
        formatDate(date) {
            if (!date) return '-';
            const d = new Date(date);
            return d.toLocaleDateString('vi-VN');
        },
    },
};
</script>

<style scoped>
.job-detail-page {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.card {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.card-title {
    font-weight: 700;
    font-size: 1.8rem;
}

.job-meta .badge {
    font-size: 0.9rem;
    padding: 0.6em 0.9em;
    font-weight: 600;
}

.job-description {
    line-height: 1.8;
    color: #343a40;
}

.job-description>>>p {
    margin-bottom: 1rem;
}

.job-description>>>ul,
.job-description>>>ol {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.job-description>>>li {
    margin-bottom: 0.5rem;
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a4299 100%);
    color: white;
    text-decoration: none;
}

.btn-outline-primary {
    color: #667eea;
    border-color: #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
}

@media (max-width: 768px) {
    .sticky-top {
        position: static !important;
        margin-top: 1rem;
    }
}
</style>
