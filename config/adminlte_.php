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

	'title' => 'Bravo',

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

	'logo' => '<b>BSP</b>',

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

	'dashboard_url' => 'home',

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
		],
		[
			'text' => 'Anggota',
			'url' => 'members',
			'icon' => 'users',
			'label' => '',
			'label_color' => 'success',
            'can'=> 'view.member.total_member'
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
		'TRANSAKSI ANGGOTA',
		// halaman member
		[
			'text' => 'Simpan Pinjam',
			'icon' => 'handshake-o',
			'submenu' => [
				[
					'text' => 'Pinjaman',
					'url' => 'member-loans'
				],
				[
					'text' => 'Simpanan',
					'url' => 'member-deposits'
				]
			]
		],
		// halaman admin
		[
			'text' => 'Transactions',
			'icon' => 'briefcase',
			'can' =>'view.loan.ts_loan_all',
			'submenu' => [
				[
					'text' => 'Plafon Anggota',
					'url' => 'plafons',
				],
				[
					'text' => 'Pinjaman',
					'url' => 'get-loans',
				],
				[
					'text' => 'Simpanan',
					'url' => 'ts-deposits'
				]
			]
		],

		'KELOLA MASTER DATA',
		[
			'text' => 'Tipe Transaksi',
			'icon' => 'briefcase',
            'can'  =>'view.transaction.management',
			'submenu' => [
				[
					'text' => 'Daftar Pinjaman',
					'url' => 'loans'
				],
                [
                    'text' => 'Tipe Deposito/Simpanan',
                    'url' => 'deposits',
//                    'icon' => 'book',
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
					'text' => 'Daftar Lokasi',
					'url' => 'locations',
                    'can'=> 'view.master.location'
				]
			]
		],
		[
			'text' => 'Posisi',
			'url' => 'positions',
			'icon' => 'sitemap',
            'can'=> 'view.master.position'
		],
		[
			'text' => 'Otentifikasi',
			'icon' => 'lock',
            'can'=>'view.auth.management',
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
//				[
//					'text' => 'Hak User',
//					'url' => 'privileges'
//				]
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
		JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
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
