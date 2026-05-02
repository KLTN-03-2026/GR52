<template>
    <div class="row g-3">

        <!-- ══ STAT CARDS ══ -->
        <div class="col-12">
            <div class="row g-3">
                <div class="col-6 col-md-3" v-for="s in statCards" :key="s.label">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3 py-3">
                            <div class="stat-icon" :class="s.bg"><i :class="s.icon"></i></div>
                            <div>
                                <div class="stat-num" :class="s.color">{{ s.value }}</div>
                                <div class="stat-label">{{ s.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ FILTER ══ -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fa-solid fa-search text-muted"></i></span>
                                <input v-model="filter.search" type="text"
                                    class="form-control" placeholder="Tìm tên nhân viên..."
                                    @input="currentPage=1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select v-model="filter.loai_nghi" class="form-select form-select-sm" @change="currentPage=1">
                                <option value="">Tất cả loại</option>
                                <option value="phep_nam">Phép năm</option>
                                <option value="om">Nghỉ ốm</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select v-model="filter.trang_thai" class="form-select form-select-sm" @change="currentPage=1">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1">Chờ duyệt</option>
                                <option value="2">Đã duyệt</option>
                                <option value="3">Từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select v-model="filter.thang" class="form-select form-select-sm" @change="loadDon()">
                                <option v-for="i in 12" :key="i" :value="i">Tháng {{ i }}</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input v-model="filter.nam" type="number" class="form-control form-control-sm"
                                @change="loadDon()">
                        </div>
                        <div class="col-auto ms-auto d-flex gap-2">
                            <button @click="resetFilter()" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-rotate-left me-1"></i>Reset
                            </button>
                            <button @click="xuatBaoCao()" class="btn btn-success btn-sm">
                                <i class="fa-regular fa-file-excel me-1"></i>Xuất báo cáo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ BẢNG DANH SÁCH ══ -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0 fw-bold">
                        <i class="fa-solid fa-file-lines me-2 text-primary"></i>
                        Danh Sách Đơn Nghỉ Phép
                        <span class="badge bg-primary ms-1">{{ filtered.length }}</span>
                    </h6>
                    <!-- Tabs trạng thái -->
                    <div class="d-flex gap-1 flex-wrap">
                        <button v-for="tab in tabs" :key="tab.value"
                            @click="filter.trang_thai = tab.value; currentPage=1"
                            :class="['btn btn-sm', filter.trang_thai == tab.value ? tab.activeClass : 'btn-outline-secondary']"
                            style="font-size:.75rem">
                            {{ tab.label }}
                            <span class="badge ms-1 rounded-pill" :class="tab.badgeClass">
                                {{ countByStatus(tab.value) }}
                            </span>
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-nowrap" style="font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.04em">
                                    <th class="ps-3">#</th>
                                    <th>Nhân Viên</th>
                                    <th class="text-center">Loại Nghỉ</th>
                                    <th class="text-center">Từ Ngày</th>
                                    <th class="text-center">Đến Ngày</th>
                                    <th class="text-center">Số Ngày</th>
                                    <th>Lý Do</th>
                                    <th class="text-center">Trạng Thái</th>
                                    <th class="text-center">Người Duyệt</th>
                                    <th class="text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="paginated.length">
                                    <tr v-for="(v, k) in paginated" :key="v.id"
                                        class="align-middle" style="font-size:.875rem"
                                        :class="rowClass(v.trang_thai)">

                                        <td class="ps-3 text-muted">{{ (currentPage-1)*pageSize + k + 1 }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="nv-avatar" :style="{background: avatarColor(v.id_nhan_vien)}">
                                                    {{ initials(v.nhan_vien?.ho_va_ten) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold" style="font-size:.875rem">{{ v.nhan_vien?.ho_va_ten }}</div>
                                                    <div style="font-size:.72rem;color:#6b7280">{{ v.nhan_vien?.phong_ban?.ten_phong_ban }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <span :class="loaiBadge(v.loai_nghi)" class="badge">
                                                {{ loaiLabel(v.loai_nghi) }}
                                            </span>
                                        </td>

                                        <td class="text-center text-nowrap">{{ formatDate(v.ngay_bat_dau) }}</td>
                                        <td class="text-center text-nowrap">{{ formatDate(v.ngay_ket_thuc) }}</td>

                                        <td class="text-center">
                                            <span class="fw-bold text-primary">{{ v.so_ngay }}</span>
                                            <span class="text-muted" style="font-size:.72rem"> ngày</span>
                                        </td>

                                        <td style="max-width:180px">
                                            <span class="text-truncate d-block" style="font-size:.82rem" :title="v.ly_do">
                                                {{ v.ly_do }}
                                            </span>
                                            <span v-if="v.nguoi_thay_the" style="font-size:.72rem;color:#6b7280">
                                                <i class="fa-solid fa-user-clock me-1"></i>Thay thế: {{ v.nguoi_thay_the }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span :class="trangThaiBadge(v.trang_thai)" class="badge">
                                                {{ trangThaiLabel(v.trang_thai) }}
                                            </span>
                                        </td>

                                        <td class="text-center" style="font-size:.78rem;color:#6b7280">
                                            <template v-if="v.trang_thai >= 2">
                                                <div>{{ v.nguoi_duyet?.ho_va_ten || 'Admin' }}</div>
                                                <div>{{ formatDate(v.ngay_duyet) }}</div>
                                            </template>
                                            <span v-else class="text-muted">—</span>
                                        </td>

                                        <td class="text-center text-nowrap">
                                            <!-- XEM CHI TIẾT -->
                                            <button @click="openXem(v)"
                                                data-bs-toggle="modal" data-bs-target="#modalXem"
                                                class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>

                                            <!-- DUYỆT & TỪ CHỐI chỉ khi đang chờ -->
                                            <template v-if="v.trang_thai === 1">
                                                <button @click="openDuyet(v)"
                                                    data-bs-toggle="modal" data-bs-target="#modalDuyet"
                                                    class="btn btn-sm btn-success me-1" title="Phê duyệt">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                                <button @click="openTuChoi(v)"
                                                    data-bs-toggle="modal" data-bs-target="#modalTuChoi"
                                                    class="btn btn-sm btn-warning me-1" title="Từ chối">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </template>

                                            <!-- XÓA (không xóa đơn đã duyệt) -->
                                            <button @click="setDelete(v)"
                                                data-bs-toggle="modal" data-bs-target="#modalXoa"
                                                class="btn btn-sm btn-danger"
                                                :disabled="v.trang_thai === 2"
                                                title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fa-3x d-block mb-3" style="color:#d1d5db"></i>
                                        Không có đơn nghỉ phép nào
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Phân trang -->
                <div class="card-footer bg-white d-flex align-items-center justify-content-between flex-wrap gap-2"
                    v-if="filtered.length">
                    <div style="font-size:.82rem;color:#6b7280">
                        Hiển thị {{ (currentPage-1)*pageSize+1 }}–{{ Math.min(currentPage*pageSize, filtered.length) }}
                        / {{ filtered.length }} đơn
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <select v-model="pageSize" class="form-select form-select-sm w-auto" @change="currentPage=1">
                            <option :value="10">10/trang</option>
                            <option :value="20">20/trang</option>
                        </select>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item" :class="{disabled: currentPage===1}">
                                    <a class="page-link" style="cursor:pointer" @click="currentPage--">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>
                                </li>
                                <li v-for="p in totalPages" :key="p" class="page-item" :class="{active: p===currentPage}">
                                    <a class="page-link" style="cursor:pointer" @click="currentPage=p">{{ p }}</a>
                                </li>
                                <li class="page-item" :class="{disabled: currentPage===totalPages}">
                                    <a class="page-link" style="cursor:pointer" @click="currentPage++">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <!-- ══ MODAL XEM CHI TIẾT ══ -->
        <div class="modal fade" id="modalXem" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" v-if="xem_item">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-file-lines me-2 text-info"></i>Chi Tiết Đơn Nghỉ Phép
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Profile nhân viên -->
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-light mb-3">
                            <div class="nv-avatar nv-avatar-lg"
                                :style="{background: avatarColor(xem_item.id_nhan_vien)}">
                                {{ initials(xem_item.nhan_vien?.ho_va_ten) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ xem_item.nhan_vien?.ho_va_ten }}</div>
                                <div class="text-muted small">{{ xem_item.nhan_vien?.phong_ban?.ten_phong_ban }}</div>
                                <span :class="trangThaiBadge(xem_item.trang_thai)" class="badge mt-1">
                                    {{ trangThaiLabel(xem_item.trang_thai) }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-list">
                            <div class="detail-row">
                                <span class="detail-lbl">Loại nghỉ</span>
                                <span class="detail-val">
                                    <span :class="loaiBadge(xem_item.loai_nghi)" class="badge">
                                        {{ loaiLabel(xem_item.loai_nghi) }}
                                    </span>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-lbl">Thời gian</span>
                                <span class="detail-val">
                                    {{ formatDate(xem_item.ngay_bat_dau) }} →
                                    {{ formatDate(xem_item.ngay_ket_thuc) }}
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-lbl">Số ngày nghỉ</span>
                                <span class="detail-val fw-bold text-primary">{{ xem_item.so_ngay }} ngày làm việc</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-lbl">Lý do</span>
                                <span class="detail-val">{{ xem_item.ly_do }}</span>
                            </div>
                            <div class="detail-row" v-if="xem_item.nguoi_thay_the">
                                <span class="detail-lbl">Người thay thế</span>
                                <span class="detail-val">{{ xem_item.nguoi_thay_the }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-lbl">Ngày gửi đơn</span>
                                <span class="detail-val">{{ formatDate(xem_item.created_at) }}</span>
                            </div>
                            <template v-if="xem_item.trang_thai >= 2">
                                <div class="detail-row">
                                    <span class="detail-lbl">{{ xem_item.trang_thai === 2 ? 'Duyệt bởi' : 'Từ chối bởi' }}</span>
                                    <span class="detail-val">{{ xem_item.nguoi_duyet?.ho_va_ten }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-lbl">Ngày xử lý</span>
                                    <span class="detail-val">{{ formatDate(xem_item.ngay_duyet) }}</span>
                                </div>
                            </template>
                            <div class="detail-row" v-if="xem_item.ly_do_tu_choi">
                                <span class="detail-lbl text-danger">Lý do từ chối</span>
                                <span class="detail-val text-danger">{{ xem_item.ly_do_tu_choi }}</span>
                            </div>
                        </div>

                        <!-- Ảnh hưởng lương -->
                        <div class="luong-impact mt-3" v-if="xem_item.trang_thai === 2">
                            <div class="luong-impact-header">
                                <i class="fa-solid fa-calculator me-2"></i>Ảnh hưởng đến lương
                            </div>
                            <div class="luong-impact-body">
                                <div class="d-flex justify-content-between mb-1" style="font-size:.82rem">
                                    <span>Số ngày nghỉ:</span>
                                    <strong>{{ xem_item.so_ngay }} ngày</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1" style="font-size:.82rem">
                                    <span>Loại:</span>
                                    <strong>{{ loaiLabel(xem_item.loai_nghi) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between" style="font-size:.82rem">
                                    <span>Có lương:</span>
                                    <strong class="text-success">{{ soNgayCoLuong(xem_item) }} ngày</strong>
                                </div>
                                <div class="d-flex justify-content-between" style="font-size:.82rem">
                                    <span>Trừ lương:</span>
                                    <strong :class="soNgayKhongLuong(xem_item) > 0 ? 'text-danger' : 'text-muted'">
                                        {{ soNgayKhongLuong(xem_item) }} ngày
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <template v-if="xem_item.trang_thai === 1">
                            <button @click="openDuyet(xem_item)"
                                data-bs-dismiss="modal"
                                data-bs-toggle="modal" data-bs-target="#modalDuyet"
                                class="btn btn-success">
                                <i class="fa-solid fa-check me-1"></i>Phê duyệt
                            </button>
                            <button @click="openTuChoi(xem_item)"
                                data-bs-dismiss="modal"
                                data-bs-toggle="modal" data-bs-target="#modalTuChoi"
                                class="btn btn-warning">
                                <i class="fa-solid fa-xmark me-1"></i>Từ chối
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>


        <!-- ══ MODAL DUYỆT ══ -->
        <div class="modal fade" id="modalDuyet" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" v-if="duyet_item">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-success">
                            <i class="fa-solid fa-circle-check me-2"></i>Phê Duyệt Đơn Nghỉ Phép
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tóm tắt đơn -->
                        <div class="don-summary mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="nv-avatar" :style="{background: avatarColor(duyet_item.id_nhan_vien)}">
                                    {{ initials(duyet_item.nhan_vien?.ho_va_ten) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ duyet_item.nhan_vien?.ho_va_ten }}</div>
                                    <div class="text-muted small">{{ loaiLabel(duyet_item.loai_nghi) }} · {{ duyet_item.so_ngay }} ngày</div>
                                </div>
                            </div>
                            <div class="text-muted small">
                                {{ formatDate(duyet_item.ngay_bat_dau) }} → {{ formatDate(duyet_item.ngay_ket_thuc) }}
                            </div>
                        </div>

                        <!-- Ảnh hưởng lương khi duyệt -->
                        <div class="luong-impact mb-3">
                            <div class="luong-impact-header">
                                <i class="fa-solid fa-money-bill-wave me-2"></i>Ghi nhận vào bảng lương
                            </div>
                            <div class="luong-impact-body">
                                <div class="d-flex justify-content-between mb-2" style="font-size:.86rem">
                                    <span>Hạn mức:</span>
                                    <strong>{{ hanMucLabel(duyet_item.loai_nghi) }}</strong>
                                </div>
                                <div class="text-muted small">
                                    Hệ thống tự tách số ngày có lương và số ngày trừ lương khi duyệt theo số dư phép còn lại.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ghi chú khi duyệt</label>
                            <input v-model="duyet_form.ghi_chu_duyet" type="text"
                                class="form-control" placeholder="VD: Chấp thuận, nhớ bàn giao công việc...">
                        </div>

                        <div class="alert alert-success py-2 mb-0" style="font-size:.78rem">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Sau khi phê duyệt, thông tin sẽ được ghi nhận vào hệ thống tính lương tháng
                            {{ new Date().getMonth() + 1 }}/{{ new Date().getFullYear() }}.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button @click="duyet()" class="btn btn-success" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check me-1"></i>Xác nhận phê duyệt
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- ══ MODAL TỪ CHỐI ══ -->
        <div class="modal fade" id="modalTuChoi" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" v-if="tu_choi_item">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-warning">
                            <i class="fa-solid fa-hand-stop me-2"></i>Từ Chối Đơn Nghỉ Phép
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="don-summary mb-3">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="nv-avatar" :style="{background: avatarColor(tu_choi_item.id_nhan_vien)}">
                                    {{ initials(tu_choi_item.nhan_vien?.ho_va_ten) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ tu_choi_item.nhan_vien?.ho_va_ten }}</div>
                                    <div class="text-muted small">
                                        {{ loaiLabel(tu_choi_item.loai_nghi) }} ·
                                        {{ formatDate(tu_choi_item.ngay_bat_dau) }} → {{ formatDate(tu_choi_item.ngay_ket_thuc) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Lý do từ chối <span class="text-danger">*</span>
                            </label>
                            <textarea v-model="ly_do_tu_choi" class="form-control" rows="3"
                                placeholder="Nhập lý do từ chối để nhân viên biết và điều chỉnh...">
                            </textarea>
                        </div>

                        <div class="alert alert-warning py-2 mb-0" style="font-size:.78rem">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            Nhân viên sẽ nhận được thông báo từ chối kèm lý do này.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button @click="tuChoi()" class="btn btn-warning text-dark" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i>Từ chối đơn
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- ══ MODAL XÓA ══ -->
        <div class="modal fade" id="modalXoa" tabindex="-1">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content" v-if="delete_item">
                    <div class="modal-body text-center p-4">
                        <div class="del-icon mb-3"><i class="fa-solid fa-trash-can"></i></div>
                        <h6 class="fw-bold mb-2">Xóa đơn nghỉ phép?</h6>
                        <p class="text-muted small">{{ delete_item.nhan_vien?.ho_va_ten }} · {{ loaiLabel(delete_item.loai_nghi) }}</p>
                    </div>
                    <div class="modal-footer border-0 pt-0 gap-2">
                        <button class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Hủy</button>
                        <button @click="xoa()" class="btn btn-danger flex-fill" data-bs-dismiss="modal">
                            <i class="fa-solid fa-trash me-1"></i>Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';

const API    = 'http://127.0.0.1:8000/api/admin/don-nghi-phep';
const COLORS = ['#1a56db','#059669','#d97706','#dc2626','#7c3aed','#0891b2','#c026d3'];
const CURRENT_MONTH = new Date().getMonth() + 1;
const CURRENT_YEAR = new Date().getFullYear();

export default {
    data() {
        return {
            list_raw:     [],
            xem_item:     null,
            duyet_item:   null,
            tu_choi_item: null,
            delete_item:  null,
            ly_do_tu_choi:'',
            loading: false,

            duyet_form: { ghi_chu_duyet: '' },

            filter: {
                search:     '',
                loai_nghi:  '',
                trang_thai: '',
                thang:      CURRENT_MONTH,
                nam:        CURRENT_YEAR,
            },

            currentPage: 1,
            pageSize:    10,

            tabs: [
                { value: '',  label: 'Tất cả',    activeClass: 'btn-secondary', badgeClass: 'bg-secondary'          },
                { value: '1', label: 'Chờ duyệt', activeClass: 'btn-warning',   badgeClass: 'bg-warning text-dark'  },
                { value: '2', label: 'Đã duyệt',  activeClass: 'btn-success',   badgeClass: 'bg-success'            },
                { value: '3', label: 'Từ chối',   activeClass: 'btn-danger',    badgeClass: 'bg-danger'             },
            ],
        };
    },

    computed: {
        filtered() {
            let list = [...this.list_raw];
            if (this.filter.search) {
                const q = this.filter.search.toLowerCase();
                list = list.filter(v => v.nhan_vien?.ho_va_ten?.toLowerCase().includes(q));
            }
            if (this.filter.loai_nghi)  list = list.filter(v => v.loai_nghi === this.filter.loai_nghi);
            if (this.filter.trang_thai) list = list.filter(v => String(v.trang_thai) === this.filter.trang_thai);
            return list;
        },

        totalPages() { return Math.ceil(this.filtered.length / this.pageSize) || 1; },

        paginated() {
            const s = (this.currentPage - 1) * this.pageSize;
            return this.filtered.slice(s, s + this.pageSize);
        },

        statCards() {
            const l = this.list_raw;
            const tongNgay = l.filter(v => v.trang_thai === 2).reduce((s, v) => s + (v.so_ngay || 0), 0);
            return [
                { label: 'Tổng đơn tháng', value: l.length,                                       icon: 'fa-solid fa-file-lines',     bg: 'bg-primary bg-opacity-10 text-primary',  color: 'text-primary'  },
                { label: 'Chờ phê duyệt',  value: l.filter(v => v.trang_thai === 1).length,       icon: 'fa-solid fa-hourglass-half', bg: 'bg-warning bg-opacity-10 text-warning',  color: 'text-warning'  },
                { label: 'Đã phê duyệt',   value: l.filter(v => v.trang_thai === 2).length,       icon: 'fa-solid fa-circle-check',   bg: 'bg-success bg-opacity-10 text-success',  color: 'text-success'  },
                { label: 'Tổng ngày đã duyệt', value: tongNgay + ' ngày',                         icon: 'fa-solid fa-calendar-minus', bg: 'bg-info bg-opacity-10 text-info',        color: 'text-info'     },
            ];
        },
    },

    mounted() { this.loadDon(); },

    methods: {
        authHeader() { return { Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien') }; },

        request(method, url, data = {}, config = {}) {
            const options = {
                ...config,
                headers: { ...this.authHeader(), ...(config.headers || {}) },
            };

            return method === 'get'
                ? axios.get(url, { ...options, params: data })
                : axios[method](url, data, options);
        },

        loadDon() {
            if (this.loading) return;
            this.loading = true;
            this.request('get', `${API}/data`, { thang: this.filter.thang, nam: this.filter.nam })
                .then(res => {
                    if (res.data.status) {
                        this.list_raw = Array.isArray(res.data.data) ? res.data.data : [];
                        this.currentPage = 1;
                    } else {
                        this.$toast.error(res.data.message || 'Không thể tải danh sách đơn.');
                    }
                })
                .catch(err => this.handleError(err))
                .finally(() => (this.loading = false));
        },

        resetFilter() {
            this.filter = { search: '', loai_nghi: '', trang_thai: '', thang: CURRENT_MONTH, nam: CURRENT_YEAR };
            this.loadDon();
        },

        // ── XEM ────────────────────────────────────────────────────────
        openXem(v) { this.xem_item = v; },

        // ── DUYỆT ──────────────────────────────────────────────────────
        openDuyet(v) {
            this.duyet_item = v;
            this.duyet_form = {
                ghi_chu_duyet: '',
            };
        },
        duyet() {
            this.request('post', `${API}/duyet`, {
                id:               this.duyet_item.id,
                id_nguoi_duyet:   this.getCurrentAdminId(),
                ghi_chu_duyet:    this.duyet_form.ghi_chu_duyet,
            })
                .then(res => {
                    if (res.data.status) { this.$toast.success(res.data.message); this.loadDon(); }
                    else this.$toast.error(res.data.message);
                })
                .catch(err => this.handleError(err));
        },

        // ── TỪ CHỐI ────────────────────────────────────────────────────
        openTuChoi(v) { this.tu_choi_item = v; this.ly_do_tu_choi = ''; },
        tuChoi() {
            if (!this.ly_do_tu_choi.trim()) { this.$toast.error('Vui lòng nhập lý do từ chối.'); return; }
            this.request('post', `${API}/tu-choi`, {
                id:              this.tu_choi_item.id,
                id_nguoi_duyet:  this.getCurrentAdminId(),
                ly_do_tu_choi:   this.ly_do_tu_choi,
                ghi_chu_duyet:   this.ly_do_tu_choi,
            })
                .then(res => {
                    if (res.data.status) { this.$toast.success(res.data.message); this.loadDon(); }
                    else this.$toast.error(res.data.message);
                })
                .catch(err => this.handleError(err));
        },

        // ── XÓA ────────────────────────────────────────────────────────
        setDelete(v) { this.delete_item = v; },
        xoa() {
            this.request('post', `${API}/delete`, { id: this.delete_item.id })
                .then(res => {
                    if (res.data.status) { this.$toast.success(res.data.message); this.loadDon(); }
                    else this.$toast.error(res.data.message);
                })
                .catch(err => this.handleError(err));
        },

        // ── XUẤT BÁO CÁO ───────────────────────────────────────────────
        xuatBaoCao() {
            this.request('get', `${API}/xuat-excel`, { thang: this.filter.thang, nam: this.filter.nam }, {
                responseType: 'blob',
            }).then(res => {
                const url  = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                link.href  = url;
                link.setAttribute('download', `nghi_phep_T${this.filter.thang}_${this.filter.nam}.xlsx`);
                document.body.appendChild(link);
                link.click(); link.remove();
            }).catch(() => this.$toast.error('Xuất báo cáo thất bại.'));
        },

        getCurrentAdminId() {
            try { return JSON.parse(localStorage.getItem('user_data'))?.id || 1; }
            catch { return 1; }
        },

        countByStatus(val) {
            if (val === '') return this.list_raw.length;
            return this.list_raw.filter(v => String(v.trang_thai) === String(val)).length;
        },

        handleError(err) {
            const data = err?.response?.data;
            if (data?.errors) Object.values(data.errors).forEach(msgs => msgs.forEach(m => this.$toast.error(m)));
            else this.$toast.error(data?.message || 'Đã có lỗi xảy ra.');
        },

        formatDate(d) { return d ? new Date(d).toLocaleDateString('vi-VN') : '—'; },
        initials(name) { return name ? name.split(' ').map(w => w[0]).slice(-2).join('').toUpperCase() : '?'; },
        avatarColor(id) { return COLORS[id % COLORS.length]; },

        loaiLabel(l)  { return { phep_nam:'Phép năm', om:'Nghỉ ốm' }[l] || l; },
        loaiBadge(l)  { return { phep_nam:'bg-info', om:'bg-warning text-dark' }[l] || 'bg-light text-dark'; },
        hanMucLabel(l) { return l === 'om' ? '5 ngày/năm, không cộng dồn' : '12 ngày/năm, được cộng dồn tối đa 2 năm'; },
        soNgayCoLuong(v) { return v?.so_ngay_co_luong ?? (['phep_nam', 'om'].includes(v?.loai_nghi) ? (v?.so_ngay || 0) : 0); },
        soNgayKhongLuong(v) { return v?.so_ngay_khong_luong ?? (['phep_nam', 'om'].includes(v?.loai_nghi) ? 0 : (v?.so_ngay || 0)); },
        trangThaiLabel(t) { return { 1:'Chờ duyệt', 2:'Đã duyệt', 3:'Từ chối' }[t] || '—'; },
        trangThaiBadge(t) { return { 1:'bg-warning text-dark', 2:'bg-success', 3:'bg-danger' }[t] || 'bg-secondary'; },
        rowClass(t) { return { 1:'', 2:'table-success', 3:'table-danger' }[t] ?? ''; },
    },
};
</script>

<style scoped>
.nv-avatar    { width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.78rem;flex-shrink:0; }
.nv-avatar-lg { width:52px;height:52px;font-size:1rem; }

.stat-icon    { width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0; }
.stat-num     { font-size:1.35rem;font-weight:700;line-height:1; }
.stat-label   { font-size:.72rem;color:#6b7280;margin-top:2px; }

.detail-list  { background:#f8fafc;border-radius:10px;padding:12px 16px; }
.detail-row   { display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #e2e8f0;font-size:.875rem; }
.detail-row:last-child { border-bottom:none; }
.detail-lbl   { color:#64748b;flex-shrink:0;width:130px; }
.detail-val   { font-weight:500;text-align:right; }

.don-summary  { background:#f8fafc;border-radius:10px;padding:12px 14px;border:1px solid #e2e8f0; }

.luong-impact { border-radius:10px;overflow:hidden;border:1px solid #e2e8f0; }
.luong-impact-header { background:#f1f5f9;padding:8px 14px;font-size:.8rem;font-weight:600;color:#374151;display:flex;align-items:center; }
.luong-impact-body   { padding:12px 14px; }

.del-icon { width:60px;height:60px;border-radius:50%;background:#fde8e8;color:#e02424;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto; }
</style>
