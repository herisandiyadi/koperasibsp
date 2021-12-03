
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Nama Policy <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('name',old('name'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Nama','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Description <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::textarea('description',old('project_description'), ['class' => 'form-control col-md-7 col-xs-12', 'id' => 'description','placeholder'=>'Description','required'=>true]) !!}
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
