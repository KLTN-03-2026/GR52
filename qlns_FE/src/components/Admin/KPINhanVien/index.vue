

<template>
    <div class="row g-3">
        <!-- Bộ lọc tháng -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 d-flex align-items-center gap-3 flex-wrap">
                    <span class="fw-semibold" style="font-size:.875rem">Xem KPI tháng:</span>
                    <select v-model="filter.thang" class="form-select form-select-sm w-auto" @change="load()">
                        <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                    </select>
                    <input v-model="filter.nam" type="number" class="form-control form-control-sm" style="width:90px" @change="load()">
                    <select v-model="filter.id_nhan_vien" class="form-select form-select-sm" style="width:200px" @change="load()">
                        <option value="">Tất cả nhân viên</option>
                        <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">{{ nv.ho_va_ten }}</option>
                    </select>
                    <button @click="openGan()" data-bs-toggle="modal" data-bs-target="#modalGan"
                        class="btn btn-primary btn-sm ms-auto">
                        <i class="fa-solid fa-plus me-1"></i>Gán KPI
                    </button>
                </div>
            </div>
        </div>
 
        <!-- Bảng KPI -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="fa-solid fa-chart-line me-2 text-primary"></i>
                        KPI Nhân Viên — Tháng {{ filter.thang }}/{{ filter.nam }}
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr style="font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase">
                                <th class="ps-3">Nhân Viên</th>
                                <th>Tiêu Chí</th>
                                <th class="text-center">Trọng Số</th>
                                <th class="text-center">Mục Tiêu</th>
                                <th class="text-center">Kết Quả</th>
                                <th class="text-center">% Hoàn Thành</th>
                                <th class="text-center">Điểm KPI</th>
                                <th class="text-center">Xếp Loại</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="v in list" :key="v.id" class="align-middle" style="font-size:.875rem">
                                <td class="ps-3">
                                    <div class="fw-semibold">{{ v.nhan_vien?.ho_va_ten }}</div>
                                </td>
                                <td>{{ v.tieu_chi?.ten_tieu_chi }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info bg-opacity-75">{{ v.tieu_chi?.trong_so }}%</span>
                                </td>
                                <td class="text-center">{{ v.muc_tieu }} {{ v.tieu_chi?.don_vi }}</td>
                                <td class="text-center">
                                    <span v-if="v.ket_qua_thuc_te !== null" class="fw-bold">
                                        {{ v.ket_qua_thuc_te }} {{ v.tieu_chi?.don_vi }}
                                    </span>
                                    <span v-else class="text-muted">Chưa nhập</span>
                                </td>
                                <td class="text-center">
                                    <template v-if="v.phan_tram_hoan_thanh !== null">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px">
                                                <div class="progress-bar"
                                                    :class="progressClass(v.phan_tram_hoan_thanh)"
                                                    :style="{width: Math.min(v.phan_tram_hoan_thanh,100)+'%'}"></div>
                                            </div>
                                            <span style="font-size:.78rem;font-weight:600;min-width:38px">
                                                {{ v.phan_tram_hoan_thanh }}%
                                            </span>
                                        </div>
                                    </template>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td class="text-center fw-bold text-primary">{{ v.diem_kpi ?? '—' }}</td>
                                <td class="text-center">
                                    <span v-if="v.xep_loai" :class="xepLoaiBadge(v.xep_loai)" class="badge fs-6 px-2">
                                        {{ v.xep_loai }}
                                    </span>
                                    <span v-else class="text-muted">—</span>
                                </td>
                                <td class="text-center text-nowrap">
                                    <button @click="openKetQua(v)"
                                        data-bs-toggle="modal" data-bs-target="#modalKetQua"
                                        class="btn btn-sm btn-success me-1" title="Nhập kết quả">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button @click="xoa(v)" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!list.length">
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Chưa có KPI nào được gán trong tháng này
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
 
        <!-- ── MODAL GÁN KPI ── -->
        <div class="modal fade" id="modalGan" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Gán KPI Cho Nhân Viên</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nhân viên <span class="text-danger">*</span></label>
                            <select v-model="gan_form.id_nhan_vien" class="form-select">
                                <option value="">-- Chọn nhân viên --</option>
                                <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">{{ nv.ho_va_ten }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tiêu chí KPI <span class="text-danger">*</span></label>
                            <select v-model="gan_form.id_tieu_chi" class="form-select" @change="onChangeTieuChi()">
                                <option value="">-- Chọn tiêu chí --</option>
                                <option v-for="tc in list_tieu_chi" :key="tc.id" :value="tc.id">
                                    {{ tc.ten_tieu_chi }} ({{ tc.trong_so }}%)
                                </option>
                            </select>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tháng</label>
                                <select v-model="gan_form.thang" class="form-select">
                                    <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Năm</label>
                                <input v-model="gan_form.nam" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mục tiêu tháng này</label>
                            <input v-model="gan_form.muc_tieu" type="number" class="form-control"
                                placeholder="Để trống sẽ dùng mục tiêu mặc định">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button @click="ganKpi()" class="btn btn-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check me-1"></i>Gán KPI
                        </button>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- ── MODAL NHẬP KẾT QUẢ ── -->
        <div class="modal fade" id="modalKetQua" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" v-if="ket_qua_item">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Nhập Kết Quả KPI</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="fw-bold">{{ ket_qua_item.nhan_vien?.ho_va_ten }}</div>
                            <div class="text-muted small">{{ ket_qua_item.tieu_chi?.ten_tieu_chi }}</div>
                            <div class="mt-1">
                                Mục tiêu: <strong>{{ ket_qua_item.muc_tieu }} {{ ket_qua_item.tieu_chi?.don_vi }}</strong>
                                — Trọng số: <strong>{{ ket_qua_item.tieu_chi?.trong_so }}%</strong>
                            </div>
                        </div>
 
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Kết quả thực tế <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input v-model="kq_form.ket_qua_thuc_te" type="number" min="0"
                                    class="form-control" placeholder="0">
                                <span class="input-group-text">{{ ket_qua_item.tieu_chi?.don_vi }}</span>
                            </div>
                        </div>
 
                        <!-- Preview tính toán -->
                        <div v-if="kq_form.ket_qua_thuc_te" class="preview-kpi mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>% Hoàn thành:</span>
                                <strong :class="previewPct >= 95 ? 'text-success' : previewPct >= 60 ? 'text-warning' : 'text-danger'">
                                    {{ previewPct }}%
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Điểm KPI:</span>
                                <strong class="text-primary">{{ previewDiem }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Xếp loại dự kiến:</span>
                                <span :class="xepLoaiBadge(previewXepLoai)" class="badge">{{ previewXepLoai }}</span>
                            </div>
                        </div>
 
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ghi chú</label>
                            <textarea v-model="kq_form.ghi_chu" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button @click="nhapKetQua()" class="btn btn-success" data-bs-dismiss="modal">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Lưu Kết Quả
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>
import axios from 'axios';
const API = 'http://127.0.0.1:8000/api/admin/kpi';
const API_NV = 'http://127.0.0.1:8000/api/admin/nhan-vien';
 
export default {
    data() {
        return {
            list: [], list_nhan_vien: [], list_tieu_chi: [],
            filter: { thang: new Date().getMonth() + 1, nam: new Date().getFullYear(), id_nhan_vien: '' },
            gan_form: { id_nhan_vien: '', id_tieu_chi: '', thang: new Date().getMonth() + 1, nam: new Date().getFullYear(), muc_tieu: '' },
            ket_qua_item: null,
            kq_form: { id: null, ket_qua_thuc_te: '', ghi_chu: '' },
        };
    },
 
    computed: {
        previewPct() {
            if (!this.ket_qua_item || !this.kq_form.ket_qua_thuc_te) return 0;
            return Math.round((this.kq_form.ket_qua_thuc_te / this.ket_qua_item.muc_tieu) * 100 * 100) / 100;
        },
        previewDiem() {
            if (!this.ket_qua_item) return 0;
            return Math.round(this.ket_qua_item.tieu_chi.trong_so * this.previewPct / 100 * 100) / 100;
        },
        previewXepLoai() {
            const p = this.previewPct;
            return p >= 95 ? 'A' : p >= 80 ? 'B' : p >= 60 ? 'C' : 'D';
        },
    },
 
    mounted() { this.load(); this.loadNhanVien(); this.loadTieuChi(); },
 
    methods: {
        authHeader() { return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') }; },
 
        load() {
            axios.get(`${API}/nhan-vien`, { params: this.filter, headers: this.authHeader() })
                .then(r => { if (r.data.status) this.list = r.data.data; });
        },
 
        loadNhanVien() {
            axios.get(`${API_NV}/data`, { headers: this.authHeader() })
                .then(r => { this.list_nhan_vien = Array.isArray(r.data) ? r.data : (r.data.data || []); });
        },
 
        loadTieuChi() {
            axios.get(`${API}/tieu-chi`, { headers: this.authHeader() })
                .then(r => { if (r.data.status) this.list_tieu_chi = r.data.data.filter(v => v.tinh_trang); });
        },
 
        openGan() {
            this.gan_form = { id_nhan_vien: '', id_tieu_chi: '', thang: this.filter.thang, nam: this.filter.nam, muc_tieu: '' };
        },
 
        onChangeTieuChi() {
            const tc = this.list_tieu_chi.find(v => v.id == this.gan_form.id_tieu_chi);
            if (tc) this.gan_form.muc_tieu = tc.muc_tieu;
        },
 
        ganKpi() {
            if (!this.gan_form.id_nhan_vien || !this.gan_form.id_tieu_chi) { this.$toast.error('Vui lòng chọn đủ thông tin.'); return; }
            axios.post(`${API}/nhan-vien/gan`, this.gan_form, { headers: this.authHeader() })
                .then(r => { if (r.data.status) { this.$toast.success(r.data.message); this.load(); } else this.$toast.error(r.data.message); });
        },
 
        openKetQua(v) {
            this.ket_qua_item = v;
            this.kq_form = { id: v.id, ket_qua_thuc_te: v.ket_qua_thuc_te || '', ghi_chu: v.ghi_chu || '' };
        },
 
        nhapKetQua() {
            if (!this.kq_form.ket_qua_thuc_te && this.kq_form.ket_qua_thuc_te !== 0) { this.$toast.error('Vui lòng nhập kết quả.'); return; }
            axios.post(`${API}/nhan-vien/ket-qua`, this.kq_form, { headers: this.authHeader() })
                .then(r => { if (r.data.status) { this.$toast.success(r.data.message); this.load(); } else this.$toast.error(r.data.message); });
        },
 
        xoa(v) {
            if (!confirm('Xóa bản ghi KPI này?')) return;
            axios.post(`${API}/nhan-vien/delete`, { id: v.id }, { headers: this.authHeader() })
                .then(r => { if (r.data.status) { this.$toast.success(r.data.message); this.load(); } });
        },
 
        progressClass(p) { return p >= 95 ? 'bg-success' : p >= 80 ? 'bg-primary' : p >= 60 ? 'bg-warning' : 'bg-danger'; },
        xepLoaiBadge(x) { return { A:'bg-success', B:'bg-primary', C:'bg-warning text-dark', D:'bg-danger' }[x] || 'bg-secondary'; },
    },
};
</script>
 
<style scoped>
.preview-kpi { background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; padding:12px 14px; font-size:.875rem; }
</style>