@extends('adminlte::page')
@section('title', 'Import Data Anggota')

@section('content_header')
	<h1>Import Data Anggota</h1>
@stop

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">CSV Import</div>

					<div class="panel-body">
						<form class="form-horizontal" method="POST" action="{{ route('import_parse') }}" enctype="multipart/form-data">
							{{ csrf_field() }}

							<div class="form-group">
								<label for="csv_file" class="col-md-4 control-label">CSV file to import</label>

								<div class="col-md-6">
									<input id="csv_file" type="file" class="form-control" name="csv_file" required>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="header" checked> File contains header row?
										</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-8 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										Parse CSV
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('appjs')
    <script>
    $(document).ready(function() {
        $("#submit").click(function() {
        // disable button
        $(this).prop("disabled", true);
        // add spinner to button
        $(this).html(
            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
        });
    });
    </script>
@endsection
