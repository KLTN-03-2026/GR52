<template>
    <div>
        <!-- Overlay chỉ hiện trên mobile -->
        <div v-if="isSidebarOpen && isMobile" class="sidebar-overlay d-lg-none" @click="toggleSidebar"></div>

        <!-- Sidebar -->
        <div :class="['sidebar', { 'sidebar-collapsed': !isSidebarOpen }]">
            <!-- Header -->
            <div class="sidebar-header">
                <span v-show="isSidebarOpen" class="logo-text">RecuAI</span>
                <button class="btn-toggle" @click="toggleSidebar">
                    <i :class="isSidebarOpen ? 'bx bx-chevrons-left' : 'bx bx-chevrons-right'"></i>
                </button>
            </div>

            <!-- Menu Items -->
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <!-- Home -->
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" :title="!isSidebarOpen ? 'Home' : ''">
                            <i class="fa-solid fa-home nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Home</span>
                        </router-link>
                    </li>

                    <!-- Quản Lý Nhân Viên -->
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('nhanvien')"
                            :title="!isSidebarOpen ? 'Quản Lý Nhân Viên' : ''">
                            <i class="fa-solid fa-user nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Quản Lý Nhân Viên</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.nhanvien ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.nhanvien" class="submenu">
                            <li>
                                <router-link to="/admin/nhan-vien" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Nhân Viên
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/chuc-vu" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Chức Vụ
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/phong-ban" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Phòng Ban
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Tuyển Dụng -->
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('tuyendung')"
                            :title="!isSidebarOpen ? 'Tuyển Dụng' : ''">
                            <i class="fa-solid fa-briefcase nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Tuyển Dụng</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.tuyendung ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.tuyendung" class="submenu">
                            <li>
                                <router-link to="/admin/tuyen-dung" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Vị Trí Tuyển Dụng
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/ung-vien" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Ứng Viên
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/ho-so-ung-tuyen" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Hồ Sơ Ứng Tuyển
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Quản Lý KPI -->
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('kpi')"
                            :title="!isSidebarOpen ? 'Quản Lý KPI' : ''">
                            <i class="fas fa-chart-line nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Quản Lý KPI</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.kpi ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.kpi" class="submenu">
                            <li>
                                <router-link to="/admin/tieu-chi-kpi" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Tiêu Chí KPI
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/kpi-nhan-vien" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> KPI Nhân Viên
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Quản Lý Thưởng Và Phạt -->
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('thuongphat')"
                            :title="!isSidebarOpen ? 'Thưởng Và Phạt' : ''">
                            <i class="fa-solid fa-feather-pointed nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Quản Lý Thưởng Và Phạt</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.thuongphat ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.thuongphat" class="submenu">
                            <li>
                                <router-link to="/admin/quy-dinh-cho-diem" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Quy Định Cho Điểm
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/thuong-va-phat" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Thưởng Và Phạt
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Quản Lý Hợp Đồng -->
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('hopdong')"
                            :title="!isSidebarOpen ? 'Quản Lý Hợp Đồng' : ''">
                            <i class="fa-solid fa-file-contract nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Quản Lý Hợp Đồng</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.hopdong ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.hopdong" class="submenu">
                            <li>
                                <router-link to="/admin/loai-hop-dong" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Loại Hợp Đồng
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/chi-tiet-hop-dong" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Hợp Đồng Nhân Viên
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Quản Lý Chấm Công -->
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('chamcong')"
                            :title="!isSidebarOpen ? 'Quản Lý Chấm Công' : ''">
                            <i class="fa-solid fa-calendar-plus nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Quản Lý Chấm Công</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.chamcong ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.chamcong" class="submenu">
                            <li>
                                <router-link to="/admin/cham-cong" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Chấm Công
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/luong" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Lương
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/luong-theo-thang" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Lương Theo Tháng
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Thống Kê -->
                    <li class="nav-item">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('thongke')"
                            :title="!isSidebarOpen ? 'Thống Kê' : ''">
                            <i class="fa-solid fa-chart-simple nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Thống Kê</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.thongke ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.thongke" class="submenu">
                            <li>
                                <router-link to="/admin/thong-ke-ca-cham-cong" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Thống Kê Ca Chấm Công
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/thong-ke-diem-kpi-nhan-vien" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Thống Kê KPI Nhân Viên
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/thong-ke-diem-phat" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Thống Kê Điểm Phạt
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/thong-ke-diem-thuong" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Thống Kê Điểm Thưởng
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- Chat -->
                    <li class="nav-item">
                        <router-link to="/admin/chat" class="nav-link" :title="!isSidebarOpen ? 'Chat' : ''">
                            <i class="fa-solid fa-comments nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Chat</span>
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isSidebarOpen: true,
            isMobile: false,
            openDropdowns: {
                nhanvien:   false,
                tuyendung:  false,
                kpi:        false,
                thuongphat: false,
                hopdong:    false,
                chamcong:   false,
                thongke:    false,
            }
        }
    },
    mounted() {
        this.checkMobile();
        window.addEventListener('resize', this.checkMobile);
        // Emit trạng thái ban đầu để layout cha biết
        this.$emit('sidebar-change', this.isSidebarOpen);
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.checkMobile);
    },
    methods: {
        checkMobile() {
            this.isMobile = window.innerWidth < 992;
            if (this.isMobile) {
                this.isSidebarOpen = false;
            } else {
                this.isSidebarOpen = true;
            }
            this.$emit('sidebar-change', this.isSidebarOpen);
        },
        toggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
            // Emit để layout cha cập nhật margin của content
            this.$emit('sidebar-change', this.isSidebarOpen);
        },
        toggleDropdown(key) {
            this.openDropdowns[key] = !this.openDropdowns[key];
        }
    }
}
</script>

<style scoped>
/* ── BIẾN ── */
:root {
    --sidebar-width:      260px;
    --sidebar-collapsed:  68px;
    --header-height:      60px;
    --transition:         0.25s cubic-bezier(.4, 0, .2, 1);
}

/* ── SIDEBAR ── */
.sidebar {
    position: fixed;
    top: var(--header-height, 60px);
    left: 0;
    height: calc(100vh - var(--header-height, 60px));
    width: 260px;
    background: #ffffff;
    border-right: 1px solid #e9ecef;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.06);
    transition: width 0.25s cubic-bezier(.4, 0, .2, 1);
    overflow-x: hidden;
    overflow-y: auto;
    z-index: 1050;
    display: flex;
    flex-direction: column;
}

/* Thu gọn còn icon only */
.sidebar-collapsed {
    width: 68px;
}

/* ── HEADER ── */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 12px;
    height: 56px;
    border-bottom: 1px solid #e9ecef;
    flex-shrink: 0;
}

.sidebar-collapsed .sidebar-header {
    justify-content: center;
}

.logo-text {
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e40af;
    white-space: nowrap;
    overflow: hidden;
}

/* ── TOGGLE BUTTON ── */
.btn-toggle {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #475569;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.2s, color 0.2s;
}

.btn-toggle:hover {
    background: #e2e8f0;
    color: #1e293b;
}

/* ── NAV ── */
.sidebar-nav {
    flex: 1;
    padding: 8px 0;
    overflow-y: auto;
    overflow-x: hidden;
}

.nav-item {
    margin: 1px 6px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    color: #475569;
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.2s, color 0.2s;
    white-space: nowrap;
    overflow: hidden;
    cursor: pointer;
    position: relative;
}

.nav-link:hover {
    background: #f1f5f9;
    color: #1e40af;
}

.nav-icon {
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
}

.nav-label {
    margin-left: 10px;
    font-size: 0.875rem;
    font-weight: 500;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Tooltip khi collapsed (desktop) */
.sidebar-collapsed .nav-link {
    justify-content: center;
    padding: 10px;
}

.sidebar-collapsed .nav-link[title]:hover::after {
    content: attr(title);
    position: absolute;
    left: calc(100% + 8px);
    top: 50%;
    transform: translateY(-50%);
    background: #1e293b;
    color: #fff;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.78rem;
    white-space: nowrap;
    z-index: 9999;
    pointer-events: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}

/* ── SUBMENU ── */
.submenu {
    list-style: none;
    padding: 2px 0 2px 0;
    margin: 0;
}

.submenu-link {
    display: flex;
    align-items: center;
    padding: 8px 12px 8px 42px;
    color: #64748b;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: background 0.2s, color 0.2s, padding-left 0.2s;
    white-space: nowrap;
    overflow: hidden;
}

.submenu-link:hover {
    background: #eff6ff;
    color: #1e40af;
    padding-left: 46px;
}

.submenu-link i {
    margin-right: 6px;
    font-size: 0.85rem;
    flex-shrink: 0;
}

/* ── ACTIVE LINK ── */
.router-link-active,
.router-link-exact-active {
    background: #eff6ff !important;
    color: #1e40af !important;
    font-weight: 600;
    border-left: 3px solid #1e40af;
}

/* ── OVERLAY (mobile only) ── */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 1040;
}

/* ── SCROLLBAR ── */
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-track { background: transparent; }
.sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.sidebar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

/* ── RESPONSIVE ── */
@media (max-width: 991.98px) {
    .sidebar {
        width: 260px;
        transform: translateX(-100%);
        transition: transform 0.25s cubic-bezier(.4, 0, .2, 1);
    }

    .sidebar:not(.sidebar-collapsed) {
        transform: translateX(0);
    }

    /* Mobile: không dùng width animation, dùng transform */
    .sidebar-collapsed {
        width: 260px;
        transform: translateX(-100%);
    }
}
</style>