<template>
    <div class="nghi-phep-wrapper">

        <!-- ══ STAT CARDS ══ -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3" v-for="s in statCards" :key="s.label">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="stat-icon" :class="s.bg">
                            <i :class="s.icon"></i>
                        </div>
                        <div>
                            <div class="stat-num" :class="s.color">{{ s.value }}</div>
                            <div class="stat-label">{{ s.label }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">

            <!-- ══ FORM NỘP ĐƠN ══ -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <div class="header-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fa-solid fa-file-circle-plus"></i>
                            </div>
                            Nộp Đơn Xin Nghỉ Phép
                        </h6>
                    </div>
                    <div class="card-body">

                        <!-- Loại nghỉ -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Loại nghỉ phép <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-6" v-for="loai in loaiNghiOptions" :key="loai.value">
                                    <label class="loai-card" :class="{selected: form.loai_nghi === loai.value}">
                                        <input type="radio" v-model="form.loai_nghi" :value="loai.value" class="d-none">
                                        <i :class="loai.icon" class="mb-1 d-block"></i>
                                        <span>{{ loai.label }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Ngày -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Từ ngày <span class="text-danger">*</span></label>
                                <input v-model="form.ngay_bat_dau" type="date"
                                    class="form-control" :class="{'is-invalid': errors.ngay_bat_dau}"
                                    :min="today" @change="tinhSoNgay()">
                                <div class="invalid-feedback">{{ errors.ngay_bat_dau }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Đến ngày <span class="text-danger">*</span></label>
                                <input v-model="form.ngay_ket_thuc" type="date"
                                    class="form-control" :class="{'is-invalid': errors.ngay_ket_thuc}"
                                    :min="form.ngay_bat_dau || today" @change="tinhSoNgay()">
                                <div class="invalid-feedback">{{ errors.ngay_ket_thuc }}</div>
                            </div>
                        </div>

                        <!-- Preview số ngày -->
                        <div v-if="so_ngay_preview > 0" class="preview-box mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted small">Số ngày làm việc nghỉ:</span>
                                <span class="fw-bold text-primary fs-5">{{ so_ngay_preview }} ngày</span>
                            </div>
                            <div class="text-muted" style="font-size:.72rem">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Không tính thứ 7 và chủ nhật
                            </div>
                            <!-- Cảnh báo nếu vượt hạn mức còn lại -->
                            <div v-if="form.loai_nghi && so_ngay_preview > hanMucConLai"
                                class="alert alert-warning py-1 mb-0 mt-2" style="font-size:.78rem">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                Số ngày nghỉ vượt quá hạn mức còn lại ({{ hanMucConLai }} ngày).
                                Phần vượt sẽ bị trừ lương nếu đơn được duyệt.
                            </div>
                        </div>

                        <!-- Lý do -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lý do <span class="text-danger">*</span></label>
                            <textarea v-model="form.ly_do" class="form-control"
                                :class="{'is-invalid': errors.ly_do}"
                                rows="3" placeholder="Mô tả chi tiết lý do xin nghỉ phép...">
                            </textarea>
                            <div class="invalid-feedback">{{ errors.ly_do }}</div>
                            <div class="text-end" style="font-size:.72rem;color:#9ca3af">
                                {{ form.ly_do?.length || 0 }}/500
                            </div>
                        </div>

                        <!-- Người liên hệ khi cần -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Người phụ trách thay thế</label>
                            <input v-model="form.nguoi_thay_the" type="text"
                                class="form-control"
                                placeholder="Tên đồng nghiệp sẽ xử lý công việc thay...">
                        </div>

                        <button @click="nopDon()" class="btn btn-primary w-100 btn-submit"
                            :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                            <i v-else class="fa-solid fa-paper-plane me-2"></i>
                            Gửi Đơn Xin Nghỉ Phép
                        </button>
                    </div>
                </div>
            </div>

            <!-- ══ DANH SÁCH ĐƠN ĐÃ GỬI ══ -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <div class="header-icon bg-info bg-opacity-10 text-info">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            Đơn Đã Gửi
                        </h6>
                        <!-- Tabs lọc nhanh -->
                        <div class="d-flex gap-1">
                            <button v-for="tab in tabs" :key="tab.value"
                                @click="activeTab = tab.value"
                                :class="['btn btn-sm', activeTab === tab.value ? tab.activeClass : 'btn-outline-secondary']"
                                style="font-size:.75rem">
                                {{ tab.label }}
                                <span class="badge ms-1 rounded-pill" :class="tab.badgeClass">
                                    {{ countByStatus(tab.value) }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Không có đơn -->
                        <div v-if="!filteredDon.length" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-inbox fa-2x d-block mb-2" style="color:#d1d5db"></i>
                            Chưa có đơn nghỉ phép nào
                        </div>

                        <!-- Danh sách card đơn -->
                        <div v-else class="don-list">
                            <div v-for="v in filteredDon" :key="v.id" class="don-card"
                                :class="'don-' + trangThaiClass(v.trang_thai)">

                                <div class="don-card-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <i :class="loaiIcon(v.loai_nghi)" class="text-muted"></i>
                                        <span class="fw-semibold">{{ loaiLabel(v.loai_nghi) }}</span>
                                    </div>
                                    <span :class="trangThaiBadge(v.trang_thai)" class="badge">
                                        {{ trangThaiLabel(v.trang_thai) }}
                                    </span>
                                </div>

                                <div class="don-card-body">
                                    <div class="don-info-row">
                                        <i class="fa-solid fa-calendar-days text-muted"></i>
                                        <span>{{ formatDate(v.ngay_bat_dau) }} → {{ formatDate(v.ngay_ket_thuc) }}</span>
                                        <span class="don-so-ngay">{{ v.so_ngay }} ngày làm việc</span>
                                    </div>
                                    <div class="don-info-row" v-if="v.ly_do">
                                        <i class="fa-solid fa-quote-left text-muted"></i>
                                        <span class="text-muted fst-italic" style="font-size:.82rem">{{ v.ly_do }}</span>
                                    </div>
                                    <!-- Lý do từ chối -->
                                    <div v-if="v.trang_thai === 3 && v.ly_do_tu_choi"
                                        class="don-tu-choi-reason">
                                        <i class="fa-solid fa-ban me-1"></i>
                                        Lý do từ chối: {{ v.ly_do_tu_choi }}
                                    </div>
                                    <!-- Thông tin duyệt -->
                                    <div v-if="v.trang_thai >= 2" class="don-duyet-info">
                                        <i class="fa-solid fa-user-check text-muted me-1"></i>
                                        <span class="text-muted" style="font-size:.72rem">
                                            {{ v.trang_thai === 2 ? 'Duyệt bởi' : 'Từ chối bởi' }}:
                                            {{ v.nguoi_duyet?.ho_va_ten || 'Admin' }}
                                            · {{ formatDate(v.ngay_duyet) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Footer: hủy đơn nếu chưa được duyệt -->
                                <div class="don-card-footer" v-if="v.trang_thai === 1">
                                    <span class="text-muted" style="font-size:.72rem">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        Gửi lúc {{ formatDate(v.created_at) }}
                                    </span>
                                    <button @click="huyDon(v)"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-xmark me-1"></i>Hủy đơn
                                    </button>
                                </div>
                                <div class="don-card-footer" v-else>
                                    <span class="text-muted" style="font-size:.72rem">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        Gửi lúc {{ formatDate(v.created_at) }}
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ MODAL XÁC NHẬN HỦY ══ -->
        <div class="modal fade" id="modalHuy" tabindex="-1" ref="modalHuy">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content" v-if="huy_item">
                    <div class="modal-body text-center p-4">
                        <div class="del-icon mb-3">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Hủy đơn nghỉ phép?</h6>
                        <p class="text-muted small">
                            {{ loaiLabel(huy_item.loai_nghi) }}<br>
                            {{ formatDate(huy_item.ngay_bat_dau) }} → {{ formatDate(huy_item.ngay_ket_thuc) }}
                        </p>
                    </div>
                    <div class="modal-footer border-0 pt-0 gap-2">
                        <button class="btn btn-outline-secondary flex-fill"
                            data-bs-dismiss="modal">Không</button>
                        <button @click="confirmHuy()" class="btn btn-danger flex-fill"
                            data-bs-dismiss="modal">
                            <i class="fa-solid fa-trash me-1"></i>Hủy đơn
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
//import { Modal } from 'bootstrap';

const API = 'http://127.0.0.1:8000/api/nhan-vien/nghi-phep';
const emptyForm = () => ({
    loai_nghi: '',
    ngay_bat_dau: '',
    ngay_ket_thuc: '',
    ly_do: '',
    nguoi_thay_the: '',
});

export default {
    data() {
        return {
            list_don: [],
            loading:  false,
            loadingPage: false,
            huy_item: null,
            activeTab: '',

            form: emptyForm(),
            errors: {},
            so_ngay_preview: 0,

            thongKe: {
                tong_don:       0,
                cho_duyet:      0,
                da_duyet:       0,
                phep_con_lai:   12,   // số ngày phép năm còn lại
                nghi_om_con_lai: 5,
            },

            loaiNghiOptions: [
                { value: 'phep_nam',    label: 'Phép năm',     icon: 'fa-solid fa-umbrella-beach' },
                { value: 'om',          label: 'Nghỉ ốm',      icon: 'fa-solid fa-kit-medical'    },
            ],

            tabs: [
                { value: '',  label: 'Tất cả',    activeClass: 'btn-secondary',  badgeClass: 'bg-secondary'           },
                { value: 1,   label: 'Chờ duyệt', activeClass: 'btn-warning',    badgeClass: 'bg-warning text-dark'   },
                { value: 2,   label: 'Đã duyệt',  activeClass: 'btn-success',    badgeClass: 'bg-success'             },
                { value: 3,   label: 'Từ chối',   activeClass: 'btn-danger',     badgeClass: 'bg-danger'              },
            ],
        };
    },

    computed: {
        today() { return new Date().toISOString().split('T')[0]; },

        hanMucConLai() {
            if (this.form.loai_nghi === 'om') return this.thongKe.nghi_om_con_lai || 0;
            if (this.form.loai_nghi === 'phep_nam') return this.thongKe.phep_con_lai || 0;
            return 0;
        },

        filteredDon() {
            if (this.activeTab === '') return this.list_don;
            return this.list_don.filter(d => d.trang_thai === this.activeTab);
        },

        statCards() {
            return [
                { label: 'Phép năm còn lại', value: this.thongKe.phep_con_lai, icon: 'fa-solid fa-umbrella-beach', bg: 'bg-primary bg-opacity-10 text-primary',  color: 'text-primary'  },
                { label: 'Nghỉ ốm còn lại', value: this.thongKe.nghi_om_con_lai, icon: 'fa-solid fa-kit-medical', bg: 'bg-danger bg-opacity-10 text-danger',  color: 'text-danger'  },
                { label: 'Chờ phê duyệt', value: this.thongKe.cho_duyet,    icon: 'fa-solid fa-hourglass-half', bg: 'bg-warning bg-opacity-10 text-warning',  color: 'text-warning'  },
                { label: 'Đã được duyệt', value: this.thongKe.da_duyet,     icon: 'fa-solid fa-circle-check',   bg: 'bg-success bg-opacity-10 text-success',  color: 'text-success'  },
            ];
        },
    },

    mounted() {
        this.refresh();
    },

    methods: {
        authHeader() {
            return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') };
        },

        request(method, url, data = {}, config = {}) {
            const options = {
                ...config,
                headers: { ...this.authHeader(), ...(config.headers || {}) },
            };

            return method === 'get'
                ? axios.get(url, { ...options, params: data })
                : axios[method](url, data, options);
        },

        refresh() {
            this.loadingPage = true;

            return Promise.all([this.loadDon(), this.loadThongKe()])
                .finally(() => (this.loadingPage = false));
        },

        loadDon() {
            return this.request('get', `${API}/danh-sach`)
                .then(res => {
                    if (res.data.status) {
                        this.list_don = Array.isArray(res.data.data) ? res.data.data : [];
                    } else {
                        this.$toast.error(res.data.message || 'Không thể tải danh sách đơn.');
                    }
                })
                .catch(err => this.handleError(err));
        },

        loadThongKe() {
            return this.request('get', `${API}/thong-ke`)
                .then(res => {
                    if (res.data.status) this.thongKe = { ...this.thongKe, ...res.data.data };
                })
                .catch(err => this.handleError(err));
        },

        // ── TÍNH SỐ NGÀY LÀM VIỆC ─────────────────────────────────────
        tinhSoNgay() {
            if (!this.form.ngay_bat_dau || !this.form.ngay_ket_thuc) {
                this.so_ngay_preview = 0; return;
            }
            let start = new Date(this.form.ngay_bat_dau);
            const end = new Date(this.form.ngay_ket_thuc);
            if (start > end) { this.so_ngay_preview = 0; return; }
            let count = 0;
            while (start <= end) {
                const d = start.getDay();
                if (d !== 0 && d !== 6) count++;
                start.setDate(start.getDate() + 1);
            }
            this.so_ngay_preview = count;
        },

        // ── VALIDATE ──────────────────────────────────────────────────
        validate() {
            this.errors = {};
            if (!this.form.loai_nghi)    this.errors.loai_nghi    = 'Vui lòng chọn loại nghỉ.';
            if (!this.form.ngay_bat_dau) this.errors.ngay_bat_dau = 'Vui lòng chọn ngày bắt đầu.';
            if (!this.form.ngay_ket_thuc)this.errors.ngay_ket_thuc= 'Vui lòng chọn ngày kết thúc.';
            if (!this.form.ly_do.trim()) this.errors.ly_do         = 'Vui lòng nhập lý do nghỉ phép.';
            else if (this.form.ly_do.length > 500) this.errors.ly_do = 'Lý do không được vượt quá 500 ký tự.';
            if (this.form.ngay_bat_dau && this.form.ngay_ket_thuc &&
                this.form.ngay_bat_dau > this.form.ngay_ket_thuc) {
                this.errors.ngay_ket_thuc = 'Ngày kết thúc phải sau ngày bắt đầu.';
            }
            return Object.keys(this.errors).length === 0;
        },

        // ── NỘP ĐƠN ──────────────────────────────────────────────────
        nopDon() {
            if (this.loading || !this.validate()) return;
            this.loading = true;
            this.request('post', `${API}/nop-don`, this.form)
                .then(res => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.form = emptyForm();
                        this.errors = {};
                        this.so_ngay_preview = 0;
                        this.refresh();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.loading = false));
        },

        // ── HỦY ĐƠN ──────────────────────────────────────────────────
        huyDon(v) {
            this.huy_item = v;
            new Modal(this.$refs.modalHuy).show();
        },
        confirmHuy() {
            if (!this.huy_item) return;

            this.request('post', `${API}/huy-don`, { id: this.huy_item.id })
                .then(res => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.huy_item = null;
                        this.refresh();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(err => this.handleError(err));
        },

        countByStatus(val) {
            if (val === '') return this.list_don.length;
            return this.list_don.filter(d => d.trang_thai === val).length;
        },

        handleError(err) {
            const data = err?.response?.data;
            if (data?.errors) Object.entries(data.errors).forEach(([f, msgs]) => { this.errors[f] = msgs[0]; });
            else this.$toast.error(data?.message || 'Đã có lỗi xảy ra.');
        },

        formatDate(d) { return d ? new Date(d).toLocaleDateString('vi-VN') : '—'; },

        loaiLabel(l)  { return { phep_nam: 'Phép năm', om: 'Nghỉ ốm' }[l] || l; },
        loaiIcon(l)   { return { phep_nam: 'fa-solid fa-umbrella-beach', om: 'fa-solid fa-kit-medical' }[l] || 'fa-solid fa-file'; },
        trangThaiLabel(t) { return { 1: 'Chờ duyệt', 2: 'Đã duyệt', 3: 'Từ chối' }[t] || '—'; },
        trangThaiBadge(t) { return { 1: 'bg-warning text-dark', 2: 'bg-success', 3: 'bg-danger' }[t] || 'bg-secondary'; },
        trangThaiClass(t) { return { 1: 'pending', 2: 'approved', 3: 'rejected' }[t] || ''; },
    },
};
</script>

<style scoped>
.header-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; }

/* Loại nghỉ radio cards */
.loai-card {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 10px 6px; border-radius: 10px;
    border: 2px solid #e2e8f0; background: #f8fafc;
    cursor: pointer; font-size: .78rem; font-weight: 500; color: #64748b;
    transition: all .2s; text-align: center; width: 100%;
}
.loai-card:hover   { border-color: #93c5fd; background: #eff6ff; color: #1e40af; }
.loai-card.selected{ border-color: #1a56db; background: #eff6ff; color: #1e40af; }
.loai-card i { font-size: 1.2rem; }

/* Preview box */
.preview-box {
    background: #f0f9ff; border: 1px solid #bae6fd;
    border-radius: 10px; padding: 10px 14px;
}

/* Submit button */
.btn-submit { height: 44px; font-weight: 600; letter-spacing: .3px; }

/* Stat */
.stat-icon  { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.stat-num   { font-size: 1.4rem; font-weight: 700; line-height: 1; }
.stat-label { font-size: .72rem; color: #6b7280; margin-top: 2px; }

/* Don card list */
.don-list { padding: 12px; display: flex; flex-direction: column; gap: 10px; }

.don-card {
    border-radius: 12px; border: 1px solid #e2e8f0;
    overflow: hidden; background: #fff;
    transition: box-shadow .2s;
}
.don-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }

.don-card.don-pending  { border-left: 4px solid #f59e0b; }
.don-card.don-approved { border-left: 4px solid #10b981; }
.don-card.don-rejected { border-left: 4px solid #ef4444; }

.don-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
    font-size: .875rem;
}
.don-card-body  { padding: 10px 14px; display: flex; flex-direction: column; gap: 6px; }
.don-card-footer{
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 14px; border-top: 1px solid #f1f5f9;
    background: #fafafa;
}

.don-info-row { display: flex; align-items: center; gap: 8px; font-size: .82rem; color: #374151; }
.don-so-ngay  { margin-left: auto; font-weight: 600; color: #1a56db; font-size: .78rem; background: #eff6ff; padding: 1px 8px; border-radius: 20px; }

.don-tu-choi-reason {
    background: #fef2f2; color: #b91c1c; border-radius: 6px;
    padding: 6px 10px; font-size: .78rem;
}
.don-duyet-info { font-size: .72rem; }

/* Hủy modal icon */
.del-icon { width: 60px; height: 60px; border-radius: 50%; background: #fde8e8; color: #e02424; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto; }
</style>
