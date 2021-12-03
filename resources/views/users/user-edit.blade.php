@extends('adminlte::page')
@section('title', 'Ubah User')

@section('content_header')
    <h1>Ubah User</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'post']) !!}
                    {{ method_field('PATCH') }}
                    @include('users.user-form')
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
@stop
