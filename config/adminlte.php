<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
 */

	'title' => 'Koperasi Security BSP',

	'title_prefix' => '',

	'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
	 */

	'logo' => '<b>KSBSP</b>',

	'logo_mini' => '<b>B</b>SP',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
	 */

	'skin' => 'red-light',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
	 */

	'layout' => 'fixed',

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
	 */

	'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
	 */

	'dashboard_url' => 'dashboard',

	'logout_url' => 'logout',

	'logout_method' => null,

	'login_url' => 'login',

	'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
	 */

	'menu' => [
//		'NAVIGATION',
		[
			'text' => 'Dashboard',
			'url' => 'dashboard',
            'icon' => 'tachometer',
            'active'=> ['dashboard']
		],
        [
            'text' => 'Profile',
            'url' => 'my-profile',
            'icon' => 'address-card',
            'label' => '',
            'label_color' => 'success',
            'role'=> ['MEMBER'],
//            'can'=> 'view.member.profile'
        ],
        [
            'text' => 'Kartu Anggota',
            'url' => 'member/kartu-anggota',
            'icon' => 'credit-card',
            'label' => '',
            'label_color' => 'success',
            'role'=> ['MEMBER'],
//            'can'=> 'view.member.profile'
        ],
		[
			'text' => 'Anggota',
			'url' => 'members',
			'icon' => 'users',
			'label' => '',
			'label_color' => 'success',
            'role'=> ['SUPERADMIN', 'POWERADMIN', 'PENGAWAS_1', 'PENGAWAS_2', 'ADMIN_AREA','PENGAWAS_3','ADMIN_KOPERASI', 'MANAGER','GENERAL_MANAGER'],
//            'can'=> 'view.member.total_member'
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
		//'TRANSAKSI ANGGOTA',
		// halaman member
		[
			'text' => 'Simpan Pinjam',
			'icon' => 'handshake-o',
            'role'=> ['MEMBER', 'DANSEK'],
			'submenu' => [
				[
					'text' => 'Pinjaman',
					'url' => 'member-loans'
				],
                [
                    'text' => 'Pengajuan Pinjaman',
                    'url' => 'loan-aggrement'
                ],
				[
					'text' => 'Simpanan',
					'url' => 'member-deposits'
				],
				[
					'text' => 'Pengambilan Simpanan',
					'url' => 'retrieve-member-deposits'
				],
				[
					'text' => 'Perubahan Simpanan',
					'url' => 'change-member-deposits'
				],

//				[
//					'text' => 'Pengambilan Simpanan',
//					'url' => 'member-get-deposit'
//				]
			]
		],
		[
			'text' => 'Pengunduran Diri',
			// 'url' => 'resign',
			'icon' => 'sign-out',
//			'can'=> 'view.form.resign',
			'role'=> ['MEMBER', 'DANSEK'],
			'submenu' => [
				[
					'text' => 'Diajukan',
					'url' => 'resign',
					'can'=> 'view.report.resign',
				],
				[
					'text' => 'Form Pengajuan',
					'url' => 'resign/create'
				],
			]
		],
		[
			'text' => 'Master',
			'icon' => 'sitemap',
			'can'=> 'view.member.bank',
//			'role'=> ['SUPERADMIN'],
			'submenu' => [
				[
					'text' => 'Bank',
					'url' => 'member/bank',
//					'can'=> 'view.report.resign',
				]
			]
		],
		// halaman admin
        //'TRANSAKSI ADMIN',
		[
			'text' => 'Transaksi',
			'icon' => 'briefcase',
            'can'  => 'view.transaction.management',
//            'role'=> ['SUPERADMIN', 'POWERADMIN', 'PENGAWAS_1', 'PENGAWAS_2', 'ADMIN_AREA', 'PENGAWAS_3','ADMIN_KOPERASI', 'MANAGER','GENERAL_MANAGER'],
			'submenu' => [
				[
					'text' => 'Plafon Anggota',
					'url' => 'plafons',
                    'can' => 'view.member.plafond',
				],
				[
					'text' => 'Pinjaman',
					'url' => 'get-loans',
                    'can' => 'view.member.loan',
				],
				[
					'text' => 'Simpanan',
					'url' => 'get-deposits',
                    'can' => 'view.member.deposit',
				],
                [
                    'text' => 'Pengambilan Simpanan',
                    'url' => 'pengambilan-simpanan',
                    'can' => 'view.transaction.member.retrieve.deposit',

                ],
                [
                    'text' => 'Perubahan Simpanan',
                    'url' => 'perubahan-simpanan',
                    'can' => 'view.transaction.member.change.deposit',
                ],
                [
                    'text' => 'Persetujuan Pinjaman',
                    'url' => 'persetujuan-pinjaman',
                    'can' => 'update.member.loan',
                ],
				[
					'text' => 'Tambah Simpanan',
					'url' => 'tambah-simpanan',
					'can' => 'update.member.deposit',
				],
			]
		],
        [
            'text' => 'Generate Laporan',
            'icon' => 'folder',
            'can'  => 'view.report',
//            'role'=> ['SUPERADMIN', 'POWERADMIN', 'PENGAWAS_1', 'PENGAWAS_2', 'ADMIN_AREA', 'PENGAWAS_3','ADMIN_KOPERASI', 'MANAGER','GENERAL_MANAGER'],
            'submenu' => [
                [
                    'text' => 'Simpanan',
                    'url' => 'generate/deposit-report',
                    'can' => 'view.generate.report.deposit',
                ],
                [
                    'text' => 'Simpanan Anggota',
                    'url' => 'generate/deposit-member-report',
                    'can' => 'view.generate.report.deposit',
                ],
                // [
                //     'text' => 'Data Pinjaman',
                //     'url' => '#',
                //     'can' => 'view.report.loan',
                // ],
                [
                    'text' => 'Pencairan Pinjaman',
                    'url' => 'generate/pencairan-pinjaman',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Pengambilan Sukarela',
                    'url' => 'generate/member-sukarela',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Monthly Simpan Pinjam',
                    'url' => 'generate/monthly-deposit-loan',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Anggota',
                    'url' => 'generate/member-report',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Anggota Area dan Proyek',
                    'url' => 'generate/member-report-area-proyek',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Anggota Resign',
                    'url' => 'generate/member-resign',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Pengelola HO',
                    'url' => 'generate/pengelola',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Rekap Anggota',
                    'url' => 'generate/rekap-anggota',
                    'can' => 'view.generate.report.member',
                ],
                [
                    'text' => 'Potongan Payrol',
                    'url' => 'generate/potongan-payrol',
                    'can' => 'view.generate.report.member',
                ],
//                [
//                    'text' => 'In Out Simpanan',
//                    'url' => 'generate/potongan-payrol',
//                    'can' => 'view.generate.report.member',
//                ],
                [
                    'text' => 'SHU',
                    'url' => 'shu',
                    'can' => 'view.master.shu',
                ]
            ]
        ],
		[
			'text' => 'Resign',
			'icon' => 'handshake-o',
			'url' => 'list-resign',
			'can'=> 'view.member.resign',
		],
        [
            'text' => 'Berita',
            'url' => 'article',
            'icon' => 'newspaper-o',
            'can'=> 'view.master.article'
        ],
        [
            'text' => 'SHU',
            'url' => 'shu',
            'icon' => 'cog',
            'can'=> 'view.master.shu'
        ],
		//'KELOLA MASTER DATA',
		[
			'text' => 'Tipe Transaksi',
			'icon' => 'briefcase',
            'can'  => 'view.master.transaction-type',
			'submenu' => [
				[
					'text' => 'Daftar Pinjaman',
					'url' => 'loans',
                    'can'  => 'view.master.loan',
				],
                [
                    'text' => 'Tipe Deposito/Simpanan',
                    'url' => 'deposits',
                    'can'  => 'view.master.deposit',
                ],
			]
		],
		[
			'text' => 'Proyek',
			'icon' => 'file-powerpoint-o',
            'can'=>'view.project.management',
			'submenu' => [
				[
					'text' => 'Daftar Proyek',
					'url' => 'projects',
                    'can'=> 'view.master.project'
				],
				[
					'text' => 'Daftar Area',
					'url' => 'regions',
                    'can'=>'view.master.area'
				],
				[
					'text' => 'Daftar Cabang',
					'url' => 'branch',
                   	'can'=> 'view.master.branch'
				],
				[
					'text' => 'Daftar Lokasi',
					'url' => 'locations',
                    'can'=> 'view.master.location'
				]
			]
		],
        [
            'text' => 'Inbox',
            'url' => 'contact',
            'icon' => 'envelope',
            'can'=> ''
        ],
		[
			'text' => 'Posisi',
			'url' => 'positions',
			'icon' => 'sitemap',
            'can'=> 'view.master.position'
		],
		[
			'text' => 'Privacy Policy',
			'url' => 'policy',
			'icon' => 'shield',
            'can'=> 'view.master.policy'
		],
//		[
//			'text' => 'Pengaturan',
//			'url' => 'setting',
//			'icon' => 'cog',
//            'can'=> 'view.master.setting'
//		],
        [
            'text' => 'Pengaturan Akun',
            'icon' => 'address-card',
            'can'=> 'update.account.configuration',
            'submenu' => [
                [
                    'text' => 'Ubah Password',
                    'url' => 'account-setting/password',
                    'can'=> 'update.account.configuration',
                ],
                [
                    'text' => 'Ubah Email',
                    'url' => 'account-setting/email',
                    'can'=> 'update.account.configuration',
                ],
            ]
        ],
		[
			'text' => 'Otentifikasi',
			'icon' => 'lock',
            'can' => 'view.auth.management',
			'submenu' => [
				[
					'text' => 'Daftar User',
					'url' => 'users',
                    'can'=> 'view.auth.user'
				],
				[
					'text' => 'Level User',
					'url' => 'levels',
                    'can'=> 'view.auth.level'
				],
                [
                    'text' => 'Permisions User',
                    'url' => 'permissions',
                    'can'=> 'view.auth.level'
                ],
//				[
//					'text' => 'Hak User',
//					'url' => 'privileges'
//				]
			],
		],
        [
            'text' => 'Import Data',
            'icon' => 'file-excel-o',
            'can'=>'view.management.import',
            'submenu' => [
                [
                    'text' => 'Anggota',
                    'url' => 'importdata',
                ],
                [
                    'text' => 'Admin',
                    'url' => 'importdata-admin',
                ],
            ],
        ],

        [
            'text' => 'General Setting',
            'url' => 'setting',
            'icon' => 'cog',
            'can'=> 'view.general.setting'
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

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
	 */

	'filters' => [
		JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
		JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
		JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
		JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
//		JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        \App\Helpers\BSPMenuFilter::class,
	],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
	 */

	'plugins' => [
		'datatables' => true,
		'select2' => true,
		'chartjs' => true,
	],
];
