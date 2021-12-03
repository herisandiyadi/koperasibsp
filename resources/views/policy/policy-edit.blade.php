@extends('adminlte::page')
@section('title', 'Ubah Privacy Policy')

@section('content_header')
    <!-- <a href="{{url('policy')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a> -->
    <h1>Ubah Privacy Policy</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($policy, ['route' => ['policy.update', $policy->id], 'method' => 'post']) !!}
                    {{ method_field('PATCH') }}
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
            format: "dd-MM-yyyy",
        });
    </script>
@stop
@section('ckeditor')
<script>
    CKEDITOR.replace( 'description' );
</script>
@stop
