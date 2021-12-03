@extends('adminlte::page')
@section('title', 'Tambah Daftar Pinjaman Baru')

@section('content_header')
    <h1>Tambah Daftar Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    @if($title == 'create')
                        <form  method="POST" action="{{ URL::to('loans') }}" enctype="multipart/form-data">
                      @else
                        <form  method="POST" action="{{ URL::to('loans/'.$id) }}" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">
                     @endif
                      <!-- message -->
                      <div class="text-center">
                       <!-- flash message -->
                      </div>
                      <!-- echo validation error -->
                      @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                      @endif
                      @if (session('message'))
                              <div class="alert alert-success">
                                  {{ session('message') }}
                              </div>
                      @endif
                      @if (session('error'))
                              <div class="alert alert-danger">
                                  {{ session('error') }}
                              </div>
                      @endif
                      <!--/ echo validation error -->
                      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                      <div class="row">
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="loan_name">Nama Pinjaman</label>
                        </div>
                        <div class="col-md-9">
                          <input type="text" id="loan_name" name="loan_name" value="{{ $loan_name }}" placeholder="Nama Level"  required="required" class="form-control">
                            <input type="hidden" id="id" name="id" value="{{ $id }}" autocomplete='id'>
                        </div>
                      </div>
                      <div class="clear"></div>
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="loan_name">Jasa</label>
                        </div>
                        <div class="col-md-9">
                          <input type="number" step="any" id="rate_of_interest" name="rate_of_interest" value="{{ $rate_of_interest }}" placeholder="Suku Bunga"  required="required" class="form-control">
                        </div>
                      </div>
                      <div class="clear"></div>
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="provisi">Provisi</label>
                        </div>
                        <div class="col-md-9">
                          <input type="number" step="any" id="provisi" name="provisi" value="{{ $provisi }}" placeholder="provisi"  required="required" class="form-control">
                        </div>
                      </div>
                      <div class="clear"></div>
                      <div class="form-group">
                          <div class="col-md-3">
                              <label for="provisi">Tenor</label>
                          </div>
                          <div class="col-md-9">
                              <input type="number" step="any" id="tenor" name="tenor" value="{{ $tenor }}" placeholder="tenor"  required="required" class="form-control">
                          </div>
                      </div>
                      <div class="clear"></div>
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="plafon">Plafon</label>
                        </div>
                        <div class="col-md-9">
                          <input type="number" step="any" id="plafon" name="plafon" value="{{ $plafon }}" placeholder="plafon"  required="required" class="form-control">
                        </div>
                      </div>
                      <div class="clear"></div>
                      <div class="form-group">
                          <div class="col-md-3">
                              <label for="biaya_admin">Biaya Admin</label>
                          </div>
                          <div class="col-md-9">
                              <input type="number" step="any" id="biaya_admin" name="biaya_admin" value="{{ $biaya_admin }}" placeholder="biaya admin"  required="required" class="form-control">
                          </div>
                      </div>
                          <div class="clear"></div>
                          <div class="form-group">
                              <div class="col-md-3">
                                  <label for="biaya_admin">Jasa Berjalan</label>
                              </div>
                              <div class="col-md-9">
                                  <input type="number" step="any" id="biaya_bunga_berjalan" name="biaya_bunga_berjalan" value="{{ $biaya_bunga_berjalan }}" placeholder="bunga berjalan"  required="required" class="form-control">
                              </div>
                          </div>
                          <div class="clear"></div>
                          <div class="form-group">
                              <div class="col-md-3">
                                  <label for="biaya_admin">Biaya Transfer</label>
                              </div>
                              <div class="col-md-9">
                                  <input type="number" step="any" id="biaya_transfer" name="biaya_transfer" value="{{ $biaya_transfer }}" placeholder="biaya transfer"  required="required" class="form-control">
                              </div>
                          </div>
                      <div class="clear"></div>
                      <div class="form-group">
                          <div class="col-md-3">
                              <label for="plafon">Description</label>
                          </div>
                          <div class="col-md-9">
                              <textarea step="any" id="description" name="description" placeholder="description"  class="form-control">{{ $description }}</textarea>
                          </div>
                      </div>
                      <div class="clear"></div>
                      <div class="form-group">
                          <div class="col-md-3">
                              <label for="plafon">Publish</label>
                          </div>
                          <div class="col-md-9">

                              <input type="checkbox" step="any" id="publish" name="publish" value="{{ $publish }}"  {{ $publish === 1 ? 'checked' : '' }} class="form-check-input">
                          </div>
                      </div>
                      <div class="clear"></div>
                      <div class="form-group">
                        <div class="col-md-3">
                          <label for="plafon">Lampiran</label>
                        </div>
                        <div class="col-md-9">
                           <select name="attachment" class="form-control">
                              @if($attachment == 1)
                                  <option selected value="1">Wajib</option>
                                  <option value="0">Tidak Wajib</option>
                              @else
                                  <option selected value="0">Tidak Wajib</option>
                                  <option value="1">Wajib</option>
                              @endif
                          </select>
                        </div>
                      </div>
                      <div class="clear"></div>
                          <div class="form-group">
                              <div class="col-md-3">
                                  <label for="plafon">Logo</label>
                              </div>
                              <div class="col-md-9">
                                    @if($logo !== null)
                                        <img src="{{ URL::to('/') }}/images/{{ $logo }}" width="100" style="margin-top: 10px; margin-bottom: 10px;">
                                    @endif
                                    <input type="file" name="logo" class="form-control">
                              </div>
                          </div>

                      <div class="clear"></div>
                      <div class="form-group">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-9">
                            <br/>
                            @if($title == 'edit')
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Update</button>
                            @else
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                            @endif
                        </div>
                      </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('ckeditor')
    <script>
        CKEDITOR.replace( 'description' );
    </script>
@stop
