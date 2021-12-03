@extends('adminlte::page')
@section('title', 'Artikel')
@section('content_header')
    <h1>Pesan</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Pesan Dari : {{ $contact->fromId->name }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td>Judul Pesan</td>
                                <td><b>{{ $contact->judul }}</b></td>
                            </tr>
                            <tr>
                                <td>Isi Pesan</td>
                                <td>{{ $contact->pesan }}</td>
                            </tr>
                            </tbody>
                        </table>
                </div>
                <a href="{{ url('contact') }}" class="btn btn-primary" style="margin:20px">Back</a>
            </div>
        </div>
    </div>
@endsection

@section('appjs')
@stop
