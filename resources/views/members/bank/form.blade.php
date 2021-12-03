<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama Bank <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('bank_name',old('bank_name'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nama Bank','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama Akun Bank <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('bank_account_name',old('bank_account_name'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nama Akun Bank','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nomor Akun Bank <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('bank_account_number',old('bank_account_number'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nomor Akun Bank','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Cabang Bank <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('bank_branch',old('bank_branch'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Cabang Bank','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <br>
        @if(request()->segment(3) ==  'edit')
            <button class="btn btn-primary" type="reset">Reset</button>
            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
        @else
            <button class="btn btn-primary" type="submit">Save</button>
            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
        @endif
    </div>
</div>
