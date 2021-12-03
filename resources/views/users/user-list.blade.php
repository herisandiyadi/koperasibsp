@extends('adminlte::page')
@section('title', 'Daftar User')

@section('content_header')
    <h1>Daftar User</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            @can('create.auth.user')
                            <a href="{{url('users/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>
                            @endcan
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="dtUsers" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Posisi</th>
                                <th>Area</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->position->name}}</td>
                                <td>{{empty($user->region) ? '' : $user->region->name_area}}</td>
                                <td>

                                        <a class="btn btn-primary" href="{{url('users/'.$user->id.'/edit')}}"><i class="fa fa-edit"></i></a>


                                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'post','class'=>'form-inline']) !!}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                        {!! Form::close() !!}

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('appjs')
    <script>
        $('#dtUsers').DataTable()
    </script>
@stop
