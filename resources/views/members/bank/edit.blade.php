@extends('adminlte::page')
@section('title', 'Ubah Member Bank')

@section('content_header')
    <h1>Ubah Member Bank</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($data, ['route' => ['member.bank.update', $data->id], 'method' => 'post']) !!}
                    {{ method_field('PUT') }}
                    @include('members.bank.form')
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
