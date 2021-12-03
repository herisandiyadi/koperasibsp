@extends('adminlte::page')
@section('title', 'Ubah Generate Report Member')

@section('content_header')
    <h1>Ubah Generate Report Member</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($data, ['route' => ['generate.member.update', $data->id], 'method' => 'post']) !!}
                    {{ method_field('PUT') }}
                    @include('report.generate.member.form')
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
