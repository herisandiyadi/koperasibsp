@extends('adminlte::page')
@section('title', 'List Pengambilan Simpanan Anggota')

@section('content_header')
	<h1>Import</h1>
	<style>
		.scrolling-wrapper {
			overflow-x: scroll;
			overflow-y: hidden;
			white-space: nowrap;

		.card {
			display: inline-block;
		}
	</style>
@stop

@section('content')
	<div class="container-fluid">
	<div class="row scrolling-wrapper">
		<div class="col-xs-12">
			<div class="box scrolling-wrapper">
				<div class="box-header">
					<form class="form-horizontal" method="POST" action="{{ route('import_process') }}">
						{{ csrf_field() }}

						<table class="table">
							@foreach ($csv_data as $row)
								<tr>
									@foreach ($row as $key => $value)
										<td>{{ $value }}</td>
									@endforeach
								</tr>
							@endforeach
							<tr>
								@foreach ($csv_data[0] as $key => $value)
									<td>
										<select name="fields[{{ $key }}]">
											@foreach (config('app.db_fields') as $db_field)
												<option value="{{ $loop->index }}">{{ $db_field }}</option>
											@endforeach
										</select>
									</td>
								@endforeach
							</tr>
						</table>

						<button type="submit" class="btn btn-primary">
							Import Data
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection
