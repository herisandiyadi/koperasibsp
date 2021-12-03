@extends('adminlte::page')
@section('title', 'List Pengambilan Simpanan Anggota')

@section('content_header')
	<h1>List Pengambilan Simpanan Anggota</h1>
@stop

@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<div class="col-xs-3">
						<div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
							<input class="date form-control" type="text" id="start">
						</div>
					</div>
					<div class="col-xs-3">
						<div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
							<input class="date form-control" type="text" id="end">
						</div>
					</div>
					<!-- <div class="col-xs-3">
						<button class="btn btn-primary">Search</button>
					</div> -->
				</div>
			<!-- <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        {{--<div class="input-group input-group-sm" style="width: 150px;">--}}
			{{--<a href="{{url('members/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>--}}
			{{--</div>--}}
				</div>
            </div> -->
				<!-- /.box-header -->
				<div class="box-body">
					<table id="list-change-deposit" class="table table-bordered table-hover table-condensed">
						<thead>
						<tr>
							<th width="10%">Nik BSP</th>
							<th>Nama</th>
							<th>Proyek</th>
							<th>Last Wajib</th>
							<th>New Wajib</th>
							<th>Last Sukarela</th>
							<th>New Sukarela</th>
							<th>Status Terakhir</th>
							<th class="text-center" colspan="2">Aksi</th>
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Approval</h4>
				</div>
				<div class="modal-body">
					<table class="table">
						<tr>
							<td>Tanggal <br><small>Pengajuan Perubahan simpanan</small></td>
							<td id="date"></td>
						</tr>
						<tr>
							<td>Status <br><small>Status Pengajuan</small></td>
							<td id="approval"></td>
						</tr>
						<tr>
							<td>Last Wajib <br><small>Simpanan wajib sebelumnya</small></td>
							<td id="last_wajib"></td>
							<td>New Wajib <br><small>Perubahan yang dijukan</small></td>
							<td id="new_wajib"></td>
						</tr>
						<tr>
							<td>Last Sukarela <br><small>Simpanan sukarela sebelumnya</small></td>
							<td id="last_sukarela"></td>
							<td>New Sukarela <br><small>Perubahan yang dijukan</small></td>
							<td id="new_sukarela"></td>
						</tr>
					</table>

				</div>
				<div class="modal-footer">
					<span id="button"></span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
@stop

@section('appjs')
	<script>

		$('#list-change-deposit').DataTable({
			orderCellsTop: true,
			fixedHeader: true,
			stateSave: true,
			responsive: true,
			processing: true,
			serverSide : false,
			scrollX: true,
			autoWidth :false,
			ajax :  '{{url('list-perubahan-simpanan/all')}}',
			columns: [
				{data: 'nik' , name : 'nik' },
				{data: 'name' , name : 'name' },
				{data: 'proyek' , name : 'proyek' },
				{data: 'last_wajib' , name : 'last_wajib' },
				{data: 'new_wajib' , name : 'new_wajib' },
				{data: 'last_sukarela' , name : 'last_sukarela' },
				{data: 'new_sukarela' , name : 'new_sukarela' },
				{data: 'approval' , name : 'approval' },
				{data: 'action' , name : 'action' },
			]
		});

		function showRecord(e) {
			$('#myModal').modal('show');
			$.ajax({
				type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url         : '{{url("list-perubahan-simpanan/get-status")}}', // the url where we want to POST
				data        : {
					'_token': '{{ csrf_token() }}',
					'id': e,
				},
				// using the done promise callback
				success:function(data) {
					if (data.error == 0) {
						if(data.json.approval == false){
							status = 'Not Approve';
						} else if(data.json.approval == 'approve'){
							status = 'Approve';
						}
						$('#approval').html(status);
						$('#date').html(data.json.date);
						$('#last_sukarela').html(data.json.last_sukarela);
						$('#last_wajib').html(data.json.last_wajib);
						$('#new_sukarela').html(data.json.new_sukarela);
						$('#new_wajib').html(data.json.new_wajib);
						$('#button').html('' +
							'<button class="btn btn-primary" onclick="updateRecord(\'' + data.json.id + '\', \'1 \')">Approve</button>' +
							'<button class="btn btn-danger" onclick="updateRecord(\'' + data.json.id + '\', \'0 \')">Reject</button>' +

							'');


					} else {
						// handling anomali rule
						PNotify.error({
							title: 'Gagal.',
							text: data.msg,
							type: 'error'
						});
					}
				},
				// handling error code
				error: function (data) {
					loading('hide', 1000);
					PNotify.error({
						title: 'Terjadi anomali.',
						text: 'Mohon hubungi pengembang aplikasi untuk mengatasi masalah ini.',
						type: 'error'
					});
				}
			});
		}
		function updateRecord(id,status) {
			var note = $('textarea#note').val();
			var notice = PNotify.notice({
				title: 'Confirmation Needed',
				text: 'Apakah kamu yakin untuk approve perubahan simpanan yang anda pilih ?',
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
				// process the form
				$.ajax({
					type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
					url         : '{{url('list-perubahan-simpanan/change-deposit-approval')}}', // the url where we want to POST\
					data        : {
						'_token': '{{ csrf_token() }}',
						'id': id,
						'status': status,
						'note': note
					},

					dataType    : 'json', // what type of data do we expect back from the server
					encode          : true,
					// using the done promise callback
					success:function(data) {

						if(data.error == 1){
							PNotify.error({
								title: 'Oh No!',
								text: data.msg
							});
							$("textarea#note").val('');

						} else {
							$('#myModal').modal('hide');
							PNotify.success({
								title: 'Success!',
								text: data.msg,
							});
							$("textarea#note").val('');
							table = $('#list-retrieve-deposit').DataTable();
							table.ajax.reload(null,false); //reload datatable ajax
						}
					},
					// handling error code
					error: function (data) {
						PNotify.error({
							title: 'Terjadi anomali.',
							text: 'Mohon hubungi pengembang aplikasi untuk mengatasi masalah ini.',
							type: 'error'
						});
					}
				});
				// stop the form from submitting the normal way and refreshing the page
			});
		}

	</script>
	{{--	<script>--}}
	{{--		var start = $('#start').val();--}}
	{{--		var end = $('#end').val();--}}
	{{--		$( "#start" ).change(function() {--}}
	{{--			// alert( "Handler for .change() called." );--}}
	{{--			initDatatable('#list-retrieve-deposit', 'list-retrieve-deposit', '', start, end);--}}

	{{--		});--}}

	{{--		initDatatable('#list-retrieve-deposit', 'list-retrieve-deposit', '', start, end);--}}
	{{--	</script>--}}
@stop
