<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Đăng Ký Tài Khoản</h4>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Họ tên</label>
                            <input v-model="form.ho_ten" type="text" class="form-control" placeholder="Nhập họ tên">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input v-model="form.email" type="email" class="form-control" placeholder="Nhập email">
                        </div>

                        <div class="mb-3">
                            <label>Mật khẩu</label>
                            <input v-model="form.password" type="password" class="form-control"
                                placeholder="Nhập mật khẩu">
                        </div>

                        <div class="mb-3">
                            <label>Nhập lại mật khẩu</label>
                            <input v-model="form.password_confirmation" type="password" class="form-control"
                                placeholder="Nhập lại mật khẩu">
                        </div>

                        <button @click="dangKy" class="btn btn-primary w-100">
                            Đăng Ký
                        </button>

                        <div class="text-center mt-3">
                            <span>Đã có tài khoản?</span>
                            <router-link to="/ung-vien/dang-nhap">Đăng nhập</router-link>
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
            form: {
                ho_ten: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        };
    },
    methods: {
        async dangKy() {
            // validate FE đơn giản
            if (!this.form.ho_ten || !this.form.email || !this.form.password) {
                this.$toast.error("Vui lòng nhập đầy đủ thông tin");
                return;
            }

            if (this.form.password !== this.form.password_confirmation) {
                this.$toast.error("Mật khẩu không khớp");
                return;
            }

            try {
                const res = await axios.post(
                    'http://127.0.0.1:8000/api/ung-vien/dang-ky',
                    this.form
                );

                if (res.data.status) {
                    this.$toast.success("Đăng ký thành công 🎉");

                    // 👉 chuyển sang trang đăng nhập
                    setTimeout(() => {
                        this.$router.push('/dang-nhap');
                    }, 1000);
                } else {
                    this.$toast.error(res.data.message || "Đăng ký thất bại");
                }

            } catch (err) {
                console.log(err); // xem full lỗi trong console

                if (err.response) {
                    console.log(err.response.data); // dữ liệu từ backend

                    if (err.response.data.errors) {
                        Object.values(err.response.data.errors).forEach(arr => {
                            this.$toast.error(arr[0]);
                        });
                    } else if (err.response.data.message) {
                        this.$toast.error(err.response.data.message);
                    } else {
                        this.$toast.error("Lỗi từ server");
                    }
                } else {
                    this.$toast.error("Không kết nối được server");
                }
            };
        }
    }
};
</script>

<style scoped>
.card {
    border-radius: 10px;
}
</style>