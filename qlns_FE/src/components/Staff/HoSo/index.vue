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

        <!-- ── THÔNG TIN CÁ NHÂN ── -->
        <div class="row g-3">
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
                                <input type="text" :value="userInfo?.chuc_vu?.ten_chuc_vu || 'N/A'" class="form-control"
                                    readonly>
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
                            <div class="col-12">
                                <label class="form-label fw-semibold">Trạng thái</label>
                                <div class="badge-container">
                                    <span v-if="userInfo?.tinh_trang === 1" class="badge bg-success">
                                        <i class="fa-solid fa-check-circle me-1"></i>Đang làm việc
                                    </span>
                                    <span v-else class="badge bg-danger">
                                        <i class="fa-solid fa-times-circle me-1"></i>Ngừng làm việc
                                    </span>
                                </div>
                            </div>

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
        </div>

        <!-- ── NỘI QUY & CHÍNH SÁCH ── -->
        <div class="row g-3 mt-2">
            <div class="col-lg-6">
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
                        <div v-if="userInfo?.hop_dong_lao_dong" class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2">
                                <strong>Loại hợp đồng:</strong>
                                <span class="badge bg-info">{{ userInfo.hop_dong_lao_dong.ten_loai }}</span>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <strong>Ngày ký:</strong>
                                {{ formatDate(userInfo.hop_dong_lao_dong.ngay_ky) }}
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <strong>Ngày hết hạn:</strong>
                                {{ formatDate(userInfo.hop_dong_lao_dong.ngay_het_han) }}
                            </div>
                        </div>
                        <div v-else class="alert alert-info mb-0">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Chưa có thông tin hợp đồng lao động
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
        </div>

        <!-- ── NỘI DUNG THAY ĐỔI ── -->
        <div v-if="isEditing" class="row g-3 mt-2">
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
                    <i class="fa-solid fa-info-circle"></i>
                    <span>Bạn đang trong chế độ chỉnh sửa. Hãy lưu thay đổi hoặc hủy bỏ.</span>
                </div>
            </div>
        </div>

        <!-- ── ACTION BUTTONS ── -->
        <div class="row g-3 mt-4">
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
            formData: {
                ho_va_ten: '',
                so_dien_thoai: '',
                ngay_sinh: '',
                dia_chi: '',
            },
            isEditing: false,
            loading: false,
            errors: {},
        };
    },
    mounted() {
        this.loadUserInfo();
    },
    methods: {
        async loadUserInfo() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/admin/user-info', {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien'),
                    },
                });

                if (response.data.status) {
                    this.userInfo = response.data.user;
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
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien'),
                        },
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
