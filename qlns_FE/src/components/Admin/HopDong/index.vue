<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6><b>QUẢN LÝ HỢP ĐỒNG</b></h6>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContractModal"
                        @click="openCreateModal()">
                        Tạo hợp đồng mới
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Nhân viên</label>
                            <select v-model="filter.nhan_vien" @change="loadContracts" class="form-control">
                                <option value="">Tất cả nhân viên</option>
                                <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">{{ nv.ho_va_ten }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Loại hợp đồng</label>
                            <select v-model="filter.id_loai_hop_dong" @change="loadContracts" class="form-control">
                                <option value="">Tất cả loại hợp đồng</option>
                                <option v-for="loai in list_hop_dong" :key="loai.id" :value="loai.id">{{
                                    loai.ten_hop_dong }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tìm kiếm</label>
                            <input v-model="filter.search" @keyup.enter="loadContracts" type="text" class="form-control"
                                placeholder="Mã, nhân viên, mẫu..." />
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nhân viên</th>
                                    <th>Loại hợp đồng</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(contract, index) in list_contracts" :key="contract.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ contract.nhan_vien?.ho_va_ten || getEmployeeName(contract.id_nhan_vien) ||
                                        '-' }}</td>
                                    <td>{{ contract.loai_hop_dong?.ten_hop_dong ||
                                        getContractTypeName(contract.id_loai_hop_dong) || '-' }}</td>
                                    <td>{{ formatDate(contract.ngay_bat_dau) }}</td>
                                    <td>{{ formatDate(contract.ngay_ket_thuc) }}</td>
                                    <td><span class="badge bg-secondary">{{ contractStatusText(contract.trang_thai)
                                            }}</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal"
                                            data-bs-target="#editContractModal"
                                            @click="setEditContract(contract)">Sửa</button>
                                        <button class="btn btn-sm btn-outline-danger"
                                            @click="downloadContractPdf(contract)">PDF</button>
                                    </td>
                                </tr>
                                <tr v-if="list_contracts.length === 0">
                                    <td colspan="7" class="text-center">Chưa có hợp đồng.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-secondary" @click="loadContracts">Làm mới danh sách</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createContractModal" tabindex="-1" aria-labelledby="createContractModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createContractModalLabel">Tạo hợp đồng mới</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nhân viên</label>
                            <select v-model="create_hop_dong.id_nhan_vien" @change="updateCreateEmployee"
                                class="form-control">
                                <option value="">Chọn nhân viên</option>
                                <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">{{ nv.ho_va_ten }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại hợp đồng</label>
                            <select v-model="create_hop_dong.id_loai_hop_dong" @change="updateCreatePreview"
                                class="form-control">
                                <option value="">Chọn loại hợp đồng</option>
                                <option v-for="loai in list_hop_dong" :key="loai.id" :value="loai.id">{{
                                    loai.ten_hop_dong }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày ký</label>
                            <input v-model="create_hop_dong.ngay_ky" type="date" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input v-model="create_hop_dong.ngay_bat_dau" type="date" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày kết thúc</label>
                            <input v-model="create_hop_dong.ngay_ket_thuc" type="date" class="form-control" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nội dung hợp đồng</label>
                            <div class="border rounded p-3 bg-light contract-preview" v-html="create_hop_dong.noi_dung">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" @click="createContract">Lưu mẫu hợp đồng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editContractModal" tabindex="-1" aria-labelledby="editContractModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editContractModalLabel">Chỉnh sửa hợp đồng</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Ngày ký</label>
                            <input v-model="edit_contract.ngay_ky" type="date" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input v-model="edit_contract.ngay_bat_dau" type="date" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày kết thúc</label>
                            <input v-model="edit_contract.ngay_ket_thuc" type="date" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung hợp đồng</label>
                        <textarea v-model="edit_contract.noi_dung" rows="10" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" @click="updateContract">Lưu hợp đồng</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

const API = 'http://127.0.0.1:8000/api/admin';

export default {
    data() {
        return {
            list_nhan_vien: [],
            list_hop_dong: [],
            list_contracts: [],
            filter: {
                nhan_vien: '',
                id_loai_hop_dong: '',
                search: '',
            },
            create_hop_dong: {
                id_nhan_vien: '',
                ho_va_ten: '',
                email: '',
                so_dien_thoai: '',
                dia_chi: '',
                id_loai_hop_dong: '',
                ngay_ky: new Date().toISOString().slice(0, 10),
                ngay_bat_dau: '',
                ngay_ket_thuc: '',
                noi_dung: '',
            },
            edit_contract: {},
        };
    },

    async mounted() {
        await Promise.all([this.loadNhanVien(), this.loadHopDong()]);

        if (this.$route.query.nhan_vien) {
            this.filter.nhan_vien = this.$route.query.nhan_vien;
            this.create_hop_dong.id_nhan_vien = this.$route.query.nhan_vien;
            this.updateCreateEmployee();
        }

        this.resetCreateForm();
        this.loadContracts();
    },

    methods: {
        authHeader() {
            return {
                Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien'),
            };
        },

        loadNhanVien() {
            return axios
                .get(`${API}/nhan-vien/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_nhan_vien = res.data.status ? res.data.data || [] : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách nhân viên.');
                });
        },

        loadHopDong() {
            return axios
                .get(`${API}/loai-hop-dong/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_hop_dong = res.data.status ? res.data.data || [] : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách hợp đồng.');
                });
        },

        loadContracts() {
            const params = {};
            if (this.filter.nhan_vien) params.nhan_vien = this.filter.nhan_vien;
            if (this.filter.id_loai_hop_dong) params.id_loai_hop_dong = this.filter.id_loai_hop_dong;
            if (this.filter.search) params.search = this.filter.search;

            axios
                .get(`${API}/chi-tiet-hop-dong/data`, {
                    params,
                    headers: this.authHeader(),
                })
                .then((res) => {
                    this.list_contracts = res.data.status ? res.data.data || [] : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách hợp đồng.');
                });
        },

        openCreateModal() {
            this.resetCreateForm();
        },

        resetCreateForm() {
            const defaultType = this.list_hop_dong[0]?.id || '';
            this.create_hop_dong = {
                id_nhan_vien: this.create_hop_dong.id_nhan_vien || '',
                ho_va_ten: '',
                email: '',
                so_dien_thoai: '',
                dia_chi: '',
                id_loai_hop_dong: defaultType,
                ngay_ky: new Date().toISOString().slice(0, 10),
                ngay_bat_dau: '',
                ngay_ket_thuc: '',
                noi_dung: '',
            };
            if (this.create_hop_dong.id_nhan_vien) {
                this.updateCreateEmployee();
            }
            this.updateCreatePreview();
        },

        updateCreateEmployee() {
            const employee = this.list_nhan_vien.find((nv) => nv.id == this.create_hop_dong.id_nhan_vien);
            if (employee) {
                this.create_hop_dong.ho_va_ten = employee.ho_va_ten || '';
                this.create_hop_dong.email = employee.email || '';
                this.create_hop_dong.so_dien_thoai = employee.so_dien_thoai || '';
                this.create_hop_dong.dia_chi = employee.dia_chi || '';
            } else {
                this.create_hop_dong.ho_va_ten = '';
                this.create_hop_dong.email = '';
                this.create_hop_dong.so_dien_thoai = '';
                this.create_hop_dong.dia_chi = '';
            }
            this.updateCreatePreview();
        },

        updateCreatePreview() {
            const template = this.list_hop_dong.find((loai) => loai.id == this.create_hop_dong.id_loai_hop_dong)?.noi_dung || '';
            this.create_hop_dong.noi_dung = this.renderContractTemplate(template);
        },

        renderContractTemplate(content) {
            const employee = this.create_hop_dong;
            return content
                .replaceAll('{{ho_va_ten}}', employee.ho_va_ten || '')
                .replaceAll('{{email}}', employee.email || '')
                .replaceAll('{{so_dien_thoai}}', employee.so_dien_thoai || '')
                .replaceAll('{{dia_chi}}', employee.dia_chi || '');
        },

        createContract() {
            if (!this.create_hop_dong.id_nhan_vien || !this.create_hop_dong.id_loai_hop_dong) {
                this.$toast.error('Vui lòng chọn nhân viên và loại hợp đồng.');
                return;
            }

            axios
                .post(`${API}/chi-tiet-hop-dong/create`, this.create_hop_dong, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || 'Tạo hợp đồng thành công.');
                        this.resetCreateForm();
                        this.loadContracts();
                        const modal = window.bootstrap?.Modal.getInstance(document.getElementById('createContractModal'));
                        if (modal) modal.hide();
                    } else {
                        this.$toast.error(res.data.message || 'Tạo hợp đồng thất bại.');
                    }
                })
                .catch((err) => {
                    this.handleValidationError(err);
                });
        },

        setEditContract(contract) {
            this.edit_contract = {
                ...contract,
                ngay_ky: this.toDateInput(contract.ngay_ky),
                ngay_bat_dau: this.toDateInput(contract.ngay_bat_dau),
                ngay_ket_thuc: this.toDateInput(contract.ngay_ket_thuc),
            };
        },

        updateContract() {
            axios
                .post(`${API}/chi-tiet-hop-dong/${this.edit_contract.id}/update`, this.edit_contract, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || 'Đã lưu hợp đồng.');
                        this.edit_contract = {};
                        this.loadContracts();
                        const modal = window.bootstrap?.Modal.getInstance(document.getElementById('editContractModal'));
                        if (modal) modal.hide();
                    } else {
                        this.$toast.error(res.data.message || 'Lưu hợp đồng thất bại.');
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        downloadContractPdf(contract) {
            axios
                .get(`${API}/chi-tiet-hop-dong/${contract.id}/pdf`, {
                    responseType: 'blob',
                    headers: this.authHeader(),
                })
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res.data], { type: 'application/pdf' }));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', `hop_dong_${contract.id}.pdf`);
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch(() => this.$toast.error('Xuất PDF thất bại.'));
        },

        getEmployeeName(id) {
            return this.list_nhan_vien.find((nv) => nv.id == id)?.ho_va_ten || '-';
        },

        getContractTypeName(id) {
            return this.list_hop_dong.find((loai) => loai.id == id)?.ten_hop_dong || '-';
        },

        contractStatusText(status) {
            return {
                nhap: 'Bản nháp',
                cho_nhan_vien_ky: 'Chờ nhân viên ký',
                hoan_tat: 'Hoàn tất',
            }[status] || 'Bản nháp';
        },

        formatDate(value) {
            if (!value) return '-';
            const date = new Date(value);
            if (isNaN(date)) return value;
            return date.toLocaleDateString('vi-VN');
        },

        toDateInput(value) {
            if (!value) return '';
            return String(value).split('T')[0];
        },

        handleValidationError(err) {
            const data = err?.response?.data;
            if (data?.errors) {
                Object.values(data.errors).forEach((msgs) => {
                    msgs.forEach((msg) => this.$toast.error(msg));
                });
            } else if (data?.message) {
                this.$toast.error(data.message);
            } else {
                this.$toast.error('Đã có lỗi xảy ra, vui lòng thử lại.');
            }
        },
    },
};
</script>

<style scoped>
.contract-preview {
    min-height: 160px;
    white-space: pre-wrap;
}
</style>
