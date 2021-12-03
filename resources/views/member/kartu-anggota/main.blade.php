@extends('adminlte::page')
@section('title', 'Kartu Anggota')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<style>
    /*===Basic reset===*/

    * {
        margin: 0;
        padding: 0;
        outline: none;
        box-sizing: border-box;
    }

    a>img {
        border: none;
    }

    header,
    footer,
    article,
    section,
    nav,
    aside {
        display: block;
    }


    /*===General===*/

    body {
        font-family: Arial;
        font-size: 14px;
    }

    .clearfix {
        clear: both;
    }

    h2{
        font-size:18px;
    }

    /*===Credit Card===*/

    .credit-card {
        display: block;
        position: relative;
        width: 93.75%;
        /* This is 300px on 320px wide screen */
        max-width: 500px;
        /* Just to make sure that it doesnt get crazy on bg screens */
        min-width: 300px;
        /* And make sure that it contains at least some size */
        margin: 30px auto;
        padding: 20px;
        overflow: hidden;
        border-radius: 6px;
        z-index: 1;
    }

    .credit-card .inputs {
        list-style: none;
        margin-top: 20px;
    }

    .credit-card .inputs li {
        margin-bottom: 30px;
    }

    .credit-card .inputs li.last {
        margin-bottom: 10px;
    }

    .credit-card .inputs li.expire {
        float: left;
        width: 70%;
        margin-right: 5%;
    }

    .credit-card .inputs li.expire input {
        float: left;
        width: 35%;
    }

    .credit-card .inputs li.expire input.month {
        width: 60%;
        margin-right: 5%;
    }

    .credit-card .inputs li.cvc-code {
        float: left;
        width: 25%;
    }

    .credit-card .inputs li.cvc-code input {
        width: 100%;
    }

    .credit-card .watermark {
        position: absolute;
        z-index: 10;
    }

    .credit-card form {
        position: relative;
        z-index: 50;
    }

    .credit-card .logo {
        position: absolute;
        top: 15px;
        right: 50px;
        text-transform: uppercase;
        font-weight: bold;
    }


    /*===Visa===*/

    .visa {
        color: #fff;
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.8), inset 0px 1px 3px rgba(255, 255, 255, 0.3), inset 0px 0px 2px rgba(255, 255, 255, 0.2);
    }

    .visa h2 {
        text-shadow: 1px 1px 2px rgba(106, 140, 94, 0.6);
    }

    .visa .logo {
        color: rgba(255, 255, 255, 0.9);
        font-size: 2em;
        font-style: italic;
        text-shadow: 0px 0px 3px rgba(106, 140, 94, 0.6);
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        font-size: 1.1em;
        font-weight: bold;
        text-shadow: 0px 1px 2px rgba(106, 140, 94, 0.6);
    }


    /*===Gradients===*/

    .gr-visa {
        background: #1db1cf;
        /* Old browsers */
        background: -moz-linear-gradient(top, #a2b69b 0%, #7d9b72 100%);
        /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #1db1cf), color-stop(100%, #7d9b72));
        /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #a2b69b 0%, #7d9b72 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #a2b69b 0%, #7d9b72 100%);
        /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #a2b69b 0%, #1078ab 100%);
        /* IE10+ */
        background: linear-gradient(to bottom, #a2b69b 0%, #7d9b72 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1db1cf', endColorstr='#1078ab', GradientType=0);
        /* IE6-9 */
    }


    .footer {
        font-size: 10px;
        text-align: center;
    }

    .caption div {
        box-shadow: 0 0 5px #C8C8C8;
        transition: all 0.3s ease 0s;
    }

    .img-circle {
        border-radius: 50%;
    }

    .img-circle {
        border-radius: 0;
    }

    .ratio {
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 0;
        /*padding-bottom: 100%;*/
        position: relative;
        width: 100%;
    }

    .img-circle {
        border-radius: 50%;
    }

    .img-responsive {
        display: block;
        height: auto;
        max-width: 100%;
    }

    .imgprofile {
        text-align: center;
        width: 100%;
        margin-right: 30px;
        margin-top: -5px;
    }

</style>
@section('content')
    <div id="kartuAnggota">
    <section class='credit-card visa gr-visa' style="background: #91b59a">
        <div class='logo'>
            <img src="{{{asset('images/bsp.png')}}}" width="60"/>
        </div>
        <h2 style="margin-top: -1px">Koperasi BSP</h2>
        <label>Kartu Anggota</label>
        <ul class='inputs'>
            <li class='expire last'> {{$member->full_name}}<br/> <u>{{$member->nik_koperasi}}</u><br/> {{ucwords($member->position->name)}}<br/> <br/> <label>Bergabung</label> {{$member->join_date->format('d-M-Y')}} </li>
            <li class='cvc-code last'>
                <div class='imgprofile'>
                    @if($member->picture == null || file_exists('images/'.$member->picture) == false)
                        <img class='ratio img-responsive img-circle' src="{{ asset('images/security-guard.png') }}">
                    @else
                        <img class='ratio img-responsive img-circle' src="{{ asset('images/'.$member->picture) }}">
                    @endif
                </div>
            </li>
            <div class='clearfix'></div>
        </ul>
        <hr/>
        <div class='footer'> Graha BSP Jl. Dewi Sartika No. 4A/4C Cililitan Jakarta Timur 13640<br/> [T]+62-21 : 8011388, 8013934, [F]+62-21 : 8011387 </div>
    </section>
    </div>
    <center>
{{--        <a href="{{ url('member/kartu-anggota/download') }}">--}}
{{--            <div class="btn btn-primary" id="btn-Preview-Image">Preview</div>--}}
        <a class="btn btn-primary" id="btn-Convert-Html2Image" href="#">Download</a>
{{--        </a>--}}
    </center>
    <div id="previewImage" style="display: none;">
    </div>

@endsection
@section('appjs')
    <script>
        var element = $("#kartuAnggota"); // global variable
        var getCanvas; // global variable

        // $("#btn-Preview-Image").on('click', function () {
        //     html2canvas(element, {
        //         onrendered: function (canvas) {
        //             $("#previewImage").append(canvas);
        //             getCanvas = canvas;
        //         }
        //     });
        // });

        $("#btn-Convert-Html2Image").on('click', function () {
            html2canvas(element, {
                onrendered: function (canvas) {
                    $("#previewImage").append(canvas);
                    getCanvas = canvas;
                }
            });
            var imgageData = getCanvas.toDataURL("image/png");
            // Now browser starts downloading it instead of just showing it
            var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
            $("#btn-Convert-Html2Image").attr("download", "kartu-anggota-koperasi-bsp.png").attr("href", newData);
        });
    </script>
@endsection
