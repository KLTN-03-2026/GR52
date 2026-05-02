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
                            <div class="card-hero p-0">
                                <div class="hero-gradient p-4 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="company-logo-lg">{{ (job.phong_ban?.ten_phong_ban ||
                                            'CT').slice(0, 2).toUpperCase() }}</div>
                                        <div>
                                            <h3 class="mb-1 text-white">{{ job.tieu_de }}</h3>
                                            <div class="text-white-50 small">{{ job.phong_ban?.ten_phong_ban || 'N/A' }}
                                                · {{ job.chuc_vu?.ten_chuc_vu || 'N/A' }}</div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="mb-1 text-white small">Thời hạn: {{ formatDate(job.ngay_bat_dau) }}
                                            → {{ formatDate(job.ngay_ket_thuc) }}</div>
                                        <button class="btn btn-cta btn-sm"
                                            @click="$refs.applyForm?.scrollIntoView({ behavior: 'smooth' })">Ứng tuyển
                                            ngay</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
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
                                <h5 class="mt-4 mb-2">Mô tả công việc</h5>
                                <div class="job-description" v-html="job.mo_ta"></div>

                                <!-- Add more job details here if available, e.g., yêu cầu, quyền lợi -->
                            </div>
                        </div>
                    </div>

                    <!-- Application Form Card -->
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm sticky-card">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-4">Nộp Hồ Sơ Ứng Tuyển</h5>

                                <template v-if="!isLoggedIn">
                                    <div class="alert alert-warning text-center" role="alert">
                                        Vui lòng <router-link to="/ung-vien/dang-nhap">đăng nhập</router-link> để nộp hồ
                                        sơ.
                                    </div>
                                </template>
                                <template v-else-if="hasApplied">
                                    <div class="mb-3">
                                        <div class="alert alert-info" role="alert">
                                            <strong>Bạn đã nộp hồ sơ cho vị trí này.</strong>
                                            <div class="small mt-1">Nếu muốn thay CV mới, bạn có thể nộp lại. CV cũ sẽ
                                                được xóa.</div>
                                        </div>

                                        <div v-if="!reuploadMode" class="d-grid gap-2">
                                            <button class="btn btn-outline-primary" @click="reuploadMode = true">Nộp lại
                                                CV</button>
                                        </div>

                                        <div v-else>
                                            <div class="mb-3">
                                                <label for="cvFileReplace" class="form-label">Chọn file CV mới <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" id="cvFileReplace" class="form-control"
                                                    accept=".pdf,.doc,.docx" @change="handleReplacementFileUpload">
                                                <div v-if="replacementErrors.file_cv" class="text-danger small mt-1">{{
                                                    replacementErrors.file_cv }}</div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary flex-fill"
                                                    @click.prevent="submitReplacement" :disabled="replacing">
                                                    <span v-if="replacing"
                                                        class="spinner-border spinner-border-sm me-2"></span>
                                                    Nộp lại CV
                                                </button>
                                                <button class="btn btn-outline-secondary"
                                                    @click="() => { reuploadMode = false; replacementFile = null; const f = document.getElementById('cvFileReplace'); if (f) f.value = ''; }">Hủy</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <form ref="applyForm" @submit.prevent="submitApplication">
                                        <div class="mb-3">
                                            <label for="cvFile" class="form-label">Tải lên CV của bạn <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="cvFile"
                                                @change="handleFileUpload" :class="{ 'is-invalid': errors.file_cv }"
                                                accept=".pdf,.doc,.docx">
                                            <div class="form-text">Chỉ chấp nhận file PDF, DOC, DOCX (tối đa 5MB).</div>
                                            <div v-if="errors.file_cv" class="invalid-feedback">{{ errors.file_cv }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="coverLetter" class="form-label">Ghi chú / Thư xin việc (Tùy
                                                chọn)</label>
                                            <textarea class="form-control" id="coverLetter" rows="5"
                                                v-model="applicationForm.ghi_chu_ung_vien"
                                                :class="{ 'is-invalid': errors.ghi_chu_ung_vien }"
                                                placeholder="Viết một vài dòng giới thiệu bản thân hoặc kinh nghiệm của bạn..."></textarea>
                                            <div v-if="errors.ghi_chu_ung_vien" class="invalid-feedback">{{
                                                errors.ghi_chu_ung_vien }}</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100" :disabled="submitting">
                                            <span v-if="submitting"
                                                class="spinner-border spinner-border-sm me-2"></span>
                                            <i v-else class="fa-solid fa-paper-plane me-2"></i>Nộp Hồ Sơ
                                        </button>
                                    </form>
                                </template>
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
                        <button class="btn btn-outline-primary" @click="$router.push('/ung-vien/tuyen-dung')">
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
    name: 'CandidateChiTietTuyenDung',
    data() {
        return {
            job: null,
            loading: false,
            submitting: false,
            isLoggedIn: false,
            hasApplied: false,
            currentApplication: null,
            reuploadMode: false,
            replacementFile: null,
            replacing: false,
            replacementErrors: {},
            applicationForm: {
                file_cv: null,
                ghi_chu_ung_vien: '',
            },
            errors: {},
        };
    },
    async created() {
        await this.checkLoginStatus();
        await this.loadJobDetails();
        if (this.isLoggedIn && this.job) {
            await this.checkIfAlreadyApplied();
        }
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
                    this.$router.replace('/ung-vien/trang-chu');
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
        async checkIfAlreadyApplied() {
            try {
                const token = localStorage.getItem('token_ung_vien');
                if (!token) return;

                const response = await axios.get(`${API}/ung-vien/ho-so-ung-tuyen`, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                const applications = response.data.data || [];
                const found = applications.find(app => app.vi_tri_tuyen_dung_id === this.job.id);
                if (found) {
                    this.hasApplied = true;
                    this.currentApplication = found;
                } else {
                    this.hasApplied = false;
                    this.currentApplication = null;
                }
            } catch (error) {
                console.error("Error checking if already applied:", error);
                this.hasApplied = false;
            }
        },
        handleReplacementFileUpload(event) {
            this.replacementFile = event.target.files[0];
            if (this.replacementErrors.file_cv) this.replacementErrors.file_cv = null;
        },
        handleFileUpload(event) {
            this.applicationForm.file_cv = event.target.files[0];
            if (this.errors.file_cv) {
                this.errors.file_cv = null; // Clear error when file is selected
            }
        },
        async submitApplication() {
            this.errors = {};
            this.submitting = true;

            const formData = new FormData();
            formData.append('file_cv', this.applicationForm.file_cv);
            formData.append('ghi_chu_ung_vien', this.applicationForm.ghi_chu_ung_vien);

            try {
                const jobId = this.$route.params.id;
                if (!jobId || jobId === 'undefined') {
                    toaster.error('ID vị trí không hợp lệ.');
                    this.submitting = false;
                    return;
                }
                const token = localStorage.getItem('token_ung_vien');

                const response = await axios.post(`${API}/ung-vien/ung-tuyen/${jobId}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.data.status) {
                    toaster.success('Nộp hồ sơ thành công!');
                    this.hasApplied = true; // Mark as applied
                    // refresh currentApplication list
                    await this.checkIfAlreadyApplied();
                    this.applicationForm.file_cv = null;
                    this.applicationForm.ghi_chu_ung_vien = '';
                    // Optionally, clear the file input visually
                    const fileInput = document.getElementById('cvFile');
                    if (fileInput) fileInput.value = '';
                } else {
                    toaster.error(response.data.message || 'Lỗi khi nộp hồ sơ.');
                }
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                } else if (error.response?.data?.message) {
                    toaster.error(error.response.data.message);
                } else {
                    toaster.error('Đã xảy ra lỗi không mong muốn.');
                }
                console.error("Error submitting application:", error);
            } finally {
                this.submitting = false;
            }
        },

        async submitReplacement() {
            // Replace existing CV: delete old application (if available) then upload new CV
            this.replacementErrors = {};
            if (!this.replacementFile) {
                this.replacementErrors.file_cv = 'Vui lòng chọn file CV mới.';
                return;
            }
            this.replacing = true;
            try {
                const jobId = this.$route.params.id;
                if (!jobId || jobId === 'undefined') {
                    toaster.error('ID vị trí không hợp lệ.');
                    this.replacing = false;
                    return;
                }
                const token = localStorage.getItem('token_ung_vien');

                // If we have a current application id, delete it first
                if (this.currentApplication && this.currentApplication.id) {
                    try {
                        await axios.delete(`${API}/ung-vien/ho-so-ung-tuyen/${this.currentApplication.id}`, {
                            headers: { Authorization: `Bearer ${token}` }
                        });
                    } catch (delErr) {
                        // If deletion fails, show warning but attempt upload anyway
                        console.warn('Could not delete old application:', delErr);
                        toaster.warning('Không xóa được hồ sơ cũ nhưng sẽ tiếp tục nộp file mới.');
                    }
                }

                // Upload new CV
                const formData = new FormData();
                formData.append('file_cv', this.replacementFile);
                // allow optional note reuse
                formData.append('ghi_chu_ung_vien', this.applicationForm.ghi_chu_ung_vien || '');

                const response = await axios.post(`${API}/ung-vien/ung-tuyen/${jobId}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.data.status) {
                    toaster.success('Nộp lại CV thành công!');
                    this.reuploadMode = false;
                    this.replacementFile = null;
                    // clear file input visually
                    const fileInput = document.getElementById('cvFileReplace');
                    if (fileInput) fileInput.value = '';
                    // refresh application state
                    await this.checkIfAlreadyApplied();
                } else {
                    toaster.error(response.data.message || 'Lỗi khi nộp lại hồ sơ.');
                }
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.replacementErrors = error.response.data.errors;
                } else if (error.response?.data?.message) {
                    toaster.error(error.response.data.message);
                } else {
                    toaster.error('Đã xảy ra lỗi khi nộp lại hồ sơ.');
                }
                console.error('Error submitting replacement:', error);
            } finally {
                this.replacing = false;
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

.card-hero .hero-gradient {
    background: linear-gradient(135deg, #2b7be4 0%, #2ca9d9 100%);
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.company-logo-lg {
    width: 72px;
    height: 72px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 1.25rem;
}

.sticky-card {
    position: sticky;
    top: 1.5rem;
}

.btn-cta {
    background: linear-gradient(90deg, #fff 0%, #f0f7ff 100%);
    color: #0b4a8b;
    border-radius: 999px;
    padding: 0.35rem 0.8rem;
    border: none;
    font-weight: 700;
    box-shadow: 0 6px 18px rgba(12, 64, 122, 0.08);
}

.alert-info {
    background: linear-gradient(90deg, #eef6ff, #f7fbff);
    color: #0b3f71;
    border: 1px solid rgba(11, 63, 113, 0.06);
}

.job-description {
    font-size: 0.98rem;
    color: #2f3b45;
}

.card-body {
    padding: 1.5rem;
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

.form-label {
    font-weight: 600;
    color: #343a40;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 0.75rem 1rem;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
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
}

.alert-warning,
.alert-info {
    border-radius: 8px;
}
</style>
