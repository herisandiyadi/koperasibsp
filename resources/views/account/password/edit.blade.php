@extends('adminlte::page')
@section('title', 'Ubah Password')

@section('content_header')
    <h1>Ubah Password</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($auth, ['route' => ['account.password.update', $auth->id], 'method' => 'post']) !!}
                    {{ method_field('PUT') }}
                    @include('account.password.form')
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
