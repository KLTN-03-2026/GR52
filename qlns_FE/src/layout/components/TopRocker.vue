<template>

    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="topbar-logo-header">
                <div class="">
<<<<<<< HEAD
                    <img width="200%"
                        src="blob:https://gemini.google.com/079a84f5-9162-4aa4-b48e-4069a5d63aac"
                        class="logo-icon" alt="logo icon">
                </div>
                <div class="">

                </div>
            </div>
            <button class="mobile-toggle-menu" type="button" @click="$emit('toggle-sidebar')" aria-label="Mở menu">
                <i class='bx bx-menu'></i>
            </button>
=======
                    <img width="200%" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Zootopia_logo.svg/2560px-Zootopia_logo.svg.png" class="logo-icon" alt="logo icon">
                </div>
                <div class="">
                  
                </div>
            </div>
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
            <div class="search-bar flex-grow-1">
                <div class="position-relative search-bar-box">
                    <input type="text" class="form-control search-control" placeholder="Tìm Kiếm?">
                    <span class="position-absolute top-50 search-show translate-middle-y"><i
                            class='bx bx-search'></i></span>
                    <span class="position-absolute top-50 search-close translate-middle-y"><i
                            class='bx bx-x'></i></span>
                </div>
            </div>
            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item mobile-search-icon">
                        <a class="nav-link" href="#"> <i class='bx bx-search'></i>
                        </a>
                    </li>
<<<<<<< HEAD
                    <li class="nav-item">
                        <button class="theme-toggle-btn" type="button" @click="toggleTheme"
                            :aria-label="isDarkMode ? 'Chuyển sang light mode' : 'Chuyển sang dark mode'"
                            :title="isDarkMode ? 'Light mode' : 'Dark mode'">
                            <i :class="isDarkMode ? 'bx bx-sun' : 'bx bx-moon'"></i>
                        </button>
                    </li>
=======
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                    <li class="nav-item dropdown dropdown-large">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false"> <i class='bx bx-category'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="row row-cols-3 g-3 p-3">
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-cosmic text-white"><i
                                            class='bx bx-group'></i>
                                    </div>
                                    <div class="app-title">Teams</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-burning text-white"><i
                                            class='bx bx-atom'></i>
                                    </div>
                                    <div class="app-title">Projects</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-lush text-white"><i
                                            class='bx bx-shield'></i>
                                    </div>
                                    <div class="app-title">Tasks</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-kyoto text-dark"><i
                                            class='bx bx-notification'></i>
                                    </div>
                                    <div class="app-title">Feeds</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
                                    </div>
                                    <div class="app-title">Files</div>
                                </div>
                                <div class="col text-center">
                                    <div class="app-box mx-auto bg-gradient-moonlit text-white"><i
                                            class='bx bx-filter-alt'></i>
                                    </div>
                                    <div class="app-title">Alerts</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-large">
<<<<<<< HEAD
                        <a v-on:click="loadThongBao()"
                            class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
=======
                        <a v-on:click="loadThongBao()" class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                            role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span
                                class="alert-count">7</span>
                            <i class='bx bx-bell'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <p class="msg-header-title">Notifications</p>
                                    <p class="msg-header-clear ms-auto">Marks all as read</p>
                                </div>
                            </a>
                            <div class="header-notifications-list">
<<<<<<< HEAD
                                <a v-for="(value, index) in list" :key="index" class="dropdown-item"
                                    href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div :class="'notify bg-light-primary ' + value.color_thong_bao"
                                            style="width: 50px; height:  50px;"><i :class="value.icon_thong_bao"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name text-wrap">{{ value.tieu_de }}<span
                                                    class="msg-time float-end">14 Sec
                                                    ago</span></h6>
                                            <p class="msg-info text-wrap">{{ value.noi_dung }}</p>
                                        </div>
                                    </div>
                                </a>
=======
                                <template v-for="(value, index) in list" :key="index">
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div :class="'notify bg-light-primary ' + value.color_thong_bao" style="width: 50px; height:  50px;"><i
                                                    :class="value.icon_thong_bao"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name text-wrap">{{ value.tieu_de }}<span
                                                        class="msg-time float-end">14 Sec
                                                        ago</span></h6>
                                                <p class="msg-info text-wrap">{{ value.noi_dung }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                            </div>
                            <a href="javascript:;">
                                <div class="text-center msg-footer">View All Notifications</div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-large">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span
                                class="alert-count">8</span>
                            <i class='bx bx-comment'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:;">
                                <div class="msg-header">
                                    <p class="msg-header-title">Messages</p>
                                    <p class="msg-header-clear ms-auto">Marks all as read</p>
                                </div>
                            </a>
                            <div class="header-message-list">
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-1.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
                                                    ago</span></h6>
                                            <p class="msg-info">The standard chunk of lorem</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-2.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
                                                    sec ago</span></h6>
                                            <p class="msg-info">Many desktop publishing packages</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-3.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
                                                    ago</span></h6>
                                            <p class="msg-info">Various versions have evolved over</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-4.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
                                                    min ago</span></h6>
                                            <p class="msg-info">Making this the first true generator</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-5.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
                                                    ago</span></h6>
                                            <p class="msg-info">Duis aute irure dolor in reprehenderit</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-6.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
                                                    ago</span></h6>
                                            <p class="msg-info">The passage is attributed to an unknown</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-7.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
                                                    ago</span></h6>
                                            <p class="msg-info">The point of using Lorem</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-8.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
                                                    ago</span></h6>
                                            <p class="msg-info">It was popularised in the 1960s</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-9.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
                                                    ago</span></h6>
                                            <p class="msg-info">Various versions have evolved over</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-10.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
                                                    ago</span></h6>
                                            <p class="msg-info">If you are going to use a passage</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="../../assets/images/avatars/avatar-11.png" class="msg-avatar"
                                                alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
                                                    ago</span></h6>
                                            <p class="msg-info">All the Lorem Ipsum generators</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <a href="javascript:;">
                                <div class="text-center msg-footer">View All Messages</div>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
<<<<<<< HEAD

                    <div class="user-info ps-3">
                        <h6 class="user-name mb-0">{{ userInfo.ten_nhan_vien }}</h6>
                        <!-- <p class="designattion mb-0">{{ userInfo.vai_tro }}</p> -->
=======
                    
                    <div class="user-info ps-3">
                       
                        <p class="designattion mb-0">Admin</p>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <router-link to="/admin/profile">
                            <a class="dropdown-item" href="/admin/profile"><i
                                    class="bx bx-user"></i><span>Profile</span></a>
                        </router-link>
                    </li>
                    <li>
                        <a v-on:click="dangXuat()" class="dropdown-item" href="javascript:;"><i
                                class='bx bx-log-out-circle'></i><span>Đăng Xuất</span></a>
                    </li>
                    <li><a v-on:click="dangXuatAll()" class="dropdown-item" href="javascript:;"><i
                                class='bx bx-log-out-circle'></i><span>Đăng Xuất Tất Cả</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>
<script>
import axios from 'axios';
<<<<<<< HEAD
import authService from '../../services/authService';
export default {
    data() {
        return {
            list: [],
            isDarkMode: false,
            userInfo: {
                ten_nhan_vien: '',
                vai_tro: ''
            }
        }
    },
    mounted() {
        this.initTheme();
        this.loadUserInfo();
        //this.loadThongBao();
    },
    methods: {
        initTheme() {
            const savedTheme = localStorage.getItem('qlns_theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.isDarkMode = savedTheme ? savedTheme === 'dark' : prefersDark;
            this.applyTheme();
        },
        applyTheme() {
            const theme = this.isDarkMode ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
            document.body.classList.toggle('dark-theme', this.isDarkMode);
            localStorage.setItem('qlns_theme', theme);
        },
        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            this.applyTheme();
        },
        loadUserInfo() {
            // Kiểm tra candidate trước
            const candidateToken = localStorage.getItem("token_ung_vien");
            if (candidateToken) {
                const candidateData = localStorage.getItem("candidate_data");
                if (candidateData) {
                    try {
                        const data = JSON.parse(candidateData);
                        this.userInfo.ten_nhan_vien = data.ho_ten || data.ten || data.name || 'Ứng Viên';
                        this.userInfo.vai_tro = 'Ứng Viên';
                        return;
                    } catch (e) {
                        console.error('Error parsing candidate data:', e);
                    }
                }
            }

            // Kiểm tra employee - load từ localStorage ngay để hiển thị tên ngay lập tức
            const localUser = authService.getCurrentUser();
            if (localUser) {
                this.userInfo.ten_nhan_vien = localUser.ho_va_ten || localUser.ten_nhan_vien || localUser.ho_ten || localUser.name || this.userInfo.ten_nhan_vien;
                this.userInfo.vai_tro = localUser.vai_tro || localUser.role || localStorage.getItem('user_role') || this.userInfo.vai_tro;
            }

            // Kiểm tra token
            const token = localStorage.getItem("tk_nhan_vien");
            if (!token) {
                this.userInfo.ten_nhan_vien = 'Guest';
                this.userInfo.vai_tro = 'guest';
                return;
            }

            // Gọi API để làm mới thông tin từ server
            axios
                .get("http://127.0.0.1:8000/api/admin/user-info", {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                })
                .then((res) => {
                    console.log('User info response:', res.data);
                    // Xử lý flexible dù response có format như nào
                    const data = res.data.data || res.data;
                    if (data) {
                        this.userInfo.ten_nhan_vien = data.ho_va_ten || data.ten_nhan_vien || data.ho_ten || data.name || 'User';
                        this.userInfo.vai_tro = data.vai_tro || data.role || 'staff';
                    }
                })
                .catch((error) => {
                    console.error('Lỗi khi lấy thông tin người dùng:', error.response?.data || error.message);
                    // Không đặt lại, giữ lại thông tin từ localStorage
                });
        },
=======
export default {
    data() {
        return {
            list: []
        }
    },
    mounted() {
        //this.loadThongBao();
    },
    methods: {
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
        loadThongBao() {
            axios
                .post("http://127.0.0.1:8000/api/admin/thong-bao/data", {}, {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    this.list = res.data.data;
                });
        },
        dangXuat() {
<<<<<<< HEAD
            const candidateToken = localStorage.getItem("token_ung_vien");

            if (candidateToken) {
                // Đăng xuất ứng viên
                localStorage.removeItem('token_ung_vien');
                localStorage.removeItem('candidate_data');
                localStorage.removeItem('candidate_role');
                this.$toast.success("Đã đăng xuất");
                this.$router.push('/ung-vien/dang-nhap');
                return;
            }

            // Đăng xuất nhân viên
            axios
                .get("http://127.0.0.1:8000/api/admin/logout", {
=======
            axios
                .get("http://127.0.0.1:8000/api/admin/dang-xuat", {
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        localStorage.setItem('tk_nhan_vien', '');
                        this.$router.push('/admin/dang-nhap');
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        },
        dangXuatAll() {
            axios
                .get("http://127.0.0.1:8000/api/admin/dang-xuat-all", {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem("tk_nhan_vien")
                    }
                })
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        localStorage.setItem('tk_nhan_vien', '');
                        this.$router.push('/admin/dang-nhap');
                    } else {
                        this.$toast.error(res.data.message);
                    }
                });
        }
    },

}
</script>
<style></style>
