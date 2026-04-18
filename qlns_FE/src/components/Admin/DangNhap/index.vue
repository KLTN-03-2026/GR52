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
                                                <button v-on:click="login()" type="button" class="btn btn-primary"><i
                                                        class="fa-solid fa-lock-open"></i>Đăng
                                                    Nhập</button>
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
export default {
    data() {
        return {
            tk: { 'email': '', 'password': '' },

        }
    },
    mounted() {

    },
    methods: {

        login() {
            axios
                .post("http://127.0.0.1:8000/api/admin/dang-nhap", this.tk)
                .then((res) => {
                    if (res.data.status) {
                        
                        localStorage.setItem('tk_nhan_vien', res.data.token);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + res.data.token;

                        this.$toast.success(res.data.message);
                        
                        this.$nextTick(() => {
                            this.$router.push('/admin/nhan-vien');
                        });
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch(err => {
                    const msg = err?.response?.data?.message || 'Email hoặc mật khẩu không chính xác.';
                    this.$toast.error(msg);
                });
        },


    }
}
</script>
<style></style>
