<template>
    <div class="row g-3">

        <!-- ── HEADER + BỘ LỌC ── -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label class="form-label fw-semibold mb-0" style="font-size:.82rem">Bảng lương:</label>
                        </div>
                        <div class="col-auto">
                            <select v-model="filter.thang" class="form-select form-select-sm" @change="loadBangLuong()">
                                <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <input v-model="filter.nam" type="number" class="form-control form-control-sm"
                                style="width:90px" @change="loadBangLuong()">
                        </div>
                        <div class="col-md-3">
                            <select v-model="filter.id_nhan_vien" class="form-select form-select-sm"
                                @change="loadBangLuong()">
                                <option value="">Tất cả nhân viên</option>
                                <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">
                                    {{ nv.ho_va_ten }}
                                </option>
                            </select>
                        </div>
                        <div class="col-auto ms-auto d-flex gap-2">
                            <button @click="xuatExcel()" class="btn btn-success btn-sm">
                                <i class="fa-regular fa-file-excel me-1"></i>Xuất Excel
                            </button>
                            <button @click="openTinhLuong()" data-bs-toggle="modal" data-bs-target="#modalTinhLuong"
                                class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-calculator me-1"></i>Tính Lương
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── TỔNG KẾT ── -->
        <div class="col-12">
            <div class="row g-3">
                <div class="col-6 col-md-3" v-for="s in summaryCards" :key="s.label">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3 py-3">
                            <div class="sum-icon" :class="s.bg"><i :class="s.icon"></i></div>
                            <div>
                                <div class="sum-num" :class="s.color">{{ s.value }}</div>
                                <div class="sum-label">{{ s.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── BẢNG LƯƠNG ── -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="fa-solid fa-money-bill-wave me-2 text-primary"></i>
                        Bảng Lương Tháng {{ filter.thang }}/{{ filter.nam }}
                        <span class="badge bg-primary ms-1">{{ list.length }}</span>
                    </h6>
                    <!-- Tab trạng thái -->
                    <div class="d-flex gap-1">
                        <button v-for="tab in statusTabs" :key="tab.value" @click="filterStatus = tab.value"
                            :class="['btn btn-sm', filterStatus === tab.value ? tab.activeClass : 'btn-outline-secondary']"
                            style="font-size:.75rem">
                            {{ tab.label }}
                            <span class="badge ms-1" :class="tab.badgeClass">
                                {{list.filter(v => tab.value === '' || v.trang_thai === tab.value).length}}
                            </span>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr
                                style="font-size:.72rem;font-weight:600;color:#6b7280;text-transform:uppercase;white-space:nowrap">
                                <th class="ps-3">Nhân Viên</th>
                                <th class="text-end">LCB</th>
                                <th class="text-center">Ngày Làm</th>
                                <th class="text-center">Giờ Làm</th>
                                <th class="text-center">Nghỉ KL</th>
                                <th class="text-center">XL KPI</th>
                                <th class="text-end">Thưởng KPI</th>
                                <th class="text-end">Thưởng</th>
                                <th class="text-end">Phạt</th>
                                <th class="text-end">Khấu Trừ NL</th>
                                <th class="text-end lương-col">Lương Thực Nhận</th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="filteredList.length">
                                <tr v-for="v in filteredList" :key="v.id" class="align-middle" style="font-size:.82rem">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="nv-av" :style="{ background: avatarColor(v.id_nhan_vien) }">
                                                {{ initials(v.nhan_vien?.ho_va_ten) }}
                                            </div>
                                            <div class="fw-semibold">{{ v.nhan_vien?.ho_va_ten }}</div>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ fmtVND(v.luong_co_ban) }}</td>
                                    <td class="text-center">
                                        {{ v.so_ngay_lam_viec_chuan }}
                                        <span class="text-muted" style="font-size:.7rem">chuẩn</span>
                                    </td>
                                    <td class="text-center">{{ v.so_gio_lam_thuc_te }}h</td>
                                    <td class="text-center">
                                        <span v-if="v.so_ngay_nghi_khong_luong > 0" class="text-danger fw-bold">
                                            {{ v.so_ngay_nghi_khong_luong }} ngày
                                        </span>
                                        <span v-else class="text-muted">—</span>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="v.xep_loai_kpi" :class="xepLoaiBadge(v.xep_loai_kpi)" class="badge">
                                            {{ v.xep_loai_kpi }}
                                        </span>
                                        <span v-else class="text-muted">—</span>
                                    </td>
                                    <td class="text-end">
                                        <span v-if="v.thuong_kpi" class="text-success">+{{ fmtVND(v.thuong_kpi)
                                            }}</span>
                                        <span v-else class="text-muted">—</span>
                                    </td>
                                    <td class="text-end">
                                        <span v-if="v.tong_thuong" class="text-success">+{{ fmtVND(v.tong_thuong)
                                            }}</span>
                                        <span v-else class="text-muted">—</span>
                                    </td>
                                    <td class="text-end">
                                        <span v-if="v.tong_phat" class="text-danger">-{{ fmtVND(v.tong_phat) }}</span>
                                        <span v-else class="text-muted">—</span>
                                    </td>
                                    <td class="text-end">
                                        <span v-if="v.khau_tru_nghi" class="text-danger">-{{ fmtVND(v.khau_tru_nghi)
                                            }}</span>
                                        <span v-else class="text-muted">—</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="luong-final">{{ fmtVND(v.luong_thuc_nhan) }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span :class="ttBadge(v.trang_thai)" class="badge">
                                            {{ ttLabel(v.trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <!-- Xem chi tiết -->
                                        <button type="button" @click="openXem(v)" data-bs-toggle="modal"
                                            data-bs-target="#modalXem" class="btn btn-sm btn-info me-1"
                                            title="Xem chi tiết lương">
                                            <i class="fa-solid fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">Chi tiết</span>
                                        </button>
                                        <!-- Duyệt -->
                                        <button v-if="isPayrollPending(v)" type="button"
                                            @click="doiTrangThai(v, 'da_duyet')" class="btn btn-sm btn-success me-1"
                                            title="Duyệt lương">
                                            <i class="fa-solid fa-check"></i>
                                            <span class="d-none d-md-inline ms-1">Duyệt</span>
                                        </button>
                                        <!-- Đã trả -->
                                        <button v-if="isPayrollApproved(v)" type="button"
                                            @click="doiTrangThai(v, 'da_tra')" class="btn btn-sm btn-primary"
                                            title="Đánh dấu đã trả">
                                            <i class="fa-solid fa-money-bill-wave"></i>
                                            <span class="d-none d-md-inline ms-1">Đã trả</span>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="13" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fa-3x d-block mb-3" style="color:#e5e7eb"></i>
                                    Chưa có bảng lương. Nhấn "Tính Lương" để tạo.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot v-if="filteredList.length" class="table-light border-top">
                            <tr class="fw-bold" style="font-size:.82rem">
                                <td class="ps-3" colspan="7">
                                    TỔNG CỘNG ({{ filteredList.length }} nhân viên)
                                </td>
                                <td class="text-end text-success">
                                    +{{fmtVND(filteredList.reduce((s, v) => s + parseFloat(v.tong_thuong || 0), 0))}}
                                </td>
                                <td class="text-end text-danger">
                                    -{{fmtVND(filteredList.reduce((s, v) => s + parseFloat(v.tong_phat || 0), 0))}}
                                </td>
                                <td class="text-end text-danger">
                                    -{{fmtVND(filteredList.reduce((s, v) => s + parseFloat(v.khau_tru_nghi || 0), 0))}}
                                </td>
                                <td class="text-end luong-col">
                                    <div class="luong-final">
                                        {{fmtVND(filteredList.reduce((s, v) => s + parseFloat(v.luong_thuc_nhan || 0), 0))}}
                                    </div>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>


        <!-- ══ MODAL TÍNH LƯƠNG ══ -->
        <div class="modal fade" id="modalTinhLuong" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-calculator me-2 text-primary"></i>
                            Tính Lương Tháng {{ tinh_form.thang }}/{{ tinh_form.nam }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Cấu hình -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tháng</label>
                                <select v-model="tinh_form.thang" class="form-select">
                                    <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Năm</label>
                                <input v-model="tinh_form.nam" type="number" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Ngày công chuẩn</label>
                                <input v-model="tinh_form.so_ngay_chuan" type="number" min="20" max="31"
                                    class="form-control">
                                <div class="form-text">Thường là 26 ngày/tháng</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Giờ chuẩn/ngày</label>
                                <input v-model="tinh_form.gio_chuan_ngay" type="number" min="4" max="12" step="0.5"
                                    class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tính cho nhân viên</label>
                                <select v-model="tinh_form.id_nhan_vien" class="form-select">
                                    <option value="">Tất cả nhân viên đang làm việc</option>
                                    <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">
                                        {{ nv.ho_va_ten }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Công thức preview -->
                        <div class="formula-box">
                            <div class="formula-title">
                                <i class="fa-solid fa-function me-2"></i>Công Thức Tính Lương
                            </div>
                            <div class="formula-body">
                                <div class="formula-row">
                                    <span>Lương thực tế</span>
                                    <span>= (Ngày làm + Ngày nghỉ có phép) × (LCB ÷ {{ tinh_form.so_ngay_chuan
                                        }})</span>
                                </div>
                                <div class="formula-row">
                                    <span>Thưởng KPI</span>
                                    <span>= LCB × (Hệ số KPI − 1) &nbsp; <span class="badge bg-light text-dark"
                                            style="font-size:.7rem">A=+20%, B=0%, C=-20%, D=-50%</span></span>
                                </div>
                                <div class="formula-row text-danger">
                                    <span>Khấu trừ nghỉ KL</span>
                                    <span>= Ngày nghỉ KL × Lương ngày</span>
                                </div>
                                <div class="formula-row fw-bold border-top pt-2 mt-1">
                                    <span>Lương thực nhận</span>
                                    <span>= Thực tế + KPI + Thưởng − Phạt − Nghỉ KL</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning py-2 mt-3 mb-0" style="font-size:.78rem">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            Nhân viên đã có trạng thái <strong>Đã trả lương</strong> sẽ không bị tính lại.
                            Các tháng khác nhau được lưu riêng biệt.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" @click="tinhLuong()" class="btn btn-primary" data-bs-dismiss="modal"
                            :disabled="loading_tinh">
                            <span v-if="loading_tinh" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-play me-1"></i>
                            Bắt Đầu Tính Lương
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- ══ MODAL XEM CHI TIẾT ══ -->
        <div class="modal fade" id="modalXem" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" v-if="xem_item">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            Chi Tiết Lương — {{ xem_item.nhan_vien?.ho_va_ten }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Cột trái: Input -->
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <div class="detail-section-title">Dữ Liệu Đầu Vào</div>
                                    <div class="detail-row">
                                        <span>Lương cơ bản</span>
                                        <span class="fw-bold">{{ fmtVND(xem_item.luong_co_ban) }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>Ngày công chuẩn</span>
                                        <span>{{ xem_item.so_ngay_lam_viec_chuan }} ngày</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>Giờ làm thực tế</span>
                                        <span>{{ xem_item.so_gio_lam_thuc_te }}h</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>Nghỉ có lương</span>
                                        <span class="text-success">{{ xem_item.so_ngay_nghi_co_phep }} ngày</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>Nghỉ không lương</span>
                                        <span class="text-danger">{{ xem_item.so_ngay_nghi_khong_luong }} ngày</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>Điểm KPI</span>
                                        <span>{{ xem_item.tong_diem_kpi }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>Xếp loại KPI</span>
                                        <span v-if="xem_item.xep_loai_kpi" :class="xepLoaiBadge(xem_item.xep_loai_kpi)"
                                            class="badge">
                                            {{ xem_item.xep_loai_kpi }} (×{{ xem_item.he_so_kpi }})
                                        </span>
                                        <span v-else class="text-muted">Chưa có KPI</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Cột phải: Tính toán -->
                            <div class="col-md-6">
                                <div class="detail-section">
                                    <div class="detail-section-title">Bảng Tính Lương</div>
                                    <div class="detail-row">
                                        <span>Lương ngày</span>
                                        <span>{{ fmtVND(xem_item.luong_ngay) }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span>Lương thực tế</span>
                                        <span class="fw-bold">{{ fmtVND(xem_item.luong_thuc_te) }}</span>
                                    </div>
                                    <div class="detail-row text-success">
                                        <span>+ Thưởng KPI</span>
                                        <span class="fw-bold">{{ fmtVND(xem_item.thuong_kpi) }}</span>
                                    </div>
                                    <div class="detail-row text-success">
                                        <span>+ Thưởng</span>
                                        <span class="fw-bold">{{ fmtVND(xem_item.tong_thuong) }}</span>
                                    </div>
                                    <div class="detail-row text-danger">
                                        <span>− Phạt</span>
                                        <span class="fw-bold">{{ fmtVND(xem_item.tong_phat) }}</span>
                                    </div>
                                    <div class="detail-row text-danger">
                                        <span>− Nghỉ không lương</span>
                                        <span class="fw-bold">{{ fmtVND(xem_item.khau_tru_nghi) }}</span>
                                    </div>
                                    <div class="detail-row border-top pt-2 mt-1">
                                        <span>Lương trước thuế</span>
                                        <span class="fw-bold">{{ fmtVND(xem_item.luong_truoc_thue) }}</span>
                                    </div>
                                    <div class="detail-row text-danger">
                                        <span>− Thuế TNCN</span>
                                        <span>{{ fmtVND(xem_item.thue_tncn) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tổng cuối -->
                            <div class="col-12">
                                <div class="final-luong">
                                    <div class="final-luong-label">Lương Thực Nhận</div>
                                    <div class="final-luong-value">{{ fmtVND(xem_item.luong_thuc_nhan) }}</div>
                                    <div class="final-luong-status">
                                        <span :class="ttBadge(xem_item.trang_thai)" class="badge">
                                            {{ ttLabel(xem_item.trang_thai) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button v-if="isPayrollPending(xem_item)" type="button"
                            @click="doiTrangThai(xem_item, 'da_duyet')" class="btn btn-success" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check me-1"></i>Duyệt Lương
                        </button>
                        <button v-if="isPayrollApproved(xem_item)" type="button"
                            @click="doiTrangThai(xem_item, 'da_tra')" class="btn btn-primary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-money-bill-wave me-1"></i>Đánh Dấu Đã Trả
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';

const API = 'http://127.0.0.1:8000/api/admin/luong';
const API_NV = 'http://127.0.0.1:8000/api/admin/nhan-vien';

const COLORS = ['#1a56db', '#059669', '#d97706', '#7c3aed', '#dc2626', '#0891b2', '#db2777'];

export default {
    data() {
        return {
            list: [],
            list_nhan_vien: [],
            xem_item: null,
            loading: false,
            loading_tinh: false,
            filterStatus: '',

            filter: {
                thang: new Date().getMonth() + 1,
                nam: new Date().getFullYear(),
                id_nhan_vien: '',
            },

            tinh_form: {
                thang: new Date().getMonth() + 1,
                nam: new Date().getFullYear(),
                so_ngay_chuan: 26,
                gio_chuan_ngay: 8,
                id_nhan_vien: '',
            },

            tongHop: {},

            statusTabs: [
                { value: '', label: 'Tất cả', activeClass: 'btn-secondary', badgeClass: 'bg-secondary' },
                { value: 'nhap', label: 'Chưa duyệt', activeClass: 'btn-warning', badgeClass: 'bg-warning text-dark' },
                { value: 'da_duyet', label: 'Đã duyệt', activeClass: 'btn-success', badgeClass: 'bg-success' },
                { value: 'da_tra', label: 'Đã trả', activeClass: 'btn-primary', badgeClass: 'bg-primary' },
            ],
        };
    },

    computed: {
        filteredList() {
            if (this.filterStatus === '') return this.list;
            return this.list.filter(v => v.trang_thai === this.filterStatus);
        },

        summaryCards() {
            const l = this.list;
            return [
                {
                    label: 'Tổng Quỹ Lương', icon: 'fa-solid fa-sack-dollar',
                    value: this.fmtVND(l.reduce((s, v) => s + parseFloat(v.luong_thuc_nhan || 0), 0)),
                    bg: 'bg-primary bg-opacity-10 text-primary', color: 'text-primary',
                },
                {
                    label: 'Tổng Thưởng', icon: 'fa-solid fa-trophy',
                    value: this.fmtVND(l.reduce((s, v) => s + parseFloat(v.tong_thuong || 0) + parseFloat(v.thuong_kpi || 0), 0)),
                    bg: 'bg-success bg-opacity-10 text-success', color: 'text-success',
                },
                {
                    label: 'Tổng Phạt', icon: 'fa-solid fa-gavel',
                    value: this.fmtVND(l.reduce((s, v) => s + parseFloat(v.tong_phat || 0), 0)),
                    bg: 'bg-danger bg-opacity-10 text-danger', color: 'text-danger',
                },
                {
                    label: 'Số Nhân Viên', icon: 'fa-solid fa-users',
                    value: l.length + ' người',
                    bg: 'bg-info bg-opacity-10 text-info', color: 'text-info',
                },
            ];
        },
    },

    mounted() {
        this.loadBangLuong();
        this.loadNhanVien();
    },

    methods: {
        authHeader() { return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') }; },

        loadBangLuong() {
            this.loading = true;
            axios.get(`${API}/bang-luong`, { params: this.filter, headers: this.authHeader() })
                .then(r => { if (r.data.status) { this.list = r.data.data; this.tongHop = r.data.tong_hop; } })
                .finally(() => (this.loading = false));
        },

        loadNhanVien() {
            axios.get(`${API_NV}/data`, { headers: this.authHeader() })
                .then(r => { this.list_nhan_vien = Array.isArray(r.data) ? r.data : (r.data.data || []); });
        },

        openTinhLuong() {
            this.tinh_form.thang = this.filter.thang;
            this.tinh_form.nam = this.filter.nam;
        },

        tinhLuong() {
            this.loading_tinh = true;
            axios.post(`${API}/tinh-luong`, this.tinh_form, { headers: this.authHeader() })
                .then(r => {
                    if (r.data.status) {
                        this.$toast.success(r.data.message);
                        if (r.data.errors?.length) {
                            r.data.errors.forEach(e => this.$toast.error(`${e.nhan_vien}: ${e.loi}`));
                        }
                        this.loadBangLuong();
                    } else {
                        this.$toast.error(r.data.message || 'Tính lương thất bại.');
                    }
                })
                .catch(err => {
                    const data = err?.response?.data;
                    if (data?.errors) Object.values(data.errors).forEach(ms => ms.forEach(m => this.$toast.error(m)));
                    else this.$toast.error(data?.message || 'Lỗi hệ thống.');
                })
                .finally(() => (this.loading_tinh = false));
        },

        doiTrangThai(v, tt) {
            const msgMap = { da_duyet: 'Duyệt lương thành công.', da_tra: 'Đã đánh dấu trả lương.' };
            axios.post(`${API}/doi-trang-thai`, { id: v.id, trang_thai: tt }, { headers: this.authHeader() })
                .then(r => {
                    if (r.data.status) { this.$toast.success(msgMap[tt] || 'Cập nhật thành công.'); this.loadBangLuong(); }
                    else this.$toast.error(r.data.message);
                });
        },

        openXem(v) { this.xem_item = v; },

        xuatExcel() {
            axios.get(`${API}/xuat-excel`, {
                params: { thang: this.filter.thang, nam: this.filter.nam },
                responseType: 'blob',
                headers: this.authHeader(),
            }).then(r => {
                const url = window.URL.createObjectURL(new Blob([r.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `bang_luong_T${this.filter.thang}_${this.filter.nam}.csv`);
                document.body.appendChild(link);
                link.click(); link.remove();
            }).catch(() => this.$toast.error('Xuất Excel thất bại.'));
        },

        fmtVND(n) { return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', maximumFractionDigits: 0 }).format(n || 0); },
        initials(nm) { return nm ? nm.split(' ').map(w => w[0]).slice(-2).join('').toUpperCase() : '?'; },
        avatarColor(id) { return COLORS[id % COLORS.length]; },
        xepLoaiBadge(x) { return { A: 'bg-success', B: 'bg-primary', C: 'bg-warning text-dark', D: 'bg-danger' }[x] || 'bg-secondary'; },
        isPayrollPending(item) {
            return item?.trang_thai === 'nhap' || item?.trang_thai === 0 || item?.trang_thai === '0';
        },
        isPayrollApproved(item) {
            return item?.trang_thai === 'da_duyet' || item?.trang_thai === 1 || item?.trang_thai === '1';
        },
        isPayrollPaid(item) {
            return item?.trang_thai === 'da_tra' || item?.trang_thai === 2 || item?.trang_thai === '2';
        },
        ttLabel(t) {
            return {
                nhap: 'Chưa duyệt',
                da_duyet: 'Đã duyệt',
                da_tra: 'Đã trả',
                0: 'Chưa duyệt',
                1: 'Đã duyệt',
                2: 'Đã trả',
            }[t] || t;
        },
        ttBadge(t) {
            return {
                nhap: 'bg-warning text-dark',
                da_duyet: 'bg-success',
                da_tra: 'bg-primary',
                0: 'bg-warning text-dark',
                1: 'bg-success',
                2: 'bg-primary',
            }[t] || 'bg-secondary';
        },
    },
};
</script>

<style scoped>
/* Summary */
.sum-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.sum-num {
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.2;
}

.sum-label {
    font-size: .7rem;
    color: #6b7280;
    margin-top: 2px;
}

/* Lương final col */
.luong-col {
    background: rgba(26, 86, 219, .04);
}

.luong-final {
    font-weight: 700;
    font-size: .95rem;
    color: #1a56db;
    white-space: nowrap;
}

/* Avatar */
.nv-av {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: .7rem;
    flex-shrink: 0;
}

/* Formula box */
.formula-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.formula-title {
    background: #f1f5f9;
    padding: 8px 14px;
    font-size: .8rem;
    font-weight: 600;
    color: #374151;
}

.formula-body {
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.formula-row {
    display: flex;
    justify-content: space-between;
    font-size: .78rem;
    gap: 12px;
}

.formula-row span:first-child {
    color: #6b7280;
    flex-shrink: 0;
    min-width: 120px;
}

/* Detail modal */
.detail-section {
    background: #f8fafc;
    border-radius: 10px;
    padding: 14px 16px;
    height: 100%;
}

.detail-section-title {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #6b7280;
    margin-bottom: 10px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: .82rem;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row span:first-child {
    color: #64748b;
}

/* Final lương */
.final-luong {
    background: linear-gradient(135deg, #1a56db, #3b82f6);
    border-radius: 12px;
    padding: 20px 24px;
    text-align: center;
    color: #fff;
}

.final-luong-label {
    font-size: .82rem;
    opacity: .85;
    margin-bottom: 6px;
}

.final-luong-value {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -.5px;
}

.final-luong-status {
    margin-top: 8px;
}
</style>