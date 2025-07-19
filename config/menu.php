<?php

return [
    [
        'type' => 'item',
        'title' => 'Dashboard',
        'icon' => 'fas.gauge',
        'link' => 'dashboard',
        'can' => 'dashboard'
    ],
    [
        'type' => 'separator',
        'title' => 'Master Data',
        'can' => 'master-data'
    ],
    [
        'type' => 'item',
        'title' => 'Fakultas',
        'icon' => 'fas.building',
        'link' => 'faculties',
        'can'  => 'manage-faculties',
    ],
    [
        'type' => 'item',
        'title' => 'Program Studi',
        'icon' => 'fas.building',
        'link' => 'departments',
        'can'  => 'manage-departments',
    ],
    [
        'type' => 'item',
        'title' => 'Dosen',
        'icon' => 'fas.user',
        'link' => 'lecturers',
        'can'  => 'manage-lecturers',
    ],
    [
        'type' => 'item',
        'title' => 'Mahasiswa',
        'icon' => 'fas.user',
        'link' => 'students',
        'can'  => 'manage-students',
    ],
    [
        'type' => 'item',
        'title' => 'Users',
        'icon' => 'fas.user',
        'link' => 'users',
        'can'  => 'manage-users',
    ],
    [
        'type' => 'separator',
        'title' => 'Menu',
        'can' => 'main-menu'
    ],
    [
        'type' => 'item',
        'title' => 'Pengajuan Judul',
        'icon' => 'fas.quote-left',
        'link' => '#',
        'can'  => 'manage-theses',
    ],
    [
        'type' => 'separator',
        'title' => 'Lainnya',
        'can' => 'settings'
    ],
    [
        'type' => 'sub',
        'title' => 'Settings',
        'icon' => 'fas.gear',
        'can'  => 'settings',
        'submenu' => [
            [
                'title' => 'Roles',
                'icon' => 'fas.user-tie',
                'link' => 'roles',
                'can'  => 'manage-roles',
            ],
            [
                'title' => 'Permissions',
                'icon' => 'fas.users-line',
                'link' => 'permissions',
                'can'  => 'manage-permissions',
            ],
        ]
    ],
];
