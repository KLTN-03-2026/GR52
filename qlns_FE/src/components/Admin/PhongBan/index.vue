<template>
    <div class="row">
        <div class="col-lg-4">
<<<<<<< HEAD
            <div class='card border-top border-4 border-primary'>
=======
            <div class='card border-top border-0 border-4 border-primary'>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                <div class='card-header'>
                    <h5>Thêm Mới Phòng Ban</h5>
                </div>
                <div class='card-body'>
                    <div class="">
                        <div class=" mb-3">
                            <label class="mb-2">Tên Phòng Ban</label>
                            <input v-model="create_phong_ban.ten_phong_ban" type="text" class="form-control"
                                placeholder="">
                        </div>
                        <div class=" mb-3">
                            <label class="mb-2"> Phòng Ban Cha</label>
                            <select v-model="create_phong_ban.id_phong_ban_cha" class="form-select">
                                <option value="">-- Chọn phòng ban cha --</option>
                                <option v-for="pb in list_phong_ban" :key="pb.id" :value="pb.id">
                                    {{ pb.ten_phong_ban }}
                                </option>
                            </select>
                        </div>
                        <div class=" mb-3">
                            <label class="mb-2">Trưởng Phòng</label>
                            <select v-model="create_phong_ban.id_truong_phong" class="form-select">
                                <option value="">-- Chọn Trưởng Phòng --</option>
<<<<<<< HEAD
                                <option v-for="(nv, nk) in list_nhan_vien" :key="nv.id || nk" :value="nv.id">
                                    {{ nv.ho_va_ten || nv.hoTen || (nv.ho && nv.ten ? nv.ho + ' ' + nv.ten : (nv.email
                                        || 'NV #' + nv.id)) }}
                                </option>
=======
                                <template v-for="(nv, nk) in list_nhan_vien" :key="nv.id || nk">
                                    <option :value="nv.id">{{ nv.ho_va_ten || nv.hoTen || (nv.ho && nv.ten ? nv.ho + ' '
                                        + nv.ten : (nv.email || 'NV #' + nv.id)) }}</option>
                                </template>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                            </select>
                            <div v-if="!list_nhan_vien || !list_nhan_vien.length" class="form-text text-muted">Không có
                                nhân viên để chọn</div>
                        </div>
                        <div class=" mb-3">
                            <label class="mb-2">Tình Trạng</label>
                            <select v-model="create_phong_ban.tinh_trang" class="form-select"
                                aria-label="Default select example">
                                <option value="1">Hiển Thị</option>
                                <option value="0">Tạm tắt</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class='card-footer text-end'>
                    <button @click="createPhongBan()" type="button" class="btn btn-primary">Thêm Mới</button>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
<<<<<<< HEAD
            <div class='card border-top border-4 border-primary'>
=======
            <div class='card border-top border-0 border-4 border-primary'>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                <div class='card-header d-flex justify-content-between align-items-center'>
                    <h5>Danh Sách Phòng Ban</h5>
                    <button @click="xuatExcel()" type="button" class="btn btn-success me-2">
                        <i class="fa-regular fa-file-excel"></i> Xuất Excel
                    </button>
                </div>
                <div class='card-body'>
                    <table class='table table-bordered'>
                        <thead>
                            <tr class="text-center">
                                <th class='align-middle'>#</th>
                                <th class='align-middle'>Tên Phòng Ban</th>
                                <th class='align-middle'>Phòng Ban Cha</th>
                                <th class='align-middle'>Trưởng phòng</th>
                                <th class='align-middle'>Tình Trạng</th>
                                <th class='align-middle'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
<<<<<<< HEAD
                            <tr v-for="(v, k) in list_phong_ban" :key="v.id || k" class='align-middle text-center'>
                                <th class='align-middle'>{{ k + 1 }}</th>
                                <td class='align-middle'>{{ v.ten_phong_ban }}</td>
                                <td>{{ getTenPhongBanCha(v.id_phong_ban_cha) }}</td>
                                <td>{{ getTenTruongPhong(v.id_truong_phong) }}</td>
                                <td class="align-middle text-nowrap text-center">
                                    <template v-if="v.tinh_trang == 1">
                                        <button v-on:click="changeStatus(v)" class="btn btn-success w-100">Hiển
                                            Thị</button>
                                    </template>
                                    <template v-else>
                                        <button v-on:click="changeStatus(v)" class="btn btn-danger w-100">Tạm
                                            Tắt</button>
                                    </template>
                                </td>
                                <td class='align-middle'>
                                    <button type="button" class="btn btn-primary me-2" data-bs-toggle='modal'
                                        @click="editPhongBan(v)" data-bs-target='#edit'>Cập
                                        Nhật</button>
                                    <button type="button" data-bs-toggle='modal' data-bs-target='#xoa'
                                        @click="setDeletePhongBan(v)" class="btn btn-danger">Xóa</button>
                                </td>
                            </tr>
=======
                            <template v-for="(v, k) in list_phong_ban" :key="v.id || k">
                                <tr class='align-middle text-center'>
                                    <th class='align-middle'>{{ k + 1 }}</th>
                                    <td class='align-middle'>{{ v.ten_phong_ban }}</td>
                                    <td>{{ getTenPhongBanCha(v.id_phong_ban_cha) }}</td>
                                    <td>{{ getTenTruongPhong(v.id_truong_phong) }}</td>
                                    <td class="align-middle text-nowrap text-center">
                                        <template v-if="v.tinh_trang == 1">
                                            <button v-on:click="changeStatus(v)" class="btn btn-success w-100">Hiển
                                                Thị</button>
                                        </template>
                                        <template v-else>
                                            <button v-on:click="changeStatus(v)" class="btn btn-danger w-100">Tạm
                                                Tắt</button>
                                        </template>
                                    </td>
                                    <td class='align-middle'>
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle='modal'
                                            @click="editPhongBan(v)" data-bs-target='#edit'>Cập
                                            Nhật</button>
                                        <button type="button" data-bs-toggle='modal' data-bs-target='#xoa'
                                            @click="setDeletePhongBan(v)" class="btn btn-danger">Xóa</button>
                                    </td>
                                </tr>
                            </template>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe

                        </tbody>
                    </table>
                </div>
                <div class='modal fade' id='edit' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h1 class='modal-title fs-5' id='exampleModalLabel'>Cập Nhật Phòng Ban</h1>
                                <button type='button' class='btn-close' data-bs-dismiss='modal'
                                    aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <div class=" mb-3">
                                    <label class="mb-2">Tên Phòng Ban</label>
                                    <input v-model="edit_phong_ban.ten_phong_ban" type="text" class="form-control"
                                        placeholder="">
                                </div>
                                <div class=" mb-3">
                                    <label class="mb-2"> Phòng Ban Cha</label>
                                    <select v-model="edit_phong_ban.id_phong_ban_cha" class="form-select">
                                        <option value="">-- Chọn phòng ban cha --</option>
                                        <option v-for="pb in list_phong_ban" :key="pb.id" :value="pb.id">
                                            {{ pb.ten_phong_ban }}
                                        </option>
                                    </select>
                                </div>
                                <div class=" mb-3">
                                    <label class="mb-2">Trưởng Phòng</label>
                                    <select v-model="edit_phong_ban.id_truong_phong" class="form-select">
                                        <option value="">-- Chọn Trưởng Phòng --</option>
<<<<<<< HEAD
                                        <option v-for="(nv, nk) in list_nhan_vien" :key="nv.id || nk" :value="nv.id">
                                            {{ nv.ho_va_ten || nv.hoTen || (nv.ho && nv.ten ? nv.ho + ' ' + nv.ten :
                                                (nv.email || 'NV #' + nv.id)) }}
                                        </option>
=======
                                        <template v-for="(nv, nk) in list_nhan_vien" :key="nv.id || nk">
                                            <option :value="nv.id">{{ nv.ho_va_ten || nv.hoTen || (nv.ho && nv.ten ?
                                                nv.ho + ' ' + nv.ten : (nv.email || 'NV #' + nv.id)) }}</option>
                                        </template>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                                    </select>
                                    <div v-if="!list_nhan_vien || !list_nhan_vien.length" class="form-text text-muted">
                                        Không có
                                        nhân viên để chọn</div>
                                </div>
                                <div class=" mb-3">
                                    <label class="mb-2">Tình Trạng</label>
                                    <select v-model="edit_phong_ban.tinh_trang" class="form-select"
                                        aria-label="Default select example">
                                        <option value="1">Hiển Thị</option>
                                        <option value="0">Tạm tắt</option>
                                    </select>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                <button @click="capNhatPhongBan()" type='button' class='btn btn-primary'
                                    data-bs-dismiss='modal'>Xác
                                    Nhận</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='modal fade' id='xoa' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h1 class='modal-title fs-5' id='exampleModalLabel'>Xóa Phòng Ban</h1>
                                <button type='button' class='btn-close' data-bs-dismiss='modal'
                                    aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-white"><i class="bx bxs-message-square-x"></i>
                                        </div>
                                        <div class="ms-1">
                                            <h6 class="mb-1 text-white">Bạn chắc chắc xóa phòng ban <b>{{
                                                delete_phong_ban.ten_phong_ban }}</b> này chứ
                                                !!!</h6>
                                            <div class="text-white text-nowrap"><b>LƯU Ý !!!</b> Điều này không thể khôi
                                                phục
                                                khi ấn xác nhận
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                <button @click="xoaPhongBan()" type='button' class='btn btn-danger'
                                    data-bs-dismiss='modal'>Xóa</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            create_phong_ban: {
                ten_phong_ban: '',
                id_phong_ban_cha: '',
                id_truong_phong: '',
                tinh_trang: 1
            },
            edit_phong_ban: {
                id: null,
                ten_phong_ban: '',
                id_phong_ban_cha: '',
                id_truong_phong: '',
                tinh_trang: 1
            },
            delete_phong_ban: {
                id: null,
                ten_phong_ban: ''
            },
            list_phong_ban: [],
            list_nhan_vien: [],
        }

    },
    mounted() {
        this.loadPhongBan();
        this.loadNhanVien();
    },
    methods: {
        xuatExcel() {
            axios
                .get('http://127.0.0.1:8000/api/admin/phong-ban/xuat-excel', {
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
                    link.setAttribute('download', 'phong_ban.xlsx');
                    document.body.appendChild(link);
                    link.click();
                });
        },
        loadPhongBan() {
            axios
                .get('http://127.0.0.1:8000/api/admin/phong-ban/data', {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    if (res.data && res.data.status !== undefined) {
                        this.list_phong_ban = res.data.status ? (res.data.data || []) : (res.data || []);
                        if (!res.data.status) this.$toast.error(res.data.message);
                    } else {
                        this.list_phong_ban = res.data || [];
                    }
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách phòng ban.');
                })
        },

        loadNhanVien() {
            axios
                .get('http://127.0.0.1:8000/api/admin/nhan-vien/data', {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    console.debug('loadNhanVien response:', res && res.data);
                    if (res.data && res.data.status !== undefined) {
                        this.list_nhan_vien = res.data.status ? (res.data.data || []) : (res.data || []);
                    } else {
                        this.list_nhan_vien = res.data || [];
                    }
                })
                .catch(() => {
                    this.$toast.error('Không thể tải danh sách nhân viên.');
                });
        },
        createPhongBan() {
            if (!this.create_phong_ban.ten_phong_ban) {
                this.$toast.error("Vui lòng nhập tên phòng ban");
                return;
            }

            axios
                .post('http://127.0.0.1:8000/api/admin/phong-ban/create', this.create_phong_ban, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success("Thêm phòng ban thành công 🎉");
                        this.loadPhongBan();
                        this.create_phong_ban = { ten_phong_ban: '', id_phong_ban_cha: '', id_truong_phong: '', tinh_trang: 1 };
                    } else {
                        this.$toast.error(res.data.message || "Thêm thất bại");
                    }
                })
                .catch((err) => {
                    this.$toast.error("Có lỗi xảy ra khi thêm phòng ban");
                });
        },
        capNhatPhongBan() {
            if (!this.edit_phong_ban.ten_phong_ban) {
                this.$toast.error("Tên phòng ban không được để trống");
                return;
            }
            axios
                .post('http://127.0.0.1:8000/api/admin/phong-ban/update', this.edit_phong_ban, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success("Cập nhật thành công ✅");
                        this.loadPhongBan();
                        this.edit_phong_ban = { id: null, ten_phong_ban: '', id_phong_ban_cha: '', id_truong_phong: '', tinh_trang: 1 };
                    } else {
                        this.$toast.error(res.data.message || "Cập nhật thất bại");
                    }
                })
                .catch(() => {
                    this.$toast.error("Có lỗi xảy ra khi cập nhật");
                });
        },
        xoaPhongBan() {
            axios
                .post('http://127.0.0.1:8000/api/admin/phong-ban/delete', this.delete_phong_ban, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success("Xóa thành công 🗑️");
                        this.loadPhongBan();
                        this.delete_phong_ban = { id: null, ten_phong_ban: '' };
                    } else {
                        this.$toast.error(res.data.message || "Xóa thất bại");
                    }
                })
                .catch(() => {
                    this.$toast.error("Có lỗi khi xóa phòng ban");
                });
        },
       changeStatus(value) {
    const old = value.tinh_trang;

    // ép về số 0/1
    value.tinh_trang = Number(old) === 1 ? 0 : 1;

    axios.post('http://127.0.0.1:8000/api/admin/phong-ban/change-status', {
        id: value.id,
        tinh_trang: Number(value.tinh_trang) // 👈 ép kiểu luôn
    }, {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
        }
    })
    .then((res) => {
        if (res.data.status) {
            this.$toast.success("Đổi trạng thái thành công 🔄");
        } else {
            value.tinh_trang = old;
            this.$toast.error(res.data.message || "Không thể đổi trạng thái");
        }
    })
    .catch(() => {
        value.tinh_trang = old;
        this.$toast.error("Lỗi khi đổi trạng thái");
    });
},
        editPhongBan(v) {
            this.edit_phong_ban = Object.assign({}, v);
        },

        setDeletePhongBan(v) {
            this.delete_phong_ban = Object.assign({}, v);
        },
        getTenTruongPhong(id) {
            if (!id) return '-';
            const nv = this.list_nhan_vien.find(x => x.id == id);
            if (!nv) return '-';
            if (nv.ho_va_ten) return nv.ho_va_ten;
            if (nv.hoTen) return nv.hoTen;
            if (nv.ho && nv.ten) return nv.ho + ' ' + nv.ten;
            return nv.email || ('NV #' + nv.id) || '-';
        },

        getTenPhongBanCha(id) {
            if (!id) return '-';
            const pb = this.list_phong_ban.find(x => x.id == id);
            return pb ? pb.ten_phong_ban : '-';
        }

    }
}
</script>
<style></style>
