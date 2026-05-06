<template>
    <div class="ho-so-wrapper">
        <!-- ── HEADER ── -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar-large">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ userInfo?.ho_va_ten }}</h4>
                        <p class="text-muted mb-0">{{ userInfo?.email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs nav-fill">
                    <li class="nav-item">
                        <button class="nav-link" :class="{ active: activeTab === 'info' }" @click="activeTab = 'info'">
                            Thông tin
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" :class="{ active: activeTab === 'contract' }"
                            @click="activeTab = 'contract'">
                            Hợp đồng
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" :class="{ active: activeTab === 'password' }"
                            @click="activeTab = 'password'">
                            Đổi mật khẩu
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <!-- Thông tin -->
            <div v-show="activeTab === 'info'" class="row g-3">
                <!-- Cột 1: Thông tin cơ bản -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <div class="header-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                Thông Tin Cơ Bản
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Họ và tên -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Họ và tên</label>
                                    <input v-model="formData.ho_va_ten" type="text" class="form-control"
                                        :readonly="!isEditing" :class="{ 'is-invalid': errors.ho_va_ten }">
                                    <div class="invalid-feedback">{{ errors.ho_va_ten }}</div>
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" :value="userInfo?.email" class="form-control" readonly>
                                    <small class="text-muted">Email không thể thay đổi</small>
                                </div>

                                <!-- Số điện thoại -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Số điện thoại</label>
                                    <input v-model="formData.so_dien_thoai" type="text" class="form-control"
                                        :readonly="!isEditing" placeholder="Nhập số điện thoại"
                                        :class="{ 'is-invalid': errors.so_dien_thoai }">
                                    <div class="invalid-feedback">{{ errors.so_dien_thoai }}</div>
                                </div>

                                <!-- Ngày sinh -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Ngày sinh</label>
                                    <input v-model="formData.ngay_sinh" type="date" class="form-control"
                                        :readonly="!isEditing" :class="{ 'is-invalid': errors.ngay_sinh }">
                                    <div class="invalid-feedback">{{ errors.ngay_sinh }}</div>
                                </div>

                                <!-- Địa chỉ -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Địa chỉ</label>
                                    <textarea v-model="formData.dia_chi" class="form-control" :readonly="!isEditing"
                                        placeholder="Nhập địa chỉ" rows="2"
                                        :class="{ 'is-invalid': errors.dia_chi }"></textarea>
                                    <div class="invalid-feedback">{{ errors.dia_chi }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột 2: Thông tin công ty -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <div class="header-icon bg-info bg-opacity-10 text-info">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                Thông Tin Công Ty
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Phòng ban -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Phòng ban</label>
                                    <input type="text" :value="userInfo?.phong_ban?.ten_phong_ban || 'N/A'"
                                        class="form-control" readonly>
                                    <small class="text-muted">Phòng ban được quản lý bởi HR</small>
                                </div>

                                <!-- Chức vụ -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Chức vụ</label>
                                    <input type="text" :value="userInfo?.chuc_vu?.ten_chuc_vu || 'N/A'"
                                        class="form-control" readonly>
                                    <small class="text-muted">Chức vụ được quản lý bởi HR</small>
                                </div>

                                <!-- Lương cơ bản -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Lương cơ bản</label>
                                    <div class="input-group">
                                        <input type="text" :value="formatCurrency(userInfo?.luong_co_ban || 0)"
                                            class="form-control" readonly>
                                        <span class="input-group-text">VND</span>
                                    </div>
                                    <small class="text-muted">Mức lương được quản lý bởi HR</small>
                                </div>

                                <!-- Trạng thái -->
                                <!-- <div class="col-12">
                                    <label class="form-label fw-semibold">Trạng thái</label>
                                    <div class="badge-container">
                                        <span :class="['badge', employeeStatusBadge]">
                                            <i
                                                :class="['fa-solid', employeeStatusIsActive ? 'fa-check-circle' : 'fa-times-circle', 'me-1']"></i>
                                            {{ employeeStatusText }}
                                        </span>
                                    </div>
                                </div> -->

                                <!-- Ngày tuyển dụng -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Ngày tuyển dụng</label>
                                    <input type="text" :value="formatDate(userInfo?.created_at)" class="form-control"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liên hệ & Hỗ trợ -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <div class="header-icon bg-success bg-opacity-10 text-success">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                Liên Hệ & Hỗ Trợ
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 py-2">
                                    <strong><i class="fa-solid fa-envelope me-1 text-primary"></i>Email:</strong>
                                    {{ userInfo?.email }}
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <strong><i class="fa-solid fa-phone me-1 text-primary"></i>Điện thoại:</strong>
                                    {{ userInfo?.so_dien_thoai || 'Chưa cập nhật' }}
                                </div>
                                <div class="list-group-item px-0 py-2">
                                    <strong><i class="fa-solid fa-map-pin me-1 text-primary"></i>Địa chỉ:</strong>
                                    {{ userInfo?.dia_chi || 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <div class="alert alert-light mt-3 mb-0 p-2" style="font-size: .875rem">
                                <i class="fa-solid fa-question-circle me-1 text-info"></i>
                                Cần hỗ trợ? Vui lòng liên hệ phòng HR
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NỘI DUNG THAY ĐỔI -->
                <div v-if="isEditing" class="col-12">
                    <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>Bạn đang trong chế độ chỉnh sửa. Hãy lưu thay đổi hoặc hủy bỏ.</span>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button v-if="!isEditing" @click="startEdit" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i>Chỉnh Sửa Hồ Sơ
                        </button>
                        <button v-if="isEditing" @click="saveChanges" class="btn btn-success" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-save me-1"></i>Lưu Thay Đổi
                        </button>
                        <button v-if="isEditing" @click="cancelEdit" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-times me-1"></i>Hủy Bỏ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hợp đồng -->
            <div v-show="activeTab === 'contract'" class="row g-3">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <div class="header-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fa-solid fa-file-contract"></i>
                                </div>
                                Hợp Đồng Lao Động
                            </h6>
                        </div>
                        <div class="card-body">
                            <div v-if="contracts.length" class="contract-list">
                                <div v-for="contract in contracts" :key="contract.id" class="contract-item">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <strong>{{ contract.loai_hop_dong?.ten_hop_dong || 'Hợp đồng lao động'
                                                }}</strong>
                                            <div class="text-muted small">
                                                {{ formatDate(contract.ngay_bat_dau) }} - {{
                                                formatDate(contract.ngay_ket_thuc) }}
                                            </div>
                                            <span class="badge bg-secondary mt-1">{{
                                                contractStatusText(contract.trang_thai) }}</span>
                                        </div>
                                        <span class="badge bg-info">{{ formatDate(contract.ngay_ky) }}</span>
                                    </div>
                                    <div v-if="contract.noi_dung" class="contract-content mt-2"
                                        v-html="contract.noi_dung"></div>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <button class="btn btn-sm btn-outline-danger"
                                            @click="downloadContractPdf(contract)">
                                            <i class="fa-solid fa-file-pdf me-1"></i>PDF
                                        </button>
                                        <button v-if="contract.trang_thai === 'cho_nhan_vien_ky'"
                                            class="btn btn-sm btn-success" @click="signContract(contract)">
                                            <i class="fa-solid fa-signature me-1"></i>Ký Xác Nhận
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="alert alert-info mb-0">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Chưa có thông tin hợp đồng lao động
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đổi mật khẩu -->
            <div v-show="activeTab === 'password'" class="row g-3">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <div class="header-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                Đổi Mật Khẩu
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mật khẩu hiện tại</label>
                                    <input v-model="passwordForm.current_password" type="password" class="form-control">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Mật khẩu mới</label>
                                    <input v-model="passwordForm.password" type="password" class="form-control">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                                    <input v-model="passwordForm.password_confirmation" type="password"
                                        class="form-control">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-danger" :disabled="passwordLoading" @click="changePassword">
                                        <span v-if="passwordLoading"
                                            class="spinner-border spinner-border-sm me-1"></span>
                                        <i v-else class="fa-solid fa-key me-1"></i>Đổi Mật Khẩu
                                    </button>
                                </div>
                            </div>
                        </div>
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

export default {
    name: 'StaffHoSo',
    data() {
        return {
            userInfo: null,
            activeTab: 'info',
            formData: {
                ho_va_ten: '',
                so_dien_thoai: '',
                ngay_sinh: '',
                dia_chi: '',
            },
            passwordForm: {
                current_password: '',
                password: '',
                password_confirmation: '',
            },
            contracts: [],
            signingContractId: null,
            isEditing: false,
            loading: false,
            passwordLoading: false,
            errors: {},
        };
    },
    mounted() {
        this.loadUserInfo();
        this.loadContracts();
    },
    computed: {
        employeeStatusIsActive() {
            return [1, '1', true, 'true'].includes(this.userInfo?.tinh_trang);
        },
        employeeStatusText() {
            return this.employeeStatusIsActive ? 'Đang làm việc' : 'Ngừng làm việc';
        },
        employeeStatusBadge() {
            return this.employeeStatusIsActive ? 'bg-success' : 'bg-danger';
        },
    },
    methods: {
        authHeader() {
            return {
                Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien'),
            };
        },
        async loadUserInfo() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/admin/user-info', {
                    headers: this.authHeader(),
                });

                if (response.data.status) {
                    this.userInfo = response.data.data || response.data.user;
                    this.contracts = this.userInfo?.hop_dongs || this.contracts;
                    this.initFormData();
                }
            } catch (error) {
                console.error('Lỗi khi tải thông tin:', error);
                toaster.error('Không thể tải thông tin hồ sơ');
            }
        },
        initFormData() {
            this.formData = {
                ho_va_ten: this.userInfo.ho_va_ten,
                so_dien_thoai: this.userInfo.so_dien_thoai || '',
                ngay_sinh: this.userInfo.ngay_sinh || '',
                dia_chi: this.userInfo.dia_chi || '',
            };
        },
        async loadContracts() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/nhan-vien/hop-dong/xem', {
                    headers: this.authHeader(),
                });
                if (response.data.status) {
                    this.contracts = response.data.data || [];
                }
            } catch (error) {
                console.error('Lỗi khi tải hợp đồng:', error);
            }
        },
        startEdit() {
            this.isEditing = true;
        },
        cancelEdit() {
            this.isEditing = false;
            this.errors = {};
            this.initFormData();
        },
        async saveChanges() {
            this.errors = {};
            this.loading = true;

            try {
                const response = await axios.post(
                    'http://127.0.0.1:8000/api/nhan-vien/update-profile',
                    this.formData,
                    {
                        headers: this.authHeader(),
                    }
                );

                if (response.data.status) {
                    toaster.success('Cập nhật hồ sơ thành công!');
                    this.isEditing = false;
                    await this.loadUserInfo();
                } else {
                    toaster.error(response.data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                }
                toaster.error(error.response?.data?.message || 'Lỗi cập nhật hồ sơ');
            } finally {
                this.loading = false;
            }
        },
        async changePassword() {
            if (!this.passwordForm.current_password || !this.passwordForm.password || !this.passwordForm.password_confirmation) {
                toaster.error('Vui lòng nhập đầy đủ thông tin đổi mật khẩu');
                return;
            }

            this.passwordLoading = true;
            try {
                const response = await axios.post(
                    'http://127.0.0.1:8000/api/nhan-vien/change-password',
                    this.passwordForm,
                    { headers: this.authHeader() }
                );

                if (response.data.status) {
                    toaster.success(response.data.message || 'Đổi mật khẩu thành công!');
                    this.passwordForm = {
                        current_password: '',
                        password: '',
                        password_confirmation: '',
                    };
                } else {
                    toaster.error(response.data.message || 'Đổi mật khẩu thất bại');
                }
            } catch (error) {
                toaster.error(error.response?.data?.message || 'Lỗi đổi mật khẩu');
            } finally {
                this.passwordLoading = false;
            }
        },
        async signContract(contract) {
            const signature = window.prompt('Nhập họ tên để ký xác nhận hợp đồng:', this.userInfo?.ho_va_ten || '');
            if (!signature) return;

            this.signingContractId = contract.id;
            try {
                const response = await axios.post(
                    `http://127.0.0.1:8000/api/nhan-vien/hop-dong/${contract.id}/ky`,
                    { chu_ky_nhan_vien: signature },
                    { headers: this.authHeader() }
                );
                if (response.data.status) {
                    toaster.success(response.data.message || 'Đã ký xác nhận hợp đồng.');
                    await this.loadContracts();
                } else {
                    toaster.error(response.data.message || 'Ký hợp đồng thất bại.');
                }
            } catch (error) {
                toaster.error(error.response?.data?.message || 'Lỗi ký hợp đồng');
            } finally {
                this.signingContractId = null;
            }
        },
        downloadContractPdf(contract) {
            axios
                .get(`http://127.0.0.1:8000/api/nhan-vien/hop-dong/${contract.id}/pdf`, {
                    headers: this.authHeader(),
                    responseType: 'blob',
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
                .catch(() => toaster.error('Không thể tải PDF hợp đồng.'));
        },
        contractStatusText(status) {
            return {
                nhap: 'Bản nháp',
                cho_nhan_vien_ky: 'Chờ ký xác nhận',
                hoan_tat: 'Đã hoàn tất',
            }[status] || 'Bản nháp';
        },
        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('vi-VN');
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN').format(value);
        },
    },
};
</script>

<style scoped>
.ho-so-wrapper {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    flex-shrink: 0;
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.card {
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.form-control:readonly,
.form-control[readonly] {
    background-color: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}

textarea:readonly {
    background-color: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}

.badge-container {
    display: inline-block;
}

.list-group-item {
    background: transparent;
    border: none;
    border-bottom: 1px solid #f0f0f0;
}

.list-group-item:last-child {
    border-bottom: none;
}

.contract-list {
    display: grid;
    gap: 0.75rem;
}

.contract-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.85rem;
}

.contract-content {
    color: #495057;
    font-size: 0.9rem;
    max-height: 140px;
    overflow: auto;
}

.alert {
    border-radius: 8px;
    font-size: 0.9rem;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.btn:disabled {
    opacity: 0.6;
}
</style>
