<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="email">Email <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::email('email',old('email'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Email','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="password">Password <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::password('password', ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Password','required'=>true]) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <br>
        @if(request()->segment(3) ==  'edit')
            <button class="btn btn-primary" type="reset">Reset</button>
            {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
        @else
{{--            <button class="btn btn-primary" type="submit">Save</button>--}}
            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
        @endif
    </div>
</div>
