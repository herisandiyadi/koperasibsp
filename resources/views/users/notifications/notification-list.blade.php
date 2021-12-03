@extends('adminlte::page')
@section('title', 'Semua Pemberitahuan')

@section('content_header')
    <h1>Semua Pemberitahuan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            {{--<a href="{{url('users/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>--}}
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="dtNotifications" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Tentang</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            {{--@foreach(auth()->user()->notifications as $notification)--}}
                            {{--<tr>--}}

                                {{--<td>{{$notification->data['content']['title']}}</td>--}}
                                {{--<td>{{$notification->data['content']['description']}}</td>--}}
                                {{--<td>--}}
                                    {{--<a class="btn" href="{{url('notification/'.$notification->id.'/resolve')}}"><i class="fa fa-link"></i></a>--}}
                                    {{--@if ($notification->read_at === null)--}}
                                       {{--<button class="btn"><i class="fa fa-envelope-o"></i></button>--}}
                                    {{--@else--}}
                                        {{--<button class="btn"><i class="fa fa-envelope-open-o"></i></button>--}}
                                    {{--@endif--}}
                               {{--</td>--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('appjs')
    <script>
        $('#dtNotifications').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{url('notification/get-all')}}',
            columns: [
                {data: 'title', name: 'title'},
                {data: 'description', name: 'description'},
                {data: 'date', name: 'date'},
                {data: 'action', name: 'action'}
            ]
        });
    </script>
@stop