<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama Simpanan <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('deposit_name',old('deposit_name'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nama Simpanan','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Jumlah Minimal <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        <div class="input-group">
            <span class="input-group-addon">Rp</span>
            {!! Form::text('deposit_minimal',old('deposit_minimal'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Jumlah Minimal','required'=>true]) !!}
        </div>
    </div>
</div>
<br>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Jumlah Maximal <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        <div class="input-group">
            <span class="input-group-addon">Rp</span>
            {!! Form::text('deposit_maximal',old('deposit_maximal'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Jumlah Maximal','required'=>true]) !!}
        </div>
    </div>
</div>
<br>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <br>
        @if(request()->segment(3) ==  'edit')
            <button class="btn btn-primary" type="reset">Reset</button>
            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
        @else
            <button class="btn btn-primary" type="reset">Reset</button>
            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
        @endif
    </div>
</div>