@extends('adminlte::page')
@section('title', 'Ubah Artikel')

@section('content_header')
    <style>
        #tags-list, span.k-icon.k-clear-value.k-i-close, span.k-icon.k-i-loading.k-hidden {
            display: none !important;
        }
    </style>
    <a href="{{url('article')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
    <h1>Ubah Artikel</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Form::model($article, ['route' => ['article.update', $article->id], 'method' => 'post', 'files' => true]) !!}
                    {{ method_field('PATCH') }}
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
            format: "dd-MM-yyyy",
        });
    </script>
    <script>
        $(document).ready(function() {
            let tagSource = [];
            const oldTags = @json($article->tags);
            if(oldTags && _.isArray(oldTags)){
                oldTags.forEach(function(e){
                    const tagObj = {
                        id: e,
                        name:e
                    };
                    tagSource.push(tagObj);
                });
            }

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
                        id: value,
                        name: value
                    };

                    dataSource.add(newItem);
                    var newValue = newItem.id;
                    widget.value(widget.value().concat([newValue]));
                }
            }
            $("#tags").kendoMultiSelect({
                dataTextField: "name",
                dataValueField: "id",
                dataSource: {
                    data:tagSource
                },
                dataBound: onDataBound
            });
            $("#tags").data("kendoMultiSelect").value(@json($article->tags));
        });
    </script>
@stop
@section('ckeditor')
<script>
    CKEDITOR.replace( 'description' );
</script>
@stop
