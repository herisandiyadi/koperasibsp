@extends('adminlte::page')
@section('title', 'Generate Report Member Area dan Proyek')

@section('content_header')
    <h1>Generate Report Member Area dan Proyek</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'generate/member-report-area-proyek', 'method' => 'post']) !!}
                    @include('report.generate.member-area.form')
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
