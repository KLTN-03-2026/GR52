<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Đăng Nhập</h4>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Email</label>
                            <input v-model="form.email" type="email" class="form-control" placeholder="Nhập email">
                        </div>

                        <div class="mb-3">
                            <label>Mật khẩu</label>
                            <input v-model="form.password" type="password" class="form-control" placeholder="Nhập mật khẩu">
                        </div>

                        <button @click="dangNhap" class="btn btn-primary w-100">
                            Đăng Nhập
                        </button>

                        <div class="text-center mt-3">
                            <span>Chưa có tài khoản?</span>
                            <router-link to="/dang-ky">Đăng ký</router-link>
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
                email: '',
                password: ''
            }
        };
    },
    methods: {
        async dangNhap() {
            if (!this.form.email || !this.form.password) {
                this.$toast.error("Vui lòng nhập đầy đủ thông tin");
                return;
            }

            try {
                const res = await axios.post(
                    'http://127.0.0.1:8000/api/ung-vien/dang-nhap',
                    this.form
                );

                if (res.data.status) {
                    this.$toast.success("Đăng nhập thành công 🎉");

                    // Lưu token ứng viên
                    localStorage.setItem('token_ung_vien', res.data.token);
                    
                    // Lưu thông tin ứng viên
                    const candidateData = res.data.data || res.data;
                    localStorage.setItem('candidate_data', JSON.stringify(candidateData));
                    localStorage.setItem('candidate_role', 'candidate');

                    // Redirect sang trang chủ ứng viên
                    this.$router.push('/ung-vien/trang-chu');

                } else {
                    this.$toast.error(res.data.message || "Sai tài khoản hoặc mật khẩu");
                }

            } catch (err) {
                console.log(err);

                if (err.response?.data?.errors) {
                    Object.values(err.response.data.errors).forEach(arr => {
                        this.$toast.error(arr[0]);
                    });
                } else {
                    this.$toast.error(err.response?.data?.message || "Lỗi đăng nhập");
                }
            }
        }
    }
};
</script>

<style scoped>
.card {
    border-radius: 10px;
}
</style>