<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Posisi User <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        @if (request()->segment(3) == 'edit')
            {!! Form::select('position_id', $positions , $user->position_id , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Pilih Posisi','required'=>true]) !!}
        @else
            {!! Form::select('position_id', $positions , old('position_id') , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Pilih Posisi','required'=>true]) !!}
        @endif
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Region User <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        @if (request()->segment(3) == 'edit')
            {!! Form::select('region_id', $regions , $user->region_id , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Pilih Region','required'=>true]) !!}
        @else
            {!! Form::select('region_id', $regions , old('region_id') , ['class' => 'form-control col-md-7 col-xs-12 select2','placeholder'=>'Pilih Region','required'=>true]) !!}
        @endif
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('name',old('name'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nama','required'=>true]) !!}
    </div>
</div>
<br>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Username <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('username', old('username'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Username','required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Email <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('email', old('email'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true]) !!}
    </div>
</div>
<br>
@if (request()->segment(3) == 'edit')
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Password Lama <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::hidden('_uid', $user->id) !!}
        {!! Form::password('old_password', ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Password lama']) !!}
    </div>
</div>
@endif

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Password <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::password('password', ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Password','min'=>6]) !!}
    </div>
</div>
<br>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Konfirmasi Password <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::password('password_confirmation', ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Konfirmasi Password']) !!}
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
