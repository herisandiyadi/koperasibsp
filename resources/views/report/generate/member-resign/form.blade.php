<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Tanggal Awal <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::date('start',old('start'), ['id' => 'start', 'class' => 'form-control datepicker col-md-7 col-xs-12','placeholder'=>'Tanggal Awal','required'=>true]) !!}
    </div>
</div>
<br/>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Tanggal Akhir <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::date('end',old('end'), ['id' => 'end', 'class' => 'form-control datepicker col-md-7 col-xs-12','placeholder'=>'Tanggal Akhir','required'=>true]) !!}
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <br>
        @if(request()->segment(3) ==  'edit')
            <button class="btn btn-primary" type="reset">Reset</button>
            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
        @else
            <button class="btn btn-primary" type="reset">Reset</button>
            <div class="btn btn-primary" id="search_data">Search</div>
            {!! Form::submit('Download', ['class' => 'btn btn-success']) !!}
        @endif
    </div>
</div>
