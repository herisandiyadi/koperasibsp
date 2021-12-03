<?php
return [
    config('auth.level.SUPERADMIN.name')=> [
        'NAVIGATION 1',
        [
            'text' => 'Dashboard',
            'url' => 'dashboard',
            // 'can' => 'manage-blog',
        ],
        [
            'text' => 'Anggota',
            'url' => 'members',
            'icon' => 'users',
            'label' => '',
            'label_color' => 'success',
        ],
        // [
        // 	'text' => 'Keuangan',
        // 	'icon' => 'money',
        // 	'submenu' => [
        // 		[
        // 			'text' => 'Deposito',
        // 			'url' => 'finance/deposit'
        // 		],
        // 		[
        // 			'text' => 'Pinjaman',
        // 			'url' => 'finance/loan',
        // 		],
        // 	],
        // ],
        'Kelola M Data',
//		[
//			'text' => 'Menu',
//			'url' => 'menu',
//			'icon' => 'book',
//		],
        [
            'text' => 'Transaksi',
            'icon' => 'briefcase',
            'submenu' => [
                [
                    'text' => 'Daftar Pinjaman',
                    'url' => 'loans'
                ]
            ]
        ],
        [
            'text' => 'Proyek',
            'icon' => 'briefcase',
            'submenu' => [
                [
                    'text' => 'Daftar Proyek',
                    'url' => 'projects'
                ],
                [
                    'text' => 'Daftar Area',
                    'url' => 'regions'
                ],
                [
                    'text' => 'Daftar Lokasi',
                    'url' => 'locations'
                ]
            ]
        ],
        [
            'text' => 'Posisi',
            'url' => 'positions',
            'icon' => 'sitemap',
        ],
        [
            'text' => 'Tipe Deposito/Simpanan',
            'url' => 'deposits',
            'icon' => 'book',
        ],
        [
            'text' => 'Otentifikasi',
            'icon' => 'lock',
            'submenu' => [
                [
                    'text' => 'Daftar User',
                    'url' => 'users'
                ],
                [
                    'text' => 'Level User',
                    'url' => 'levels'
                ],
                [
                    'text' => 'Hak User',
                    'url' => 'privileges'
                ]
            ],
        ],
//		'LABELS',
//		[
//			'text' => 'Important',
//			'icon_color' => 'red',
//		],
//		[
//			'text' => 'Warning',
//			'icon_color' => 'yellow',
//		],
//		[
//			'text' => 'Information',
//			'icon_color' => 'aqua',
//		],
    ],
];