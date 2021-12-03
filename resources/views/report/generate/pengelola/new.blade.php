@extends('adminlte::page')
@section('title', 'Generate Report Member Resign')

@section('content_header')
    <h1>Generate Report Pengelola HO</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'generate/pengelola', 'method' => 'post']) !!}
                    @include('report.generate.pengelola.form')
                    {!! Form::close() !!}
{{--                    <br/>--}}
{{--                    <br/>--}}
{{--                    <br/>--}}
                    <h4>Preview Data</h4>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Nama Area</th>
                            <th>Jumlah Pengelola Karyawan</th>
                            <th>Jumlah Anggota Koperasi</th>
                            <th>Belum Anggota</th>
                        </thead>
                        <tbody id="edpinfo">
                        @foreach($admins as $admin)
                        <tr>

                            <td>{{$admin['region_name']}}</td>
                            <td>{{$admin['jumlah_pengelola'] }}</td>
                            <td>{{$admin['jumlah_anggota']}}</td>
                            <td>{{$admin['belum_anggota']}}</td>
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
        $(".datepicker").kendoDatePicker({
            format: "yyyy-MM-dd",
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#search_data').on('click', function () {
                $value = $(this).val();
                var start = $('#start').val();
                var end = $('#end').val();

                if(start == '' || end == '')
                {
                    PNotify.error({
                        title: 'Error',
                        text: 'Pastikan semua form terisi dengan benar.',
                    });
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: '/generate/pengelola/get-pengelola',
                    data: {'_token' : "{{csrf_token()}}",'search': $value, 'start': start, 'end':end},
                    success: function (data) {
                        $('#edpinfo').html(data);

                    }
                })

            });
        });

    </script>
@stop
