$('.date').datepicker({

	format: 'mm-dd-yyyy'

  });

// global function using select2
function initSelect(idDom) {
	$(idDom).select2();
}
function loading() {
	// setTimeout(function () {
	// $("#overlay").css("display", "block");

	// }, 20);
	$( "#overlay" ).show(3000);
}
function checkErrorCounter(){
	currentError += 1;
	if(currentError >= 10){
		return false;
	}
	return true;
}
// Global function for ajax datatable record
function initDatatable(idTable, urlIndex, param='', start='', end=''){
	const dynamic = dynamicColumn(urlIndex);
	if (param != '') {
		urlIndex =  APP_URL + '/' +urlIndex+'/'+param;
	}
	// Setup - add a text input to each footer cell
	$(idTable +' thead tr').clone(true).appendTo( idTable +' thead' );
	$(idTable +' thead tr:eq(1) th').each( function (i) {
		const thLength = $(idTable +' thead tr:eq(1) th').length;
        const title = $(this).text();
		if(i < thLength - 1 && title.toLowerCase() !== "aksi" && title.toLowerCase() !== "action"){
            $(this).html( '<input type="text" placeholder="Cari '+title+'" />' );

            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
		}

		if(i == thLength-1)
		{
			$(this).text("");
		}
	} );
	var table = $(idTable).DataTable({
		ajax: ({
			type: "get",
			url: APP_URL + '/' +urlIndex,
			data: {

			},

		}),
		// orderCellsTop: true,
		fixedHeader: true,
		// stateSave: true,
		responsive: true,
		processing: true,
		serverSide : false,
		ajax :  urlIndex,
		columns: dynamic,
		scrollX: true,
		autoWidth:false,
		select:true,
		dom:
	"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		{
			extend:    'copyHtml5',
			text:      '<i class="fa fa-files-o"></i> Copy',
			titleAttr: 'Copy'
		},
		{
			extend:    'excelHtml5',
			text:      '<i class="fa fa-file-excel-o"></i> Excel',
			titleAttr: 'Excel'
		},
		{
			extend:    'csvHtml5',
			text:      '<i class="fa fa-file-text-o"></i> CSV',
			titleAttr: 'CSV'
		},
		{
			extend:    'pdfHtml5',
			text:      '<i class="fa fa-file-pdf-o"></i> PDF',
			titleAttr: 'PDF'
		}
	],
	// columnDefs: [
	// 	{
	// 		targets: -1,
	// 		visible: false
	// 	}
	// ],
		lengthMenu: [
			[ 10, 25, 50, -1 ],
			[ '10 rows', '25 rows', '50 rows', 'Show all' ]
		],
	});
}
// Global function for ajax method post datatable record
function initDatatablePost(idTable, urlIndex, idSearch = null, token){
	const dynamic = dynamicColumn(urlIndex);
	// Setup - add a text input to each footer cell
	$(idTable +' thead tr').clone(true).appendTo( idTable +' thead' );
    $(idTable +' thead tr:eq(1) th').each( function (i) {
        const thLength = $(idTable +' thead tr:eq(1) th').length;
        const title = $(this).text();
        if(i < thLength - 1 && title.toLowerCase() !== "aksi" && title.toLowerCase() !== "action"){
            $(this).html( '<input type="text" placeholder="Cari '+title+'" />' );

            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        }

        if(i == thLength-1)
        {
            $(this).text("");
        }
    } );
	var table = $(idTable).DataTable({
		processing: true,
		serverSide : false,
		ajax: ({
			type: "post",
			url: APP_URL + '/' +urlIndex,
			data: {
				_token: token,
				idSearch: idSearch,
			},

		}),
		columns: dynamic,
		scrollX: true,
		autoWidth:false,
		lengthMenu: [
			[ 10, 25, 50, -1 ],
			[ '10 rows', '25 rows', '50 rows', 'Show all' ]
		],
	});
}
// Global dynamic column
function dynamicColumn(uri){
	let columns;
	switch(uri){
		case 'levels':
		columns = [
			{data: 'name' , name : 'name' },
			{data: 'description' , name : 'description' },
			{data: 'count' , name : 'count' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'positions':
		columns = [
			{data: 'name' , name : 'name' },
			{data: 'level_id' , name : 'level_id' },
			{data: 'description' , name : 'description' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'regions':
		columns = [
			{data: 'name_area' , name : 'name_area' },
			{data: 'address' , name : 'address' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'locations':
		columns = [
			{data: 'project_id' , name : 'project_id' },
			{data: 'location_name' , name : 'location_name' },
			{data: 'province_id' , name : 'province_id' },
			{data: 'city_id' , name : 'city_id' },
			{data: 'district_id' , name : 'district_id' },
			{data: 'village_id' , name : 'village_id' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'loans':
		columns = [
			{data: 'loan_name' , name : 'loan_name' },
			{data: 'rate_of_interest' , name : 'rate_of_interest' },
			{data: 'provisi' , name : 'provisi' },
            {data: 'tenor' , name : 'tenor' },
            {data: 'plafon' , name : 'plafon' },
			{data: 'attachment' , name : 'attachment' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'members':
		columns = [
			{data: 'nik_koperasi' , name : 'nik_koperasi' },
			{data: 'fullname' , name : 'fullname' },
			{data: 'project' , name : 'project' },
            {data: 'start_date' , name : 'start_date' },
            {data: 'end_date' , name : 'end_date' },
            {data: 'is_active' , name : 'is_active' },
            {data: 'deposit' , name : 'deposit' },
            {data: 'loan' , name : 'loan' },
            {data: 'action' , name : 'action' },

		];break;
        case 'member-detail-deposit':
        columns = [
            {data: 'deposit_number' , name : 'deposit_number' },
            {data: 'date' , name : 'date' },
            {data: 'type' , name : 'type' },
            {data: 'total' , name : 'total' },
            {data: 'status' , name : 'status' },
            {data: 'keterangan' , name : 'keterangan' },
            {data: 'action' , name : 'action' },
        ];break;
        case 'get-detail-deposit':
            columns = [
                {data: 'debit' , name : 'debit' },
                {data: 'credit' , name : 'credit' },
                {data: 'action' , name : 'action' },
            ];break;
        case 'member-detail-loan':
            columns = [
                {data: 'jenis_pinjaman' , name : 'jenis_pinjaman' },
                {data: 'total' , name : 'total' },
                {data: 'start' , name : 'start' },
                {data: 'end' , name : 'end' },
                {data: 'period' , name : 'period' },
                {data: 'in_period' , name : 'in_period' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
            ];break;
        case 'get-detail-loan':
            columns = [
                {data: 'loan_number' , name : 'loan_number' },
                {data: 'value' , name : 'value' },
                {data: 'service' , name : 'service' },
                {data: 'pay_date' , name : 'pay_date' },
                {data: 'in_period' , name : 'in_period' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
            ];break;
		case 'member-loans':
        columns = [
            {data: 'no' , name : 'no' },
            {data: 'loan_number' , name : 'loan_number' },
            {data: 'member' , name : 'member' },
            {data: 'loan_type' , name : 'loan_type' },
            {data: 'value' , name : 'value' },
            {data: 'period' , name : 'period' },
            {data: 'in_period' , name : 'in_period' },
            {data: 'start_date' , name : 'start_date' },
            {data: 'end_date' , name : 'end_date' },
            {data: 'status' , name : 'status' },
            {data: 'action' , name : 'action' },
            // {data: 'action' , name : 'action' },
        ];break;
        case 'get-loans':
        columns = [
            {data: 'loan_number' , name : 'laon_number' },
			{data: 'member' , name : 'member' },
			{data: 'loan_type' , name : 'loan_type' },
			{data: 'value' , name : 'value' },
			{data: 'period' , name : 'period' },
			{data: 'in_period' , name : 'in_period' },
			{data: 'start_date' , name : 'start_date' },
			{data: 'end_date' , name : 'end_date' },
			{data: 'status' , name : 'status' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'get-loans':
		columns = [
			{data: 'loan_number' , name : 'loan_number' },
			{data: 'member' , name : 'member' },
			{data: 'loan_type' , name : 'loan_type' },
			{data: 'value' , name : 'value' },
			{data: 'period' , name : 'period' },
			{data: 'in_period' , name : 'in_period' },
			{data: 'start_date' , name : 'start_date' },
			{data: 'end_date' , name : 'end_date' },
			{data: 'status' , name : 'status' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'ts-deposits':
		columns = [
			{data: 'deposit_number' , name : 'deposit_number' },
			{data: 'member' , name : 'member' },
            {data: 'date' , name : 'date' },
            {data: 'type' , name : 'type' },
			{data: 'total_deposit' , name : 'total_deposit' },
			{data: 'status' , name : 'status' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
        case 'simpanan-pokok':
            columns = [
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'member' , name : 'member' },
                {data: 'date' , name : 'date' },
                {data: 'type' , name : 'type' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'simpanan-wajib':
            columns = [
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'member' , name : 'member' },
                {data: 'date' , name : 'date' },
                {data: 'type' , name : 'type' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'simpanan-sukarela':
            columns = [
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'member' , name : 'member' },
                {data: 'date' , name : 'date' },
                {data: 'type' , name : 'type' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'simpanan-berjangka':
            columns = [
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'member' , name : 'member' },
                {data: 'date' , name : 'date' },
                {data: 'type' , name : 'type' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'simpanan-shu':
            columns = [
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'member' , name : 'member' },
                {data: 'date' , name : 'date' },
                {data: 'type' , name : 'type' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'simpanan-lainnya':
            columns = [
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'member' , name : 'member' },
                {data: 'date' , name : 'date' },
                {data: 'type' , name : 'type' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
		case 'ts-deposits-id':
		columns = [
			{data: 'deposit_number' , name : 'deposit_number' },
			{data: 'member' , name : 'member' },
			{data: 'total_deposit' , name : 'total_deposit' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'plafons':
		columns = [
			{data: 'nik_koperasi' , name : 'nik_koperasi' },
			{data: 'anggota' , name : 'anggota' },
			{data: 'nominal' , name : 'nominal' },
			{data: 'action' , name : 'action' },
			// {data: 'action' , name : 'action' },
		];break;
		case 'member-deposits':
        columns = [
            {data: 'member' , name : 'member' },
            {data: 'type' , name : 'type' },
            {data: 'deposit_number' , name : 'deposit_number' },
            {data: 'total_deposit' , name : 'total_deposit' },
            {data: 'status' , name : 'status' },
            {data: 'action' , name : 'action' },
            // {data: 'action' , name : 'action' },
        ];break;
        case 'wajib':
            columns = [
                {data: 'member' , name : 'member' },
                {data: 'type' , name : 'type' },
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'sukarela':
            columns = [
                {data: 'member' , name : 'member' },
                {data: 'type' , name : 'type' },
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
		case 'sukarela-member-deposit':
            columns = [
                {data: 'member' , name : 'member' },
                {data: 'type' , name : 'type' },
				{data: 'deposit_type' , name : 'deposit_type' },
				{data: 'tanggal' , name : 'tanggal' },
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'pokok':
            columns = [
                {data: 'member' , name : 'member' },
                {data: 'type' , name : 'type' },
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'lainnya':
            columns = [
                {data: 'member' , name : 'member' },
                {data: 'type' , name : 'type' },
                {data: 'deposit_number' , name : 'deposit_number' },
                {data: 'total_deposit' , name : 'total_deposit' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'member-active':
        columns = [
            {data: 'nik_bsp' , name : 'nik_bsp' },
            {data: 'fullname' , name : 'fullname' },
            {data: 'project' , name : 'project' },
            {data: 'join_date' , name : 'join_date' },
            {data: 'end_date' , name : 'end_date' },
            {data: 'action' , name : 'action' },
            // {data: 'action' , name : 'action' },
        ];break;
        case 'member-deposit-list':
        columns = [
            {data: 'deposits_type' , name : 'deposits_type' },
        	{data: 'transaction_id' , name : 'transaction_id' },
            {data: 'total' , name : 'total' },
            {data: 'status' , name : 'status' },
        ];break;
        case 'view-detail':
        columns = [
            {data: 'deposits_type' , name : 'deposits_type' },
        	{data: 'transaction_id' , name : 'transaction_id' },
            {data: 'total' , name : 'total' },
            {data: 'status' , name : 'status' },
            {data: 'action' , name : 'action' },
        ];break;
        case 'resign':
        columns = [
            {data: 'no' , name : 'no' },
        	{data: 'date' , name : 'date' },
            {data: 'reason' , name : 'reason' },
            {data: 'approval' , name : 'approval' },
            {data: 'action' , name : 'action' },
        ];break;
        case 'dtProjects':
        columns = [
            {data: 'project_name' , name : 'project_name' },
            {data: 'project_code' , name : 'project_code' },
            {data: 'region_id' , name : 'region_id' },
            {data: 'address' , name : 'address' },
            {data: 'start_date' , name : 'start_date' },
            {data: 'end_date' , name : 'end_date' },
            {data: 'action' , name : 'action' },
        ];break;
        case 'deposit-report':
            columns = [
                {data: 'name' , name : 'name' },
                {data: 'start' , name : 'start' },
                {data: 'end' , name : 'end' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
            ];break;
        case 'member-report':
            columns = [
                {data: 'name' , name : 'name' },
                {data: 'start' , name : 'start' },
                {data: 'end' , name : 'end' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
            ];break;
        case 'bank':
            columns = [
                {data: 'member' , name : 'member' },
                {data: 'bank_name' , name : 'bank_name' },
                {data: 'bank_account_name' , name : 'bank_account_name' },
                {data: 'bank_account_number' , name : 'bank_account_number' },
                {data: 'action' , name : 'action' },
            ];break;
        case 'rekap-anggota':
            columns = [
                {data: 'name' , name : 'name' },
                {data: 'start' , name : 'start' },
                {data: 'end' , name : 'end' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
            ];break;
        case 'projects':
            columns = [
                {data: 'project_name' , name : 'project_name' },
                {data: 'project_code' , name : 'project_code' },
                {data: 'region' , name : 'region' },
                {data: 'address' , name : 'address' },
                {data: 'start_date' , name : 'start_date' },
                {data: 'end_date' , name : 'end_date' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' },
            ];break;
        case 'permissions':
            columns = [
                {data: 'name' , name : 'name' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'general-setting':
            columns = [
                {data: 'name' , name : 'name' },
                {data: 'content' , name : 'content' },
                {data: 'action' , name : 'action' },
                // {data: 'action' , name : 'action' },
            ];break;
        case 'persetujuan-pinjaman':
            columns = [
                {data: 'loan_number' , name : 'loan_number' },
                {data: 'member' , name : 'member' },
                {data: 'loan_name' , name : 'loan_name' },
                {data: 'value' , name : 'value' },
                {data: 'start_date' , name : 'start_date' },
                {data: 'end_date' , name : 'end_date' },
                {data: 'status' , name : 'status' },
                {data: 'action' , name : 'action' }
            ];break;
    }
return columns;
}

// Global function for ajax delete record
function destroyData(url, id, token, table, action = null) {
	var notice = PNotify.notice({
		title: 'Confirmation Needed',
		text: 'Apakah Kamu Yakin?',
		// icon: 'fas fa-question-circle',
		hide: false,
		stack: {
			'dir1': 'down',
			'modal': true,
			'firstpos1': 25
		},
		modules: {
			Confirm: {
				confirm: true
			},
			Buttons: {
				closer: false,
				sticker: false
			},
			History: {
				history: false
			},
		}
	});
	notice.on('pnotify.confirm', function() {
		$.ajax({
			url : url + '/' + id,
			type: 'DELETE',
			data: {_method: 'delete', _token :token},
			dataType : 'json',
			success:function(data){
				if (data == 'Success') {
					PNotify.success({
						title: 'Success!',
						text: 'Data yang anda pilih telah terhapus.'
					  });

				}else{
					PNotify.error({
						title: 'Oh No!',
						text: 'Data gagal dihapus.'
					  });

				}
                table = $('#'+table).DataTable();
                table.ajax.reload(null,false); //reload datatable ajax
			},
			failed: function(err){
				console.error(err);
			}
		});
	});
	notice.on('pnotify.cancel', function() {
	});
  }

  // Global function for ajax modifyData record
function modifyData(url, id, token, table, action = null) {
	var notice = PNotify.notice({
		title: 'Confirmation Needed',
		text: 'Apakah Kamu Yakin?',
		// icon: 'fas fa-question-circle',
		hide: false,
		stack: {
			'dir1': 'down',
			'modal': true,
			'firstpos1': 25
		},
		modules: {
			Confirm: {
				confirm: true,
                prompt: true,
                promptMultiLine: true,
			},
			Buttons: {
				closer: false,
				sticker: false
			},
			History: {
				history: false
			},
		}
	});
	notice.on('pnotify.confirm', function(e) {
		$.ajax({
			url : url,
			type: 'post',
			data: {
					'id': id,
					_token :token,
					action :action,
                    reason :e.value,
				   },
			dataType : 'json',
			success:function(data){
				if (data == 'Success') {
					PNotify.success({
						title: 'Success!',
						text: 'Data yang anda pilih telah diperbaharui.'
					  });
					table = $('#'+table).DataTable();
					table.ajax.reload(null,false); //reload datatable ajax
				}else{
					PNotify.error({
						title: 'Oh No!',
						text: 'Data gagal diperbaharui.'
					  });
					table = $('#'+table).DataTable();
					table.ajax.reload(null,false); //reload datatable ajax
				}
			},
			failed: function(err){
				console.error(err);
			}
		});
	});
	notice.on('pnotify.cancel', function() {
	});
  }

function modifyDataLoan(url, id, token, table, action = null) {

    $.ajax({
        url : url,
        type: 'post',
        data: {
            'id': id,
            _token :token,
            action :action
        },
        dataType : 'json',
        success:function(data){
            $('#ubahLoanModal').modal('show');
            $('#jumlahPinjaman').val(data.value);
            $('#jumlahBiayaAdmin').val(data.biaya_admin);
            $('#jumlahBiayaTransfer').val(data.biaya_transfer);
            $('#descriptionPinjaman').val(data.notes);
            $('#loanId').val(data.id);

        },
        failed: function(err){
            PNotify.error({
                title: 'Oh No!',
                text: 'Data Tidak Ditemukan.'
            });
        }
    });
}
  // Global function for ajax modifyData record
function patchData(url, id, token, table, action = null) {
	var notice = PNotify.notice({
		title: 'Confirmation Needed',
		text: 'Apakah Kamu Yakin?',
		// icon: 'fas fa-question-circle',
		hide: false,
		stack: {
			'dir1': 'down',
			'modal': true,
			'firstpos1': 25
		},
		modules: {
			Confirm: {
				confirm: true
			},
			Buttons: {
				closer: false,
				sticker: false
			},
			History: {
				history: false
			},
		}
	});
	notice.on('pnotify.confirm', function() {
		$.ajax({
			url : APP_URL + '/' +url+'/'+id,
			type: 'patch',
			data: {
					'id': id,
					_token :token,
					action :action
				   },
			dataType : 'json',
			success:function(data){
				if (data.error == 0) {
					PNotify.success({
						title: 'Success!',
						text: 'Data yang anda pilih telah diperbaharui.'
					  });
					table = $('#'+table).DataTable();
					table.ajax.reload(null,false); //reload datatable ajax
				}else{
					PNotify.error({
						title: 'Oh No!',
						text: 'Data gagal diperbaharui.'
					  });
					table = $('#'+table).DataTable();
					table.ajax.reload(null,false); //reload datatable ajax
				}
			},
			failed: function(err){
				console.error(err);
			}
		});
	});
	notice.on('pnotify.cancel', function() {
	});
  }

  function markAsRead(uid)
  {
      const url = window.location.origin+'/notification/'+uid+'/mark-as-read';
      $.get(url,function(resp) {
          console.log(resp);
      })
      .fail(function(err) {
          console.log(err);
      })
  }

