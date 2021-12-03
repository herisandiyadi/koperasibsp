<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Pilih Area <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::select('region_id', $regions , null , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Pilih Area','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama Cabang <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('branch_name',old('branch_name'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nama Cabang','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Kode Cabang <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('branch_code',old('branch_code'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Kode Cabang','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Telp <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::number('telp',old('telp'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Telepon','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Alamat <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::textarea('address', old('address'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Alamat','required'=>true,'rows'=>'4']) !!}
    </div>
</div>
<br>
<div class="clear-fix1"></div>
<br>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Status Aktif <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::select('status', ['Aktif'=>'Aktif','Berakhir'=>'Berakhir'] , null , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Status Aktif','required'=>true]) !!}
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
            <button class="btn btn-primary" type="reset">Reset</button>
            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
        @endif
    </div>
</div>