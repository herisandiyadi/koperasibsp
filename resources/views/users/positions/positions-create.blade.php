@extends('adminlte::page')
@section('title', 'Tambah Posisi Baru')

@section('content_header')
    <a href="{{url('positions')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Tambah Posisi Baru</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    @if($form['title'] == 'create')
                        <form  method="POST" action="{{ URL::to('positions') }}">
                      @else
                        <form  method="POST" action="{{ URL::to('positions/'.$form['id']) }}" id="editForm">
                        <input type="hidden" name="_method" value="PATCH">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <input type="text" id="name" name="name" value="{{ $form['name'] }}" placeholder="Nama Level" autocomplete='name' required="required" class="form-control col-md-7 col-xs-12">
                            <input type="hidden" id="id" name="id" value="{{ $form['id'] }}" autocomplete='id'>
                        </div>
                      </div>
                      <div class="clear-fix1"></div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="level_id">Level <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                           @if($form['title'] == 'create')
                            <select name="level_id" id="level_id" required="required" class="form-control col-md-7 col-xs-12">
                                <option value="">Pilih Level</option>
                                @foreach ($level_id as $item)
                                  <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @else
                            <select name="level_id" id="level_id" required="required" class="form-control col-md-7 col-xs-12">
                                @foreach($level_id as $item)
                                    <option value=""></option>
                                    @if($form['level_id'] == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @endif
                        </div>
                      </div>
                      <div class="clear-fix"></div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="order_level">Order Level <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <input type="number" id="order_level" name="order_level" value="{{ $form['order_level'] }}" placeholder="Order Level" autocomplete='order' required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="clear-fix"></div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="description">Deskripsi <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <textarea name="description" id="description"  autocomplete='Deskripsi' required="required" class="form-control col-md-7 col-xs-12" placeholder="Deskripsi">{{ $form['description'] }}</textarea>
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
        $(document).ready(function(){
          var title      = '{{$form['title']}}';
          if(title       == 'edit'){
            var level_id = '{{$form['id']}}';
            $('#editForm [name=level_id]').val(level_id);
            $('#editForm [name=level_id]').trigger("chosen:updated");
            console.log(level_id)
          }
        })
        initSelect("select")
    </script>
@stop
