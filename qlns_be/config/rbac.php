<?php

return [
    'root_admin' => [
        'id' => 1,
        'email' => 'an.nguyen@recuai.vn',
    ],

    'roles' => [
        'admin' => [
            'label' => 'Quản trị hệ thống',
            'layout' => 'admin',
            'dashboard' => '/admin/nhan-vien',
        ],
        'hr' => [
            'label' => 'Nhân sự',
            'layout' => 'admin',
            'dashboard' => '/admin/tuyen-dung',
        ],
        'staff' => [
            'label' => 'Nhân viên',
            'layout' => 'staff',
            'dashboard' => '/dashboard-staff',
        ],
    ],

    'permissions' => [
        'system.admin' => 'ADMIN',
        'system.hr' => 'HR',
    ],

    'permission_aliases' => [
        'admin' => 'system.admin',
        'hr' => 'system.hr',
        'role.admin' => 'system.admin',
        'role.hr' => 'system.hr',
    ],
];
