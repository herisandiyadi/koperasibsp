@extends('adminlte::page')
@section('title', 'Form Pengajuan pengunduran diri')

@section('content')
<!-- grafik member -->
<div class="box box-info">
<div class="box-header with-border">
<h3 class="box-title">Pengajuan pengunduran diri</h3>

<div class="box-tools pull-right">
  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
  </button>
</div>
</div>
<!-- /.box-header -->
<div class="box-body">
<div class="col-md-12">
	 <form  method="POST" action="{{ URL::to('resign') }}">
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<!-- echo validation error -->
        @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
        @endif
        @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
        @endif
        <!--/ echo validation error -->
		<div class="col-md-5 no-padding">
            <div class="form-group">
                <label class="control-label">Tanggal diajukan</label><p></p>
                <input type="text" class="form-control" name="date" id="datepicker" value="{{\Carbon\Carbon::parse(now())->format('Y-m-d')}}" />
            </div>
        </div>
        <div class="col-md-12 no-padding">
        <div class="form-group">
        	<label for="">Alasan</label><p></p>
        	<textarea name="reason" id="reason" cols="30" rows="10" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <div class="col-md-12" style="padding: 0px;">
                <input type="checkbox" id="agree" /> <label data-toggle="modal" data-target="#exampleModal">Persyaratan pengunduran diri, setuju ?</label>
            </div>
        </div>
            <br/><br/>
        <div class="form-group">
        	<button class="btn btn-default" type="reset">Batal</button>
        	<button class="btn btn-primary" id="submit_postcode" type="submit">Ajukan</button>
        </div>
        </div>
	</form>
</div>
</div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $policy->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $policy->description !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="setuju()"  class="btn btn-primary">Setuju</button>
            </div>
        </div>
    </div>
</div>
<!-- /.box-body -->
@endsection
@section('appjs')
<script>
    function setuju() {
        document.getElementById("agree").checked = true;
        $('#submit_postcode').removeAttr('disabled');
    }

    $('#submit_postcode').attr('disabled', 'disabled');
    $('#agree').change(function () {
        if(this.checked) {
            $('#submit_postcode').removeAttr('disabled');
        }else{
            $('#submit_postcode').attr('disabled', 'disabled');
        }
    });
	// create DatePicker from input HTML element
$("#datepicker").kendoDatePicker({
    // display month and year in the input
    format: "yyyy-MM-dd",
    min: new Date(),

    // specifies that DateInput is used for masking the input element
    dateInput: true
  });
</script>
@endsection
