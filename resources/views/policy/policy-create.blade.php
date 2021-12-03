@extends('adminlte::page')
@section('title', 'Tambah Privacy Policy Baru')

@section('content_header')
    <a href="{{url('projects')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Tambah Privacy Policy Baru</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'policy', 'method' => 'post']) !!}
                    @include('policy.policy-form')
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
@section('ckeditor')
<script>
    CKEDITOR.replace( 'description' );
</script>
@stop
