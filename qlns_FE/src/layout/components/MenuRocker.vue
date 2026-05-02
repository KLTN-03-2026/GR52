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
                    <!-- Quản Lý Nhân Viên -->
                    <li class="nav-item" v-auth="[roles.ADMIN]">
                        <a href="/admin/trang-chu" class="nav-link" :title="!isSidebarOpen ? 'Trang Chủ' : ''">

                            <i class="fa-solid fa-house nav-icon"></i>

                            <span class="nav-label" v-show="isSidebarOpen">
                                Trang Chủ
                            </span>
                        </a>
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
                    <li class="nav-item" v-auth="[roles.ADMIN, roles.HR]">
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
                        </ul>
                    </li>

                    <!-- Nghỉ Phép -->
                    <li class="nav-item" v-auth="[roles.ADMIN]">
                        <router-link to="/admin/nghi-phep" class="nav-link" :title="!isSidebarOpen ? 'Nghỉ Phép' : ''">
                            <i class="fa-solid fa-calendar-check nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Nghỉ Phép</span>
                        </router-link>
                    </li>

                    <!-- Chấm Công -->
                    <li class="nav-item" v-auth="[roles.ADMIN]">
                        <router-link to="/admin/cham-cong" class="nav-link" :title="!isSidebarOpen ? 'Chấm Công' : ''">
                            <i class="fa-solid fa-clock nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Chấm Công</span>
                        </router-link>
                    </li>

                    <!-- Lương & KPI -->
                    <li class="nav-item" v-auth="[roles.ADMIN]">
                        <a href="javascript:;" class="nav-link" @click="toggleDropdown('luongkpi')"
                            :title="!isSidebarOpen ? 'Lương & KPI' : ''">
                            <i class="fa-solid fa-money-bill nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Lương & KPI</span>
                            <i v-show="isSidebarOpen"
                                :class="['bx', 'ms-auto', openDropdowns.luongkpi ? 'bx-chevron-up' : 'bx-chevron-down']"></i>
                        </a>
                        <ul v-show="isSidebarOpen && openDropdowns.luongkpi" class="submenu">
                            <li>
                                <router-link to="/admin/bang-luong" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Bảng Lương
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/kpi" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Tiêu Chí KPI
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/kpi-nhan-vien" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> KPI Nhân Viên
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/thuong-phat" class="submenu-link">
                                    <i class="bx bx-right-arrow-alt"></i> Thưởng & Phạt
                                </router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import { ROLES } from "../../services/rbac";

export default {
    data() {
        return {
            roles: ROLES,
            isSidebarOpen: true,
            isMobile: false,
            openDropdowns: {
                nhanvien: false,
                tuyendung: false,
                luongkpi: false,
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
            if (!this.isSidebarOpen) {
                Object.keys(this.openDropdowns).forEach((key) => {
                    this.openDropdowns[key] = false;
                });
            }
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
    --sidebar-width: 260px;
    --sidebar-collapsed: 68px;
    --header-height: 60px;
    --transition: 0.25s cubic-bezier(.4, 0, .2, 1);
}

/* ── SIDEBAR ── */
.sidebar {
    position: fixed;
    top: var(--header-height, 60px);
    left: 0;
    height: calc(100vh - var(--header-height, 60px));
    width: 260px;
    background: var(--qlns-surface);
    border-right: 1px solid var(--qlns-border);
    box-shadow: var(--qlns-shadow-sm);
    transition: width 0.25s cubic-bezier(.4, 0, .2, 1);
    overflow-x: hidden;
    overflow-y: auto;
    z-index: 1050;
    display: flex;
    flex-direction: column;
}

/* Thu gọn còn icon only */
.sidebar-collapsed {
    width: 68px !important;
}

/* ── HEADER ── */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 12px;
    height: 56px;
    border-bottom: 1px solid var(--qlns-border);
    flex-shrink: 0;
}

.sidebar-collapsed .sidebar-header {
    justify-content: center;
}

.logo-text {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--qlns-primary);
    white-space: nowrap;
    overflow: hidden;
}

/* ── TOGGLE BUTTON ── */
.btn-toggle {
    background: var(--qlns-surface-muted);
    border: 1px solid var(--qlns-border);
    color: var(--qlns-text-muted);
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
    background: var(--qlns-primary-soft);
    color: var(--qlns-primary);
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
    color: var(--qlns-text-muted);
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.2s, color 0.2s;
    white-space: nowrap;
    overflow: hidden;
    cursor: pointer;
    position: relative;
}

.nav-link:hover {
    background: var(--qlns-primary-soft);
    color: var(--qlns-primary);
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

.sidebar-collapsed .nav-label,
.sidebar-collapsed .submenu,
.sidebar-collapsed .nav-link .ms-auto {
    display: none !important;
}

.sidebar-collapsed .nav-link[title]:hover::after {
    content: attr(title);
    position: absolute;
    left: calc(100% + 8px);
    top: 50%;
    transform: translateY(-50%);
    background: var(--qlns-text);
    color: var(--qlns-surface);
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.78rem;
    white-space: nowrap;
    z-index: 9999;
    pointer-events: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
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
    color: var(--qlns-text-muted);
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.85rem;
    transition: background 0.2s, color 0.2s, padding-left 0.2s;
    white-space: nowrap;
    overflow: hidden;
}

.submenu-link:hover {
    background: var(--qlns-primary-soft);
    color: var(--qlns-primary);
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
    background: var(--qlns-primary-soft) !important;
    color: var(--qlns-primary) !important;
    font-weight: 600;
    border-left: 3px solid var(--qlns-primary);
}

/* ── OVERLAY (mobile only) ── */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 1040;
}

/* ── SCROLLBAR ── */
.sidebar::-webkit-scrollbar {
    width: 4px;
}

.sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
    background: var(--qlns-border-strong);
    border-radius: 4px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: var(--qlns-text-soft);
}

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
