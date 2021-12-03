<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Pilih Area <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::select('region_id', $regions , null , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Pilih Area','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama Proyek <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('project_name',old('project_name'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nama Proyek','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Kode Proyek <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('project_code',old('project_code'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Kode Proyek','required'=>true]) !!}
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
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nomor Kontrak <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('contract_number',old('contract_number'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nomor Kontrak','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Total Anggota <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::number('total_member',old('total_member'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Total Anggota','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Tanggal Penggajian <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::number('date_salary',old('date_salary'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Tanggal Penggajian','required'=>true]) !!}
    </div>
</div>
<br>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Status Aktif <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::select('status', ['Aktif'=>'Aktif','Berakhir'=>'Berakhir'] , null , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Status Aktif','required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Tanggal Mulai <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::date('start_date', old('start_date'), ['class' => 'form-control datepicker','placeholder'=>'Tanggal Mulai','required'=>true]) !!}
    </div>
</div>
<br>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Tanggal Berakhir <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::date('end_date', old('end_date'), ['class' => 'form-control datepicker','placeholder'=>'Tanggal Berakhir','required'=>true]) !!}
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
