@extends('adminlte::page')
@section('title', 'Ubah Informasi Akun')

@section('content_header')
    <h1>Ubah Informasi Akun</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($auth, ['route' => ['account.email.update', $auth->id], 'method' => 'post']) !!}
                    {{ method_field('PUT') }}
                    @include('account.email.form')
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
