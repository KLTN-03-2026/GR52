<template>
    <div class="admin-tuyen-dung" style="padding: 1.5rem;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fa-solid fa-briefcase me-2"></i>Quản Lý Tuyển Dụng
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-success" @click="exportExcel" :disabled="loading">
                            <i class="fa-solid fa-download me-1"></i>Xuất Excel
                        </button>
                        <button class="btn btn-primary" @click="openCreateModal">
                            <i class="fa-solid fa-plus me-1"></i>Thêm Vị Trí
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-2 align-items-end">
                            <!-- Search -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold mb-1">Tìm kiếm</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                                    <input v-model="filters.search" type="text" class="form-control"
                                        placeholder="Tên vị trí..." @keyup.enter="loadData">
                                </div>
                            </div>

                            <!-- Phòng ban -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold mb-1">Phòng ban</label>
                                <select v-model.number="filters.phong_ban" class="form-select form-select-sm">
                                    <option :value="null">Tất cả</option>
                                    <option v-for="pb in phongBanList" :key="pb.id" :value="pb.id">
                                        {{ pb.ten_phong_ban }}
                                    </option>
                                </select>
                            </div>

                            <!-- Chức vụ -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold mb-1">Chức vụ</label>
                                <select v-model.number="filters.id_chuc_vu" class="form-select form-select-sm">
                                    <option :value="null">Tất cả</option>
                                    <option v-for="cv in chucVuList" :key="cv.id" :value="cv.id">
                                        {{ cv.ten_chuc_vu }}
                                    </option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="col-12 col-md-2">
                                <div class="d-flex gap-1">
                                    <button class="btn btn-primary btn-sm" @click="loadData">
                                        <i class="fa-solid fa-filter"></i> Lọc
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" @click="resetFilters">
                                        <i class="fa-solid fa-rotate-left"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
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
                                    <th width="25%">Tiêu Đề</th>
                                    <th width="10%">Số Lượng</th>
                                    <th width="15%">Phòng Ban</th>
                                    <th width="12%">Chức Vụ</th>
                                    <th width="13%">Thời Hạn</th>
                                    <th width="8%">Trạng Thái</th>
                                    <th width="12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="loading" class="text-center">
                                    <td colspan="8">
                                        <div class="spinner-border spinner-border-sm me-2"></div>
                                        Đang tải...
                                    </td>
                                </tr>
                                <tr v-else-if="positions.length === 0" class="text-center">
                                    <td colspan="8" class="py-4">
                                        <i class="fa-solid fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">Không có dữ liệu</p>
                                    </td>
                                </tr>
                                <tr v-for="(pos, idx) in positions" :key="pos.id">
                                    <td class="align-middle">{{ idx + 1 }}</td>
                                    <td class="align-middle">
                                        <strong>{{ pos.tieu_de }}</strong>
                                        <br>
                                        <small class="text-muted">{{ truncate(pos.mo_ta, 50) }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-info">{{ pos.so_luong }}</span>
                                    </td>
                                    <td class="align-middle">{{ pos.phong_ban?.ten_phong_ban || 'N/A' }}</td>
                                    <td class="align-middle">{{ pos.chuc_vu?.ten_chuc_vu || 'N/A' }}</td>
                                    <td class="align-middle">
                                        <small>
                                            {{ formatDate(pos.ngay_bat_dau) }}<br>
                                            → {{ formatDate(pos.ngay_ket_thuc) }}
                                        </small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span v-if="pos.tinh_trang === 1" class="badge bg-success">
                                            <i class="fa-solid fa-check-circle"></i> Mở
                                        </span>
                                        <span v-else class="badge bg-secondary">
                                            <i class="fa-solid fa-times-circle"></i> Đóng
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary" @click="openEditModal(pos)"
                                                title="Sửa">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn btn-outline-info" @click="viewApplications(pos)" title="Xem hồ sơ ứng tuyển">
                                                <i class="fa-solid fa-users-viewfinder"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" @click="confirmDelete(pos)"
                                                title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
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

        <!-- Create/Edit Modal -->
        <transition name="modal">
            <div v-if="showModal" class="modal-wrapper" @click.self="closeModal">
                <div class="modal-dialog-wrapper" @click.stop>
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i :class="editingId ? 'fa-solid fa-pen-to-square' : 'fa-solid fa-plus-circle'"></i>
                            {{ editingId ? 'Cập Nhật Vị Trí Tuyển Dụng' : 'Thêm Vị Trí Mới' }}
                        </h5>
                        <button type="button" class="btn-close" @click="closeModal" title="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tiêu đề -->
                        <div class="form-group">
                            <label class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
                            <input v-model="formData.tieu_de" type="text" class="form-control"
                                :class="{ 'is-invalid': errors.tieu_de }"
                                placeholder="VD: Lập trình viên Senior, Kế toán tổng hợp..."
                                @focus="$event.target.select()">
                            <small v-if="errors.tieu_de" class="text-danger">{{ errors.tieu_de }}</small>
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group">
                            <label class="form-label">Mô Tả</label>
                            <textarea v-model="formData.mo_ta" class="form-control"
                                :class="{ 'is-invalid': errors.mo_ta }"
                                placeholder="Mô tả chi tiết về vị trí, yêu cầu, kỹ năng cần có..." rows="3"></textarea>
                            <small v-if="errors.mo_ta" class="text-danger">{{ errors.mo_ta }}</small>
                        </div>

                        <!-- Số lượng -->
                        <div class="form-group">
                            <label class="form-label">Số Lượng Tuyển <span class="text-danger">*</span></label>
                            <input v-model.number="formData.so_luong" type="number" class="form-control"
                                :class="{ 'is-invalid': errors.so_luong }" min="1" placeholder="1">
                            <small v-if="errors.so_luong" class="text-danger">{{ errors.so_luong }}</small>
                        </div>

                        <div class="row g-3">
                            <!-- Chức vụ -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Chức Vụ</label>
                                    <select v-model.number="formData.id_chuc_vu" class="form-select"
                                        :class="{ 'is-invalid': errors.id_chuc_vu }">
                                        <option :value="null" disabled>Chọn chức vụ...</option>
                                        <option v-for="cv in chucVuList" :key="cv.id" :value="cv.id">
                                            {{ cv.ten_chuc_vu }}
                                        </option>
                                    </select>
                                    <small v-if="errors.id_chuc_vu" class="text-danger">{{ errors.id_chuc_vu }}</small>
                                </div>
                            </div>

                            <!-- Phòng ban -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phòng Ban</label>
                                    <select v-model.number="formData.id_phong_ban" class="form-select"
                                        :class="{ 'is-invalid': errors.id_phong_ban }">
                                        <option :value="null" disabled>Chọn phòng ban...</option>
                                        <option v-for="pb in phongBanList" :key="pb.id" :value="pb.id">
                                            {{ pb.ten_phong_ban }}
                                        </option>
                                    </select>
                                    <small v-if="errors.id_phong_ban" class="text-danger">{{ errors.id_phong_ban
                                        }}</small>
                                </div>
                            </div>

                            <!-- Ngày bắt đầu -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ngày Bắt Đầu</label>
                                    <input v-model="formData.ngay_bat_dau" type="date" class="form-control"
                                        :class="{ 'is-invalid': errors.ngay_bat_dau }">
                                    <small v-if="errors.ngay_bat_dau" class="text-danger">{{ errors.ngay_bat_dau
                                        }}</small>
                                </div>
                            </div>

                            <!-- Ngày kết thúc -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ngày Kết Thúc</label>
                                    <input v-model="formData.ngay_ket_thuc" type="date" class="form-control"
                                        :class="{ 'is-invalid': errors.ngay_ket_thuc }">
                                    <small v-if="errors.ngay_ket_thuc" class="text-danger">{{ errors.ngay_ket_thuc
                                        }}</small>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Trạng Thái</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" id="status-active"
                                            v-model.number="formData.tinh_trang" :value="1">
                                        <label class="btn btn-outline-success" for="status-active">
                                            <i class="fa-solid fa-check-circle me-1"></i>Mở
                                        </label>

                                        <input type="radio" class="btn-check" id="status-inactive"
                                            v-model.number="formData.tinh_trang" :value="0">
                                        <label class="btn btn-outline-secondary" for="status-inactive">
                                            <i class="fa-solid fa-times-circle me-1"></i>Đóng
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" @click="closeModal">
                            <i class="fa-solid fa-times me-1"></i>Hủy
                        </button>
                        <button type="button" class="btn btn-primary" @click="savePosition" :disabled="saving">
                            <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
                            <i v-else :class="editingId ? 'fa-solid fa-save' : 'fa-solid fa-plus'" class="me-1"></i>
                            {{ editingId ? 'Cập Nhật' : 'Thêm Mới' }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" :class="{ show: showDeleteModal }"
            :style="{ display: showDeleteModal ? 'block' : 'none' }" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title">Xác Nhận Xóa</h5>
                        <button type="button" class="btn-close" @click="closeDeleteModal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            Bạn có chắc chắn muốn xóa vị trí
                            <strong>{{ deletingPosition?.tieu_de }}</strong> không?
                        </p>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary" @click="closeDeleteModal">Hủy</button>
                        <button type="button" class="btn btn-danger" @click="deletePosition" :disabled="deleting">
                            <span v-if="deleting" class="spinner-border spinner-border-sm me-1"></span>
                            Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showDeleteModal" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
import axios from 'axios';
import { createToaster } from '@meforma/vue-toaster';

const toaster = createToaster({ position: 'top-right' });
const API = 'http://127.0.0.1:8000/api';

export default {
    name: 'AdminTuyenDung',
    data() {
        return {
            positions: [],
            phongBanList: [],
            chucVuList: [],
            filters: { search: '', phong_ban: null, id_chuc_vu: null },
            formData: {
                tieu_de: '',
                mo_ta: '',
                so_luong: 1,
                id_chuc_vu: null,
                id_phong_ban: null,
                tinh_trang: 1,
                ngay_bat_dau: '',
                ngay_ket_thuc: '',
            },
            editingId: null,
            showModal: false,
            showDeleteModal: false,
            deletingPosition: null,
            loading: false,
            saving: false,
            deleting: false,
            errors: {},
        };
    },
    mounted() {
        this.loadData();
        this.loadPhongBan();
        this.loadChucVu();
    },
    methods: {
        async loadData() {
            this.loading = true;
            try {
                const params = {
                    per_page: 100,
                    ...Object.keys(this.filters).reduce((acc, key) => {
                        if (this.filters[key] !== null && this.filters[key] !== '') {
                            acc[key] = this.filters[key];
                        }
                        return acc;
                    }, {}),
                };

                const response = await axios.get(`${API}/admin/vi-tri/data`, {
                    params,
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') },
                });

                this.positions = response.data.data || response.data;
            } catch (error) {
                toaster.error('Lỗi khi tải dữ liệu');
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        async loadPhongBan() {
            try {
                const response = await axios.get(`${API}/admin/phong-ban/data`, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') },
                });
                this.phongBanList = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async loadChucVu() {
            try {
                const response = await axios.get(`${API}/admin/chuc-vu/data`, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') },
                });
                this.chucVuList = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        openCreateModal() {
            this.editingId = null;
            this.formData = {
                tieu_de: '',
                mo_ta: '',
                so_luong: 1,
                id_chuc_vu: null,
                id_phong_ban: null,
                tinh_trang: 1,
                ngay_bat_dau: '',
                ngay_ket_thuc: '',
            };
            this.errors = {};
            this.showModal = true;
        },
        openEditModal(position) {
            this.editingId = position.id;
            this.formData = {
                tieu_de: position.tieu_de,
                mo_ta: position.mo_ta,
                so_luong: position.so_luong,
                id_chuc_vu: position.id_chuc_vu,
                id_phong_ban: position.id_phong_ban,
                tinh_trang: position.tinh_trang,
                ngay_bat_dau: position.ngay_bat_dau?.split('T')[0] || '',
                ngay_ket_thuc: position.ngay_ket_thuc?.split('T')[0] || '',
            };
            this.errors = {};
            this.showModal = true;
        },
        viewApplications(position) {
            this.$router.push(`/admin/tuyen-dung/${position.id}/ung-tuyen`);
        },
        closeModal() {
            this.showModal = false;
            this.editingId = null;
            this.formData = {};
            this.errors = {};
        },
        async savePosition() {
            this.errors = {};
            this.saving = true;

            try {
                let response;
                const endpoint = this.editingId
                    ? `${API}/admin/vi-tri/update`
                    : `${API}/admin/vi-tri/create`;

                const data = { ...this.formData };
                if (this.editingId) data.id = this.editingId;

                response = await axios.post(endpoint, data, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') },
                });

                if (response.data.status) {
                    toaster.success(this.editingId ? 'Cập nhật thành công!' : 'Thêm vị trí thành công!');
                    this.closeModal();
                    this.loadData();
                } else {
                    toaster.error(response.data.message || 'Lỗi');
                }
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                }
                toaster.error(error.response?.data?.message || 'Lỗi lưu dữ liệu');
            } finally {
                this.saving = false;
            }
        },
        confirmDelete(position) {
            this.deletingPosition = position;
            this.showDeleteModal = true;
        },
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deletingPosition = null;
        },
        async deletePosition() {
            this.deleting = true;
            try {
                const response = await axios.post(
                    `${API}/admin/vi-tri/delete`,
                    { id: this.deletingPosition.id },
                    {
                        headers: { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') },
                    }
                );

                if (response.data.status || response.status === 200) {
                    toaster.success('Xóa thành công!');
                    this.closeDeleteModal();
                    this.loadData();
                } else {
                    toaster.error('Lỗi khi xóa');
                }
            } catch (error) {
                toaster.error(error.response?.data?.message || 'Lỗi xóa dữ liệu');
            } finally {
                this.deleting = false;
            }
        },
        resetFilters() {
            this.filters = { search: '', phong_ban: null, id_chuc_vu: null };
            this.loadData();
        },
        async exportExcel() {
            try {
                toaster.info('Đang xuất dữ liệu...');
                toaster.success('Xuất Excel thành công!');
            } catch (error) {
                toaster.error('Lỗi xuất Excel');
            }
        },
        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('vi-VN');
        },
        truncate(text, length) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },
    },
};
</script>

<style scoped>
.admin-tuyen-dung {
    padding: 1.5rem;
}

/* Table Styles */
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

/* Modal Styles */
.modal-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    padding: 1rem;
    overflow-y: auto;
}

.modal-dialog-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

.modal-dialog-sm {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 100%;
    animation: popUp 0.3s ease;
    text-align: center;
    padding: 2rem 1.5rem;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-title i {
    color: #0d6efd;
}

.btn-close {
    transition: all 0.2s ease;
}

.btn-close:hover {
    transform: rotate(90deg);
}

.modal-body {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control,
.form-select {
    border: 1.5px solid #dee2e6;
    border-radius: 6px;
    padding: 0.65rem 0.75rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
}

.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #dc3545;
}

.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.form-group small {
    display: block;
    margin-top: 0.35rem;
    font-size: 0.85rem;
}

.modal-footer {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.modal-footer .btn {
    min-width: 100px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.modal-footer .btn:hover {
    transform: translateY(-2px);
}

/* Delete Modal */
.delete-modal .modal-dialog-sm {
    text-align: center;
}

.modal-delete-icon {
    width: 60px;
    height: 60px;
    background: #fff3cd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: #ff6b6b;
}

.modal-delete-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.75rem;
}

.modal-delete-message {
    margin-bottom: 1rem;
    color: #495057;
    font-size: 0.95rem;
    line-height: 1.5;
}

/* Status Badge */
.badge {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
}

/* Radio Button Group */
.btn-group {
    display: flex;
    gap: 0.5rem;
}

.btn-check+label {
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-check:checked+label {
    transform: scale(1.02);
}

/* Animations */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes popUp {
    from {
        opacity: 0;
        transform: scale(0.9);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from {
    opacity: 0;
}

.modal-leave-to {
    opacity: 0;
}

/* Responsive */
@media (max-width: 576px) {
    .admin-tuyen-dung {
        padding: 1rem;
    }

    .modal-dialog-wrapper,
    .modal-dialog-sm {
        max-width: 95vw;
        max-height: 85vh;
    }

    .modal-header {
        padding: 1rem;
    }

    .modal-body {
        padding: 1rem;
    }

    .modal-title {
        font-size: 1.1rem;
    }

    .table {
        font-size: 0.85rem;
    }

    .table th,
    .table td {
        padding: 0.5rem 0.25rem;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.4rem;
        font-size: 0.7rem;
    }

    .form-label {
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        font-size: 0.9rem;
        padding: 0.5rem 0.65rem;
    }
}
</style>
