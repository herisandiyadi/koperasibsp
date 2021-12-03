@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
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

        <!-- /.login-logo -->
        <div class="login-box-body" style="padding-bottom:30px;">
			<div class="text-center">
			<img src="{{{asset('images/bsp.png')}}}" width="120">
			</div>
			<div class="login-logo">
				<a href="{{ url(config('adminlte.login_url', '')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
			</div>
            <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
            @if (session('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
            @endif
            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                {!! csrf_field() !!}

                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}"
                   class="text-center"
                >{{ trans('adminlte::adminlte.i_forgot_my_password') }}</a>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit"class="btn btn-primary btn-block">{{ trans('adminlte::adminlte.sign_in') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
		</div>

        <div class="col-xs-12 text-center" id="footAcc">
            <a href="{{ asset('register/create') }}" >Belum punya akun ? Daftar disini !</a>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('js')
@stop
