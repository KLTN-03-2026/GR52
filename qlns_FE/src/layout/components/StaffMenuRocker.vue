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
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <router-link to="/dashboard-staff" class="nav-link" :title="!isSidebarOpen ? 'Dashboard' : ''">
                            <i class="fa-solid fa-home nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Dashboard</span>
                        </router-link>
                    </li>

                    <!-- Chấm Công -->
                    <li class="nav-item">
                        <router-link to="/nhan-vien/cham-cong" class="nav-link"
                            :title="!isSidebarOpen ? 'Chấm Công' : ''">
                            <i class="fa-solid fa-clock nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Chấm Công</span>
                        </router-link>
                    </li>

                    <!-- Nghỉ Phép -->
                    <li class="nav-item">
                        <router-link to="/nhan-vien/nghi-phep" class="nav-link"
                            :title="!isSidebarOpen ? 'Nghỉ Phép' : ''">
                            <i class="fa-solid fa-calendar-check nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Nghỉ Phép</span>
                        </router-link>
                    </li>

                    <!-- Hồ Sơ Cá Nhân -->
                    <!-- <li class="nav-item">
                        <router-link to="/nhan-vien/ho-so" class="nav-link" :title="!isSidebarOpen ? 'Hồ Sơ' : ''">
                            <i class="fa-solid fa-user nav-icon"></i>
                            <span class="nav-label" v-show="isSidebarOpen">Hồ Sơ Cá Nhân</span>
                        </router-link>
                    </li> -->
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

.nav-link.router-link-active {
    background: var(--qlns-primary-soft);
    color: var(--qlns-primary);
    font-weight: 600;
    border-left: 3px solid var(--qlns-primary);
    padding-left: 9px;
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

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040;
}

@media (max-width: 991.98px) {
    .sidebar {
        width: 260px;
        transform: translateX(-100%);
        transition: transform 0.25s cubic-bezier(.4, 0, .2, 1);
    }

    .sidebar:not(.sidebar-collapsed) {
        transform: translateX(0);
    }

    .sidebar-collapsed {
        width: 260px;
        transform: translateX(-100%);
    }
}
</style>
