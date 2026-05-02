<template>
    <div class="dashboard">

        <!-- ══ TOPBAR ══ -->
        <div class="dash-header">
            <div>
                <h4 class="dash-title">
                    <i class="fa-solid fa-chart-pie me-2 text-primary"></i>
                    Dashboard Nhân Sự
                </h4>
                <p class="dash-sub">Tổng quan hệ thống RecuAI — Tháng {{ filter.thang }}/{{ filter.nam }}</p>
            </div>
            <div class="dash-filter">
                <select v-model="filter.thang" class="form-select form-select-sm" @change="loadAll()">
                    <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                </select>
                <input v-model="filter.nam" type="number" class="form-control form-control-sm" style="width:90px"
                    @change="loadAll()">
                <button @click="loadAll()" class="btn btn-primary btn-sm" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                    <i v-else class="fa-solid fa-arrows-rotate me-1"></i>Cập nhật
                </button>
            </div>
        </div>

        <!-- ══ CẢNH BÁO NỔI ══ -->
        <transition name="fade">
            <div class="alert-banner" v-if="tongCanhBao > 0">
                <div class="alert-banner-inner">
                    <div class="alert-banner-left">
                        <div class="alert-pulse"></div>
                        <span class="fw-bold">{{ tongCanhBao }} cảnh báo cần xử lý</span>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span v-if="canhBao.nghi_qua_nhieu?.tong" class="alert-tag">
                            <i class="fa-solid fa-bed me-1"></i>{{ canhBao.nghi_qua_nhieu.tong }} nghỉ nhiều
                        </span>
                        <span v-if="canhBao.chua_check_in?.tong" class="alert-tag">
                            <i class="fa-solid fa-user-clock me-1"></i>{{ canhBao.chua_check_in.tong }} chưa check-in
                        </span>
                        <span v-if="canhBao.ot_bat_thuong?.tong" class="alert-tag">
                            <i class="fa-solid fa-bolt me-1"></i>{{ canhBao.ot_bat_thuong.tong }} OT bất thường
                        </span>
                    </div>
                    <button @click="scrollTo('sec-canh-bao')" class="alert-link">
                        Xem chi tiết <i class="fa-solid fa-arrow-down ms-1"></i>
                    </button>
                </div>
            </div>
        </transition>

        <!-- ══ KPI CARDS ══ -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3 kpi-col" v-for="(c, i) in kpiCards" :key="c.label"
                :style="{ animationDelay: i * 0.07 + 's' }">
                <div class="kpi-card" :class="c.theme">
                    <div class="kpi-left">
                        <div class="kpi-icon"><i :class="c.icon"></i></div>
                        <div>
                            <div class="kpi-num">{{ c.value }}</div>
                            <div class="kpi-label">{{ c.label }}</div>
                        </div>
                    </div>
                    <div class="kpi-sub-row" v-if="c.subs">
                        <span v-for="s in c.subs" :key="s.label" class="kpi-sub-item">
                            <span class="kpi-sub-val" :class="s.color">{{ s.val }}</span>
                            <span class="kpi-sub-lbl">{{ s.label }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ HÀNG 1: Chấm công ══ -->
        <div class="row g-3 mb-4" id="sec-cham-cong">
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="section-dot blue"></span>
                            <span class="chart-card-title">① Chấm Công</span>
                            <span class="chart-card-sub">Số lượt &amp; tổng giờ làm</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <select v-model="ccFilter.loai" class="form-select form-select-sm" style="width:120px"
                                @change="loadChamCong()">
                                <option value="thang">Theo tháng</option>
                                <option value="nam">Theo năm</option>
                                <option value="ngay">Theo ngày</option>
                            </select>
                            <input v-if="ccFilter.loai === 'ngay'" v-model="ccFilter.ngay" type="date"
                                class="form-control form-control-sm" style="width:140px" @change="loadChamCong()">
                        </div>
                    </div>
                    <div class="chart-body-lg">
                        <canvas ref="canvasChamCong" v-show="!loading_cc"></canvas>
                        <div v-if="loading_cc" class="chart-skeleton"></div>
                    </div>
                    <div class="chart-footer-stats">
                        <div class="cf-stat" v-for="s in ccFooter" :key="s.label">
                            <span class="cf-val" :class="s.color">{{ s.val }}</span>
                            <span class="cf-lbl">{{ s.label }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card h-100">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="section-dot blue"></span>
                            <span class="chart-card-title">Phân Bổ Ca</span>
                            <span class="chart-card-sub">Sáng / Chiều / Tối</span>
                        </div>
                    </div>
                    <div class="chart-body-md d-flex align-items-center justify-content-center">
                        <canvas ref="canvasCaLam" v-show="!loading_cc"></canvas>
                    </div>
                    <div class="chart-footer-stats">
                        <div class="cf-stat">
                            <span class="cf-val" style="color:#0891b2">{{ phanBoCa.sang || 0 }}</span>
                            <span class="cf-lbl">Ca Sáng</span>
                        </div>
                        <div class="cf-stat">
                            <span class="cf-val" style="color:#d97706">{{ phanBoCa.chieu || 0 }}</span>
                            <span class="cf-lbl">Ca Chiều</span>
                        </div>
                        <div class="cf-stat">
                            <span class="cf-val" style="color:#7c3aed">{{ phanBoCa.toi || 0 }}</span>
                            <span class="cf-lbl">Ca Tối</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ HÀNG 2: Nghỉ phép ══ -->
        <div class="row g-3 mb-4" id="sec-nghi-phep">
            <div class="col-lg-5">
                <div class="chart-card">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="section-dot amber"></span>
                            <span class="chart-card-title">② Nghỉ Phép</span>
                            <span class="chart-card-sub">Phân loại đơn nghỉ</span>
                        </div>
                        <div class="d-flex gap-2">
                            <select v-model="npFilter.thang" class="form-select form-select-sm" style="width:100px"
                                @change="loadNghiPhep()">
                                <option v-for="i in 12" :key="i" :value="i">T{{ i }}</option>
                            </select>
                            <input v-model="npFilter.nam" type="number" class="form-control form-select-sm"
                                style="width:80px" @change="loadNghiPhep()">
                        </div>
                    </div>
                    <div class="chart-body-md d-flex align-items-center justify-content-center">
                        <canvas ref="canvasNghiPhep" v-show="!loading_np"></canvas>
                    </div>
                    <div class="chart-footer-stats">
                        <div class="cf-stat" v-for="s in npFooter" :key="s.label">
                            <span class="cf-val" :class="s.color">{{ s.val }}</span>
                            <span class="cf-lbl">{{ s.label }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="chart-card">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="section-dot amber"></span>
                            <span class="chart-card-title">Xu Hướng Nghỉ Phép</span>
                            <span class="chart-card-sub">Tổng ngày nghỉ được duyệt — {{ npFilter.nam }}</span>
                        </div>
                    </div>
                    <div class="chart-body-lg">
                        <canvas ref="canvasNghiPhepNam" v-show="!loading_np"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ HÀNG 3: Tổng quan nhân sự ══ -->
        <div class="row g-3 mb-4" id="sec-tong-quan">
            <div class="col-12">
                <div class="section-header">
                    <span class="section-dot green"></span>
                    <span class="section-title">③ Tổng Quan Nhân Sự</span>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="chart-card-title">Tuyển Dụng Theo Tháng</span>
                            <span class="chart-card-sub">Nhân viên mới gia nhập — {{ filter.nam }}</span>
                        </div>
                    </div>
                    <div class="chart-body-lg"><canvas ref="canvasTuyenDung"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card h-100">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="chart-card-title">Tỷ Lệ Nhân Sự</span>
                            <span class="chart-card-sub">Đang làm / Đã nghỉ</span>
                        </div>
                    </div>
                    <div class="chart-body-md d-flex align-items-center justify-content-center">
                        <canvas ref="canvasTiLeNV"></canvas>
                    </div>
                    <div class="chart-footer-stats">
                        <div class="cf-stat">
                            <span class="cf-val text-success">{{ tongQuanData.tong_hop?.dang_lam_viec || 0 }}</span>
                            <span class="cf-lbl">Đang làm</span>
                        </div>
                        <div class="cf-stat">
                            <span class="cf-val text-danger">{{ tongQuanData.tong_hop?.da_nghi || 0 }}</span>
                            <span class="cf-lbl">Đã nghỉ</span>
                        </div>
                        <div class="cf-stat">
                            <span class="cf-val text-primary">{{ tongQuanData.tong_hop?.moi_thang_nay || 0 }}</span>
                            <span class="cf-lbl">Mới T.này</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ HÀNG 4: Phòng ban + Chức vụ ══ -->
        <div class="row g-3 mb-4">
            <div class="col-lg-7" id="sec-phong-ban">
                <div class="chart-card">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="section-dot purple"></span>
                            <span class="chart-card-title">④ Nhân Sự / Phòng Ban</span>
                            <span class="chart-card-sub">Phân bổ nhân viên theo đơn vị</span>
                        </div>
                    </div>
                    <div class="chart-body-lg"><canvas ref="canvasPhongBan"></canvas></div>
                </div>
            </div>
            <div class="col-lg-5" id="sec-chuc-vu">
                <div class="chart-card">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="section-dot teal"></span>
                            <span class="chart-card-title">⑤ Nhân Sự / Chức Vụ</span>
                            <span class="chart-card-sub">Cơ cấu theo vị trí</span>
                        </div>
                    </div>
                    <div class="chart-body-lg"><canvas ref="canvasChucVu"></canvas></div>
                </div>
            </div>
        </div>

        <!-- ══ TOP NV ══ -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <div class="chart-card-head">
                        <div class="chart-card-title-wrap">
                            <span class="section-dot green"></span>
                            <span class="chart-card-title">Top 5 Giờ Làm Nhiều Nhất</span>
                            <span class="chart-card-sub">Tháng {{ filter.thang }}/{{ filter.nam }}</span>
                        </div>
                    </div>
                    <div style="padding:0 16px 16px">
                        <div v-if="topNV.length" class="top-nv-list">
                            <div v-for="(v, i) in topNV" :key="v.id_nhan_vien" class="top-nv-item">
                                <div class="top-nv-rank" :class="'rank-' + (i + 1)">{{ i + 1 }}</div>
                                <div class="top-nv-avatar" :style="{ background: avatarColors[i] }">
                                    {{ initials(v.nhan_vien?.ho_va_ten) }}
                                </div>
                                <div class="top-nv-info">
                                    <div class="top-nv-name">{{ v.nhan_vien?.ho_va_ten }}</div>
                                    <div class="top-nv-sub">{{ v.so_luot }} lượt chấm công</div>
                                </div>
                                <div class="top-nv-bar-wrap">
                                    <div class="top-nv-bar">
                                        <div class="top-nv-bar-fill"
                                            :style="{ width: barWidth(v.tong_gio) + '%', background: avatarColors[i] }">
                                        </div>
                                    </div>
                                </div>
                                <div class="top-nv-hours">{{ v.tong_gio }}<span>h</span></div>
                            </div>
                        </div>
                        <div v-else class="text-center text-muted py-3">
                            <i class="fa-solid fa-inbox fa-2x d-block mb-2" style="color:#e5e7eb"></i>
                            Không có dữ liệu
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ ⑥ CẢNH BÁO ══ -->
        <div id="sec-canh-bao" class="row g-3">
            <div class="col-12">
                <div class="section-header">
                    <span class="section-dot red"></span>
                    <span class="section-title">⑥ Cảnh Báo Nhân Sự</span>
                    <span v-if="tongCanhBao > 0" class="badge bg-danger ms-2">{{ tongCanhBao }}</span>
                </div>
            </div>

            <div class="col-12">
                <div class="cb-card">
                    <div class="cb-head red">
                        <div class="cb-head-icon"><i class="fa-solid fa-bed"></i></div>
                        <div class="cb-head-text">
                            <div class="cb-head-title">Nhân viên nghỉ quá nhiều</div>
                            <div class="cb-head-sub">
                                Ngưỡng >= {{ canhBao.nghi_qua_nhieu?.nguong || 3 }} ngày/tháng —
                                <strong>{{ canhBao.nghi_qua_nhieu?.tong || 0 }} nhân viên</strong>
                            </div>
                        </div>
                    </div>
                    <div class="cb-body" v-if="canhBao.nghi_qua_nhieu?.data?.length">
                        <div class="cb-row" v-for="v in canhBao.nghi_qua_nhieu.data" :key="v.id_nhan_vien">
                            <div class="cb-avatar" :style="{ background: mucDoColorHex(v.muc_do) }">
                                {{ initials(v.ho_va_ten) }}
                            </div>
                            <div class="cb-info">
                                <div class="cb-name">{{ v.ho_va_ten }}</div>
                                <div class="cb-detail">{{ v.so_don }} đơn nghỉ</div>
                            </div>
                            <div class="cb-progress-wrap">
                                <div class="cb-bar">
                                    <div class="cb-bar-fill"
                                        :style="{ width: Math.min(v.tong_ngay * 12, 100) + '%', background: mucDoColorHex(v.muc_do) }">
                                    </div>
                                </div>
                            </div>
                            <div class="cb-badge" :class="'cb-badge-' + v.muc_do">{{ v.tong_ngay }} ngày</div>
                        </div>
                    </div>
                    <div v-else class="cb-empty">
                        <i class="fa-solid fa-circle-check text-success me-2"></i>Không có cảnh báo
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="cb-card h-100">
                    <div class="cb-head amber">
                        <div class="cb-head-icon"><i class="fa-solid fa-user-clock"></i></div>
                        <div class="cb-head-text">
                            <div class="cb-head-title">Chưa Check-in Hôm Nay</div>
                            <div class="cb-head-sub">
                                {{ canhBao.chua_check_in?.ngay }} —
                                <strong>{{ canhBao.chua_check_in?.tong || 0 }} nhân viên</strong>
                            </div>
                        </div>
                    </div>
                    <div class="cb-body" v-if="canhBao.chua_check_in?.data?.length">
                        <div class="cb-row" v-for="v in canhBao.chua_check_in.data.slice(0, 6)" :key="v.id_nhan_vien">
                            <div class="cb-avatar" style="background:#f59e0b">{{ initials(v.ho_va_ten) }}</div>
                            <div class="cb-info">
                                <div class="cb-name">{{ v.ho_va_ten }}</div>
                                <div class="cb-detail">{{ v.phong_ban || 'Chưa có PB' }}</div>
                            </div>
                            <span class="badge bg-warning text-dark" style="font-size:.7rem;flex-shrink:0">Vắng</span>
                        </div>
                        <div v-if="canhBao.chua_check_in.data.length > 6" class="text-center text-muted py-2"
                            style="font-size:.75rem">
                            +{{ canhBao.chua_check_in.data.length - 6 }} nhân viên khác
                        </div>
                    </div>
                    <div v-else class="cb-empty">
                        <i class="fa-solid fa-circle-check text-success me-2"></i>Tất cả đã check-in
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="cb-card h-100">
                    <div class="cb-head blue">
                        <div class="cb-head-icon"><i class="fa-solid fa-bolt"></i></div>
                        <div class="cb-head-text">
                            <div class="cb-head-title">OT Bất Thường</div>
                            <div class="cb-head-sub">
                                TB >= {{ canhBao.ot_bat_thuong?.nguong || 10 }}h/ngày —
                                <strong>{{ canhBao.ot_bat_thuong?.tong || 0 }} nhân viên</strong>
                            </div>
                        </div>
                    </div>
                    <div class="cb-body" v-if="canhBao.ot_bat_thuong?.data?.length">
                        <div class="cb-row" v-for="v in canhBao.ot_bat_thuong.data" :key="v.id_nhan_vien">
                            <div class="cb-avatar" :style="{ background: mucDoColorHex(v.muc_do) }">
                                {{ initials(v.ho_va_ten) }}
                            </div>
                            <div class="cb-info">
                                <div class="cb-name">{{ v.ho_va_ten }}</div>
                                <div class="cb-detail">Max {{ v.max_gio }}h · {{ v.so_ngay }} ngày</div>
                            </div>
                            <div class="cb-progress-wrap">
                                <div class="cb-bar">
                                    <div class="cb-bar-fill"
                                        :style="{ width: Math.min((v.tb_gio / 16) * 100, 100) + '%', background: mucDoColorHex(v.muc_do) }">
                                    </div>
                                </div>
                            </div>
                            <div class="cb-badge" :class="'cb-badge-' + v.muc_do">{{ v.tb_gio }}h TB</div>
                        </div>
                    </div>
                    <div v-else class="cb-empty">
                        <i class="fa-solid fa-circle-check text-success me-2"></i>Không có cảnh báo OT
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import {
    Chart, CategoryScale, LinearScale, BarElement, LineElement,
    PointElement, ArcElement, Tooltip, Legend, Filler,
    BarController, LineController, DoughnutController,
} from 'chart.js';

Chart.register(
    BarController, LineController, DoughnutController,
    CategoryScale, LinearScale, BarElement, LineElement, PointElement, ArcElement, Tooltip, Legend, Filler
);

const API = 'http://127.0.0.1:8000/api/admin/thong-ke';

const C = {
    blue: { fill: 'rgba(26,86,219,.12)', line: '#1a56db' },
    green: { fill: 'rgba(5,150,105,.12)', line: '#059669' },
    amber: { fill: 'rgba(217,119,6,.12)', line: '#d97706' },
    red: { fill: 'rgba(220,38,38,.12)', line: '#dc2626' },
    purple: { fill: 'rgba(124,58,237,.12)', line: '#7c3aed' },
    teal: { fill: 'rgba(8,145,178,.12)', line: '#0891b2' },
    pink: { fill: 'rgba(219,39,119,.12)', line: '#db2777' },
};
const COLOR_LIST = Object.values(C).map(c => c.line);

const BASE_OPTIONS = {
    responsive: true, maintainAspectRatio: false,
    animation: { duration: 600, easing: 'easeOutQuart' },
    plugins: {
        legend: { labels: { font: { size: 11 }, boxWidth: 11, padding: 10 } },
        tooltip: { bodyFont: { size: 11 }, titleFont: { size: 11 } },
    },
};

export default {
    name: 'Dashboard',
    data() {
        return {
            loading: false, loading_cc: false, loading_np: false,
            filter: { thang: new Date().getMonth() + 1, nam: new Date().getFullYear() },
            ccFilter: { loai: 'thang', ngay: new Date().toISOString().split('T')[0] },
            npFilter: { thang: new Date().getMonth() + 1, nam: new Date().getFullYear() },
            chamCongData: {}, nghiPhepData: {}, tongQuanData: {},
            phongBanData: [], chucVuData: [], canhBao: {},
            charts: {},
            avatarColors: ['#1a56db', '#059669', '#d97706', '#7c3aed', '#0891b2'],
        };
    },

    computed: {
        tongCanhBao() {
            return (this.canhBao.nghi_qua_nhieu?.tong || 0)
                + (this.canhBao.chua_check_in?.tong || 0)
                + (this.canhBao.ot_bat_thuong?.tong || 0);
        },
        phanBoCa() { return this.chamCongData.phan_bo_ca || {}; },
        topNV() { return this.chamCongData.top_nhan_vien || []; },
        maxGio() { return Math.max(...this.topNV.map(v => parseFloat(v.tong_gio) || 0), 1); },

        kpiCards() {
            const tq = this.tongQuanData.tong_hop || {};
            const cc = this.chamCongData.tong_hop || {};
            const np = this.nghiPhepData.tong_hop || {};
            return [
                {
                    label: 'Tổng Nhân Viên', value: tq.tong_nhan_vien || 0, icon: 'fa-solid fa-users', theme: 'blue',
                    subs: [{ label: 'Đang làm', val: tq.dang_lam_viec || 0, color: 'text-success' }, { label: 'Nghỉ', val: tq.da_nghi || 0, color: 'text-danger' }]
                },
                {
                    label: 'Chấm Công Tháng', value: cc.tong_luot || 0, icon: 'fa-solid fa-clock', theme: 'green',
                    subs: [{ label: 'Tổng giờ', val: (cc.tong_gio || 0) + 'h', color: 'text-success' }, { label: 'TB/ngày', val: (cc.tb_gio_ngay || 0) + 'h', color: 'text-info' }]
                },
                {
                    label: 'Đơn Nghỉ Phép', value: np.tong_don || 0, icon: 'fa-solid fa-calendar-xmark', theme: 'amber',
                    subs: [{ label: 'Chờ duyệt', val: np.cho_duyet || 0, color: 'text-warning' }, { label: 'Đã duyệt', val: np.da_duyet || 0, color: 'text-success' }]
                },
                {
                    label: 'Cảnh Báo', value: this.tongCanhBao, icon: 'fa-solid fa-triangle-exclamation', theme: this.tongCanhBao > 0 ? 'red' : 'gray',
                    subs: this.tongCanhBao > 0 ? [{ label: 'Cần xử lý', val: '!', color: 'text-danger' }] : [{ label: 'Ổn định', val: 'OK', color: 'text-success' }]
                },
            ];
        },

        ccFooter() {
            const d = this.chamCongData.tong_hop || {};
            return [
                { label: 'Đã XN', val: d.da_xac_nhan || 0, color: 'text-success' },
                { label: 'Chờ XN', val: d.cho_xac_nhan || 0, color: 'text-primary' },
                { label: 'Vắng', val: d.vang_mat || 0, color: 'text-danger' },
                { label: 'TB giờ', val: (d.tb_gio_ngay || 0) + 'h', color: 'text-info' },
            ];
        },

        npFooter() {
            const d = this.nghiPhepData.tong_hop || {};
            return [
                { label: 'Đã duyệt', val: d.da_duyet || 0, color: 'text-success' },
                { label: 'Chờ duyệt', val: d.cho_duyet || 0, color: 'text-warning' },
                { label: 'Từ chối', val: d.tu_choi || 0, color: 'text-danger' },
                { label: 'Tổng ngày', val: d.tong_ngay_nghi || 0, color: 'text-primary' },
            ];
        },
    },

    mounted() { this.loadAll(); },
    beforeUnmount() { Object.values(this.charts).forEach(c => c?.destroy()); },

    methods: {
        authHeader() { return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') }; },

        async loadAll() {
            this.loading = true;
            this.npFilter.thang = this.filter.thang;
            this.npFilter.nam = this.filter.nam;
            try {
                await Promise.all([
                    this.loadChamCong(), this.loadNghiPhep(), this.loadTongQuan(),
                    this.loadPhongBan(), this.loadChucVu(), this.loadCanhBao(),
                ]);
            } finally { this.loading = false; }
        },

        async loadChamCong() {
            this.loading_cc = true;
            try {
                const res = await axios.get(`${API}/cham-cong`, {
                    params: { loai: this.ccFilter.loai, thang: this.filter.thang, nam: this.filter.nam, ngay: this.ccFilter.ngay },
                    headers: this.authHeader(),
                });
                if (res.data.status) {
                    this.chamCongData = res.data;
                    this.$nextTick(() => { this.renderChamCong(); this.renderCaLam(); });
                }
            } finally { this.loading_cc = false; }
        },

        async loadNghiPhep() {
            this.loading_np = true;
            try {
                const res = await axios.get(`${API}/nghi-phep`, {
                    params: { thang: this.npFilter.thang, nam: this.npFilter.nam },
                    headers: this.authHeader(),
                });
                if (res.data.status) {
                    this.nghiPhepData = res.data;
                    this.$nextTick(() => { this.renderNghiPhep(); this.renderNghiPhepNam(); });
                }
            } finally { this.loading_np = false; }
        },

        async loadTongQuan() {
            const res = await axios.get(`${API}/tong-quan`, { headers: this.authHeader() });
            if (res.data.status) {
                this.tongQuanData = res.data;
                this.$nextTick(() => { this.renderTuyenDung(); this.renderTiLeNV(); });
            }
        },

        async loadPhongBan() {
            const res = await axios.get(`${API}/phong-ban`, { headers: this.authHeader() });
            if (res.data.status) { this.phongBanData = res.data.data; this.$nextTick(() => this.renderPhongBan()); }
        },

        async loadChucVu() {
            const res = await axios.get(`${API}/chuc-vu`, { headers: this.authHeader() });
            if (res.data.status) { this.chucVuData = res.data.data; this.$nextTick(() => this.renderChucVu()); }
        },

        async loadCanhBao() {
            const res = await axios.get(`${API}/canh-bao`, {
                params: { thang: this.filter.thang, nam: this.filter.nam },
                headers: this.authHeader(),
            });
            if (res.data.status) this.canhBao = res.data;
        },

        destroy(key) { this.charts[key]?.destroy(); delete this.charts[key]; },

        renderChamCong() {
            this.destroy('cc');
            const data = this.chamCongData.bieu_do || [];
            const canvas = this.$refs.canvasChamCong;
            if (!canvas || !data.length) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            this.charts.cc = new Chart(ctx, {
                data: {
                    labels: data.map(v => v.label),
                    datasets: [
                        { type: 'bar', label: 'Số lượt', data: data.map(v => v.so_luot), backgroundColor: C.blue.fill, borderColor: C.blue.line, borderWidth: 1.5, borderRadius: 5, yAxisID: 'y' },
                        { type: 'line', label: 'Tổng giờ', data: data.map(v => v.tong_gio), borderColor: C.green.line, backgroundColor: 'transparent', borderWidth: 2.5, pointRadius: 3, tension: 0.4, yAxisID: 'y1' },
                    ],
                },
                options: {
                    ...BASE_OPTIONS,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        y: { position: 'left', beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 10 } } },
                        y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { font: { size: 10 } } },
                        x: { ticks: { font: { size: 10 }, maxRotation: 40 } },
                    },
                },
            });
        },

        renderCaLam() {
            this.destroy('ca');
            const ca = this.chamCongData.phan_bo_ca || {};
            const canvas = this.$refs.canvasCaLam;
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            this.charts.ca = new Chart(canvas, {
                type: 'doughnut',
                data: { labels: ['Ca Sáng', 'Ca Chiều', 'Ca Tối'], datasets: [{ data: [ca.sang || 0, ca.chieu || 0, ca.toi || 0], backgroundColor: [C.teal.line, C.amber.line, C.purple.line], borderWidth: 0, hoverOffset: 6 }] },
                options: { ...BASE_OPTIONS, cutout: '68%', plugins: { ...BASE_OPTIONS.plugins, legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 9, padding: 8 } } } },
            });
        },

        renderNghiPhep() {
            this.destroy('np');
            const data = this.nghiPhepData.phan_loai || [];
            const canvas = this.$refs.canvasNghiPhep;
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            const loaiMap = { phep_nam: 'Phép năm', om: 'Nghỉ ốm', viec_rieng: 'Việc riêng', khong_luong: 'Không lương' };
            this.charts.np = new Chart(canvas, {
                type: 'doughnut',
                data: { labels: data.map(v => loaiMap[v.loai_nghi] || v.loai_nghi), datasets: [{ data: data.map(v => v.tong_ngay), backgroundColor: COLOR_LIST, borderWidth: 0, hoverOffset: 8 }] },
                options: { ...BASE_OPTIONS, cutout: '60%', plugins: { ...BASE_OPTIONS.plugins, legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 9, padding: 8 } }, tooltip: { callbacks: { label: c => ` ${c.label}: ${c.raw} ngày` } } } },
            });
        },

        renderNghiPhepNam() {
            this.destroy('npnam');
            const data = this.nghiPhepData.bieu_do_nam || [];
            const canvas = this.$refs.canvasNghiPhepNam;
            if (!canvas || !data.length) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            this.charts.npnam = new Chart(canvas, {
                type: 'line',
                data: { labels: data.map(v => v.label), datasets: [{ label: 'Ngày nghỉ đã duyệt', data: data.map(v => v.tong_ngay), borderColor: C.amber.line, backgroundColor: C.amber.fill, borderWidth: 2.5, fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: C.amber.line }] },
                options: { ...BASE_OPTIONS, plugins: { ...BASE_OPTIONS.plugins, legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 10 } } }, x: { ticks: { font: { size: 10 } } } } },
            });
        },

        renderTuyenDung() {
            this.destroy('td');
            const data = this.tongQuanData.tuyen_dung_theo_thang || [];
            const canvas = this.$refs.canvasTuyenDung;
            if (!canvas || !data.length) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            this.charts.td = new Chart(canvas, {
                type: 'bar',
                data: { labels: data.map(v => v.label), datasets: [{ label: 'Nhân viên mới', data: data.map(v => v.so_luong), backgroundColor: data.map((_, i) => i === this.filter.thang - 1 ? C.green.line : C.green.fill), borderColor: C.green.line, borderWidth: 1.5, borderRadius: 6 }] },
                options: { ...BASE_OPTIONS, plugins: { ...BASE_OPTIONS.plugins, legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,.04)' } }, x: { ticks: { font: { size: 10 } } } } },
            });
        },

        renderTiLeNV() {
            this.destroy('tile');
            const tq = this.tongQuanData.tong_hop || {};
            const canvas = this.$refs.canvasTiLeNV;
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            this.charts.tile = new Chart(canvas, {
                type: 'doughnut',
                data: { labels: ['Đang làm việc', 'Đã nghỉ'], datasets: [{ data: [tq.dang_lam_viec || 0, tq.da_nghi || 0], backgroundColor: [C.green.line, C.red.line], borderWidth: 0, hoverOffset: 6 }] },
                options: { ...BASE_OPTIONS, cutout: '68%', plugins: { ...BASE_OPTIONS.plugins, legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 9, padding: 8 } } } },
            });
        },

        renderPhongBan() {
            this.destroy('pb');
            const data = this.phongBanData || [];
            const canvas = this.$refs.canvasPhongBan;
            if (!canvas || !data.length) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            this.charts.pb = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: data.map(v => v.ten), datasets: [
                        { label: 'Đang làm', data: data.map(v => v.dang_lam), backgroundColor: C.blue.fill, borderColor: C.blue.line, borderWidth: 1.5, borderRadius: 4 },
                        { label: 'Đã nghỉ', data: data.map(v => v.da_nghi), backgroundColor: C.red.fill, borderColor: C.red.line, borderWidth: 1.5, borderRadius: 4 },
                    ]
                },
                options: { ...BASE_OPTIONS, scales: { x: { ticks: { font: { size: 10 }, maxRotation: 35 } }, y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,.04)' } } } },
            });
        },

        renderChucVu() {
            this.destroy('cv');
            const data = this.chucVuData || [];
            const canvas = this.$refs.canvasChucVu;
            if (!canvas || !data.length) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            this.charts.cv = new Chart(canvas, {
                type: 'bar',
                data: { labels: data.map(v => v.ten), datasets: [{ label: 'Nhân viên', data: data.map(v => v.tong_nv), backgroundColor: COLOR_LIST.slice(0, data.length), borderWidth: 0, borderRadius: 6 }] },
                options: { ...BASE_OPTIONS, indexAxis: 'y', plugins: { ...BASE_OPTIONS.plugins, legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,.04)' } }, y: { ticks: { font: { size: 10 } } } } },
            });
        },

        scrollTo(id) { document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' }); },
        initials(name) { return name ? name.split(' ').map(w => w[0]).slice(-2).join('').toUpperCase() : '?'; },
        barWidth(v) { return Math.max((parseFloat(v) / this.maxGio) * 100, 4); },
        mucDoColorHex(m) { return { cao: '#dc2626', trung_binh: '#d97706', thap: '#0891b2' }[m] || '#6b7280'; },
    },
};
</script>

<style scoped>
.dashboard {
    padding: 4px 0 40px
}

.dash-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 12px
}

.dash-title {
    font-size: 1.4rem;
    font-weight: 700;
    letter-spacing: -.4px;
    margin: 0;
    color: #111827
}

.dash-sub {
    font-size: .78rem;
    color: #6b7280;
    margin: 3px 0 0
}

.dash-filter {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap
}

.alert-banner {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    border-radius: 12px;
    padding: 12px 18px;
    margin-bottom: 20px
}

.alert-banner-inner {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap
}

.alert-banner-left {
    display: flex;
    align-items: center;
    gap: 10px
}

.alert-pulse {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #fca5a5;
    box-shadow: 0 0 0 4px rgba(252, 165, 165, .3);
    animation: pulse 1.5s infinite
}

@keyframes pulse {

    0%,
    100% {
        box-shadow: 0 0 0 4px rgba(252, 165, 165, .3)
    }

    50% {
        box-shadow: 0 0 0 8px rgba(252, 165, 165, .1)
    }
}

.alert-banner * {
    color: #fff
}

.alert-tag {
    font-size: .72rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, .3)
}

.alert-link {
    margin-left: auto;
    font-size: .78rem;
    background: rgba(255, 255, 255, .15);
    border: 1px solid rgba(255, 255, 255, .3);
    padding: 4px 12px;
    border-radius: 20px;
    cursor: pointer
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity .3s
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0
}

.kpi-col {
    animation: slideUp .4s ease both
}

@keyframes slideUp {
    from {
        transform: translateY(16px);
        opacity: 0
    }

    to {
        transform: translateY(0);
        opacity: 1
    }
}

.kpi-card {
    border-radius: 14px;
    padding: 16px 18px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .07);
    height: 100%
}

.kpi-card.blue {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border: 1px solid #bfdbfe
}

.kpi-card.green {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1px solid #86efac
}

.kpi-card.amber {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1px solid #fcd34d
}

.kpi-card.red {
    background: linear-gradient(135deg, #fff1f2, #ffe4e6);
    border: 1px solid #fca5a5
}

.kpi-card.gray {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6);
    border: 1px solid #d1d5db
}

.kpi-left {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px
}

.kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;
    background: rgba(255, 255, 255, .7);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0
}

.kpi-card.blue .kpi-icon {
    color: #1d4ed8
}

.kpi-card.green .kpi-icon {
    color: #15803d
}

.kpi-card.amber .kpi-icon {
    color: #b45309
}

.kpi-card.red .kpi-icon {
    color: #b91c1c
}

.kpi-card.gray .kpi-icon {
    color: #6b7280
}

.kpi-num {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1;
    letter-spacing: -.5px;
    color: #111827
}

.kpi-label {
    font-size: .72rem;
    font-weight: 600;
    color: #374151;
    margin-top: 2px
}

.kpi-sub-row {
    display: flex;
    gap: 16px;
    padding-top: 8px;
    border-top: 1px solid rgba(0, 0, 0, .06)
}

.kpi-sub-item {
    display: flex;
    flex-direction: column;
    gap: 1px
}

.kpi-sub-val {
    font-size: .88rem;
    font-weight: 700
}

.kpi-sub-lbl {
    font-size: .64rem;
    color: #9ca3af
}

.section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px
}

.section-title {
    font-size: .95rem;
    font-weight: 700;
    color: #111827
}

.section-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0
}

.section-dot.blue {
    background: #1a56db
}

.section-dot.amber {
    background: #d97706
}

.section-dot.green {
    background: #059669
}

.section-dot.purple {
    background: #7c3aed
}

.section-dot.teal {
    background: #0891b2
}

.section-dot.red {
    background: #dc2626
}

.chart-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
    overflow: hidden;
    height: 100%
}

.chart-card-head {
    padding: 14px 16px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap
}

.chart-card-title-wrap {
    display: flex;
    flex-direction: column;
    gap: 2px
}

.chart-card-title {
    font-size: .92rem;
    font-weight: 700;
    color: #111827
}

.chart-card-sub {
    font-size: .72rem;
    color: #9ca3af
}

.chart-body-lg {
    padding: 12px 14px;
    height: 260px;
    position: relative
}

.chart-body-md {
    padding: 12px 14px;
    height: 220px;
    position: relative
}

.chart-skeleton {
    height: 100%;
    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
    background-size: 200% 100%;
    animation: shimmer 1.2s infinite;
    border-radius: 8px
}

@keyframes shimmer {
    0% {
        background-position: 200% 0
    }

    100% {
        background-position: -200% 0
    }
}

.chart-footer-stats {
    display: flex;
    border-top: 1px solid #f3f4f6
}

.cf-stat {
    flex: 1;
    padding: 10px 0;
    text-align: center;
    border-right: 1px solid #f3f4f6
}

.cf-stat:last-child {
    border-right: none
}

.cf-val {
    display: block;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1
}

.cf-lbl {
    display: block;
    font-size: .65rem;
    color: #9ca3af;
    margin-top: 3px
}

.top-nv-list {
    display: flex;
    flex-direction: column;
    gap: 10px
}

.top-nv-item {
    display: grid;
    grid-template-columns: 28px 36px 1fr auto auto;
    align-items: center;
    gap: 10px;
    padding: 6px 0;
    border-bottom: 1px solid #f9fafb
}

.top-nv-item:last-child {
    border-bottom: none
}

.top-nv-rank {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    font-weight: 700;
    flex-shrink: 0
}

.rank-1 {
    background: #fef3c7;
    color: #92400e
}

.rank-2 {
    background: #e5e7eb;
    color: #374151
}

.rank-3 {
    background: #fde8e8;
    color: #9b1c1c
}

.rank-4,
.rank-5 {
    background: #f3f4f6;
    color: #6b7280
}

.top-nv-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: .75rem;
    flex-shrink: 0
}

.top-nv-name {
    font-size: .82rem;
    font-weight: 600;
    color: #111827
}

.top-nv-sub {
    font-size: .7rem;
    color: #9ca3af;
    margin-top: 1px
}

.top-nv-bar-wrap {
    min-width: 120px
}

.top-nv-bar {
    height: 6px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden
}

.top-nv-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width .8s ease
}

.top-nv-hours {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    white-space: nowrap;
    min-width: 40px;
    text-align: right
}

.top-nv-hours span {
    font-size: .72rem;
    color: #9ca3af;
    font-weight: 400
}

.cb-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
    overflow: hidden;
    height: 100%
}

.cb-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px
}

.cb-head.red {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-bottom: 1px solid #fecaca
}

.cb-head.amber {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border-bottom: 1px solid #fde68a
}

.cb-head.blue {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-bottom: 1px solid #bfdbfe
}

.cb-head-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    background: rgba(255, 255, 255, .7);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0
}

.cb-head.red .cb-head-icon {
    color: #b91c1c
}

.cb-head.amber .cb-head-icon {
    color: #b45309
}

.cb-head.blue .cb-head-icon {
    color: #1d4ed8
}

.cb-head-title {
    font-size: .88rem;
    font-weight: 700;
    color: #111827
}

.cb-head-sub {
    font-size: .72rem;
    color: #6b7280;
    margin-top: 2px
}

.cb-body {
    padding: 8px 12px
}

.cb-row {
    display: grid;
    grid-template-columns: 34px 1fr auto auto;
    align-items: center;
    gap: 8px;
    padding: 7px 4px;
    border-bottom: 1px solid #f9fafb
}

.cb-row:last-child {
    border-bottom: none
}

.cb-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: .72rem;
    flex-shrink: 0
}

.cb-name {
    font-size: .8rem;
    font-weight: 600;
    color: #111827
}

.cb-detail {
    font-size: .7rem;
    color: #9ca3af;
    margin-top: 1px
}

.cb-progress-wrap {
    min-width: 80px
}

.cb-bar {
    height: 5px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden
}

.cb-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width .6s ease
}

.cb-badge {
    font-size: .7rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
    white-space: nowrap;
    flex-shrink: 0
}

.cb-badge-cao {
    background: #fee2e2;
    color: #b91c1c
}

.cb-badge-trung_binh {
    background: #fef3c7;
    color: #b45309
}

.cb-badge-thap {
    background: #dbeafe;
    color: #1d4ed8
}

.cb-empty {
    padding: 18px;
    text-align: center;
    font-size: .82rem;
    color: #9ca3af
}
</style>