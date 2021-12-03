<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Pilih Anggota <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        <select id="member_id" class="cari form-control col-md-7 col-xs-12 select2" name="member_id" required="required"></select>
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nominal <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::number('nominal',old('nominal'), ['id' => 'nominal', 'class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nominal','required'=>true]) !!}
    </div>
</div>
<br/>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Tanggal <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::date('post_date',old('post_date'), ['id' => 'post_date', 'class' => 'form-control datepicker col-md-7 col-xs-12','placeholder'=>'Tanggal','required'=>true]) !!}
    </div>
</div>
<br/>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Keterangan <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('desc',old('desc'), ['id' => 'desc', 'class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Keterangan','required'=>true]) !!}
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
            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
        @endif
    </div>
</div>
