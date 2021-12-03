@extends('adminlte::page')
@section('title', 'Setting maksimal peminjaman anggota / plafon')

@section('content_header')
    <a href="{{url('plafons')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    @if($form['title'] == 'create')
        <h1>Batas pinjaman untuk anggota</h1>
    @else
        <h1>Ubah Level</h1>
     @endif

@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    @if($form['title'] == 'create')
                        <form  method="POST" action="{{ URL::to('plafons') }}">
                      @else
                        <form  method="POST" action="{{ URL::to('plafons/'.$form['id']) }}">
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
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-5" for="name">Nama Anggota *<span class="required"></span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-7">
                          @if($form['title'] == 'edit')
                            <input type="text" value="{{ $form['member_id'] }}" placeholder="Nominal" autocomplete='Nominal' required="required" class="form-control col-md-7 col-xs-12" disabled="">
                          @else
                          <select name="member_id" id="member_id" required="required" class="form-control col-md-7 col-xs-1">
                            <option value="">Pilih anggota</option>
                              @foreach($member as $item)
                                <option value="{{ $item->id }}">[{{ $item->username }}] {{ $item->first_name.' '.$item->first_name }}</option>
                              @endforeach
                          </select>
                          @endif
                          <input type="hidden" id="id" name="id" value="{{ $form['id'] }}" autocomplete='id'>
                        </div>
                       </div> 
                      <div class="clear-fix1"></div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-5" for="description">Nominal <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-7">
                            <input type="text" id="nominal" name="nominal" value="{{ $form['nominal'] }}" placeholder="Nominal" autocomplete='Nominal' required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                        </div> 
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-6">
                            <br>
                            @if($form['title'] == 'edit')
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Update</button>
                            @else
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                            @endif
                            <br>
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
$(document).ready(function() {
  initSelect('select');
  $('#nominal').mask('0.000.000.000', {reverse:true});
})

</script>
@stop