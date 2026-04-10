<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mt-2">
                    <h6><b>DANH SÁCH NHÂN VIÊN</b></h6>
                    <div>
                        <button @click="xuatExcel()" type="button" class="btn btn-success me-2">
                            <i class="fa-regular fa-file-excel"></i> Xuất Excel
                        </button>
                        <button id="open-add-modal" type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Thêm Mới
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-group mt-3 w-100 mb-3">
                        <input id="search-input" v-on:keyup.enter="TimKiemBE()" v-model="search.noi_dung" type="text"
                            class="form-control search-control border border-1 border-secondary"
                            placeholder="Search...">
                        <span class="position-absolute top-50 search-show translate-middle-y" style="left: 15px;"><i
                                class="bx bx-search"></i></span>
                        <button v-on:click="TimKiemBE()" class="btn btn-outline-secondary" type="button"
                            id="search-button">Tìm
                            Kiếm</button>
                          
                            
                    </div>

                    <div class="row g-3">
                        <template v-if="list_nhan_vien && list_nhan_vien.length">
                            <template v-for="(v, k) in list_nhan_vien" :key="v.id || k">
                                <div class="col-lg-4 col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title mb-2">{{ v.ho_va_ten }}</h5>
                                            <p class="mb-1"><strong>Email:</strong> {{ v.email || '-' }}</p>
                                            <p class="mb-1"><strong>Ngày sinh:</strong> {{ displayNgaySinh(v) || '-' }}
                                            </p>
                                            <p class="mb-1"><strong>Phone:</strong> {{ displaySoDienThoai(v) || '-' }}
                                            </p>
                                            <p class="mb-1"><strong>Địa chỉ:</strong> {{ displayDiaChi(v) || '-' }}</p>
                                            <p class="mb-1"><strong>Lương:</strong> {{ displayLuong(v) || '-' }}</p>
                                            <p class="mb-1"><strong>Chức vụ:</strong> {{ displayChucVu(v) || '-' }}</p>
                                            <p class="mb-1"><strong>Phòng ban:</strong> {{ displayPhongBan(v) || '-' }}
                                            </p>
                                        </div>
                                        <div class="card-footer text-center">
                                            <button class="btn btn-warning me-2" data-bs-toggle="modal"
                                                data-bs-target="#phanQuyenModal" v-on:click="loadChucNang(v)">Phân
                                                Quyền</button>
                                            <button class="btn btn-info me-2" data-bs-toggle="modal"
                                                data-bs-target="#hopDongModal"
                                                v-on:click="Object.assign(create_hop_dong, v)">Tạo Hợp Đồng</button>
                                            <button class="btn btn-primary me-2" data-bs-toggle="modal"
                                                data-bs-target="#capnhatDM"
                                                v-on:click="Object.assign(edit_nhan_vien, v)">Cập nhật</button>
                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#delModal"
                                                v-on:click="Object.assign(delete_nhan_vien, v)">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template v-else>
                            <div class="col-12 text-center py-4">Không có nhân viên nào.</div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm Mới Nhân Viên</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Họ Và Tên</label>
                            <input id="add-name" v-model="create_nhan_vien.ho_va_ten" type="text" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input id="add-email" v-model="create_nhan_vien.email" type="email" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Ngày Sinh</label>
                            <input id="add-birthday" v-model="create_nhan_vien.ngay_sinh" type="date"
                                class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Số Điện Thoại</label>
                            <input id="add-phone" v-model="create_nhan_vien.so_dien_thoai" type="text"
                                class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <input id="add-password" v-model="create_nhan_vien.password" type="password"
                                class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Địa Chỉ</label>
                            <input id="add-address" v-model="create_nhan_vien.dia_chi" type="text" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Lương Cơ Bản</label>
                            <input id="add-salary" v-model="create_nhan_vien.luong_co_ban" type="number"
                                class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Chức Vụ</label>
                            <select id="add-role" v-model="create_nhan_vien.id_chuc_vu" class="form-control mt-2">
                                <template v-for="(v, k) in list_chuc_vu" :key="k">
                                    <option v-bind:value="v.id">{{ v.ten_chuc_vu }}</option>
                                </template>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Phòng Ban</label>
                            <select id="add-department" v-model="create_nhan_vien.id_phong_ban"
                                class="form-control mt-2">
                                <template v-for="(v, k) in list_phong_ban" :key="k">
                                    <option v-bind:value="v.id">{{ v.ten_phong_ban }}</option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="add-submit" v-on:click="createNhanVien()" class="btn btn-primary"
                            data-bs-dismiss="modal">Thêm
                            Mới</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Xóa Nhân Viên</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                            <div class="d-flex align-items-center">
                                <div class="font-35 text-white"><i class="bx bxs-message-square-x"></i>
                                </div>
                                <div class="ms-1">
                                    <h6 class="mb-1 text-white">Bạn chắc chắc xóa nhân viên <b>{{
                                        delete_nhan_vien.ho_va_ten }}</b> này chứ
                                        !!!</h6>
                                    <div class="text-white text-nowrap"><b>LƯU Ý !!!</b> Điều này không thể khôi
                                        phục
                                        khi ấn xác nhận
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="delete-submit" type="button" class="btn btn-danger" data-bs-dismiss="modal"
                            v-on:click="xoaNhanVien()">Xác
                            nhận</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="phanQuyenModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Phân Quyền Cho Nhân Viên {{
                            phan_quyen_nhan_vien.ho_va_ten }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class='table table-bordered table-hover'>
                            <thead>
                                <tr>
                                    <th class='align-middle text-center'>#</th>
                                    <th class='align-middle text-center'>Tên Chức Năng</th>
                                    <th class='align-middle text-center'>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(v, k) in list_chuc_nang" :key="k">
                                    <tr>
                                        <th class="text-center align-middle">
                                            {{ k + 1 }}
                                        </th>
                                        <td class="align-middle">
                                            {{ v.ten_chuc_nang }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <button v-if="v.is_phan_quyen == 1" v-on:click="removeQuyen(v)"
                                                class="btn btn-primary">Đã Phân
                                                Quyền</button>
                                            <button v-else v-on:click="setQuyen(v)" class="btn btn-danger">Chưa Phân
                                                Quyền</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="capnhatDM" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Cập nhật Nhân Viên</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Họ Và Tên</label>
                            <input id="edit-name" v-model="edit_nhan_vien.ho_va_ten" type="text" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input id="edit-email" v-model="edit_nhan_vien.email" type="email" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Ngày Sinh</label>
                            <input id="edit-birthday" v-model="edit_nhan_vien.ngay_sinh" type="date"
                                class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Số Điện Thoại</label>
                            <input id="edit-phone" v-model="edit_nhan_vien.so_dien_thoai" type="text"
                                class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Địa Chỉ</label>
                            <input id="edit-address" v-model="edit_nhan_vien.dia_chi" type="text" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Lương Cơ Bản</label>
                            <input id="edit-salary" v-model="edit_nhan_vien.luong_co_ban" type="number"
                                class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Chức Vụ</label>
                            <select id="edit-role" v-model="edit_nhan_vien.id_chuc_vu" class="form-control mt-2">
                                <template v-for="(v, k) in list_chuc_vu" :key="k">
                                    <option v-bind:value="v.id">{{ v.ten_chuc_vu }}</option>
                                </template>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Phòng Ban</label>
                            <select id="edit-department" v-model="edit_nhan_vien.id_phong_ban"
                                class="form-control mt-2">
                                <template v-for="(v, k) in list_phong_ban" :key="k">
                                    <option v-bind:value="v.id">{{ v.ten_phong_ban }}</option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="edit-submit" type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            v-on:click="capNhatNhanVien()">Cập
                            nhật</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="hopDongModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tạo Hợp Đồng Nhân Viên</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-label fw-bold">Chọn Hợp Đồng</label>
                                <select v-model="create_hop_dong.id_loai_hop_dong" v-on:change="loadNoiDung()"
                                    class="form-control">
                                    <template v-for="(v, k) in list_hop_dong" :key="k">
                                        <option v-bind:value="v.id">{{ v.ten_hop_dong }}</option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <!-- <textarea v-model="create_hop_dong.noi_dung" class="form-control" cols="30"
                                    rows="10"></textarea> -->
                                <ckeditor v-model="create_hop_dong.noi_dung" :editor="editor" :config="editorConfig" />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4">
                                <label class="form-label fw-bold">Ngày Bắt Đầu</label>
                                <input v-model="create_hop_dong.ngay_bat_dau" type="date" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-bold">Ngày Kết Thúc</label>
                                <input v-model="create_hop_dong.ngay_ket_thuc" type="date" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-bold">Ngày Ký</label>
                                <input v-model="create_hop_dong.ngay_ky" type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            v-on:click="taoHopDong()">Tạo Hợp
                            Đồng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import { ClassicEditor, Bold, Essentials, Italic, Mention, Paragraph, Undo, Heading, BlockQuote, Font, Link, List, Alignment } from 'ckeditor5';
import { Ckeditor } from '@ckeditor/ckeditor5-vue';
import 'ckeditor5/ckeditor5.css';

const API = 'http://127.0.0.1:8000/api/admin';

export default {
    components: { Ckeditor },

    data() {
        return {
            create_nhan_vien: {},
            edit_nhan_vien: {},
            delete_nhan_vien: {},
            phan_quyen_nhan_vien: {},
            create_hop_dong: {},

            list_nhan_vien: [],
            list_chuc_vu: [],
            list_phong_ban: [],
            list_hop_dong: [],
            list_chuc_nang: [],

            search: { noi_dung: '', id_chuc_vu: null, id_phong_ban: null },

            editor: ClassicEditor,
            editorConfig: {
                plugins: [
                    Bold, Essentials, Italic, Mention, Paragraph,
                    Undo, Heading, BlockQuote, Font, Link, List, Alignment
                ],
                toolbar: [
                    'heading', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'fontSize', 'fontFamily', 'fontColor',
                    '|', 'link', 'bulletedList', 'numberedList', 'blockQuote',
                    '|', 'Alignment'
                ],
            },
        };
    },

    mounted() {
        this.loadNhanVien();
        this.loadChucVu();
        this.loadPhongBan();
        this.loadHopDong();
    },

    methods: {
        authHeader() {
            return {
                Authorization: 'Bearer ' + localStorage.getItem('tk_nhan_vien')
            };
        },

        // ─── LOAD NHÂN VIÊN ─────────────────────────────────
        loadNhanVien() {
            axios
                .get(`${API}/nhan-vien/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_nhan_vien = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách nhân viên.');
                });
        },

        // ─── TÌM KIẾM ───────────────────────────────────────
        TimKiemBE() {
            axios
                .get(`${API}/nhan-vien`, {
                    params: { search: this.search.noi_dung },
                    headers: this.authHeader(),
                })
                .then((res) => {
                    this.list_nhan_vien = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Tìm kiếm thất bại.');
                });
        },

        // ─── LOAD CHỨC VỤ ───────────────────────────────────
        loadChucVu() {
            axios
                .get(`${API}/chuc-vu/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_chuc_vu = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách chức vụ.');
                });
        },

        // ─── LOAD PHÒNG BAN ─────────────────────────────────
        loadPhongBan() {
            axios
                .get(`${API}/phong-ban/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_phong_ban = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách phòng ban.');
                });
        },

        // ─── LOAD HỢP ĐỒNG ─────────────────────────────────
        loadHopDong() {
            axios
                .get(`${API}/loai-hop-dong/data`, { headers: this.authHeader() })
                .then((res) => {
                    this.list_hop_dong = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách hợp đồng.');
                });
        },

        // ─── CREATE ─────────────────────────────────────────
        createNhanVien() {
            axios
                .post(`${API}/nhan-vien/create`, this.create_nhan_vien, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || 'Thêm thành công');
                        this.create_nhan_vien = {};
                        this.loadNhanVien();
                    } else {
                        this.$toast.error(res.data.message || 'Thêm thất bại');
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        // ─── UPDATE ─────────────────────────────────────────
        capNhatNhanVien() {
            axios
                .post(`${API}/nhan-vien/update`, this.edit_nhan_vien, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadNhanVien();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        // ─── DELETE ─────────────────────────────────────────
        xoaNhanVien() {
            axios
                .post(`${API}/nhan-vien/delete`, this.delete_nhan_vien, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.delete_nhan_vien = {};
                        this.loadNhanVien();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        // ─── LOAD CHỨC NĂNG ─────────────────────────────────
        loadChucNang(value) {
            this.phan_quyen_nhan_vien = value;

            axios
                .post(`${API}/chuc-nang/data`, { id: value.id }, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    this.list_chuc_nang = res.data.status
                        ? res.data.data || []
                        : res.data || [];
                })
                .catch(() => {
                    this.$toast.error('Không thể tải chức năng.');
                });
        },

        // ─── CẤP QUYỀN ──────────────────────────────────────
        setQuyen(value) {
            axios
                .post(`${API}/phan-quyen/create`, {
                    id_nhan_vien: this.phan_quyen_nhan_vien.id,
                    id_chuc_nang: value.id,
                }, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success('Cấp quyền thành công');
                        this.loadChucNang(this.phan_quyen_nhan_vien);
                    } else {
                        this.$toast.error('Cấp quyền thất bại');
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        // ─── THU HỒI QUYỀN ─────────────────────────────────
        removeQuyen(value) {
            axios
                .post(`${API}/phan-quyen/delete`, {
                    id_nhan_vien: this.phan_quyen_nhan_vien.id,
                    id_chuc_nang: value.id,
                }, {
                    headers: this.authHeader(),
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success('Thu hồi quyền thành công');
                        this.loadChucNang(this.phan_quyen_nhan_vien);
                    } else {
                        this.$toast.error('Thu hồi quyền thất bại');
                    }
                })
                .catch((err) => this.handleValidationError(err));
        },

        // ─── EXPORT EXCEL ───────────────────────────────────
        xuatExcel() {
            axios
                .get(`${API}/nhan-vien/xuat-excel`, {
                    responseType: 'blob',
                    headers: this.authHeader(),
                })
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'nhan_vien.xlsx');
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(() => {
                    this.$toast.error('Xuất Excel thất bại.');
                });
        },


        // ─── HỢP ĐỒNG: TỰ ĐIỀN NỘI DUNG KHI CHỌN LOẠI ─────────────────
        // loadNoiDung() {
        //     const found = this.list_hop_dong.find(
        //         (v) => v.id == this.create_hop_dong.id_loai_hop_dong
        //     );
        //     if (found) {
        //         this.create_hop_dong.noi_dung = found.noi_dung;
        //     }
        // },

        // // ─── HỢP ĐỒNG: TẠO HỢP ĐỒNG ────────────────────────────────────
        // taoHopDong() {
        //     axios
        //         .post(`${API}/chi-tiet-hop-dong/create`, this.create_hop_dong, {
        //             headers: this.authHeader(),
        //         })
        //         .then((res) => {
        //             if (res.status === 200 || res.status === 201) {
        //                 this.$toast.success('Tạo hợp đồng thành công.');
        //                 this.create_hop_dong = {};
        //             } else {
        //                 this.$toast.error('Tạo hợp đồng thất bại.');
        //             }
        //         })
        //         .catch((err) => {
        //             this.handleValidationError(err);
        //         });
        // },

        xuatExcel() {
            axios
                .get('http://127.0.0.1:8000/api/admin/nhan-vien/xuat-excel', {
                    responseType: 'blob',
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    if (res.data.status == false) {
                        this.$toast.error(res.data.message);
                    }
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'nhan_vien.xlsx');
                    document.body.appendChild(link);
                    link.click();
                });
        },

        displayChucVu(v) {
            if (!v) return '';
            // Trường hợp BE có eager load relationship
            if (v.chucVu?.ten_chuc_vu) return v.chucVu.ten_chuc_vu;
            if (v.chuc_vu?.ten_chuc_vu) return v.chuc_vu.ten_chuc_vu;
            // Trường hợp BE chỉ trả id → tra cứu trong list_chuc_vu
            const id = v.id_chuc_vu ?? null;
            if (id) {
                const found = this.list_chuc_vu.find((c) => c.id === id);
                return found?.ten_chuc_vu ?? '';
            }
            return '';
        },

        // ─── HIỂN THỊ TÊN PHÒNG BAN ─────────────────────────────────────
        displayPhongBan(v) {
            if (!v) return '';
            if (v.phongBan?.ten_phong_ban) return v.phongBan.ten_phong_ban;
            if (v.ten_phong_ban) return v.ten_phong_ban;
            const id = v.id_phong_ban ?? null;
            if (id) {
                const found = this.list_phong_ban.find((p) => p.id === id);
                return found?.ten_phong_ban ?? '';
            }
            return '';
        },

        displayNgaySinh(v) {
            if (!v) return '';

            const raw =
                v.ngay_sinh ||
                v.ngaysinh ||
                v.birth_date ||
                v.birthday ||
                v.profile?.ngay_sinh;

            if (!raw) return '';

            const date = new Date(raw);

            if (isNaN(date)) return raw; // fallback nếu format lạ

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            return `${day}/${month}/${year}`;
        },

        displaySoDienThoai(v) {
            if (!v) return '';
            return v.so_dien_thoai || v.sdt || v.phone || v.contact_phone || v.profile?.so_dien_thoai || '';
        },

        displayDiaChi(v) {
            if (!v) return '';
            return v.dia_chi || v.address || v.profile?.dia_chi || '';
        },

        displayLuong(v) {
            if (!v) return '';
            const raw = v.luong_co_ban ?? v.salary ?? v.luong ?? v.profile?.luong_co_ban;
            if (raw === undefined || raw === null || raw === '') return '';
            const num = Number(raw);
            if (Number.isNaN(num)) return String(raw) + ' đ';
            return new Intl.NumberFormat('vi-VN').format(num) + ' đ';
        },

        // ─── XỬ LÝ LỖI VALIDATION TỪ BE ────────────────────────────────
        handleValidationError(err) {
            const data = err?.response?.data;
            if (data?.errors) {
                // Laravel validation error: { errors: { field: ['msg'] } }
                Object.values(data.errors).forEach((msgs) => {
                    msgs.forEach((msg) => this.$toast.error(msg));
                });
            } else if (data?.message) {
                this.$toast.error(data.message);
            } else {
                this.$toast.error('Đã có lỗi xảy ra, vui lòng thử lại.');
            }
        },
    },
};
</script>
<style></style>
