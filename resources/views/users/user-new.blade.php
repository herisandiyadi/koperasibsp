@extends('adminlte::page')
@section('title', 'Tambah User Baru')

@section('content_header')
    <h1>Tambah User Baru</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'users', 'method' => 'post']) !!}
                    @include('users.user-form')
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
@stop
