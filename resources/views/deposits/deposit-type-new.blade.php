@extends('adminlte::page')
@section('title', 'Tambah Tipe Simpanan')

@section('content_header')
    <a href="{{url('deposits')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Tambah Tipe Simpanan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'deposits', 'method' => 'post']) !!}
                    @include('deposits.deposit-type-form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('appjs')

@stop