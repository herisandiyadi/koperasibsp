<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */



Auth::routes();
Route::get('/import-member', 'ImportExcelController@import_member')->name('import_member');
Route::get('/import-member-pinjaman', 'ImportExcelController@import_member_pinjaman')->name('import_member_pinjaman');
Route::get('/import-member-pokok-kredit', 'ImportExcelController@import_member_pokok_kredit')->name('import_member_pokok_kredit');
Route::get('/import-member-pokok-debit', 'ImportExcelController@import_member_pokok_debit')->name('import_member_pokok_debit');

Route::get('/import-member-wajib-debit', 'ImportExcelController@import_member_wajib_debit')->name('import_member_wajib_debit');
Route::get('/import-member-wajib-kredit', 'ImportExcelController@import_member_wajib_kredit')->name('import_member_wajib_kredit');

Route::get('/import-member-sukarela-debit', 'ImportExcelController@import_member_sukarela_debit')->name('import_member_sukarela_debit');
Route::get('/import-member-sukarela-kredit', 'ImportExcelController@import_member_sukarela_kredit')->name('import_member_sukarela_kredit');

Route::get('/import-member-shu-debit', 'ImportExcelController@import_member_shu_debit')->name('import_member_shu_debit');
Route::get('/import-member-lainnya-debit', 'ImportExcelController@import_member_lainnya_debit')->name('import_member_lainnya_debit');

Route::get('/import-member-update-project', 'ImportExcelController@update_project')->name('update_project');
Route::get('/import-member-update-simpanan-configs', 'ImportExcelController@update_simpanan_config')->name('update_simpanan_config');
Route::get('/import-member-project', 'ImportExcelController@update_member_project')->name('update_member_project');

Route::get('/update-wajib', 'ImportExcelController@updatingDateWajib')->name('updatingDateWajib');

Route::get('logout', 'LogController@logout');
Route::get('register', 'Auth\RegisterController@register')->name('RegisterController');
//Route::group(['middleware' => ['role:SUPERADMIN']], function () {
//    Route::get('role-test', function() {
//        return \App\Level::privilegeGenerator();
//        return \Spatie\Permission\Models\Permission::where('guard_name','web')->pluck('name')->toArray();
//        $roles = auth()->user();
//        $p = \Spatie\Permission\Models\Permission::where('name', 'view.member.total_member')->first()->name;
//        return \Spatie\Permission\Models\Role::find(1)->givePermissionTo($p);
//        return App\User::first()->addRole('ssasd');
//    });
//});
Route::get('register/create', 'Auth\RegisterController@create');
Route::get('verify/{el}', 'LoadController@verification');
Route::post('daftar', 'Auth\RegisterController@store');
Route::get('get/projects', 'ProjectController@getProject');
Route::get('projects/datatable/{par}', 'ProjectController@datatable');
Route::get('privacy-policy', 'PrivacyPolicyController@privacy');

Route::group(['prefix' => 'jobs'], function () {
	Route::get('generate-deposit', 'JobsController@generate_deposit');
});
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['role:POWERADMIN']], function() {
        Route::get('pow/permit-creator', 'PowController@permissionCreator');
        Route::get('pow/test', 'PowController@test');
    });

    Route::prefix('contact')->group(function () {
        Route::get('/', 'ContactController@index');
        Route::post('/', 'ContactController@store');
        Route::get('create', 'ContactController@create');
        Route::delete('{id}', 'ContactController@destroy');
        Route::patch('{id}', 'ContactController@update')->name('contract.update');
        Route::get('{id}/show', 'ContactController@show');
        Route::get('{id}/edit', 'ContactController@edit');
        Route::get('datatable/{query}', 'ContactController@datatable');
    });

    Route::group(['prefix'=>'notification'], function(){
        Route::get('view-all', 'UserController@viewAllNotifications');
        Route::get('get-all', 'UserController@getAllNotifications');
        Route::get('{id}/resolve','UserController@resolveNotifUrl');
        Route::get('{id}/mark-as-read', 'UserController@markAsRead');
    });
    Route::prefix('levels')->group(function () {
        Route::get('/', 'LevelController@index')->middleware("permission:view.auth.level");
        Route::post('/', 'LevelController@store')->middleware("permission:create.auth.level");
        Route::get('create', 'LevelController@create')->middleware("permission:create.auth.level");
        Route::delete('{id}', 'LevelController@destroy')->middleware("permission:delete.auth.level");
        Route::patch('{id}', 'LevelController@update')->name('levels.update')->middleware("permission:update.auth.level");
        Route::get('{id}', 'LevelController@show')->middleware("permission:view.auth.level");
        Route::get('{id}/edit', 'LevelController@edit')->middleware("permission:update.auth.level");
    });
    Route::prefix('users')->group(function () {
        Route::get('/', 'UserController@index')->middleware("permission:view.auth.user");
        Route::post('/', 'UserController@store')->middleware("permission:create.auth.user");
        Route::get('create', 'UserController@create')->middleware("permission:create.auth.user");
        Route::delete('{id}', 'UserController@destroy')->name('users.destroy')->middleware("permission:delete.auth.user");
        Route::patch('{id}', 'UserController@update')->name('users.update')->middleware("permission:update.auth.user");
        Route::get('{id}', 'UserController@show')->middleware("permission:view.auth.user");
        Route::get('{id}/edit', 'UserController@edit')->middleware("permission:update.auth.user");
    });
    Route::prefix('deposits')->group(function () {
        Route::get('/', 'DepositController@index')->middleware("permission:view.master.transaction-type");
        Route::post('/', 'DepositController@store')->middleware("permission:create.master.transaction-type");
        Route::get('create', 'DepositController@create')->middleware("permission:create.master.transaction-type");
        Route::delete('{id}', 'DepositController@destroy')->name('deposits.destroy')->middleware("permission:delete.master.transaction-type");
        Route::patch('{id}', 'DepositController@update')->name('deposits.update')->middleware("permission:update.master.transaction-type");
        Route::get('{id}', 'DepositController@show')->middleware("permission:view.master.transaction-type");
        Route::get('{id}/edit', 'DepositController@edit')->middleware("permission:update.master.transaction-type");
	});

	Route::prefix('setting')->group(function () {
        Route::get('/', 'GeneralSettingController@index')->middleware("permission:view.master.setting");
        Route::post('/', 'GeneralSettingController@store')->middleware("permission:create.master.level");
        Route::get('create', 'GeneralSettingController@create')->middleware("permission:create.master.setting");
        Route::delete('{id}', 'GeneralSettingController@destroy')->middleware("permission:delete.master.setting");
        Route::patch('{id}', 'GeneralSettingController@update')->name('setting.update')->middleware("permission:update.master.setting");
        Route::get('{id}', 'GeneralSettingController@show')->middleware("permission:view.master.setting");
        Route::get('{id}/edit', 'GeneralSettingController@edit')->middleware("permission:update.master.setting");
    });

	Route::prefix('policy')->group(function () {
        Route::get('/', 'PolicyController@index')->middleware("permission:view.master.policy");
        Route::post('/', 'PolicyController@store')->middleware("permission:create.master.policy");
        Route::get('create', 'PolicyController@create')->middleware("permission:create.master.policy");
        Route::delete('{id}', 'PolicyController@destroy')->middleware("permission:delete.master.policy");
        Route::patch('/{id}', 'PolicyController@update')->name('policy.update')->middleware("permission:update.master.policy");
        Route::get('{id}', 'PolicyController@show')->middleware("permission:view.master.policy");
		Route::get('{id}/edit', 'PolicyController@edit')->middleware("permission:update.master.policy");
		Route::get('datatable/{query}', 'PolicyController@datatable')->middleware("permission:view.master.policy");
	});

	Route::prefix('article')->group(function () {
        Route::get('/', 'ArticleController@index')->middleware("permission:view.master.article");
        Route::post('/', 'ArticleController@store')->middleware("permission:create.master.article");
        Route::get('create', 'ArticleController@create')->middleware("permission:create.master.article");
        Route::delete('{id}', 'ArticleController@destroy')->middleware("permission:delete.master.article");
        Route::patch('/{id}', 'ArticleController@update')->name('article.update')->middleware("permission:update.master.article");
        Route::get('{id}', 'ArticleController@show')->middleware("permission:view.master.article");
		Route::get('{id}/edit', 'ArticleController@edit')->middleware("permission:update.master.article");
		Route::get('datatable/{query}', 'ArticleController@datatable')->middleware("permission:view.master.article");
        Route::get('{id}/publish/{blast}','ArticleController@publish');
    });

	Route::resource('plafons', 'PlafonController')->middleware('su.only');

    Route::group(['middleware' => ['isVerify']], function () {
//        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/dashboard', 'HomeController@index')->name('dashboard');
        Route::get('/count-member', 'HomeController@countMember');
        Route::get('/', 'HomeController@index')->name('dashboard');
        Route::get('member-active', 'HomeController@memberActive');
        Route::get('profile-member/{el}', 'HomeController@profileMember');
        Route::get('my-profile', 'HomeController@myProfile');

        // minimize the complexity of the code
        Route::group(['namespace' => 'Panel', 'as' => 'panel'], function () {
            includeRouteFiles(__DIR__.'/user/');
        });

    });

    Route::group(['prefix'=>'privilege','middleware' => ['role:POWERADMIN|SUPERADMIN']], function(){
       Route::get('{level}/view','PrivilegeController@view');
       Route::post('{level}/update','PrivilegeController@update');
       Route::get('/add-permission/{name}', 'PrivilegeController@addPermission');
    });
	Route::post('/loadData', 'LoadController@loadData')->name('loadData');
//	Route::resource('regions', 'RegionController');
//	Route::resource('projects', 'ProjectController');

    Route::prefix('permissions')->group(function () {
        Route::get('/', 'PermissionController@index');
        Route::post('/', 'PermissionController@store');
        Route::get('create', 'PermissionController@create');
        Route::delete('{id}', 'PermissionController@destroy');
        Route::patch('{id}', 'PermissionController@update')->name('permissions.update');
        Route::get('{id}', 'PermissionController@show');
        Route::get('{id}/edit', 'PermissionController@edit');
    });

	Route::prefix('regions')->group(function () {
		Route::get('/', 'RegionController@index')->middleware("permission:view.master.area");
		Route::post('/', 'RegionController@store')->middleware("permission:create.master.area");
		Route::get('create', 'RegionController@create')->middleware("permission:create.master.area");
		Route::delete('{id}', 'RegionController@destroy')->middleware("permission:delete.master.area");
		Route::patch('{id}', 'RegionController@update')->name('regions.update')->middleware("permission:update.master.area");
		Route::get('{id}', 'RegionController@show')->middleware("permission:view.master.area");
		Route::get('{id}/edit', 'RegionController@edit')->middleware("permission:update.master.area");
	});
	Route::prefix('projects')->group(function () {
		Route::get('/', 'ProjectController@index')->middleware("permission:view.master.project");
		Route::post('/', 'ProjectController@store')->middleware("permission:create.master.project");
		Route::get('create', 'ProjectController@create')->middleware("permission:create.master.project");
		Route::delete('{id}', 'ProjectController@destroy')->middleware("permission:delete.master.project");
		Route::patch('{id}', 'ProjectController@update')->name('projects.update')->middleware("permission:update.master.project");
		Route::get('{id}', 'ProjectController@show')->middleware("permission:view.master.project");
		Route::get('{id}/edit', 'ProjectController@edit')->middleware("permission:update.master.project");
		Route::get('datatable/{query}', 'ProjectController@datatable')->middleware("permission:view.master.project");
	});

    Route::prefix('branch')->group(function(){
        Route::get('/', 'BranchController@index')->middleware("permission:view.master.branch");
        Route::post('/', 'BranchController@store')->middleware("permission:create.master.branch");
        Route::get('create', 'BranchController@create')->middleware("permission:create.master.branch");
        Route::delete('{id}', 'BranchController@destroy')->middleware("permission:delete.master.branch");
        Route::patch('{id}', 'BranchController@update')->name('branch.update')->middleware("permission:update.master.branch");
        Route::get('{id}', 'BranchController@show')->middleware("permission:view.master.branch");
        Route::get('{id}/edit', 'BranchController@edit')->middleware("permission:update.master.branch");
    });

	Route::prefix('positions')->group(function () {
		Route::get('/', 'PositionController@index')->middleware("permission:view.master.position");
		Route::post('/', 'PositionController@store')->middleware("permission:create.master.position");
		Route::get('create', 'PositionController@create')->middleware("permission:create.master.position");
		Route::delete('{id}', 'PositionController@destroy')->middleware("permission:delete.master.position");
		Route::patch('{id}', 'PositionController@update')->name('positions.update')->middleware("permission:update.master.position");
		Route::get('{id}', 'PositionController@show')->middleware("permission:view.master.position");
		Route::get('{id}/edit', 'PositionController@edit')->middleware("permission:update.master.position");
	});

	Route::prefix('locations')->group(function () {
		Route::get('/', 'LocationController@index')->middleware("permission:view.master.location");
		Route::post('/', 'LocationController@store')->middleware("permission:create.master.location");
		Route::get('create', 'LocationController@create')->middleware("permission:create.master.location");
		Route::delete('{id}', 'LocationController@destroy')->middleware("permission:delete.master.location");
		Route::patch('{id}', 'LocationController@update')->name('locations.update')->middleware("permission:update.master.location");
		Route::get('{id}', 'LocationController@show')->middleware("permission:view.master.location");
		Route::get('{id}/edit', 'LocationController@edit')->middleware("permission:update.master.location");
	});

    Route::prefix('generate')->group(function () {
        Route::prefix('deposit-report')->group(function () {
            Route::get('/', 'GenerateReportDepositsController@index');
            Route::post('/', 'GenerateReportDepositsController@store');
            Route::get('create', 'GenerateReportDepositsController@create');
            Route::delete('{id}', 'GenerateReportDepositsController@destroy');
            Route::put('{id}', 'GenerateReportDepositsController@update')->name('generate.deposit.update');
            Route::get('{id}', 'GenerateReportDepositsController@show');
            Route::get('{id}/download', 'GenerateReportDepositsController@download');
            Route::get('{id}/edit', 'GenerateReportDepositsController@edit');
        });

        Route::prefix('member-report')->group(function () {
            Route::get('/', 'GenerateReportMembersController@index');
            Route::post('/', 'GenerateReportMembersController@store');
            Route::get('create', 'GenerateReportMembersController@create');
            Route::delete('{id}', 'GenerateReportMembersController@destroy');
            Route::put('{id}', 'GenerateReportMembersController@update')->name('generate.member.update');
            Route::get('{id}', 'GenerateReportMembersController@show');
            Route::get('{id}/download', 'GenerateReportMembersController@download');
            Route::get('{id}/edit', 'GenerateReportMembersController@edit');
        });

        Route::prefix('rekap-anggota')->group(function () {
            Route::get('/', 'GenerateRekapAnggotaController@index');
            Route::post('/', 'GenerateRekapAnggotaController@store');
            Route::get('create', 'GenerateRekapAnggotaController@create');
            Route::delete('{id}', 'GenerateRekapAnggotaController@destroy');
            Route::put('{id}', 'GenerateRekapAnggotaController@update')->name('generate.rekap.anggota');
            Route::get('{id}', 'GenerateRekapAnggotaController@show');
            Route::get('{id}/download', 'GenerateRekapAnggotaController@download');
            Route::get('{id}/edit', 'GenerateRekapAnggotaController@edit');
        });

        Route::prefix('deposit-member-report')->group(function () {
            Route::get('/', 'GenerateMemberReportDeposit@index');
            Route::post('/', 'GenerateMemberReportDeposit@download');
            Route::get('/get-member', 'GenerateMemberReportDeposit@getMember');
            Route::post('/get-member/deposit', 'GenerateMemberReportDeposit@getMemberDeposit');
            Route::get('{id}/download', 'GenerateMemberReportDeposit@download');
        });

        Route::prefix('member-report-area-proyek')->group(function () {
            Route::get('/', 'GenerateMemberReportAreaProyek@index');
            Route::post('/', 'GenerateMemberReportAreaProyek@download');
            Route::get('{id}/download', 'GenerateMemberReportAreaProyek@download');
        });

        Route::prefix('member-resign')->group(function () {
            Route::get('/', 'GenerateMemberResign@index');
            Route::post('/', 'GenerateMemberResign@download');
            Route::post('/get-member', 'GenerateMemberResign@getMember');
            Route::get('{id}/download', 'GenerateMemberResign@download');
        });

        Route::prefix('pengelola')->group(function () {
            Route::get('/', 'GenerateReportPengelolaController@index');
            Route::post('/', 'GenerateReportPengelolaController@download');
            Route::post('/get-pengelola', 'GenerateReportPengelolaController@getPengelola');
            Route::get('{id}/download', 'GenerateReportPengelolaController@download');
        });

        Route::prefix('member-sukarela')->group(function () {
            Route::get('/', 'GenerateReportSukarelaController@index');
            Route::post('/', 'GenerateReportSukarelaController@download');
            Route::get('/get-member', 'GenerateReportSukarelaController@getMember');
            Route::post('/get-member/deposit', 'GenerateReportSukarelaController@getMemberDeposit');
            Route::get('{id}/download', 'GenerateReportSukarelaController@download');
        });

        Route::prefix('potongan-payrol')->group(function () {
            Route::get('/', 'GenerateReportPotonganPayroll@index');
            Route::post('/', 'GenerateReportPotonganPayroll@download');
            Route::get('/get-member', 'GenerateReportPotonganPayroll@getMember');
            Route::post('/get-member/deposit', 'GenerateReportPotonganPayroll@getMemberDeposit');
            Route::get('{id}/download', 'GenerateReportPotonganPayroll@download');
        });

        Route::prefix('monthly-deposit-loan')->group(function () {
            Route::get('/', 'GenerateReportMonthlyController@index');
            Route::post('/', 'GenerateReportMonthlyController@download');
            Route::get('/get-member', 'GenerateReportMonthlyController@getMember');
            Route::post('/get-member/deposit', 'GenerateReportMonthlyController@getMemberDeposit');
            Route::get('{id}/download', 'GenerateReportMonthlyController@download');
        });

        Route::prefix('pencairan-pinjaman')->group(function () {
            Route::get('/', 'GeneratePencairanPinjamanController@index');
            Route::post('/', 'GeneratePencairanPinjamanController@download');
            Route::get('/get-member', 'GeneratePencairanPinjamanController@getMember');
            Route::post('/get-member/deposit', 'GeneratePencairanPinjamanController@getMemberDeposit');
            Route::get('{id}/download', 'GeneratePencairanPinjamanController@download');
        });
    });

	Route::prefix('tambah-simpanan')->group(function () {
        Route::get('/', 'TambahSimpananController@index');
		Route::post('/new', 'TambahSimpananController@store');
    });

    Route::prefix('persetujuan-pinjaman')->group(function () {
        Route::get('/', 'ApprovalController@index');
    });


//	Route::resource('members', 'MemberController')->middleware('su.only');
	Route::resource('members', 'MemberController');
	Route::group(['prefix'=>'member'], function(){
	    Route::get('kartu-anggota', 'MemberController@viewKartuAnggota');
        Route::get('kartu-anggota/download', 'MemberController@downloadKartuAnggota');
        Route::get('{id}/profile', 'MemberController@viewProfile');
        Route::group(['prefix'=>'bank'], function() {
            Route::get('/', 'BankController@index');
            Route::post('/', 'BankController@store');
            Route::get('create', 'BankController@create');
            Route::delete('{id}', 'BankController@destroy');
            Route::put('{id}', 'BankController@update')->name('member.bank.update');
            Route::get('{id}', 'BankController@show');
            Route::get('{id}/download', 'BankController@download');
            Route::get('{id}/edit', 'BankController@edit');
        });
    });

	Route::group(['prefix' => 'account-setting'], function (){
        Route::get('/password', 'AccountController@password');
        Route::put('/password/update', 'AccountController@updatePassword')->name('account.password.update');

        Route::get('/email', 'AccountController@email');
        Route::put('/email/update', 'AccountController@updateEmail')->name('account.email.update');
    });

	Route::resource('loans', 'LoanController');
    Route::resource('ts-deposits', 'TsDepositsController');
	Route::get('view-detail/{el}', 'TsDepositsController@depositDetail');
    Route::post('view-deposit', 'TsDepositsController@viewDeposit');
    Route::post('update-deposit', 'TsDepositsController@updateDeposit');

    Route::get('update-member-deposit', 'TsDepositsController@updateDepositMember');
    Route::get('update-member-project', 'TsDepositsController@updateProjectMember');
    Route::get('update-member-role', 'ImportController@updateRoleAdmin');

    Route::get('get-deposits', 'TsDepositsController@index');
    Route::get('get-deposits/simpanan-wajib', 'TsDepositsController@wajib');
    Route::get('get-deposits/simpanan-sukarela', 'TsDepositsController@sukarela');
    Route::get('get-deposits/simpanan-pokok', 'TsDepositsController@pokok');
    Route::get('get-deposits/simpanan-lainnya', 'TsDepositsController@lainnya');
    Route::get('get-deposits/simpanan-shu', 'TsDepositsController@shu');

    Route::post('ts-deposits-id', 'LoadController@testPost');

    Route::get('member-deposits', 'TsDepositsController@ts_deposit_members');
    Route::get('member-deposits/wajib', 'TsDepositsController@ts_deposit_members_wajib');
    Route::get('member-deposits/sukarela', 'TsDepositsController@ts_deposit_members_sukarela');
    Route::get('member-deposits/pokok', 'TsDepositsController@ts_deposit_members_pokok');
    Route::get('member-deposits/lainnya', 'TsDepositsController@ts_deposit_members_lainnya');

	Route::get('member-deposit-list/{el}', 'TsDepositsController@ts_deposit_members_detail');

    Route::get('member-detail-deposit/{id}', 'MemberDetailDepositController@show');
    Route::get('member-detail-deposit/{id}/wajib', 'MemberDetailDepositController@ts_deposit_members_wajib');
    Route::get('member-detail-deposit/{id}/pokok', 'MemberDetailDepositController@ts_deposit_members_pokok');
    Route::get('member-detail-deposit/{id}/sukarela', 'MemberDetailDepositController@ts_deposit_members_sukarela');
	Route::get('member-detail-deposit/{id}/sukarela-member-deposit', 'MemberDetailDepositController@ts_deposit_members_sukarela');
//    Route::get('member-detail-deposit/{id}/shu', 'MemberDetailDepositController@show');
    Route::get('member-detail-deposit/{id}/lainnya', 'MemberDetailDepositController@ts_deposit_members_lainnya');

    Route::get('get-detail-deposit/{id}', 'MemberDetailDepositController@getDetaiList');

    Route::get('member-detail-loan/{id}', 'MemberDetailLoanController@show');
    Route::get('get-detail-loan/{id}', 'MemberDetailLoanController@getDetaiList');

	Route::get('list-resign', 'ResignController@list_resign');

	Route::get('list-resign/{query}', 'ResignController@listResign');
	Route::get('list-resign/download/{member_id}', 'ResignController@download');
	Route::post('list-resign/get-status-resign', 'ResignController@getStatusResign');
	Route::post('list-resign/approval', 'ResignController@approve');
    Route::get('update-status-member', 'ResignController@updateStatusMember');


    Route::get('list-change-deposit', 'DepositController@list_change_deposit');
	Route::get('list-change-deposit/{query}', 'DepositController@list_change_deposit');



	Route::get('retrieve-member-deposits', 'MemberController@retrieve_deposit');
	Route::post('retrieve-member-deposits/form', 'MemberController@post_retrieve_deposit');
    Route::get('update-member-admin', 'MemberController@updateMemberAdmin');


	Route::get('change-member-deposits', 'MemberController@change_deposit');
	Route::post('change-member-deposits/form', 'MemberController@post_change_deposit');

    Route::get('list-change-deposits', 'MemberController@change_deposit');

	Route::get('pengambilan-simpanan', 'DepositController@pengambilan_simpanan');
	Route::get('list-pengambilan-simpanan/{query}', 'DepositController@list_pengambilan_simpanan');
	Route::post('list-pengambilan-simpanan/get-status', 'DepositController@getStatus');
	Route::post('list-pengambilan-simpanan/approval', 'DepositController@approve');

	Route::get('perubahan-simpanan', 'DepositController@change_deposit');
	Route::get('list-perubahan-simpanan/{query}', 'DepositController@list_change_deposit');
	Route::post('list-perubahan-simpanan/get-status', 'DepositController@getPerubahanSimpanan');

	Route::post('list-perubahan-simpanan/change-deposit-approval', 'DepositController@change_deposit_approve');

	/**
     * CONTOH middleware privilege
     */
    Route::get('permission-test', function(){
        return 'Bisa lihat pengajuan pinjaman';
    })->middleware('permission:view.loan.application');

    Route::get('role-test', function(){
        return "cuma Poweradmin yang bisa";
    })->middleware('role:POWERADMIN');
    /**
    * Handling for resign application
    */
    Route::resource('resign', 'ResignController');

    Route::group(['prefix' => 'shu'], function(){
    	Route::resource('/', 'ShuController');
		Route::get('datatable/{query}', 'ShuController@datatable')->middleware("permission:view.master.shu");
		Route::get('{id}/complete','ShuController@complete');
		Route::delete('{id}', 'ShuController@destroy')->middleware("permission:delete.master.shu");
		Route::get('download/{id}', 'ShuController@download')->middleware("permission:view.master.shu");

	});

	Route::get('/importdata', 'ImportController@getImport')->name('import');
	Route::post('/import_parse', 'ImportController@parseImport')->name('import_parse');
	Route::post('/import_process', 'ImportController@processImport')->name('import_process');

    Route::get('/importdata-admin', 'ImportController@getImportAdmin')->name('import_admin');
    Route::post('/import_parse_admin', 'ImportController@parseImportAdmin')->name('import_parse_admin');
});
