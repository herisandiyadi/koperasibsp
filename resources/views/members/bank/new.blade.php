@extends('adminlte::page')
@section('title', 'Tambah Generate Report Member')

@section('content_header')
    <h1>Tambah Generate Report Member</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'member/bank', 'method' => 'post']) !!}
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
