@extends('adminlte::page')
@section('title', 'Tambah Cabang Baru')

@section('content_header')
    <a href="{{url('branch')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Tambah Cabang Baru</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'branch', 'method' => 'post']) !!}
                    @include('projects.branches.branch-form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop