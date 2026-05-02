<template>
    <div class="row g-3">

        <!-- ── ĐỒNG HỒ + TRẠNG THÁI HÔM NAY ── -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-responsive">
                    <div class="row align-items-center g-3 g-md-4">
                        <div class="col-12 col-md-6 text-center text-md-start">
                            <div class="clock-display">{{ currentTime }}</div>
                            <div class="date-display">{{ currentDate }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row g-2 g-md-3">
                                <div class="col-6 col-md-4" v-for="ca in caStatus" :key="ca.key">
                                    <div class="ca-card" :class="ca.class">
                                        <div class="ca-name">{{ ca.label }}</div>
                                        <div class="ca-time">{{ ca.range }}</div>
                                        <div class="ca-status">{{ ca.status }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── PANEL CHECK-IN / CHECK-OUT ── -->
        <div class="col-12 col-lg-6">
            <div class="card border-top border-4 border-primary">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa-solid fa-fingerprint me-2 text-primary"></i>Chấm Công Hôm Nay</h6>
                </div>
                <div class="card-body p-responsive">

                    <!-- Chọn ca -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chọn ca làm việc</label>
                        <div class="action-btn-group">
                            <button v-for="ca in caOptions" :key="ca.value" @click="selectedCa = ca.value"
                                :class="['btn btn-sm', selectedCa === ca.value ? 'btn-primary' : 'btn-outline-secondary']">
                                <i :class="ca.icon + ' me-1'"></i>{{ ca.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Camera Preview -->
                    <div class="camera-wrapper mb-3">
                        <div class="camera-label">
                            <i class="fa-solid fa-camera me-1"></i>
                            {{ capturedImage ? 'Ảnh đã chụp' : 'Camera xác nhận' }}
                        </div>

                        <!-- Camera stream -->
                        <video v-if="!capturedImage && cameraActive" ref="videoEl" class="camera-video" autoplay
                            playsinline></video>

                        <!-- Placeholder khi chưa mở camera -->
                        <div v-if="!capturedImage && !cameraActive" class="camera-placeholder" @click="startCamera">
                            <i class="fa-solid fa-camera fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">Nhấn để mở camera</p>
                        </div>

                        <!-- Ảnh đã chụp -->
                        <img v-if="capturedImage" :src="capturedImage" class="camera-video" alt="Ảnh xác nhận" />

                        <!-- Canvas ẩn để capture -->
                        <canvas ref="canvasEl" style="display:none"></canvas>
                    </div>

                    <!-- Camera controls -->
                    <div class="action-btn-group mb-3">
                        <button v-if="!cameraActive && !capturedImage" @click="startCamera"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-video me-1"></i>Mở camera
                        </button>
                        <button v-if="cameraActive && !capturedImage" @click="capturePhoto"
                            class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-camera me-1"></i>Chụp ảnh
                        </button>
                        <button v-if="capturedImage" @click="retakePhoto" class="btn btn-outline-danger btn-sm">
                            <i class="fa-solid fa-rotate-left me-1"></i>Chụp lại
                        </button>
                    </div>

                    <!-- Ghi chú -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú (tuỳ chọn)</label>
                        <input v-model="ghiChu" type="text" class="form-control form-control-sm"
                            placeholder="VD: Làm việc từ xa hôm nay...">
                    </div>

                    <!-- Nút check-in / check-out -->
                    <div class="action-btn-group">
                        <button @click="checkIn()" class="btn btn-success" :disabled="!canCheckIn || loading"
                            v-if="!currentRecord || !currentRecord.gio_vao">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-right-to-bracket me-1"></i>
                            Check-in
                        </button>
                        <button @click="checkOut()" class="btn btn-danger flex-fill" :disabled="!canCheckOut || loading"
                            v-if="currentRecord && currentRecord.gio_vao && !currentRecord.gio_ra">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fa-solid fa-right-from-bracket me-1"></i>
                            Check-out
                        </button>
                    </div>

                    <!-- Đã check-out xong -->
                    <div v-if="currentRecord && currentRecord.gio_ra"
                        class="alert alert-success mt-3 mb-0 py-2 text-center">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        Đã hoàn thành ca {{ caLabel(currentRecord.ca_lam) }}.
                        Tổng <strong>{{ currentRecord.so_gio_lam }}h</strong> làm việc.
                    </div>

                </div>
            </div>
        </div>

        <!-- ── DANH SÁCH CHẤM CÔNG HÔM NAY + LỊCH SỬ ── -->
        <div class="col-12 col-lg-6">

            <!-- Hôm nay -->
            <div class="card border-top border-4 border-success mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa-solid fa-clock me-2 text-success"></i>Chấm công hôm nay</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-sm">
                        <thead class="table-light">
                            <tr class="text-center text-nowrap">
                                <th>Ca</th>
                                <th>Giờ Vào</th>
                                <th>Giờ Ra</th>
                                <th>Số Giờ</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in homNayList" :key="r.id" class="align-middle text-center">
                                <td><span :class="caBadge(r.ca_lam)" class="badge">{{ caLabel(r.ca_lam) }}</span></td>
                                <td class="fw-semibold text-success">{{ r.gio_vao || '—' }}</td>
                                <td class="fw-semibold text-danger">{{ r.gio_ra || '—' }}</td>
                                <td>{{ r.so_gio_lam ? r.so_gio_lam + 'h' : '—' }}</td>
                                <td><span :class="trangThaiBadge(r.trang_thai)" class="badge">{{
                                        trangThaiLabel(r.trang_thai) }}</span></td>
                            </tr>
                            <tr v-if="!homNayList.length">
                                <td colspan="5" class="text-center text-muted py-3">Chưa có bản ghi chấm công hôm nay
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lịch sử tháng -->
            <div class="card border-top border-4 border-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fa-solid fa-calendar-days me-2 text-secondary"></i>Lịch sử chấm công</h6>
                    <div class="d-flex gap-2">
                        <select v-model="filterThang" class="form-select form-select-sm w-auto" @change="loadLichSu">
                            <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                        </select>
                        <input v-model="filterNam" type="number" class="form-control form-control-sm" style="width:80px"
                            @change="loadLichSu">
                    </div>
                </div>
                <div class="card-body border-bottom py-2">
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="fw-bold text-primary fs-5">{{ lichSuStats.so_ngay }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Ngày công</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success fs-5">{{ lichSuStats.tong_gio }}h</div>
                            <div class="text-muted" style="font-size:0.75rem">Tổng giờ làm</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-warning fs-5">{{ lichSuStats.da_xac_nhan }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Đã xác nhận</div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height:300px;overflow-y:auto">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light sticky-top">
                            <tr class="text-center text-nowrap">
                                <th>Ngày</th>
                                <th>Ca</th>
                                <th>Vào</th>
                                <th>Ra</th>
                                <th>Giờ</th>
                                <th>TT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in lichSuList" :key="r.id" class="align-middle text-center">
                                <td class="text-nowrap">{{ formatDate(r.ngay_lam_viec) }}</td>
                                <td><span :class="caBadge(r.ca_lam)" class="badge">{{ caLabel(r.ca_lam) }}</span></td>
                                <td>{{ r.gio_vao || '—' }}</td>
                                <td>{{ r.gio_ra || '—' }}</td>
                                <td>{{ r.so_gio_lam ? r.so_gio_lam + 'h' : '—' }}</td>
                                <td><span :class="trangThaiBadge(r.trang_thai)" class="badge"
                                        style="font-size:0.65rem">{{ trangThaiLabel(r.trang_thai) }}</span></td>
                            </tr>
                            <tr v-if="!lichSuList.length">
                                <td colspan="6" class="text-center text-muted py-3">Không có dữ liệu</td>
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

const API = 'http://127.0.0.1:8000/api/nhan-vien/cham-cong';
const CURRENT_MONTH = new Date().getMonth() + 1;
const CURRENT_YEAR = new Date().getFullYear();
const emptyStats = () => ({ so_ngay: 0, tong_gio: '0.00', da_xac_nhan: 0 });

export default {
    data() {
        return {
            currentTime: '',
            currentDate: '',
            timer: null,
            loading: false,
            loadingPage: false,
            cameraActive: false,
            capturedImage: null,
            stream: null,

            selectedCa: '',
            ghiChu: '',

            homNayList: [],
            lichSuList: [],
            lichSuStats: emptyStats(),

            filterThang: CURRENT_MONTH,
            filterNam: CURRENT_YEAR,

            caOptions: [
                { value: 'sang', label: 'Sáng', icon: 'fa-solid fa-sun' },
                { value: 'chieu', label: 'Chiều', icon: 'fa-solid fa-cloud-sun' },
                { value: 'toi', label: 'Tối', icon: 'fa-solid fa-moon' },
            ],
        };
    },

    computed: {
        // Bản ghi của ca đang chọn hôm nay
        currentRecord() {
            if (!this.selectedCa) return null;
            return this.homNayList.find(r => r.ca_lam === this.selectedCa) || null;
        },

        canCheckIn() {
            return this.selectedCa && this.capturedImage && !this.currentRecord;
        },

        canCheckOut() {
            return this.capturedImage && this.currentRecord && !this.currentRecord.gio_ra;
        },

        caStatus() {
            const caMap = {
                sang: { label: 'Ca Sáng', range: '06:00 - 12:00' },
                chieu: { label: 'Ca Chiều', range: '12:00 - 18:00' },
                toi: { label: 'Ca Tối', range: '18:00 - 24:00' },
            };
            return ['sang', 'chieu', 'toi'].map(key => {
                const record = this.homNayList.find(r => r.ca_lam === key);
                let statusText = 'Chưa chấm', cls = 'ca-card-idle';
                if (record) {
                    if (record.gio_ra) { statusText = 'Hoàn thành'; cls = 'ca-card-done'; }
                    else { statusText = 'Đang làm'; cls = 'ca-card-active'; }
                }
                return { key, ...caMap[key], status: statusText, class: cls };
            });
        },
    },

    mounted() {
        this.updateTime();
        this.timer = setInterval(this.updateTime, 1000);
        this.refresh();
    },

    beforeUnmount() {
        clearInterval(this.timer);
        this.stopCamera();
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

        updateTime() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('vi-VN');
            this.currentDate = now.toLocaleDateString('vi-VN', { weekday: 'long', day: '2-digit', month: '2-digit', year: 'numeric' });
        },

        refresh() {
            if (this.loadingPage) return;
            this.loadingPage = true;

            Promise.all([this.loadHomNay(), this.loadLichSu()])
                .finally(() => (this.loadingPage = false));
        },

        // ── LOAD DATA ──
        loadHomNay() {
            return this.request('get', `${API}/hom-nay`)
                .then(res => {
                    if (res.data.status) this.homNayList = Array.isArray(res.data.data) ? res.data.data : [];
                    else this.$toast.error(res.data.message || 'Không tải được chấm công hôm nay.');
                })
                .catch(this.handleError);
        },

        loadLichSu() {
            return this.request('get', `${API}/lich-su`, null, {
                params: { thang: this.filterThang, nam: this.filterNam },
            }).then(res => {
                if (res.data.status) {
                    this.lichSuList = Array.isArray(res.data.data) ? res.data.data : [];
                    this.lichSuStats = this.buildHistoryStats(this.lichSuList, res.data);
                } else {
                    this.$toast.error(res.data.message || 'Không tải được lịch sử chấm công.');
                }
            }).catch(this.handleError);
        },

        // ── CAMERA ──
        startCamera() {
            if (!navigator.mediaDevices?.getUserMedia) {
                this.$toast.error('Trình duyệt không hỗ trợ camera.');
                return;
            }

            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
                .then(stream => {
                    this.stream = stream;
                    this.cameraActive = true;
                    this.$nextTick(() => {
                        this.$refs.videoEl.srcObject = stream;
                    });
                })
                .catch(() => this.$toast.error('Không thể truy cập camera. Vui lòng cấp quyền.'));
        },

        capturePhoto() {
            const video = this.$refs.videoEl;
            const canvas = this.$refs.canvasEl;
            if (!video || !canvas || !video.videoWidth) {
                this.$toast.error('Camera chưa sẵn sàng, vui lòng thử lại.');
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            this.capturedImage = canvas.toDataURL('image/jpeg', 0.8);
            this.stopCamera();
        },

        retakePhoto() {
            this.capturedImage = null;
            this.stopCamera();
            this.startCamera();
        },

        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
                this.stream = null;
            }
            this.cameraActive = false;
        },

        // ── CHECK-IN ──
        checkIn() {
            if (this.loading) return;
            if (!this.selectedCa) { this.$toast.error('Vui lòng chọn ca làm việc.'); return; }
            if (!this.capturedImage) { this.$toast.error('Vui lòng chụp ảnh xác nhận.'); return; }

            this.loading = true;
            this.request('post', `${API}/check-in`, {
                ca_lam: this.selectedCa,
                anh_check_in: this.capturedImage,
                ghi_chu_nhan_vien: this.ghiChu,
            })
                .then(res => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.resetActionState();
                        return Promise.all([this.loadHomNay(), this.loadLichSu()]);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.loading = false));
        },

        // ── CHECK-OUT ──
        checkOut() {
            if (this.loading) return;
            if (!this.capturedImage) { this.$toast.error('Vui lòng chụp ảnh xác nhận check-out.'); return; }
            if (!this.currentRecord) { this.$toast.error('Không tìm thấy bản ghi check-in.'); return; }

            this.loading = true;
            this.request('post', `${API}/check-out`, {
                id: this.currentRecord.id,
                anh_check_out: this.capturedImage,
                ghi_chu_nhan_vien: this.ghiChu,
            })
                .then(res => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.resetActionState();
                        return Promise.all([this.loadHomNay(), this.loadLichSu()]);
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.loading = false));
        },

        // ── HELPERS ──
        resetActionState() {
            this.capturedImage = null;
            this.ghiChu = '';
            this.stopCamera();
        },

        buildHistoryStats(rows, fallback = {}) {
            const uniqueDays = new Set(rows.map(row => row.ngay_lam_viec).filter(Boolean));
            const tongGio = rows.reduce((total, row) => total + Number(row.so_gio_lam || 0), 0);
            const daXacNhan = rows.filter(row => Number(row.trang_thai) === 2).length;

            return {
                so_ngay: fallback.tong_ngay ?? uniqueDays.size,
                tong_gio: fallback.tong_gio ?? tongGio.toFixed(2),
                da_xac_nhan: fallback.da_xac_nhan ?? daXacNhan,
            };
        },

        handleError(err) {
            const data = err?.response?.data;
            if (data?.errors) Object.values(data.errors).forEach(msgs => msgs.forEach(m => this.$toast.error(m)));
            else this.$toast.error(data?.message || 'Đã có lỗi xảy ra.');
        },

        formatDate(d) { return d ? new Date(d).toLocaleDateString('vi-VN') : '—'; },

        caLabel(ca) { return { sang: 'Sáng', chieu: 'Chiều', toi: 'Tối' }[ca] || ca; },
        caBadge(ca) { return { sang: 'bg-info text-dark', chieu: 'bg-warning text-dark', toi: 'bg-secondary' }[ca] || 'bg-light text-dark'; },

        trangThaiLabel(t) { return ['Chưa ra', 'Đã ra', 'Xác nhận', 'Nghi ngờ'][t] ?? '—'; },
        trangThaiBadge(t) { return ['bg-warning text-dark', 'bg-primary', 'bg-success', 'bg-danger'][t] ?? 'bg-secondary'; },
    },
};
</script>

<style scoped>
.clock-display {
    font-size: 2.8rem;
    font-weight: 700;
    color: var(--bs-primary);
    line-height: 1;
    font-variant-numeric: tabular-nums;
}

.date-display {
    font-size: 0.85rem;
    color: #6b7280;
    margin-top: 4px;
}

.ca-card {
    border-radius: 10px;
    padding: 10px 8px;
    text-align: center;
    border: 1px solid #e5e7eb;
}

.ca-name {
    font-weight: 600;
    font-size: 0.8rem;
}

.ca-time {
    font-size: 0.68rem;
    color: #9ca3af;
    margin-top: 2px;
}

.ca-status {
    font-size: 0.72rem;
    font-weight: 500;
    margin-top: 4px;
}

.ca-card-idle {
    background: #f9fafb;
}

.ca-card-active {
    background: #eff6ff;
    border-color: #3b82f6;
}

.ca-card-active .ca-name,
.ca-card-active .ca-status {
    color: #1d4ed8;
}

.ca-card-done {
    background: #f0fdf4;
    border-color: #22c55e;
}

.ca-card-done .ca-name,
.ca-card-done .ca-status {
    color: #15803d;
}

.camera-wrapper {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    overflow: hidden;
    background: #f9fafb;
}

.camera-label {
    padding: 6px 12px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
    background: #fff;
}

.camera-video {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}

.camera-placeholder {
    height: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.camera-placeholder:hover {
    background: #f3f4f6;
}

.table-sm td,
.table-sm th {
    padding: 6px 10px;
    font-size: 0.82rem;
}
</style>
