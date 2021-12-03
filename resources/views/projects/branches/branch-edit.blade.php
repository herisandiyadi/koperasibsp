@extends('adminlte::page')
@section('title', 'Ubah Cabang')

@section('content_header')
    <a href="{{url('branch')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Ubah Cabang</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($branch, ['route' => ['branch.update', $branch->id]]) !!}
                    {{ method_field('PATCH') }}
                    @include('projects.branches.branch-form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
