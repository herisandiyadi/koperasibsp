@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
<style>
    #mydiv {
        position:fixed;
        top: 50%;
        left: 50%;
        height:18em;
        margin-top: -18em; /*set to a negative number 1/2 of your height*/
        margin-left: -13em; /*set to a negative number 1/2 of your width*/
    }
    body{
        background: #f1f1f1 !important;
    }
</style>
    <div class="login-box" id="mydiv">

        <div class="login-box-body" style="padding-bottom:30px;">
            <div class="text-center">
                <img src="{{{asset('images/bsp.png')}}}" width="120">
            </div>
            <div class="login-logo">
                <a href="{{ url(config('adminlte.login_url', '')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
            </div>
            <!-- /.login-logo -->
        <p class="login-box-msg">{{ trans('adminlte::adminlte.password_reset_message') }}</p>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ url(config('adminlte.password_email_url', 'password/email')) }}" method="post">
                {!! csrf_field() !!}

                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ isset($email) ? $email : old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <button type="submit"
                        class="btn btn-primary btn-block btn-flat"
                >{{ trans('adminlte::adminlte.send_password_reset_link') }}</button>
            </form>
        </div>
        <div class="col-xs-12 text-center" id="footAcc">
            <a href="{{ asset('login') }}" > Kembali ke halaman login ? klik disini !</a>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
    @yield('js')
@stop
