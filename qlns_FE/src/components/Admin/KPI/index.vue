<template>
    <div class="row g-3">
        <!-- Form thêm/sửa -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom fw-bold">
                    <i class="fa-solid fa-bullseye me-2 text-primary"></i>
                    {{ formMode === 'add' ? 'Tạo Tiêu Chí KPI' : 'Chỉnh Sửa Tiêu Chí' }}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên tiêu chí <span class="text-danger">*</span></label>
                        <input v-model="form.ten_tieu_chi" type="text" class="form-control"
                            placeholder="VD: Tỷ lệ hoàn thành task">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mô tả</label>
                        <textarea v-model="form.mo_ta" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Mục tiêu <span class="text-danger">*</span></label>
                            <input v-model="form.muc_tieu" type="number" min="0" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Đơn vị</label>
                            <input v-model="form.don_vi" type="text" class="form-control" placeholder="%">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Trọng số (%) <span class="text-danger">*</span>
                        </label>
                        <input v-model="form.trong_so" type="number" min="0" max="100" class="form-control">
                        <div class="form-text">Tổng trọng số tất cả tiêu chí nên = 100%</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button @click="save()" class="btn btn-primary flex-fill" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                            {{ formMode === 'add' ? 'Tạo Tiêu Chí' : 'Lưu' }}
                        </button>
                        <button v-if="formMode === 'edit'" @click="resetForm()" class="btn btn-outline-secondary">
                            Hủy
                        </button>
                    </div>
                </div>
            </div>
 
            <!-- Tổng trọng số -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold" style="font-size:.875rem">Tổng trọng số</span>
                        <span class="fw-bold" :class="tongTrongSo === 100 ? 'text-success' : 'text-warning'">
                            {{ tongTrongSo }}%
                        </span>
                    </div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar"
                            :class="tongTrongSo > 100 ? 'bg-danger' : tongTrongSo === 100 ? 'bg-success' : 'bg-warning'"
                            :style="{width: Math.min(tongTrongSo, 100) + '%'}"></div>
                    </div>
                    <div class="mt-1" style="font-size:.72rem;color:#9ca3af">
                        {{ tongTrongSo === 100 ? '✓ Hợp lệ' : tongTrongSo < 100 ? 'Còn thiếu ' + (100 - tongTrongSo) + '%' : 'Vượt quá 100%' }}
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Danh sách -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="fa-solid fa-list-check me-2 text-primary"></i>
                        Danh Sách Tiêu Chí KPI
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr style="font-size:.78rem;font-weight:600;color:#6b7280;text-transform:uppercase">
                                <th class="ps-3">#</th>
                                <th>Tiêu Chí</th>
                                <th class="text-center">Mục Tiêu</th>
                                <th class="text-center">Trọng Số</th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(v, k) in list" :key="v.id" class="align-middle" style="font-size:.875rem">
                                <td class="ps-3 text-muted">{{ k + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ v.ten_tieu_chi }}</div>
                                    <div class="text-muted" style="font-size:.72rem">{{ v.mo_ta }}</div>
                                </td>
                                <td class="text-center fw-bold text-primary">{{ v.muc_tieu }} {{ v.don_vi }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-75 fw-bold">{{ v.trong_so }}%</span>
                                </td>
                                <td class="text-center">
                                    <span :class="v.tinh_trang ? 'bg-success' : 'bg-secondary'" class="badge">
                                        {{ v.tinh_trang ? 'Đang dùng' : 'Tắt' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button @click="editItem(v)" class="btn btn-sm btn-primary me-1">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button @click="xoa(v)" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!list.length">
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Chưa có tiêu chí KPI nào
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>
import axios from 'axios';
const API = 'http://127.0.0.1:8000/api/admin/kpi';
 
export default {
    data() {
        return {
            list: [], loading: false, formMode: 'add',
            form: { id: null, ten_tieu_chi: '', mo_ta: '', muc_tieu: 100, don_vi: '%', trong_so: 0 },
        };
    },
    computed: {
        tongTrongSo() { return this.list.filter(v => v.tinh_trang).reduce((s, v) => s + parseFloat(v.trong_so || 0), 0); },
    },
    mounted() { this.load(); },
    methods: {
        authHeader() { return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') }; },
        load() {
            axios.get(`${API}/tieu-chi`, { headers: this.authHeader() })
                .then(r => { if (r.data.status) this.list = r.data.data; });
        },
        resetForm() {
            this.formMode = 'add';
            this.form = { id: null, ten_tieu_chi: '', mo_ta: '', muc_tieu: 100, don_vi: '%', trong_so: 0 };
        },
        editItem(v) {
            this.formMode = 'edit';
            this.form = { id: v.id, ten_tieu_chi: v.ten_tieu_chi, mo_ta: v.mo_ta || '', muc_tieu: v.muc_tieu, don_vi: v.don_vi, trong_so: v.trong_so };
        },
        save() {
            if (!this.form.ten_tieu_chi || !this.form.trong_so) { this.$toast.error('Vui lòng điền đầy đủ.'); return; }
            this.loading = true;
            const url = this.formMode === 'add' ? `${API}/tieu-chi/create` : `${API}/tieu-chi/update`;
            axios.post(url, this.form, { headers: this.authHeader() })
                .then(r => {
                    if (r.data.status) { this.$toast.success(r.data.message); this.resetForm(); this.load(); }
                    else this.$toast.error(r.data.message);
                })
                .catch(err => { const d = err?.response?.data; if (d?.errors) Object.values(d.errors).forEach(ms => ms.forEach(m => this.$toast.error(m))); })
                .finally(() => (this.loading = false));
        },
        xoa(v) {
            if (!confirm(`Xóa tiêu chí "${v.ten_tieu_chi}"?`)) return;
            axios.post(`${API}/tieu-chi/delete`, { id: v.id }, { headers: this.authHeader() })
                .then(r => { if (r.data.status) { this.$toast.success(r.data.message); this.load(); } else this.$toast.error(r.data.message); });
        },
    },
};
</script>
 