<template>
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                <div class="col mx-auto">
                    <div class="my-4 text-center">

                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    <h3 class="">ĐĂNG NHẬP NHÂN VIÊN</h3>
                                </div>
                                <div class="login-separater text-center mb-4">
                                    <hr />
                                </div>
                                <div class="form-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Email</label>
                                            <div class="input-group">
                                                <div class="input-group-text bg-transparent">
                                                    <i class="fa-solid fa-envelope"></i>
                                                </div>
                                                <input v-model="tk.email" type="email"
                                                    class="form-control border-end-0">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Password</label>
                                            <div class="input-group">
                                                <div class="input-group-text bg-transparent"><i
                                                        class="fa-solid fa-lock"></i></div>
                                                <input v-model="tk.password" type="password"
                                                    class="form-control border-end-0">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-grid">
<<<<<<< HEAD
                                                <button v-on:click="login()" type="button" class="btn btn-primary"
                                                    :disabled="isLoading"><i class="fa-solid fa-lock-open"></i>{{
                                                    isLoading ? 'Đang xử lý...' : 'Đăng Nhập' }}</button>
=======
                                                <button v-on:click="login()" type="button" class="btn btn-primary"><i
                                                        class="fa-solid fa-lock-open"></i>Đăng
                                                    Nhập</button>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<<<<<<< HEAD
import authService from '../../../services/authService';

=======
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
export default {
    data() {
        return {
            tk: { 'email': '', 'password': '' },
<<<<<<< HEAD
            isLoading: false
        }
    },
    mounted() {
        // Nếu đã đăng nhập, redirect sang dashboard
        if (authService.isAuthenticated()) {
            this.$router.push(authService.getDashboardUrl());
        }
    },
    methods: {
        login() {
            // Validation
            if (!this.tk.email || !this.tk.password) {
                this.$toast.error('Vui lòng nhập email và mật khẩu');
                return;
            }

            this.isLoading = true;

=======

        }
    },
    mounted() {

    },
    methods: {

        login() {
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
            axios
                .post("http://127.0.0.1:8000/api/admin/dang-nhap", this.tk)
                .then((res) => {
                    if (res.data.status) {
<<<<<<< HEAD
                        // Lưu token
                        localStorage.setItem('tk_nhan_vien', res.data.token);

                        // Lưu user data và role từ response
                        localStorage.setItem('user_role', res.data.role);
                        localStorage.setItem('user_data', JSON.stringify(res.data.user));

                        // Setup axios default header
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;

                        this.$toast.success(res.data.message || 'Đăng nhập thành công');

                        // Điều hướng sau đăng nhập. Nếu backend không trả role thì về trang nhân viên.
                        const dashboardUrl = authService.getDashboardUrl();

                        this.$nextTick(() => {
                            this.$router.push(dashboardUrl);
=======
                        
                        localStorage.setItem('tk_nhan_vien', res.data.token);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;

                        this.$toast.success(res.data.message);
                        
                        this.$nextTick(() => {
                            this.$router.push('/admin/nhan-vien');
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                        });
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(err => {
                    const msg = err?.response?.data?.message || 'Email hoặc mật khẩu không chính xác.';
                    this.$toast.error(msg);
<<<<<<< HEAD
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
=======
                });
        },


>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
    }
}
</script>
<style></style>
