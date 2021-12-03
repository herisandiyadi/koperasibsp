@extends('adminlte::page')
@section('title', 'Form Pengajuan perubahan simpanan')

@section('content')
	<!-- grafik member -->
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Perubahan Simpanan</h3>

			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="col-md-12">
				<form action="{{ url('change-member-deposits/form') }}" method="POST">
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
					<input type="hidden" name="sukarela" value="{{ $data['sukarela']  }}">

					<!-- echo validation error -->
					@if (session('message'))
						<div class="alert alert-success">
							{{ session('message') }}
						</div>
					@endif
					@if (session('error'))
						<div class="alert alert-danger">
							{{ session('error') }}
						</div>
				@endif
				<!--/ echo validation error -->

{{--					<div class="col-md-5 no-padding">--}}
{{--						<div class="form-group">--}}
{{--							<label class="control-label">Tanggal diajukan</label><p></p>--}}
{{--							<input type="text" class="form-control" name="date" id="datepicker" value="{{\Carbon\Carbon::parse(now())->format('Y-m-d')}}" />--}}
{{--						</div>--}}
{{--					</div>--}}

					<div class="col-md-12 no-padding">
						<div class="form-group">
							<label for="">Info Simpanan</label><p></p>
							<table class="table">
								<thead>
								<th>Pokok</th>
								<th>Wajib</th>
								<th>Sukarela</th>
								</thead>
								<tbody>
									<tr>
										<td>{{ number_format($data['pokok']) }}</td>
										<td>{{ number_format($data['wajib']) }}</td>
										<td>{{ number_format($data['sukarela']) }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-3 no-padding" style="margin-right: 10px;">
						<label for="">Simpanan Wajib</label><p></p>
						<input type="hidden" class="form-control" value="{{ $data['wajib'] }}" name="last_wajib" required/>
						<input type="number" class="form-control" value="{{ $data['wajib'] }}" name="wajib" required/>

					</div>
					<div class="col-md-3 no-padding">
						<label for="">Simpanan Sukarela</label><p></p>
						<input type="hidden" class="form-control" name="last_sukarela" value="{{ $data['sukarela'] }}" required/>
						<input type="number" class="form-control" name="sukarela" value="{{ $data['sukarela'] }}" required/>

					</div>
					<div class="col-md-6 no-padding" style="margin-top:30px">
						<div class="form-group">
							<div class="alert alert-warning" role="alert">
								Simpanan yang tidak diubah tetap diisi dengan nominal simpanan sebelumnya.
							</div>
						</div>
					</div>
					<div class="col-md-12 no-padding" style="margin-top:30px">
						<div class="form-group">
							<button class="btn btn-default" type="reset">Batal</button>
							<button class="btn btn-primary" type="submit">Ajukan Perubahan</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /.box-body -->
@endsection
@section('appjs')
	<script>
		// create DatePicker from input HTML element
		$("#datepicker").kendoDatePicker({
			// display month and year in the input
			format: "yyyy-MM-dd",
			min: new Date(),

			// specifies that DateInput is used for masking the input element
			dateInput: true
		});
	</script>
@endsection
