@extends('adminlte::page')
@section('title', 'Ubah Generate Rekap Anggota')

@section('content_header')
    <h1>Ubah Generate Rekap Anggota</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($data, ['route' => ['generate.rekap.anggota', $data->id], 'method' => 'post']) !!}
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
