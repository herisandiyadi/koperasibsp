@extends('adminlte::page')
@section('title', 'Generate Monthly Simpan Pinjam')

@section('content_header')
    <h1>Generate Monthly Simpan Pinjam</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'generate/monthly-deposit-loan', 'method' => 'post']) !!}
                    @include('report.generate.monthly.form')
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('#search_data').on('click', function () {
                $value = $(this).val();
                var start = $('#start').val();

                if(start == '')
                {
                    PNotify.error({
                        title: 'Error',
                        text: 'Pastikan semua form terisi dengan benar.',
                    });
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: '/generate/monthly-deposit-loan/get-member',
                    data: {'_token' : "{{csrf_token()}}",'search': $value, 'start': start, 'end':end},
                    success: function (data) {
                        $('#edpinfo').html(data);

                    }
                })

            });
        });

    </script>
@stop
