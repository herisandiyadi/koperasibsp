@extends('adminlte::page')
@section('title', 'Tambah Artikel Baru')

@section('content_header')
    <style>
        #tags-list, span.k-icon.k-clear-value.k-i-close, span.k-icon.k-i-loading.k-hidden {
            display: none !important;
        }
    </style>
    <a href="{{url('article')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Tambah Artikel Baru</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(['url' => 'article', 'method' => 'post', 'files' => true]) !!}
                    @include('article.article-form')
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
    <script>
        $(document).ready(function() {
            var currentId = 1;

            function onDataBound(e) {
                $('.k-multiselect .k-input').unbind('keyup');
                $('.k-multiselect .k-input').on('keyup', onClickEnter);
            }
            function onClickEnter(e) {
                // console.log(e);/
                if (e.keyCode === 188 || e.key === ",") {
                    var widget = $('#tags').getKendoMultiSelect();
                    var dataSource = widget.dataSource;
                    var input = $('.k-multiselect .k-input');
                    var value = input.val().replace(/,/g,'').trim();
                    if (!value || value.length === 0) {
                        return;
                    }
                    var newItem = {
                        ProductID: value,
                        ProductName: value
                    };

                    dataSource.add(newItem);
                    var newValue = newItem.ProductID;
                    widget.value(widget.value().concat([newValue]));
                }
            }
            $("#tags").kendoMultiSelect({
                dataTextField: "ProductName",
                dataValueField: "ProductID",
                dataSource: {
                    data: []
                },
                dataBound: onDataBound
            });
        });
    </script>
@stop
@section('ckeditor')
<script>
    CKEDITOR.replace( 'description' );
</script>
@stop
