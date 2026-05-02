<template>
    <div class="row g-3">

        <!-- ── STAT CARDS ── -->
        <div class="col-12">
            <div class="row g-3">
                <div class="col-6 col-md-3" v-for="s in statCards" :key="s.label">
                    <div class="card stat-card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" :class="s.bg"><i :class="s.icon"></i></div>
                            <div class="grow">
                                <div class="stat-num" :class="s.color">{{ s.value }}</div>
                                <div class="stat-label">{{ s.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── BỘ LỌC ── -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="row g-2 g-md-3 align-items-end">
                        <div class="col-12 col-sm-6 col-md-2">
                            <label class="form-label form-label-sm fw-semibold mb-1">Ngày</label>
                            <input v-model="filter.ngay" type="date" class="form-control form-control-sm"
                                @change="loadData">
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                            <label class="form-label form-label-sm fw-semibold mb-1">Ca làm</label>
                            <select v-model="filter.ca_lam" class="form-select form-select-sm" @change="loadData">
                                <option value="">Tất cả ca</option>
                                <option value="sang">Ca Sáng</option>
                                <option value="chieu">Ca Chiều</option>
                                <option value="toi">Ca Tối</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2">
                            <label class="form-label form-label-sm fw-semibold mb-1">Trạng thái</label>
                            <select v-model="filter.trang_thai" class="form-select form-select-sm" @change="loadData">
                                <option value="">Tất cả</option>
                                <option value="0">Chưa check-out</option>
                                <option value="1">Đã check-out</option>
                                <option value="2">Đã xác nhận</option>
                                <option value="3">Nghi ngờ</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label form-label-sm fw-semibold mb-1">Nhân viên</label>
                            <select v-model="filter.id_nhan_vien" class="form-select form-select-sm" @change="loadData">
                                <option value="">Tất cả</option>
                                <option v-for="nv in list_nhan_vien" :key="nv.id" :value="nv.id">{{ nv.ho_va_ten }}
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex gap-2">
                            <button @click="loadData" class="btn btn-primary btn-sm flex-fill" :disabled="loading">
                                <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                                <i v-else class="fa-solid fa-search me-1"></i>Lọc
                            </button>
                            <button @click="resetFilter" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── BẢNG CHẤM CÔNG ── -->
        <div class="col-12">
            <div class="card border-top border-4 border-primary">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0">
                        <i class="fa-solid fa-list-check me-2 text-primary"></i>
                        Danh Sách Chấm Công — {{ formatDateDisplay(filter.ngay) }}
                        <span class="badge bg-primary ms-1">{{ list_cham_cong.length }}</span>
                    </h6>
                    <!-- Xác nhận hàng loạt -->
                    <button v-if="selected.length > 0" @click="xacNhanHangLoat" class="btn btn-success btn-sm"
                        :disabled="processing">
                        <i class="fa-solid fa-check-double me-1"></i>
                        Xác nhận {{ selected.length }} bản ghi
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0" style="font-size:0.85rem">
                            <thead class="table-light">
                                <tr class="text-center text-nowrap">
                                    <th style="width:36px">
                                        <input type="checkbox" class="form-check-input" v-model="selectAll"
                                            @change="toggleSelectAll">
                                    </th>
                                    <th>#</th>
                                    <th class="text-start">Nhân Viên</th>
                                    <th>Ca</th>
                                    <th>Giờ Vào</th>
                                    <th>Giờ Ra</th>
                                    <th>Số Giờ</th>
                                    <th>Ảnh</th>
                                    <th>Trạng Thái</th>
                                    <th>Ghi Chú NV</th>
                                    <th>Thao Tác Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="list_cham_cong.length">
                                    <tr v-for="(v, k) in list_cham_cong" :key="v.id" class="align-middle"
                                        :class="{ 'table-warning': v.trang_thai === 3 }">
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" :value="v.id"
                                                v-model="selected" :disabled="v.trang_thai !== 1 || processing">
                                        </td>
                                        <td class="text-center">{{ k + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ v.nhan_vien?.ho_va_ten }}</div>
                                            <div class="text-muted small">{{ v.nhan_vien?.email }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span :class="caBadge(v.ca_lam)" class="badge">{{ caLabel(v.ca_lam)
                                            }}</span>
                                        </td>
                                        <td class="text-center fw-semibold text-success">{{ v.gio_vao || '—' }}</td>
                                        <td class="text-center fw-semibold text-danger">{{ v.gio_ra || '—' }}</td>
                                        <td class="text-center">
                                            <span v-if="v.so_gio_lam" class="fw-bold">{{ v.so_gio_lam }}h</span>
                                            <span v-else class="text-muted">—</span>
                                        </td>
                                        <td class="text-center">
                                            <!-- Nút xem ảnh -->
                                            <button @click="xemAnh(v)" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal" data-bs-target="#modalAnh"
                                                title="Xem ảnh check-in/out">
                                                <i class="fa-solid fa-image"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <span :class="trangThaiBadge(v.trang_thai)" class="badge">
                                                {{ trangThaiLabel(v.trang_thai) }}
                                            </span>
                                            <div v-if="v.ghi_chu_admin" class="text-muted small mt-1"
                                                style="font-size:0.7rem">
                                                {{ v.ghi_chu_admin }}
                                            </div>
                                        </td>
                                        <td style="max-width:120px">
                                            <span class="text-muted small text-truncate d-block"
                                                :title="v.ghi_chu_nhan_vien">
                                                {{ v.ghi_chu_nhan_vien || '—' }}
                                            </span>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <!-- Xác nhận -->
                                            <button v-if="v.trang_thai === 1" @click="xacNhan(v)"
                                                class="btn btn-sm btn-success me-1" title="Xác nhận hợp lệ"
                                                :disabled="processing">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <!-- Nghi ngờ -->
                                            <button v-if="v.trang_thai === 1 || v.trang_thai === 2"
                                                @click="openNghiNgo(v)" data-bs-toggle="modal"
                                                data-bs-target="#modalNghiNgo" class="btn btn-sm btn-warning"
                                                title="Đánh dấu nghi ngờ" :disabled="processing">
                                                <i class="fa-solid fa-flag"></i>
                                            </button>
                                            <!-- Đã xác nhận -->
                                            <span v-if="v.trang_thai === 2" class="text-success small fw-semibold">
                                                <i class="fa-solid fa-circle-check"></i> OK
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                                        Không có dữ liệu chấm công
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── MODAL XEM ẢNH ── -->
        <div class="modal fade" id="modalAnh" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" v-if="anhItem">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-image me-2 text-info"></i>
                            Ảnh xác nhận — {{ anhItem.nhan_vien?.ho_va_ten }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="anh-box">
                                    <div class="anh-label bg-success text-white">
                                        <i class="fa-solid fa-right-to-bracket me-1"></i>
                                        Ảnh Check-in — {{ anhItem.gio_vao }}
                                    </div>
                                    <img v-if="anhDetail?.url_anh_checkin" :src="anhDetail.url_anh_checkin"
                                        class="anh-img" alt="Check-in" />
                                    <div v-else class="anh-placeholder">
                                        <i class="fa-solid fa-image-slash fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small mb-0">Chưa có ảnh check-in</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="anh-box">
                                    <div class="anh-label bg-danger text-white">
                                        <i class="fa-solid fa-right-from-bracket me-1"></i>
                                        Ảnh Check-out — {{ anhItem.gio_ra || '—' }}
                                    </div>
                                    <img v-if="anhDetail?.url_anh_checkout" :src="anhDetail.url_anh_checkout"
                                        class="anh-img" alt="Check-out" />
                                    <div v-else class="anh-placeholder">
                                        <i class="fa-solid fa-image-slash fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small mb-0">Chưa có ảnh check-out</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Thông tin tóm tắt -->
                        <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded">
                            <div><span class="text-muted small">Ca:</span> <strong>{{ caLabel(anhItem.ca_lam)
                            }}</strong></div>
                            <div><span class="text-muted small">Giờ vào:</span> <strong class="text-success">{{
                                anhItem.gio_vao
                                    }}</strong></div>
                            <div><span class="text-muted small">Giờ ra:</span> <strong class="text-danger">{{
                                anhItem.gio_ra ||
                                '—' }}</strong></div>
                            <div><span class="text-muted small">Tổng giờ:</span> <strong>{{ anhItem.so_gio_lam ?
                                anhItem.so_gio_lam + 'h' : '—' }}</strong></div>
                            <div><span class="text-muted small">Trạng thái:</span>
                                <span :class="trangThaiBadge(anhItem.trang_thai)" class="badge ms-1">{{
                                    trangThaiLabel(anhItem.trang_thai) }}</span>
                            </div>
                        </div>
                        <div v-if="anhItem.ghi_chu_nhan_vien" class="mt-2 p-2 border rounded small">
                            <span class="text-muted">Ghi chú NV:</span> {{ anhItem.ghi_chu_nhan_vien }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button v-if="anhItem.trang_thai === 1" @click="xacNhan(anhItem)" class="btn btn-success"
                            data-bs-dismiss="modal" :disabled="processing">
                            <i class="fa-solid fa-check me-1"></i>Xác nhận hợp lệ
                        </button>
                        <button v-if="anhItem.trang_thai === 1" @click="openNghiNgo(anhItem)" class="btn btn-warning"
                            data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalNghiNgo">
                            <i class="fa-solid fa-flag me-1"></i>Đánh dấu nghi ngờ
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── MODAL ĐÁNH DẤU NGHI NGỜ ── -->
        <div class="modal fade" id="modalNghiNgo" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning">
                            <i class="fa-solid fa-flag me-2"></i>Đánh Dấu Nghi Ngờ
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning py-2 mb-3">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            Nhân viên: <strong>{{ nghiNgoItem?.nhan_vien?.ho_va_ten }}</strong>
                        </div>
                        <label class="form-label fw-semibold">Lý do nghi ngờ <span class="text-danger">*</span></label>
                        <textarea v-model="ghiChuNghiNgo" class="form-control" rows="3"
                            placeholder="VD: Ảnh check-in không rõ mặt, ảnh trùng lặp, thời gian bất thường..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button @click="confirmNghiNgo" class="btn btn-warning" data-bs-dismiss="modal"
                            :disabled="processing">
                            <i class="fa-solid fa-flag me-1"></i>Xác nhận đánh dấu
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';

const API = 'http://127.0.0.1:8000/api/admin/cham-cong';
const API_NV = 'http://127.0.0.1:8000/api/admin/nhan-vien/data';
const today = () => new Date().toISOString().split('T')[0];

export default {
    data() {
        return {
            list_cham_cong: [],
            list_nhan_vien: [],
            thong_ke: {},
            loading: false,
            loadingNhanVien: false,
            processing: false,
            anhItem: null,
            anhDetail: null,
            nghiNgoItem: null,
            ghiChuNghiNgo: '',
            selected: [],
            selectAll: false,

            filter: {
                ngay: today(),
                ca_lam: '',
                trang_thai: '',
                id_nhan_vien: '',
            },
        };
    },

    computed: {
        statCards() {
            const tk = this.thong_ke;
            return [
                { label: 'Tổng check-in', value: tk.tong_checkin || 0, icon: 'fa-solid fa-clock', bg: 'bg-primary bg-opacity-10 text-primary', color: 'text-primary' },
                { label: 'Đã check-out', value: tk.da_checkout || 0, icon: 'fa-solid fa-circle-check', bg: 'bg-success bg-opacity-10 text-success', color: 'text-success' },
                { label: 'Chưa check-out', value: tk.chua_checkout || 0, icon: 'fa-solid fa-hourglass-half', bg: 'bg-warning bg-opacity-10 text-warning', color: 'text-warning' },
                { label: 'Nghi ngờ', value: tk.nghi_ngo || 0, icon: 'fa-solid fa-flag', bg: 'bg-danger bg-opacity-10 text-danger', color: 'text-danger' },
            ];
        },
    },

    mounted() {
        this.loadData();
        this.loadNhanVien();
    },

    methods: {
        authHeader() {
            return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') };
        },

        request(method, url, data = null, config = {}) {
            return axios({
                method,
                url,
                data,
                ...config,
                headers: { ...this.authHeader(), ...(config.headers || {}) },
            });
        },

        buildParams() {
            const params = { ngay: this.filter.ngay };
            if (this.filter.ca_lam) params.ca_lam = this.filter.ca_lam;
            if (this.filter.trang_thai) params.trang_thai = this.filter.trang_thai;
            if (this.filter.id_nhan_vien) params.id_nhan_vien = this.filter.id_nhan_vien;
            return params;
        },

        loadData() {
            if (this.loading) return;
            this.loading = true;

            this.request('get', `${API}/data`, null, { params: this.buildParams() })
                .then(res => {
                    if (res.data.status) {
                        this.list_cham_cong = Array.isArray(res.data.data) ? res.data.data : [];
                        this.thong_ke = res.data.thong_ke || {};
                        this.selected = [];
                        this.selectAll = false;
                    } else {
                        this.$toast.error(res.data.message || 'Không tải được dữ liệu chấm công.');
                    }
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.loading = false));
        },

        loadNhanVien() {
            if (this.loadingNhanVien) return;
            this.loadingNhanVien = true;

            this.request('get', API_NV)
                .then(res => {
                    this.list_nhan_vien = Array.isArray(res.data) ? res.data : (res.data.data || []);
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.loadingNhanVien = false));
        },

        resetFilter() {
            this.filter = { ngay: today(), ca_lam: '', trang_thai: '', id_nhan_vien: '' };
            this.loadData();
        },

        toggleSelectAll() {
            this.selected = this.selectAll
                ? this.list_cham_cong.filter(r => r.trang_thai === 1).map(r => r.id)
                : [];
        },

        // ── XEM ẢNH ──
        xemAnh(v) {
            this.anhItem = v;
            this.anhDetail = null;
            this.request('get', `${API}/xem-anh/${v.id}`)
                .then(async res => {
                    if (res.data.status) {
                        const data = res.data.data;
                        // Fetch ảnh từ các endpoint mới với xác thực
                        const anhData = {};

                        if (data.url_anh_checkin) {
                            try {
                                const resCk = await this.request('get', `${API}/download-anh-checkin/${v.id}`, { responseType: 'blob' });
                                anhData.url_anh_checkin = URL.createObjectURL(resCk.data);
                            } catch (err) {
                                console.error('Lỗi tải ảnh check-in:', err);
                                anhData.url_anh_checkin = null;
                            }
                        }

                        if (data.url_anh_checkout) {
                            try {
                                const resOut = await this.request('get', `${API}/download-anh-checkout/${v.id}`, { responseType: 'blob' });
                                anhData.url_anh_checkout = URL.createObjectURL(resOut.data);
                            } catch (err) {
                                console.error('Lỗi tải ảnh check-out:', err);
                                anhData.url_anh_checkout = null;
                            }
                        }

                        this.anhDetail = { ...data, ...anhData };
                    } else {
                        this.$toast.error(res.data.message || 'Không tải được ảnh chấm công.');
                    }
                })
                .catch(err => this.handleError(err));
        },

        // ── XÁC NHẬN ──
        xacNhan(v) {
            if (this.processing) return;
            this.processing = true;

            this.request('post', `${API}/xac-nhan`, { id: v.id })
                .then(res => {
                    if (res.data.status) { this.$toast.success(res.data.message); this.loadData(); }
                    else this.$toast.error(res.data.message);
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.processing = false));
        },

        // ── NGHI NGỜ ──
        openNghiNgo(v) { this.nghiNgoItem = v; this.ghiChuNghiNgo = ''; },
        confirmNghiNgo() {
            if (!this.ghiChuNghiNgo.trim()) { this.$toast.error('Vui lòng nhập lý do nghi ngờ.'); return; }
            if (!this.nghiNgoItem || this.processing) return;
            this.processing = true;

            this.request('post', `${API}/nghi-ngo`, {
                id: this.nghiNgoItem.id,
                ghi_chu: this.ghiChuNghiNgo,
            })
                .then(res => {
                    if (res.data.status) { this.$toast.success(res.data.message); this.loadData(); }
                    else this.$toast.error(res.data.message);
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.processing = false));
        },

        // ── XÁC NHẬN HÀNG LOẠT ──
        xacNhanHangLoat() {
            if (!this.selected.length || this.processing) return;
            this.processing = true;

            this.request('post', `${API}/xac-nhan-hang-loat`, { ids: this.selected })
                .then(res => {
                    if (res.data.status) { this.$toast.success(res.data.message); this.loadData(); }
                    else this.$toast.error(res.data.message);
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.processing = false));
        },

        // ── HELPERS ──
        handleError(err) {
            const data = err?.response?.data;
            if (data?.errors) {
                Object.values(data.errors).flat().forEach(message => this.$toast.error(message));
                return;
            }
            this.$toast.error(data?.message || 'Đã có lỗi xảy ra.');
        },

        formatDateDisplay(d) { return d ? new Date(d).toLocaleDateString('vi-VN', { weekday: 'long', day: '2-digit', month: '2-digit', year: 'numeric' }) : ''; },
        caLabel(ca) { return { sang: 'Ca Sáng', chieu: 'Ca Chiều', toi: 'Ca Tối' }[ca] || ca; },
        caBadge(ca) { return { sang: 'bg-info text-dark', chieu: 'bg-warning text-dark', toi: 'bg-secondary' }[ca] || 'bg-light text-dark'; },
        trangThaiLabel(t) { return ['Chưa ra', 'Đã check-out', 'Xác nhận ✓', 'Nghi ngờ ⚑'][t] ?? '—'; },
        trangThaiBadge(t) { return ['bg-warning text-dark', 'bg-primary', 'bg-success', 'bg-danger'][t] ?? 'bg-secondary'; },
    },
};
</script>

<style scoped>
.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.stat-num {
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 2px;
}

.anh-box {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}

.anh-label {
    padding: 8px 12px;
    font-size: 0.82rem;
    font-weight: 600;
}

.anh-img {
    width: 100%;
    height: 240px;
    object-fit: cover;
    display: block;
}

.anh-placeholder {
    height: 240px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
}
</style>
