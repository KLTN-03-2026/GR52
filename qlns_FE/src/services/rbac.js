export const ROLES = {
    ADMIN: 'admin',
    HR: 'hr',
    STAFF: 'staff',
};

export const ROLE_CONFIG = {
    [ROLES.ADMIN]: {
        label: 'Quản trị hệ thống',
        layout: 'admin',
        dashboard: '/admin/nhan-vien',
    },
    [ROLES.HR]: {
        label: 'Nhân sự',
        layout: 'admin',
        dashboard: '/admin/tuyen-dung',
    },
    [ROLES.STAFF]: {
        label: 'Nhân viên',
        layout: 'staff',
        dashboard: '/dashboard-staff',
    },
};

export function getDashboardByRole(role) {
    return ROLE_CONFIG[role]?.dashboard || '/admin/nhan-vien';
}

export function getLayoutByRole(role) {
    return ROLE_CONFIG[role]?.layout || 'default';
}
