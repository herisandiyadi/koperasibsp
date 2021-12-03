@extends('adminlte::page')
@section('title', 'Tambah Project Baru')

@section('content_header')
    <a href="{{url('projects')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Tambah Proyek Baru</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'projects', 'method' => 'post']) !!}
                    @include('projects.project-form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('appjs')
    <script>
        $(".datepicker").kendoDatePicker({
            format: "yyyy-MM-dd",
        });
    </script>
@stop