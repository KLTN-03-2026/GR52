<template>
    <div class="candidate-applications" style="padding: 1.5rem;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fa-solid fa-file-lines me-2"></i>Hồ Sơ Ứng Tuyển Của Tôi
                    </h4>
                    <router-link to="/ung-vien/trang-chu" class="btn btn-outline-primary">
                        <i class="fa-solid fa-briefcase me-2"></i>Xem Vị Trí Tuyển Dụng
                    </router-link>
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
                                    <th width="30%">Vị Trí Ứng Tuyển</th>
                                    <th width="20%">Phòng Ban</th>
                                    <th width="15%">Ngày Ứng Tuyển</th>
                                    <th width="15%">Trạng Thái</th>
                                    <th width="15%">Hành Động</th>
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
                                        <p class="text-muted">Bạn chưa ứng tuyển vị trí nào.</p>
                                        <router-link to="/ung-vien/trang-chu" class="btn btn-sm btn-primary mt-2">
                                            <i class="fa-solid fa-briefcase me-1"></i>Khám phá vị trí
                                        </router-link>
                                    </td>
                                </tr>
                                <tr v-for="(app, idx) in applications" :key="app.id">
                                    <td class="align-middle">{{ idx + 1 }}</td>
                                    <td class="align-middle">
                                        <strong>{{ app.vi_tri_tuyen_dung?.tieu_de || 'N/A' }}</strong>
                                    </td>
                                    <td class="align-middle">{{ getPhongBan(app) }}</td>
                                    <td class="align-middle">{{ formatDate(app.ngay_ung_tuyen) }}</td>
                                    <td class="align-middle">
                                        <span :class="getStatusBadgeClass(app.trang_thai)">
                                            {{ getStatusText(app.trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button v-if="app.file_cv" class="btn btn-outline-primary" @click="downloadCv(app)"
                                                title="Tải CV">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-info" @click="viewDetails(app)" title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="row mt-4" v-if="applications.length > 0">
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: !meta.links || !meta.links.prev }">
                            <a class="page-link" href="#" @click.prevent="previousPage">Trước</a>
                        </li>
                        <li class="page-item" :class="{ disabled: !meta.links || !meta.links.next }">
                            <a class="page-link" href="#" @click.prevent="nextPage">Tiếp</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Chi Tiết Hồ Sơ Ứng Tuyển</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" v-if="selectedApplication">
                        <h6 class="mb-3">{{ selectedApplication.vi_tri_tuyen_dung?.tieu_de }}</h6>
                        
                        <div class="mb-3">
                            <strong>Trạng thái:</strong>
                            <span :class="getStatusBadgeClass(selectedApplication.trang_thai)">
                                {{ getStatusText(selectedApplication.trang_thai) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Ngày ứng tuyển:</strong>
                            <p class="mb-0">{{ formatDate(selectedApplication.ngay_ung_tuyen) }}</p>
                        </div>

                        <div class="mb-3" v-if="selectedApplication.ghi_chu_ung_vien">
                            <strong>Ghi chú của bạn:</strong>
                            <p class="mb-0 text-muted">{{ selectedApplication.ghi_chu_ung_vien }}</p>
                        </div>

                        <div class="mb-3" v-if="selectedApplication.file_cv">
                            <strong>CV được nộp:</strong>
                            <p class="mb-0">
                                <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                                {{ selectedApplication.file_cv.split('/').pop() }}
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button v-if="selectedApplication?.file_cv" type="button" class="btn btn-primary" 
                            @click="downloadCv(selectedApplication)">
                            <i class="fa-solid fa-download me-1"></i>Tải CV
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

const toaster = createToaster({ position: 'top-right' });
const API = 'http://127.0.0.1:8000/api';

export default {
    name: 'CandidateHoSoUngTuyen',
    data() {
        return {
            applications: [],
            loading: false,
            currentPage: 1,
            meta: {},
            selectedApplication: null,
            detailModal: null,
        };
    },
    async mounted() {
        this.detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        await this.loadApplications();
    },
    methods: {
        async loadApplications() {
            this.loading = true;
            try {
                const token = localStorage.getItem('tk_ung_vien');
                const response = await axios.get(`${API}/ung-vien/ho-so-ung-tuyen`, {
                    headers: { Authorization: `Bearer ${token}` },
                    params: { page: this.currentPage, per_page: 10 }
                });
                
                if (response.data.data) {
                    this.applications = response.data.data.data || response.data.data;
                    this.meta = {
                        links: response.data.data.links || {}
                    };
                } else {
                    this.applications = response.data;
                }
            } catch (error) {
                console.error("Error loading applications:", error);
                toaster.error('Lỗi khi tải danh sách hồ sơ ứng tuyển.');
            } finally {
                this.loading = false;
            }
        },
        async downloadCv(application) {
            try {
                const token = localStorage.getItem('tk_nhan_vien') || localStorage.getItem('tk_ung_vien');
                const response = await axios.get(`${API}/admin/ung-tuyen/${application.id}/download-cv`, {
                    headers: { Authorization: `Bearer ${token}` },
                    responseType: 'blob',
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                const jobTitle = application.vi_tri_tuyen_dung?.tieu_de || 'CV';
                link.setAttribute('download', `CV_${jobTitle}.${application.file_cv.split('.').pop()}`);
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
        viewDetails(application) {
            this.selectedApplication = application;
            this.detailModal.show();
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
        getPhongBan(app) {
            return app.vi_tri_tuyen_dung?.phong_ban?.ten_phong_ban || 'N/A';
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.loadApplications();
            }
        },
        nextPage() {
            if (this.meta.links?.next) {
                this.currentPage++;
                this.loadApplications();
            }
        }
    },
};
</script>

<style scoped>
.candidate-applications {
    padding: 1.5rem;
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

.btn-group-sm .btn {
    padding: 0.35rem 0.65rem;
    font-size: 0.85rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-group-sm .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.badge {
    padding: 0.5em 0.75em;
    border-radius: 6px;
}

.pagination {
    margin: 0;
}
</style>
