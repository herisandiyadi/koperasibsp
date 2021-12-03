@extends('adminlte::page')
@section('title', 'Generate Report Member Resign')

@section('content_header')
    <h1>Generate Report Member Resign</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'generate/member-resign', 'method' => 'post']) !!}
                    @include('report.generate.member-resign.form')
                    {!! Form::close() !!}

                    <br/>
                    <br/>
                    <br/>
                    <h4>Preview Data</h4>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Proyek</th>
                            <th>Area</th>
                            <th>Pokok</th>
                            <th>Wajib</th>
                            <th>Sukarela</th>
                            <th>Shu Ditahan</th>
                            <th>Lainnya</th>
                            <th>Total</th>

                        </tr>
                        </thead>
                        <tbody id="edpinfo">
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
                    url: '/generate/member-resign/get-member',
                    data: {'_token' : "{{csrf_token()}}",'search': $value, 'start': start, 'end':end},
                    success: function (data) {
                        $('#edpinfo').html(data);

                    }
                })

            });
        });

    </script>
@stop
