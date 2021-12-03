@extends('adminlte::page')
@section('title', 'Ubah Proyek')

@section('content_header')
    <a href="{{url('projects')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Ubah Proyek</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'post']) !!}
                    {{ method_field('PATCH') }}
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
            format: "dd-MM-yyyy",
        });
    </script>
@stop