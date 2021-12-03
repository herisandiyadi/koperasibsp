@extends('adminlte::page')
@section('title', 'Ubah Tipe Simpanan')

@section('content_header')
    <a href="{{url('deposits')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Ubah Tipe Simpanan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($deposit, ['route' => ['deposits.update', $deposit->id], 'method' => 'post']) !!}
                    {{ method_field('PUT') }}
                    @include('deposits.deposit-type-form')
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
@stop

@section('appjs')
    <script>
        $(".kui-currency").kendoNumericTextBox({
            format: "c0" //Define currency type and 2 digits precision
        });
    </script>
@stop