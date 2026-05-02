<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mt-2">
                    <h6><b>QUẢN LÝ HỒ SƠ ỨNG TUYỂN</b></h6>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label"><strong>Vị Trí Tuyển Dụng</strong></label>
                            <select v-model="filters.viTriId" class="form-select" @change="loadData">
                                <option value="">-- Tất Cả Vị Trí --</option>
                                <option v-for="vitri in list_vi_tri" :key="vitri.id" :value="vitri.id">
                                    {{ vitri.tieu_de }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Phòng Ban</strong></label>
                            <select v-model="filters.phongBanId" class="form-select" @change="loadData">
                                <option value="">-- Tất Cả Phòng Ban --</option>
                                <option v-for="pb in list_phong_ban" :key="pb.id" :value="pb.id">
                                    {{ pb.ten_phong_ban }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Trạng Thái</strong></label>
                            <select v-model="filters.trangThai" class="form-select" @change="loadData">
                                <option value="">-- Tất Cả Trạng Thái --</option>
                                <option value="0">Đang Chờ</option>
                                <option value="1">Đã Xem</option>
                                <option value="2">Đã Duyệt</option>
                                <option value="3">Từ Chối</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Tìm Kiếm</strong></label>
                            <input v-model="searchText" type="text" class="form-control" placeholder="Tên ứng viên..."
                                @keyup.enter="loadData">
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Tổng Hồ Sơ</h6>
                                    <h4 class="mb-0 text-primary">{{ applications.length }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Đang Chờ</h6>
                                    <h4 class="mb-0 text-warning">{{ countStatus(0) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Đã Duyệt</h6>
                                    <h4 class="mb-0 text-success">{{ countStatus(2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Từ Chối</h6>
                                    <h4 class="mb-0 text-danger">{{ countStatus(3) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applications Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Ứng Viên</th>
                                    <th width="12%">Email</th>
                                    <th width="18%">Vị Trí</th>
                                    <th width="12%">Phòng Ban</th>
                                    <th width="12%">Ngày Nộp</th>
                                    <th width="10%">Trạng Thái</th>
                                    <th width="17%">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="loading" class="text-center">
                                    <td colspan="8">
                                        <div class="spinner-border spinner-border-sm"></div>
                                        <span class="ms-2">Đang tải...</span>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredApplications.length === 0" class="text-center">
                                    <td colspan="8" class="py-3">Không có hồ sơ ứng tuyển nào.</td>
                                </tr>
                                <tr v-for="(app, idx) in filteredApplications" :key="app.id">
                                    <td>{{ idx + 1 }}</td>
                                    <td><strong>{{ app.ung_vien?.ho_ten || 'N/A' }}</strong></td>
                                    <td>{{ app.ung_vien?.email || '-' }}</td>
                                    <td>{{ app.vi_tri_tuyen_dung?.tieu_de || 'N/A' }}</td>
                                    <td>{{ app.vi_tri_tuyen_dung?.phong_ban?.ten_phong_ban || '-' }}</td>
                                    <td>{{ formatDate(app.ngay_ung_tuyen) }}</td>
                                    <td>
                                        <span :class="getStatusBadge(app.trang_thai)">
                                            {{ getStatusText(app.trang_thai) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button v-if="app.file_cv" class="btn btn-outline-primary"
                                                @click="downloadCv(app)" title="Tải CV">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-info" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" @click="viewDetails(app)"
                                                title="Xem Chi Tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" v-if="app.trang_thai !== 2"
                                                @click="updateApplicationStatus(app, 2)" title="Duyệt">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" v-if="app.trang_thai !== 3"
                                                @click="updateApplicationStatus(app, 3)" title="Từ Chối">
                                                <i class="fa-solid fa-times"></i>
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

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Chi Tiết Hồ Sơ Ứng Tuyển</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" v-if="selectedApp">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Thông Tin Ứng Viên</h6>
                                <p><strong>Tên:</strong> {{ selectedApp.ung_vien?.ho_ten }}</p>
                                <p><strong>Email:</strong> {{ selectedApp.ung_vien?.email }}</p>
                                <p><strong>SĐT:</strong> {{ selectedApp.ung_vien?.so_dien_thoai || '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Thông Tin Vị Trí</h6>
                                <p><strong>Vị Trí:</strong> {{ selectedApp.vi_tri_tuyen_dung?.tieu_de }}</p>
                                <p><strong>Phòng Ban:</strong> {{
                                    selectedApp.vi_tri_tuyen_dung?.phong_ban?.ten_phong_ban }}</p>
                                <p><strong>Chức Vụ:</strong> {{ selectedApp.vi_tri_tuyen_dung?.chuc_vu?.ten_chuc_vu }}
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="fw-bold">Ghi Chú Ứng Viên</h6>
                                <p class="text-muted">{{ selectedApp.ghi_chu_ung_vien || 'Không có ghi chú' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold">Ngày Ứng Tuyển</h6>
                                <p>{{ formatDate(selectedApp.ngay_ung_tuyen) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
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
const API = 'http://127.0.0.1:8000/api/admin';

export default {
    name: 'AdminQuanLyHoSoUngTuyen',
    data() {
        return {
            applications: [],
            list_vi_tri: [],
            list_phong_ban: [],
            loading: false,
            selectedApp: null,
            filters: {
                viTriId: '',
                trangThai: '',
                phongBanId: ''
            },
            searchText: ''
        };
    },
    computed: {
        filteredApplications() {
            return this.applications.filter(app => {
                const viTriMatch = !this.filters.viTriId || app.vi_tri_tuyen_dung_id == this.filters.viTriId;
                const statusMatch = this.filters.trangThai === '' || app.trang_thai == this.filters.trangThai;
                const deptMatch = !this.filters.phongBanId || app.vi_tri_tuyen_dung?.id_phong_ban == this.filters.phongBanId;
                const searchMatch = !this.searchText ||
                    (app.ung_vien?.ho_ten || '').toLowerCase().includes(this.searchText.toLowerCase()) ||
                    (app.ung_vien?.email || '').toLowerCase().includes(this.searchText.toLowerCase());

                return viTriMatch && statusMatch && deptMatch && searchMatch;
            });
        }
    },
    mounted() {
        this.loadData();
        this.loadViTri();
        this.loadPhongBan();
    },
    methods: {
        authHeader() {
            return {
                Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien')
            };
        },

        loadData() {
            this.loading = true;
            axios
                .get(`${API}/ung-tuyen/all`, { headers: this.authHeader() })
                .then((res) => {
                    this.applications = res.data.status
                        ? res.data.data || []
                        : (Array.isArray(res.data) ? res.data : []);
                })
                .catch(() => {
                    toaster.error('Lỗi khi tải danh sách hồ sơ ứng tuyển.');
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        loadViTri() {
            axios
                .get(`${API}/vi-tri/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_vi_tri = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    toaster.error('Lỗi khi tải danh sách vị trí.');
                });
        },

        loadPhongBan() {
            axios
                .get(`${API}/phong-ban/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_phong_ban = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    toaster.error('Lỗi khi tải danh sách phòng ban.');
                });
        },

        getStatusText(status) {
            const statuses = {
                0: 'Đang Chờ',
                1: 'Đã Xem',
                2: 'Đã Duyệt',
                3: 'Từ Chối'
            };
            return statuses[status] || 'N/A';
        },

        getStatusBadge(status) {
            const badges = {
                0: 'badge bg-warning text-dark',
                1: 'badge bg-info',
                2: 'badge bg-success',
                3: 'badge bg-danger'
            };
            return badges[status] || 'badge bg-secondary';
        },

        formatDate(date) {
            if (!date) return '-';
            const d = new Date(date);
            return d.toLocaleDateString('vi-VN');
        },

        countStatus(status) {
            return this.applications.filter(app => app.trang_thai == status).length;
        },

        viewDetails(app) {
            this.selectedApp = app;
        },

        downloadCv(app) {
            if (!app.file_cv) {
                toaster.error('Không tìm thấy file CV');
                return;
            }

            axios
                .get(`${API}/ung-tuyen/${app.id}/download-cv`, {
                    headers: this.authHeader(),
                    responseType: 'blob'
                })
                .then((res) => {
                    // Tạo URL từ blob
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', `CV_${app.ung_vien?.ho_ten || 'ung_vien'}.pdf`);
                    document.body.appendChild(link);
                    link.click();
                    link.parentNode.removeChild(link);
                    window.URL.revokeObjectURL(url);
                })
                .catch((err) => {
                    toaster.error('Lỗi khi tải CV: ' + (err?.response?.data?.message || err.message));
                });
        },

        updateApplicationStatus(app, status) {
            axios
                .post(`${API}/ung-tuyen/${app.id}/update-status`, {
                    trang_thai: status
                }, {
                    headers: this.authHeader()
                })
                .then((res) => {
                    if (res.data.status) {
                        toaster.success('Cập nhật trạng thái thành công');
                        this.loadData();
                    } else {
                        toaster.error(res.data.message || 'Cập nhật thất bại');
                    }
                })
                .catch((err) => {
                    toaster.error('Lỗi: ' + (err?.response?.data?.message || err.message));
                });
        }
    }
};
</script>

<style scoped>
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-group-sm .btn {
    padding: 0.35rem 0.5rem;
    font-size: 0.85rem;
}

.badge {
    padding: 0.4rem 0.6rem;
    font-weight: 500;
}
</style>
