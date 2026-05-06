<template>
    <div class="admin-applications" style="padding: 1.5rem;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header d-flex justify-content-between align-items-center gap-3">
                    <h4 class="mb-0">
                        <i class="fa-solid fa-users-viewfinder me-2"></i>Hồ Sơ Ứng Tuyển cho: <span
                            class="text-primary">{{ jobTitle }}</span>
                    </h4>
                    <button class="btn btn-outline-secondary" @click="$router.go(-1)">
                        <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách vị trí
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="text-muted mb-1">Tổng Hồ Sơ</h5>
                        <h3 class="text-primary mb-0">{{ applications.length }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="text-muted mb-1">Đang Chờ</h5>
                        <h3 class="text-warning mb-0">{{ countStatus(0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="text-muted mb-1">Đã Duyệt</h5>
                        <h3 class="text-success mb-0">{{ countStatus(2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="text-muted mb-1">Từ Chối</h5>
                        <h3 class="text-danger mb-0">{{ countStatus(3) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Ứng Viên</th>
                                    <th width="20%">Email</th>
                                    <th width="15%">Ngày Ứng Tuyển</th>
                                    <th width="15%">Trạng Thái</th>
                                    <th class="text-end action-column">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="loading" class="text-center">
                                    <td colspan="6">
                                        <div class="spinner-border spinner-border-sm me-2"></div>
                                        Đang tải...
                                    </td>
                                </tr>
                                <tr v-else-if="applications.length === 0" class="text-center">
                                    <td colspan="6" class="py-4">
                                        <i class="fa-solid fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">Chưa có hồ sơ ứng tuyển nào cho vị trí này.</p>
                                    </td>
                                </tr>
                                <tr v-for="(app, idx) in applications" :key="app.id"
                                    :class="getRowClass(app.trang_thai)">
                                    <td class="align-middle">{{ idx + 1 }}</td>
                                    <td class="align-middle">
                                        <strong>{{ app.ung_vien?.ho_ten || 'N/A' }}</strong>
                                    </td>
                                    <td class="align-middle">{{ app.ung_vien?.email || 'N/A' }}</td>
                                    <td class="align-middle">{{ formatDate(app.ngay_ung_tuyen) }}</td>
                                    <td class="align-middle">
                                        <span :class="getStatusBadgeClass(app.trang_thai)">
                                            {{ getStatusText(app.trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                :disabled="!canAnalyze(app) || isAnalyzing(app.id)"
                                                @click="analyzeSingle(app)" :title="getAnalyzeTitle(app)">
                                                <span v-if="isAnalyzing(app.id)"
                                                    class="spinner-border spinner-border-sm"></span>
                                                <i v-else class="fa-solid fa-brain"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                @click="openReviewModal(app)" title="Xem CV và đánh giá">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </button>
                                            <button v-if="app.trang_thai !== 2" type="button"
                                                class="btn btn-sm btn-outline-success" @click="quickApprove(app)"
                                                title="Duyệt ứng viên">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <button v-if="app.trang_thai !== 3" type="button"
                                                class="btn btn-sm btn-outline-danger" @click="quickReject(app)"
                                                title="Từ chối ứng viên">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                                    :id="`dropdownStatus${app.id}`" data-bs-toggle="dropdown"
                                                    aria-expanded="false" title="Thêm tùy chọn">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu" :aria-labelledby="`dropdownStatus${app.id}`">
                                                    <li><a class="dropdown-item" href="#"
                                                            @click.prevent="updateStatus(app, 0)">Đặt lại - Đang chờ</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            @click.prevent="updateStatus(app, 1)">Đã xem</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            @click.prevent="downloadCv(app)"><i
                                                                class="fa-solid fa-download me-1"></i>Tải CV</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CV Analysis Section -->
        <div class="row mt-4">
            <div class="col-12">
                <CVAnalysis ref="cvAnalysis" :viTriTuyenDungId="$route.params.id" />
            </div>
        </div>

        <!-- Review Modal -->
        <div v-if="selectedApp" class="modal-overlay" @click.self="closeReviewModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header border-bottom">
                        <div>
                            <h5 class="modal-title mb-0">
                                <i class="fa-solid fa-user-tie me-2 text-primary"></i>
                                {{ selectedApp.ung_vien?.ho_ten || 'N/A' }}
                            </h5>
                            <small class="text-muted">{{ selectedApp.ung_vien?.email }}</small>
                        </div>
                        <button type="button" class="btn-close" @click="closeReviewModal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row">
                            <!-- Left: Application Info -->
                            <div class="col-lg-4">
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3">
                                            <i class="fa-solid fa-info-circle me-2"></i>Thông Tin Ứng Tuyển
                                        </h6>
                                        <p class="mb-2">
                                            <strong>Email:</strong><br>
                                            <small>{{ selectedApp.ung_vien?.email || 'N/A' }}</small>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Điện Thoại:</strong><br>
                                            <small>{{ selectedApp.ung_vien?.so_dien_thoai || 'N/A' }}</small>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Ngày Ứng Tuyển:</strong><br>
                                            <small>{{ formatDate(selectedApp.ngay_ung_tuyen) }}</small>
                                        </p>
                                        <p class="mb-0">
                                            <strong>Trạng Thái Hiện Tại:</strong><br>
                                            <span :class="getStatusBadgeClass(selectedApp.trang_thai)">
                                                {{ getStatusText(selectedApp.trang_thai) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="card border-0">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3">
                                            <i class="fa-solid fa-sticky-note me-2"></i>Ghi Chú Đánh Giá
                                        </h6>
                                        <textarea v-model="evaluationNote" class="form-control form-control-sm" rows="4"
                                            placeholder="Nhập ghi chú về ứng viên này..."></textarea>
                                    </div>
                                </div>

                                <div class="card border-0 mt-3">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3">
                                            <i class="fa-solid fa-envelope me-2"></i>Email Kết Quả
                                        </h6>
                                        <label class="form-label small fw-bold">Ngày phỏng vấn</label>
                                        <input v-model="emailForm.ngay_phong_van" type="datetime-local"
                                            class="form-control form-control-sm mb-2">
                                        <label class="form-label small fw-bold">Ghi chú thêm</label>
                                        <textarea v-model="emailForm.ghi_chu" class="form-control form-control-sm"
                                            rows="3" placeholder="Nội dung bổ sung trong email..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: CV Preview -->
                            <div class="col-lg-8">
                                <div class="card border-0">
                                    <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px;">
                                        <h6 class="text-muted mb-3">
                                            <i class="fa-solid fa-file-pdf me-2 text-danger"></i>Tệp CV
                                        </h6>
                                        <div v-if="selectedApp.file_cv">
                                            <div class="alert alert-info mb-3" role="alert">
                                                <i class="fa-solid fa-download me-2"></i>
                                                <strong>{{ selectedApp.file_cv }}</strong>
                                                <button type="button" class="btn btn-sm btn-primary float-end"
                                                    @click="downloadCv(selectedApp)">
                                                    <i class="fa-solid fa-download me-1"></i>Tải Xuống
                                                </button>
                                            </div>
                                            <div v-if="isPdfFile(selectedApp.file_cv)" class="text-center">
                                                <iframe :src="cvPreviewUrl"
                                                    style="width: 100%; height: 500px; border: 1px solid #dee2e6; border-radius: 4px;"
                                                    @load="onIframeLoad" @error="onIframeError">
                                                </iframe>
                                            </div>
                                            <div v-else class="alert alert-info" role="alert">
                                                <i class="fa-solid fa-info-circle me-2"></i>
                                                <strong>File {{ getFileExtension(selectedApp.file_cv) }}</strong> không
                                                thể xem trước.
                                                <button type="button" class="btn btn-sm btn-primary ms-2"
                                                    @click="downloadCv(selectedApp)">
                                                    Tải xuống để xem
                                                </button>
                                            </div>
                                        </div>
                                        <div v-else class="alert alert-warning" role="alert">
                                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                            <strong>Chưa có CV</strong> — Ứng viên chưa tải lên tệp CV.
                                            Bạn vẫn có thể xem thông tin và đưa ra quyết định dựa trên ghi chú của ứng
                                            viên.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Candidate Note -->
                        <div v-if="selectedApp.ghi_chu_ung_vien" class="card border-0 mt-3">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="fa-solid fa-quote-left me-2"></i>Ghi Chú Của Ứng Viên
                                </h6>
                                <p class="mb-0" style="white-space: pre-wrap;">{{ selectedApp.ghi_chu_ung_vien }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-secondary" @click="closeReviewModal">Đóng</button>
                        <button type="button" class="btn btn-outline-danger" :disabled="sendingEmail"
                            @click="sendResultEmail('khong_dat')">
                            <span v-if="sendingEmail" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-envelope me-1"></i>Gửi Mail Không Đậu
                        </button>
                        <button type="button" class="btn btn-outline-success" :disabled="sendingEmail"
                            @click="sendResultEmail('dat')">
                            <span v-if="sendingEmail" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-envelope-circle-check me-1"></i>Gửi Mail Đậu
                        </button>
                        <button v-if="selectedApp.trang_thai !== 3" type="button" class="btn btn-danger"
                            @click="confirmReject">
                            <i class="fa-solid fa-times me-1"></i>Từ Chối
                        </button>
                        <button v-if="selectedApp.trang_thai !== 2" type="button" class="btn btn-success"
                            @click="confirmApprove">
                            <i class="fa-solid fa-check me-1"></i>Duyệt Ứng Viên
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { createToaster } from '@meforma/vue-toaster';
import CVAnalysis from '@/components/CVAnalysis.vue';

const toaster = createToaster({ position: 'top-right' });
const API = 'http://127.0.0.1:8000/api';

export default {
    name: 'AdminHoSoUngTuyen',
    components: {
        CVAnalysis,
    },
    data() {
        return {
            applications: [],
            jobTitle: 'Đang tải...',
            loading: false,
            selectedApp: null,
            evaluationNote: '',
            emailForm: {
                ngay_phong_van: '',
                ghi_chu: '',
            },
            sendingEmail: false,
            cvPreviewUrl: '',
            analyzingIds: {},
            API: API,
        };
    },
    async created() {
        const jobId = this.$route.params.id;
        if (!jobId || jobId === 'undefined') {
            toaster.error('ID vị trí không hợp lệ.');
            this.jobTitle = 'Không hợp lệ';
            this.applications = [];
            this.$router.replace('/admin/tuyen-dung');
            return;
        }
        await this.loadJobTitle();
        await this.loadApplications();
    },
    methods: {
        async loadJobTitle() {
            try {
                const jobId = this.$route.params.id;
                if (!jobId || jobId === 'undefined') {
                    throw new Error('Invalid job ID');
                }
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.get(`${API}/admin/vi-tri/show/${jobId}`, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.jobTitle = response.data.tieu_de;
            } catch (error) {
                console.error("Error loading job title:", error);
                this.jobTitle = 'Không tìm thấy';
            }
        },
        async loadApplications() {
            this.loading = true;
            try {
                const jobId = this.$route.params.id;
                if (!jobId || jobId === 'undefined') {
                    throw new Error('Invalid job ID');
                }
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.get(`${API}/admin/vi-tri/${jobId}/ung-tuyen`, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                this.applications = response.data.data || [];
            } catch (error) {
                console.error("Error loading applications:", error);
                toaster.error('Lỗi khi tải danh sách hồ sơ ứng tuyển.');
            } finally {
                this.loading = false;
            }
        },

        canAnalyze(application) {
            return !!application?.file_cv && this.isPdfFile(application.file_cv);
        },

        isAnalyzing(applicationId) {
            return !!this.analyzingIds[applicationId];
        },

        getAnalyzeTitle(application) {
            if (!application?.file_cv) return 'Hồ sơ chưa có CV';
            if (!this.isPdfFile(application.file_cv)) return 'AI hiện chỉ phân tích CV PDF';
            return 'Phân tích AI hồ sơ này';
        },

        async analyzeSingle(application) {
            if (!this.canAnalyze(application) || this.isAnalyzing(application.id)) return;

            this.analyzingIds = {
                ...this.analyzingIds,
                [application.id]: true,
            };

            try {
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.post(`${API}/admin/cv-analysis/analyze-single`,
                    { ho_so_ung_tuyen_id: application.id },
                    { headers: { Authorization: `Bearer ${token}` } }
                );

                toaster.success(response.data.message || 'Phân tích CV thành công.');
                await this.$refs.cvAnalysis?.loadAnalyses?.();
            } catch (error) {
                console.error('Error analyzing CV:', error);
                toaster.error(error.response?.data?.message || 'Phân tích AI thất bại.');
            } finally {
                const nextState = { ...this.analyzingIds };
                delete nextState[application.id];
                this.analyzingIds = nextState;
            }
        },

        async openReviewModal(application) {
            this.selectedApp = application;
            this.evaluationNote = '';
            this.emailForm = {
                ngay_phong_van: '',
                ghi_chu: '',
            };
            await this.loadCvPreview(application);
        },

        async loadCvPreview(application) {
            this.releaseCvPreview();

            if (!application?.file_cv || !this.isPdfFile(application.file_cv)) {
                return;
            }

            try {
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.get(`${API}/admin/ung-tuyen/${application.id}/preview-cv`, {
                    headers: { Authorization: `Bearer ${token}` },
                    responseType: 'blob',
                });
                this.cvPreviewUrl = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
            } catch (error) {
                console.error('Error loading CV preview:', error);
                toaster.error('Không thể xem trước CV. Vui lòng tải xuống để xem.');
            }
        },

        releaseCvPreview() {
            if (this.cvPreviewUrl) {
                window.URL.revokeObjectURL(this.cvPreviewUrl);
                this.cvPreviewUrl = '';
            }
        },

        onIframeLoad() {
            return true;
        },

        onIframeError() {
            console.error('Failed to load PDF preview');
            toaster.error('Lỗi khi tải xem trước PDF. Vui lòng tải xuống để xem.');
        },

        closeReviewModal() {
            this.releaseCvPreview();
            this.selectedApp = null;
            this.evaluationNote = '';
            this.emailForm = {
                ngay_phong_van: '',
                ghi_chu: '',
            };
        },

        async sendResultEmail(result) {
            if (!this.selectedApp) return;
            if (result === 'dat' && !this.emailForm.ngay_phong_van) {
                toaster.error('Vui lòng chọn ngày phỏng vấn trước khi gửi mail đậu.');
                return;
            }

            this.sendingEmail = true;
            try {
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.post(
                    `${API}/admin/ung-tuyen/${this.selectedApp.id}/send-result-email`,
                    {
                        ket_qua: result,
                        ngay_phong_van: result === 'dat' ? this.emailForm.ngay_phong_van : null,
                        ghi_chu: this.emailForm.ghi_chu || this.evaluationNote || '',
                    },
                    { headers: { Authorization: `Bearer ${token}` } }
                );

                if (response.data.status) {
                    const newStatus = result === 'dat' ? 2 : 3;
                    toaster.success(response.data.message || 'Đã gửi email cho ứng viên.');
                    this.selectedApp.trang_thai = newStatus;
                    const idx = this.applications.findIndex(a => a.id === this.selectedApp.id);
                    if (idx !== -1) this.applications[idx].trang_thai = newStatus;
                    this.closeReviewModal();
                } else {
                    toaster.error(response.data.message || 'Gửi email thất bại.');
                }
            } catch (error) {
                console.error('Error sending result email:', error);
                toaster.error(error.response?.data?.message || 'Lỗi khi gửi email kết quả.');
            } finally {
                this.sendingEmail = false;
            }
        },

        async downloadCv(application) {
            try {
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.get(`${API}/admin/ung-tuyen/${application.id}/download-cv`, {
                    headers: { Authorization: `Bearer ${token}` },
                    responseType: 'blob',
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `CV_${application.ung_vien.ho_ten}_${this.jobTitle}.${application.file_cv.split('.').pop()}`);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);
                toaster.success('Tải CV thành công!');
            } catch (error) {
                console.error("Error downloading CV:", error);
                toaster.error('Lỗi khi tải CV.');
            }
        },

        quickApprove(application) {
            this.selectedApp = application;
            this.confirmApprove();
        },

        quickReject(application) {
            this.selectedApp = application;
            this.confirmReject();
        },

        async confirmApprove() {
            if (!this.selectedApp) return;

            try {
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.post(`${API}/admin/ung-tuyen/${this.selectedApp.id}/update-status`,
                    { trang_thai: 2 },
                    { headers: { Authorization: `Bearer ${token}` } }
                );

                if (response.data.status) {
                    toaster.success('Duyệt ứng viên thành công!');
                    this.selectedApp.trang_thai = 2;
                    const idx = this.applications.findIndex(a => a.id === this.selectedApp.id);
                    if (idx !== -1) this.applications[idx].trang_thai = 2;
                    this.closeReviewModal();
                } else {
                    toaster.error(response.data.message || 'Lỗi khi duyệt ứng viên.');
                }
            } catch (error) {
                console.error("Error approving:", error);
                toaster.error('Lỗi khi duyệt ứng viên.');
            }
        },

        async confirmReject() {
            if (!this.selectedApp) return;

            try {
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.post(`${API}/admin/ung-tuyen/${this.selectedApp.id}/update-status`,
                    { trang_thai: 3 },
                    { headers: { Authorization: `Bearer ${token}` } }
                );

                if (response.data.status) {
                    toaster.success('Từ chối ứng viên thành công!');
                    this.selectedApp.trang_thai = 3;
                    const idx = this.applications.findIndex(a => a.id === this.selectedApp.id);
                    if (idx !== -1) this.applications[idx].trang_thai = 3;
                    this.closeReviewModal();
                } else {
                    toaster.error(response.data.message || 'Lỗi khi từ chối ứng viên.');
                }
            } catch (error) {
                console.error("Error rejecting:", error);
                toaster.error('Lỗi khi từ chối ứng viên.');
            }
        },

        async updateStatus(application, newStatus) {
            try {
                const token = localStorage.getItem('tk_nhan_vien');
                const response = await axios.post(`${API}/admin/ung-tuyen/${application.id}/update-status`,
                    { trang_thai: newStatus },
                    { headers: { Authorization: `Bearer ${token}` } }
                );

                if (response.data.status) {
                    toaster.success('Cập nhật trạng thái thành công!');
                    application.trang_thai = newStatus;
                } else {
                    toaster.error(response.data.message || 'Lỗi khi cập nhật trạng thái.');
                }
            } catch (error) {
                console.error("Error updating status:", error);
                toaster.error('Lỗi khi cập nhật trạng thái.');
            }
        },

        formatDate(date) {
            if (!date) return '-';
            const d = new Date(date);
            return d.toLocaleDateString('vi-VN');
        },

        getStatusText(status) {
            switch (status) {
                case 0: return 'Đang chờ';
                case 1: return 'Đã xem';
                case 2: return 'Đã duyệt';
                case 3: return 'Từ chối';
                default: return 'Không rõ';
            }
        },

        getStatusBadgeClass(status) {
            switch (status) {
                case 0: return 'badge bg-warning text-dark';
                case 1: return 'badge bg-info';
                case 2: return 'badge bg-success';
                case 3: return 'badge bg-danger';
                default: return 'badge bg-secondary';
            }
        },

        getRowClass(status) {
            if (status === 2) return 'table-success bg-success bg-opacity-10';
            if (status === 3) return 'table-danger bg-danger bg-opacity-10';
            return '';
        },

        countStatus(status) {
            return this.applications.filter(a => a.trang_thai === status).length;
        },

        isPdfFile(filename) {
            return filename && filename.toLowerCase().endsWith('.pdf');
        },

        getFileExtension(filename) {
            if (!filename) return 'unknown';
            const ext = filename.split('.').pop();
            return ext ? ext.toUpperCase() : 'unknown';
        }
    },
};
</script>

<style scoped>
.admin-applications {
    padding: 1.5rem;
}

.page-header {
    flex-wrap: wrap;
}

.page-header h4 {
    line-height: 1.35;
}

.action-column {
    min-width: 210px;
}

.table th {
    font-weight: 700;
    color: #495057;
    background-color: #f8f9fa;
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.action-buttons {
    align-items: center;
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    justify-content: flex-end;
}

.action-buttons .btn {
    align-items: center;
    border-radius: 6px;
    display: inline-flex;
    height: 34px;
    justify-content: center;
    padding: 0 0.65rem;
    transition: all 0.2s ease;
    min-width: 34px;
    width: auto;
}

.action-buttons .btn i {
    font-size: 0.92rem;
}

.action-buttons .spinner-border-sm {
    height: 0.9rem;
    width: 0.9rem;
}

.badge {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
}

.dropdown-menu {
    font-size: 0.9rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
}

/* Modal Overlay Styles */
.modal-overlay {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1200;
    animation: fadeIn 0.2s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.modal-dialog {
    width: 90%;
    max-width: 1200px;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-content {
    border: none;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
}

.modal-header .text-muted {
    color: rgba(255, 255, 255, 0.78) !important;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-footer {
    background-color: #f8f9fa;
    border-radius: 0 0 8px 8px !important;
}

/* Card Styles */
.card {
    border-radius: 8px;
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card.border-0 {
    border: 1px solid #e9ecef !important;
}

/* Stats Box */
.card-body h5 {
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.card-body h3 {
    font-size: 2rem;
    font-weight: 700;
}

/* Table Row Highlighting */
.table-success {
    opacity: 0.9;
}

.table-danger {
    opacity: 0.9;
}

/* Action Buttons */
.btn-success {
    background: linear-gradient(135deg, #5cb85c 0%, #449d44 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #449d44 0%, #398439 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(92, 184, 92, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #d9534f 0%, #c9302c 100%);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c9302c 0%, #ac2925 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(217, 83, 79, 0.3);
}

/* TextField styles */
textarea.form-control {
    border-radius: 6px;
    border: 1px solid #dee2e6;
    transition: border-color 0.2s ease;
}

textarea.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Alert Styles */
.alert {
    border-radius: 6px;
    border: none;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}

@media (max-width: 768px) {
    .admin-applications {
        padding: 1rem;
    }

    .page-header .btn {
        width: 100%;
    }

    .action-buttons {
        justify-content: flex-start;
        min-width: 170px;
    }
}
</style>
