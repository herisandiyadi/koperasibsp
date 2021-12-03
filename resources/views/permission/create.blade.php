@extends('adminlte::page')
@section('title', 'Tambah Permissions Baru')

@section('content_header')
    <h1>Tambah Permissions Baru</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-body">
                @if($title== 'create')
                <form  method="POST" action="{{ URL::to('permissions') }}">
                  @else
                <form  method="POST" action="{{ URL::to('permissions/'.$id) }}">
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
                <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Name <span class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-8">
                    <input type="text" id="name_area" name="name" value="{{ $name }}" placeholder="Nama Permissions" autocomplete='name' required="required" class="form-control col-md-7 col-xs-12">
                    <input type="hidden" id="id" name="id" value="{{ $id }}" autocomplete='id'>
                </div>
                </div>
                <div class="clear-fix1"></div>
                <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <br>
                    @if($title == 'edit')
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
