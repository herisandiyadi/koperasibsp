@extends('adminlte::page')
@section('title', 'General Setting')

@section('content_header')
    <h1>General Setting</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
					<form action="{{url('setting/update')}}" method="post" enctype="multipart/form-data">
                    {{ method_field('patch') }}
					@csrf
                    <div class="form-group">
						<label for="name">Tanggal Cut Off <span class="required">*</span></label>
                        @if(isset($DataSetting[0]['content']))
						    <input type="text" class="form-control" name="cutOff" style="margin-top:10px;" value="{{ $DataSetting[0]['content'] }}" require>
                        @else
                            <input type="text" class="form-control" name="cutOff" style="margin-top:10px;" value="1" require>
                        @endif
                    </div>
					<div class="form-group">
						<label for="name">Tanggal Generate Tagihan <span class="required">*</span></label>
                        @if(isset($DataSetting[1]['content']))
						    <input type="text" class="form-control" name="generateTagihan" style="margin-top:10px;" value="{{ $DataSetting[1]['content'] }}" require>
                        @else
                            <input type="text" class="form-control" name="generateTagihan" style="margin-top:10px;" value="1" require>
                        @endif
					</div>
					<button class="btn btn-primary pull-right" type="submit">Save</button>
					</form>

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
@stop
