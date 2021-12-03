@extends('adminlte::page')
@section('title', 'Ubah Generate Report Deposit')

@section('content_header')
    <h1>Ubah Generate Report Deposit</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($genDeposit, ['route' => ['generate.deposit.update', $genDeposit->id], 'method' => 'post']) !!}
                    {{ method_field('PUT') }}
                    @include('report.generate.deposit.form')
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
