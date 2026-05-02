<template>
    <div class="row g-3">
        <!-- Form thêm -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom fw-bold">
                    <i class="fa-solid fa-scale-balanced me-2 text-primary"></i>Thêm Thưởng / Phạt
                </div>
                <div class="card-body">
                    <!-- Chọn loại -->
                    <div class="d-flex gap-2 mb-3">
                        <label class="loai-btn" :class="{active: form.loai==='thuong'}"
                            @click="form.loai='thuong'">
                            <i class="fa-solid fa-trophy text-warning me-1"></i>Thưởng
                        </label>
                        <label class="loai-btn" :class="{active: form.loai==='phat', danger: form.loai==='phat'}"
                            @click="form.loai='phat'">
                            <i class="fa-solid fa-gavel text-danger me-1"></i>Phạt
                        </label>
                    </div>
 
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nhân viên <span class="text-danger">*</span></label>
                        <select v-model="form.id_nhan_vien" class="form-select">
                            <option value="">-- Chọn nhân viên --</option>
                            <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">{{ nv.ho_va_ten }}</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tháng</label>
                            <select v-model="form.thang" class="form-select">
                                <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Năm</label>
                            <input v-model="form.nam" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Số tiền (VNĐ) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"
                                :class="form.loai==='thuong' ? 'text-success' : 'text-danger'">
                                {{ form.loai==='thuong' ? '+' : '−' }}
                            </span>
                            <input v-model="form.so_tien" type="number" min="1000" step="50000" class="form-control"
                                placeholder="0">
                            <span class="input-group-text">₫</span>
                        </div>
                        <div class="form-text">{{ formatVND(form.so_tien) }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Lý do <span class="text-danger">*</span></label>
                        <textarea v-model="form.ly_do" class="form-control" rows="3"
                            :placeholder="form.loai==='thuong' ? 'VD: Hoàn thành dự án xuất sắc...' : 'VD: Đi trễ 3 lần trong tháng...'">
                        </textarea>
                    </div>
                    <button @click="save()" class="btn w-100 fw-bold"
                        :class="form.loai==='thuong' ? 'btn-warning' : 'btn-danger'"
                        :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                        <i v-else :class="form.loai==='thuong' ? 'fa-solid fa-plus' : 'fa-solid fa-minus'" class="me-1"></i>
                        {{ form.loai==='thuong' ? 'Thêm Thưởng' : 'Thêm Phạt' }}
                    </button>
                </div>
            </div>
        </div>
 
        <!-- Danh sách -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="fa-solid fa-list me-2 text-primary"></i>
                        Danh Sách Thưởng / Phạt
                    </h6>
                    <div class="d-flex gap-2">
                        <select v-model="filter.thang" class="form-select form-select-sm" style="width:100px" @change="load()">
                            <option v-for="i in 12" :key="i" :value="i">T{{ i }}</option>
                        </select>
                        <input v-model="filter.nam" type="number" class="form-control form-control-sm" style="width:80px" @change="load()">
                        <select v-model="filter.loai" class="form-select form-select-sm" style="width:110px" @change="load()">
                            <option value="">Tất cả</option>
                            <option value="thuong">Thưởng</option>
                            <option value="phat">Phạt</option>
                        </select>
                    </div>
                </div>
 
                <!-- Tổng kết -->
                <div class="d-flex gap-0 border-bottom">
                    <div class="p-3 text-center flex-fill border-end">
                        <div class="fw-bold text-success fs-5">+{{ formatVND(tongThuong) }}</div>
                        <div style="font-size:.72rem;color:#6b7280">Tổng thưởng</div>
                    </div>
                    <div class="p-3 text-center flex-fill border-end">
                        <div class="fw-bold text-danger fs-5">-{{ formatVND(tongPhat) }}</div>
                        <div style="font-size:.72rem;color:#6b7280">Tổng phạt</div>
                    </div>
                    <div class="p-3 text-center flex-fill">
                        <div class="fw-bold fs-5" :class="(tongThuong-tongPhat) >= 0 ? 'text-primary' : 'text-danger'">
                            {{ (tongThuong - tongPhat) >= 0 ? '+' : '' }}{{ formatVND(tongThuong - tongPhat) }}
                        </div>
                        <div style="font-size:.72rem;color:#6b7280">Chênh lệch</div>
                    </div>
                </div>
 
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr style="font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase">
                                <th class="ps-3">Nhân Viên</th>
                                <th class="text-center">Loại</th>
                                <th class="text-end">Số Tiền</th>
                                <th>Lý Do</th>
                                <th class="text-center">Ngày Tạo</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="v in list" :key="v.id" class="align-middle" style="font-size:.875rem">
                                <td class="ps-3 fw-semibold">{{ v.nhan_vien?.ho_va_ten }}</td>
                                <td class="text-center">
                                    <span :class="v.loai==='thuong' ? 'bg-warning text-dark' : 'bg-danger'" class="badge">
                                        <i :class="v.loai==='thuong' ? 'fa-solid fa-trophy' : 'fa-solid fa-gavel'" class="me-1"></i>
                                        {{ v.loai==='thuong' ? 'Thưởng' : 'Phạt' }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold"
                                    :class="v.loai==='thuong' ? 'text-success' : 'text-danger'">
                                    {{ v.loai==='thuong' ? '+' : '−' }}{{ formatVND(v.so_tien) }}
                                </td>
                                <td style="max-width:200px">
                                    <span class="text-truncate d-block" :title="v.ly_do">{{ v.ly_do }}</span>
                                </td>
                                <td class="text-center" style="font-size:.78rem;color:#6b7280">
                                    {{ formatDate(v.created_at) }}
                                </td>
                                <td class="text-center">
                                    <button @click="xoa(v)" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!list.length">
                                <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu</td>
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
const API    = 'http://127.0.0.1:8000/api/admin/thuong-phat';
const API_NV = 'http://127.0.0.1:8000/api/admin/nhan-vien';
 
export default {
    data() {
        return {
            list: [], list_nhan_vien: [], loading: false,
            form: { id_nhan_vien:'', thang: new Date().getMonth()+1, nam: new Date().getFullYear(), loai:'thuong', so_tien:'', ly_do:'' },
            filter: { thang: new Date().getMonth()+1, nam: new Date().getFullYear(), loai:'' },
        };
    },
    computed: {
        tongThuong() { return this.list.filter(v=>v.loai==='thuong').reduce((s,v)=>s+parseFloat(v.so_tien||0),0); },
        tongPhat()   { return this.list.filter(v=>v.loai==='phat').reduce((s,v)=>s+parseFloat(v.so_tien||0),0); },
    },
    mounted() { this.load(); this.loadNV(); },
    methods: {
        authHeader() { return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') }; },
        load() {
            axios.get(`${API}/data`, { params: this.filter, headers: this.authHeader() })
                .then(r => { if (r.data.status) this.list = r.data.data; });
        },
        loadNV() {
            axios.get(`${API_NV}/data`, { headers: this.authHeader() })
                .then(r => { this.list_nhan_vien = Array.isArray(r.data) ? r.data : (r.data.data||[]); });
        },
        save() {
            if (!this.form.id_nhan_vien||!this.form.so_tien||!this.form.ly_do) { this.$toast.error('Điền đầy đủ thông tin.'); return; }
            this.loading = true;
            axios.post(`${API}/create`, this.form, { headers: this.authHeader() })
                .then(r => {
                    if (r.data.status) {
                        this.$toast.success(r.data.message);
                        this.form = { id_nhan_vien:'', thang:this.filter.thang, nam:this.filter.nam, loai:'thuong', so_tien:'', ly_do:'' };
                        this.load();
                    } else this.$toast.error(r.data.message);
                })
                .catch(err => { const d=err?.response?.data; if(d?.errors) Object.values(d.errors).forEach(ms=>ms.forEach(m=>this.$toast.error(m))); })
                .finally(()=>(this.loading=false));
        },
        xoa(v) {
            if (!confirm('Xóa bản ghi này?')) return;
            axios.post(`${API}/delete`, { id:v.id }, { headers: this.authHeader() })
                .then(r => { if (r.data.status) { this.$toast.success(r.data.message); this.load(); } });
        },
        formatVND(n) { return new Intl.NumberFormat('vi-VN').format(n||0) + ' ₫'; },
        formatDate(d) { return d ? new Date(d).toLocaleDateString('vi-VN') : '—'; },
    },
};
</script>
 
<style scoped>
.loai-btn { flex:1;text-align:center;padding:8px 12px;border-radius:8px;border:2px solid #e5e7eb;cursor:pointer;font-weight:600;font-size:.875rem;transition:all .2s;background:#f9fafb; }
.loai-btn.active { border-color:#d97706;background:#fffbeb;color:#b45309; }
.loai-btn.active.danger { border-color:#dc2626;background:#fef2f2;color:#b91c1c; }
</style>
 