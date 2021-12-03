@extends('adminlte::page')
@section('title', 'Ajukan Pinjaman')

@section('content_header')
    <a href="{{url('member-loans')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Ajukan Pinjaman</h1>
@stop

@section('content')
<style>
.form-group{
	margin-top:10px;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                @if($data['title'] == 'create')
                <form  method="POST" action="{{ URL::to('member-loans') }}">
                  @else
                <form  method="POST" action="{{ URL::to('member-loans/'.$id) }}">
                <input type="hidden" name="_method" value="PUT">
                @endif
                <!-- message -->
                <div class="text-center">
                <!-- flash message -->
                </div>
                <!-- echo validation error -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
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
				<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

				@if($data['title'] == 'create')
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Jenis Pinjaman</label>
					<div class="col-md-9 col-sm-9 col-xs-8">
						<select name="project_id" id="project_id" class="form-control" required>
							<option value="">Pilih Pinjaman</option>
							@foreach ($datapinjaman as $item)
								<option value="{{ $item->id }}">{{ $item->loan_name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div style="padding:10px; margin:10px 0px;">
				<br/>
					<label><br/>Pinjaman Tunai</label>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Mengajukan Pinjaman Sebesar</label>
						<div class="col-md-9 col-sm-9 col-xs-8">
						<input type="number" name="jumlah_pinjaman" placeholder="Nominal Pinjaman" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Alasan Pinjaman</label>
						<div class="col-md-9 col-sm-9 col-xs-8">
						<input type="text" name="alasan_pinjaman" placeholder="Alasan Pinjaman" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
				</div>
				<div style="padding:10px; margin:10px 0px;">
				<br/>
					<label><br/>Pinjaman Barang</label>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Jenis Barang</label>
						<div class="col-md-9 col-sm-9 col-xs-8">
						<input type="text" name="jenis_barang" placeholder="Jenis Barang" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Merk</label>
						<div class="col-md-9 col-sm-9 col-xs-8">
						<input type="text" name="merk" placeholder="Merk" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Type</label>
						<div class="col-md-9 col-sm-9 col-xs-8">
						<input type="text" name="type" placeholder="Type" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
				</div>

				<div style="padding:10px; margin:10px 0px;">

					<label><br/>Sanggup mengembalikan dalam jangka waktu bulan, dengan angsuran pokok + bunga</label>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Jangka Waktu</label>
						<div class="col-md-9 col-sm-9 col-xs-8">
						<input type="number" name="jangka_waktu" placeholder="Jangka Waktu" required="required" class="form-control col-md-7 col-xs-12">
						</div>
					</div>
				</div>
				@else

				@endif
                <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <br>
                    @if($data['title'] == 'edit')
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button type="submit" class="btn btn-success">Update</button>
                    @else
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                    @endif
                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
