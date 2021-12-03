
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Judul Artikel <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('title',old('title'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Judul','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Author <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::text('author',old('author'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Author','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Isi <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        {!! Form::textarea('content',old('content'), ['class' => 'form-control col-md-7 col-xs-12', 'id' => 'description','placeholder'=>'Content','required'=>true]) !!}
    </div>
</div>
<div class="clear-fix1"></div>

<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Tags
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        <select id="tags" style="width: 100%;" name="tags[]"></select>
        {{--{!! Form::select('tags',[], old('tags'),['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Tags','required'=>false ,'multiple'=>true,'data-role'=>'tagsinput']) !!}--}}
{{--        {!! Form::text('tags',old('tags'), ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Tags','required'=>false]) !!}--}}
    </div>
</div>
<div class="clear-fix1"></div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Gambar <span class="required">*</span>
    </label>
    <div class="col-md-9 col-sm-9 col-xs-8">
		@if(request()->segment(3) == 'edit')
				<img src="{{url('images/news/'.$article->image_name)}}" width="200"/>
		@endif
        {!! Form::file('images',old('images'), ['class' => 'form-control col-md-7 col-xs-12','files' => true]) !!}
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Terbitkan</label>
    <div class="col-md-9 col-sm-9 col-xs-8">
        @if(request()->segment(3) == 'edit')
            {!! Form::checkbox('publish', true, $article->isPublished(),['class'=>'flat-red']) !!}
        @else
            {!! Form::checkbox('publish', true, false,['class'=>'flat-red']) !!}
        @endif

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
