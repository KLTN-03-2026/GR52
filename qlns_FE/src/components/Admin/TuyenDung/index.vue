<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><b>QUẢN LÝ TUYỂN DỤNG</b></h6>
                    <div>
                        <button class="btn btn-success me-2" @click="exportExcel">Xuất Excel</button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Thêm Vị
                            Trí</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3 g-2">
                        <div class="col-md-4">
                            <input v-model="filters.search" @keyup.enter="loadPositions" class="form-control"
                                placeholder="Tìm kiếm tiêu đề">
                        </div>
                        <div class="col-md-3">
                            <select v-model="filters.id_phong_ban" class="form-select">
                                <option :value="null">Tất cả phòng ban</option>
                                <option v-for="p in list_phong_ban" :key="p.id" :value="p.id">{{ p.ten_phong_ban }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select v-model="filters.id_chuc_vu" class="form-select">
                                <option :value="null">Tất cả chức vụ</option>
                                <option v-for="c in list_chuc_vu" :key="c.id" :value="c.id">{{ c.ten_chuc_vu }}</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-primary" @click="loadPositions">Lọc</button>
                            <button class="btn btn-outline-secondary" @click="resetFilters">Reset</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tiêu đề</th>
                                    <th>Số lượng</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Phòng ban</th>
                                    <th>Chức vụ</th>
                                    <th>Trạng thái</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(v, k) in list_positions" :key="v.id || k">
                                    <td class="align-middle">{{ k + 1 }}</td>
                                    <td class="align-middle">{{ v.tieu_de }}</td>
                                    <td class="align-middle">{{ v.so_luong }}</td>
                                    <td class="align-middle">{{ formatDate(v.ngay_bat_dau) }}</td>
                                    <td class="align-middle">{{ formatDate(v.ngay_ket_thuc) }}</td>
                                    <td class="align-middle">{{ displayPhongBan(v) }}</td>
                                    <td class="align-middle">{{ displayChucVu(v) }}</td>
                                    <td class="align-middle text-center">
                                        <span v-if="v.tinh_trang == 1" class="badge bg-success">Mở</span>
                                        <span v-else class="badge bg-secondary">Đóng</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-primary btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#editModal" @click="setEdit(v)">Sửa</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#delModal" @click="setDelete(v)">Xóa</button>
                                    </td>
                                </tr>
                                <tr v-if="list_positions.length === 0">
                                    <td colspan="9" class="text-center">Không có vị trí tuyển dụng.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm vị trí tuyển dụng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Tiêu đề</label>
                            <input v-model="create_position.tieu_de" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Mô tả</label>
                            <textarea v-model="create_position.mo_ta" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label>Số lượng</label>
                                <input v-model.number="create_position.so_luong" type="number" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Chức vụ</label>
                                <select v-model="create_position.id_chuc_vu" class="form-select">
                                    <option :value="null">Chọn chức vụ</option>
                                    <option v-for="c in list_chuc_vu" :key="c.id" :value="c.id">{{ c.ten_chuc_vu }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Phòng ban</label>
                                <select v-model="create_position.id_phong_ban" class="form-select">
                                    <option :value="null">Chọn phòng ban</option>
                                    <option v-for="p in list_phong_ban" :key="p.id" :value="p.id">{{ p.ten_phong_ban }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Trạng thái</label>
                                <select v-model.number="create_position.tinh_trang" class="form-select">
                                    <option :value="1">Mở</option>
                                    <option :value="0">Đóng</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Ngày bắt đầu</label>
                                <input v-model="create_position.ngay_bat_dau" type="date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Ngày kết thúc</label>
                                <input v-model="create_position.ngay_ket_thuc" type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button class="btn btn-primary" @click="createPosition" data-bs-dismiss="modal">Thêm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật vị trí</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Tiêu đề</label>
                            <input v-model="edit_position.tieu_de" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Mô tả</label>
                            <textarea v-model="edit_position.mo_ta" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label>Số lượng</label>
                                <input v-model.number="edit_position.so_luong" type="number" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Chức vụ</label>
                                <select v-model="edit_position.id_chuc_vu" class="form-select">
                                    <option :value="null">Chọn chức vụ</option>
                                    <option v-for="c in list_chuc_vu" :key="c.id" :value="c.id">{{ c.ten_chuc_vu }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Phòng ban</label>
                                <select v-model="edit_position.id_phong_ban" class="form-select">
                                    <option :value="null">Chọn phòng ban</option>
                                    <option v-for="p in list_phong_ban" :key="p.id" :value="p.id">{{ p.ten_phong_ban }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Trạng thái</label>
                                <select v-model.number="edit_position.tinh_trang" class="form-select">
                                    <option :value="1">Mở</option>
                                    <option :value="0">Đóng</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Ngày bắt đầu</label>
                                <input v-model="edit_position.ngay_bat_dau" type="date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Ngày kết thúc</label>
                                <input v-model="edit_position.ngay_ket_thuc" type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button class="btn btn-primary" @click="updatePosition" data-bs-dismiss="modal">Lưu</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="delModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xóa vị trí</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc muốn xóa vị trí <b>{{ delete_position.tieu_de }}</b>?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" @click="deletePosition" data-bs-dismiss="modal">Xóa</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';

const API = 'http://127.0.0.1:8000/api';

export default {
    data() {
        return {
            list_positions: [],
            list_chuc_vu: [],
            list_phong_ban: [],

            filters: { search: '', id_phong_ban: null, id_chuc_vu: null },

            create_position: { tieu_de: '', mo_ta: '', so_luong: 1, id_chuc_vu: null, id_phong_ban: null, tinh_trang: 1, ngay_bat_dau: '', ngay_ket_thuc: '' },
            edit_position: {},
            delete_position: {},
        };
    },
    mounted() {
        this.loadPositions();
        this.loadChucVu();
        this.loadPhongBan();
    },
    methods: {
        authHeader() {
            return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') };
        },

        handleValidationError(err) {
            const data = err?.response?.data;
            if (data?.errors) {
                Object.values(data.errors).forEach((msgs) => msgs.forEach((m) => this.$toast.error(m)));
            } else if (data?.message) {
                this.$toast.error(data.message);
            } else {
                this.$toast.error('Đã có lỗi xảy ra.');
            }
        },

        loadPositions() {
            axios.get(`${API}/admin/vi-tri/data`, { params: { search: this.filters.search, phong_ban: this.filters.id_phong_ban, id_chuc_vu: this.filters.id_chuc_vu }, headers: this.authHeader() })
                .then((res) => {
                    this.list_positions = Array.isArray(res.data) ? res.data : (res.data.data || res.data || []);
                })
                .catch(() => {
                    this.list_positions = [];
                    this.$toast?.error('Không thể tải danh sách vị trí.');
                });
        },

        loadChucVu() {
            axios.get(`${API}/admin/chuc-vu/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_chuc_vu = Array.isArray(res.data) ? res.data : (res.data.data || res.data || []);
                })
                .catch(() => {
                    this.list_chuc_vu = [];
                });
        },

        loadPhongBan() {
            axios.get(`${API}/admin/phong-ban/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_phong_ban = Array.isArray(res.data) ? res.data : (res.data.data || res.data || []);
                })
                .catch(() => {
                    this.list_phong_ban = [];
                });
        },

        resetFilters() {
            this.filters = { search: '', id_phong_ban: null, id_chuc_vu: null };
            this.loadPositions();
        },

        createPosition() {
            axios.post(`${API}/admin/vi-tri/create`, this.create_position, { headers: this.authHeader() })
                .then((res) => {
                    if (res.status === 201 || (res.data && res.data.status)) {
                        this.$toast.success(res.data.message || 'Tạo vị trí thành công.');
                        this.create_position = { tieu_de: '', mo_ta: '', so_luong: 1, id_chuc_vu: null, id_phong_ban: null, tinh_trang: 1, ngay_bat_dau: '', ngay_ket_thuc: '' };
                        this.loadPositions();
                    } else {
                        this.$toast.error('Tạo vị trí thất bại.');
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        setEdit(v) {
            this.edit_position = Object.assign({}, v);
        },
        updatePosition() {
            axios.post(`${API}/admin/vi-tri/update`, this.edit_position, { headers: this.authHeader() })
                .then((res) => {
                    if (res.data && res.data.id) {
                        this.$toast.success('Cập nhật thành công.');
                        this.loadPositions();
                    } else if (res.data && res.data.status) {
                        this.$toast.success(res.data.message || 'Cập nhật thành công.');
                        this.loadPositions();
                    } else {
                        this.$toast.error('Cập nhật thất bại.');
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        setDelete(v) {
            this.delete_position = Object.assign({}, v);
        },
        deletePosition() {
            axios.post(`${API}/admin/vi-tri/delete`, this.delete_position, { headers: this.authHeader() })
                .then((res) => {
                    if (res.data && res.data.message) {
                        this.$toast.success(res.data.message);
                        this.loadPositions();
                    } else {
                        this.$toast.error('Xóa thất bại.');
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        displayChucVu(v) {
            if (!v) return '';
            if (v.chucVu?.ten_chuc_vu) return v.chucVu.ten_chuc_vu;
            if (v.ten_chuc_vu) return v.ten_chuc_vu;
            const id = v.id_chuc_vu ?? null;
            if (id) {
                const found = this.list_chuc_vu.find((c) => c.id === id);
                return found?.ten_chuc_vu ?? '';
            }
            return '';
        },

        displayPhongBan(v) {
            if (!v) return '';
            if (v.phongBan?.ten_phong_ban) return v.phongBan.ten_phong_ban;
            if (v.ten_phong_ban) return v.ten_phong_ban;
            const id = v.id_phong_ban ?? null;
            if (id) {
                const found = this.list_phong_ban.find((p) => p.id === id);
                return found?.ten_phong_ban ?? '';
            }
            return '';
        },

        formatDate(v) {
            if (!v) return '';
            const d = new Date(v);
            if (isNaN(d)) return v;
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        },

        exportExcel() {
            axios.get(`${API}/admin/vi-tri/xuat-excel`, { responseType: 'blob', headers: this.authHeader() })
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'vi_tri_tuyen_dung.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch(() => this.$toast?.error('Xuất Excel thất bại.'));
        },
    },
};
</script>

<style scoped></style>
