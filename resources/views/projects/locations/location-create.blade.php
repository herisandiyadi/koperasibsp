@extends('adminlte::page')
@section('title', 'Tambah Lokasi Baru')

@section('content_header')
    <a href="{{url('locations')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Tambah Lokasi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-body">
                @if($form['title']== 'create')
                <form  method="POST" action="{{ URL::to('locations') }}">
                  @else
                <form  method="POST" action="{{ URL::to('locations/'.$form['id']) }}">
                <input type="hidden" name="_method" value="PUT">
                @endif
                @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Project <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    @if($form['title']== 'create')
                    <select name="project_id" id="project_id" required="required" class="form-control col-md-7 col-xs-12">
                        <option value="">Pilih Project</option>
                        @foreach ($project_id as $item)
                          <option value="{{ $item->project_id }}">{{ $item->project_name }}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="text" id="project_id" name="project_id" value="{{ $project_id->project_name }}" placeholder="project_id" autocomplete='name' required="required" class="form-control col-md-7 col-xs-12" disabled>
                    <input type="hidden" id="project_id" name="project_id" value="{{ $project_id->project_id }}" placeholder="project_id" autocomplete='name' required="required" class="form-control col-md-7 col-xs-12">
                    @endif
                </div>
                </div>
                <div class="clear-fix1"></div> 
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="nama_lokasi">Nama Lokasi <span class="required"></span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    <input type="text" id="nama_lokasi" name="nama_lokasi" value="{{ $form['nama_lokasi'] }}" placeholder="Nama Lokasi" autocomplete='name' required="required" class="form-control col-md-7 col-xs-12">
                </div>
                </div> 
                <div class="clear-fix"></div>
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Provinsi</label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    <select name="province_id" id="province_id" required="required" class="form-control col-md-7 col-xs-12" onchange="loadData('#province_id','#city_id', 'cities')">
                        <option value="">Pilih Provinsi</option>
                        @foreach ($Province as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="clear-fix"></div>
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Kota</label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    <select name="city_id" id="city_id" required="required" class="form-control col-md-7 col-xs-12" onchange="loadData('#city_id','#district_id', 'districts')">
                    </select>
                </div>
                </div> 
                <div class="clear-fix"></div>
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Kecamatan</label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    <select name="district_id" id="district_id" required="required" class="form-control col-md-7 col-xs-12" onchange="loadData('#district_id','#village_id', 'villages')">
                    </select>
                </div>
                </div>  
                <div class="clear-fix"></div>
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Desa</label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    <select name="village_id" id="village_id" required="required" class="form-control col-md-7 col-xs-12">
                    </select>
                </div>
                </div> 
                <div class="clear-fix"></div>
                <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="alamat">Alamat <span class="required"></span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    <input type="text" id="alamat" name="alamat" value="{{ $form['alamat'] }}" placeholder="Alamat" autocomplete='name' required="required" class="form-control col-md-7 col-xs-12">
                </div>
                </div> 
                <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <br>
                    @if($form['title'] == 'edit')
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
@section('appjs')
<script>
    initSelect("select");
    function loadData(id, idLoad, table){
       var trigger = $(id).val();
       var form_data = new FormData(); 
       var _token = "{{ csrf_token() }}";
       form_data.append('_token', _token);
       form_data.append('find', id);
       form_data.append('trigger', trigger);
       form_data.append('idLoad', idLoad);
       form_data.append('table', table);
       $.ajax({
            url: '{{asset('loadData')}}',
            type: "post",
            data: form_data,
            processData: false,
            contentType: false,
            
            success: function (data) {
                $(idLoad).empty();
                for(var i = 0; i < data.length;i++){
                    $(idLoad).append('<option value=' + data[i].id + '>' + data[i].name + '</option>');
                }
                },
                error: function (data) {
                    PNotify.error({
                    title: 'Oh No!',
                    text: 'Koneksi ke database gagal.'
                    });
                }
            })       
    }
</script>
@stop
