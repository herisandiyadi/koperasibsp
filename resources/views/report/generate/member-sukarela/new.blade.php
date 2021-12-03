@extends('adminlte::page')
@section('title', 'Generate Report Member Deposit')

@section('content_header')
    <h1>Generate Report Pengambilan Sukarela</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'generate/member-sukarela', 'method' => 'post']) !!}
                    @include('report.generate.member-sukarela.form')
                    {!! Form::close() !!}

                    <br/>
                    <br/>
                    <br/>
                    <h4>Preview Data</h4>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Tahun</th>
                            <th scope="col">Masuk</th>
                            <th scope="col">Keluar</th>
                            <th scope="col">Saldo</th>

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
        $('.cari').select2({
            placeholder: 'Cari...',
            ajax: {
                url: '/generate/member-sukarela/get-member',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.first_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $(document).ready(function () {
            $('#search_data').on('click', function () {
                $value = $(this).val();
                var member_id = $('#member_id').val();
                var start = $('#start').val();
                var end = $('#end').val();

                if(member_id == '' || start == '' || end == '')
                {
                    PNotify.error({
                        title: 'Error',
                        text: 'Pastikan semua form terisi dengan benar.',
                    });
                    return;
                }

                $.ajax({
                    type: 'post',
                    url: '/generate/member-sukarela/get-member/deposit',
                    data: {'_token' : "{{csrf_token()}}",'search': $value, 'member_id': member_id, 'start': start, 'end':end},
                    success: function (data) {
                        $('#edpinfo').html(data);

                    }
                })

            });
        });

    </script>
@stop
