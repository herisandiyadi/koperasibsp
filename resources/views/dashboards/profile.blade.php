@extends('adminlte::page')
@section('title', 'Profile User')

@section('content')
<style>
/* IMG displaying */
.person-card {
    margin-top: 2.5em;
    padding-top: 2.5em;
}
#who_message {
	margin-top: 0.3em;
}
.skin-red-light .main-header .navbar {
    background-color: #fff;
    border-bottom: 1px solid #c8ced3;
}
.skin-red-light .main-header .navbar .sidebar-toggle {
    color: #73818f;
}
.skin-red-light .main-header .logo {
    background-color: #fff;
    border-bottom: 1px solid #c8ced3;
    border-right: 1px solid #c8ced3;
    color: #73818f;
}
.skin-red-light .main-header .navbar .sidebar-toggle:hover {
    background-color: #fff;
    color:#2f353a;
}
.skin-red-light .main-header .logo:hover {
    background-color: #fff;
    color:#2f353a;
}
.skin-red-light .main-header .navbar .nav>li>a {
    color: #73818f;
}
.skin-red-light .main-header .navbar .nav>li>a:hover {
    background-color: #fff;
    color:#2f353a;
}
#user-profile {
	background-color: #26a59a;
	color: #fff;
	border-radius: 5px;
}
hr {
    border-top: 1px solid #CECCCC;
    margin: 20px 0;
}
#garis hr {
	margin: 8px 0px 20px 0px;
}
.glyphicon.md-36 {
    font-size: 36px;
}
.nav-tabs>li>a {
    color: #73818f;
}
.nav-tabs>li>a:hover {
    background-color: #fff;
    color:#2f353a;
}
.tabbable-line > .nav-tabs > li.active > a {
    border: 0;
    color: #333;
    border-bottom: 4px solid #26A59A;
    position: relative;
}
.tabbable-line > .nav-tabs > li > a:hover {
    border: 0;
    border-bottom: 4px solid #00BCD4;
    position: relative;
}
.tab-pane {
    margin-bottom: 20px;
}
div.space {
    height: 70px;
}
.label_head {
    font-size: 18px;
    margin: 12px 0;
}
.edit-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 10px;
    height: 60px;
    background-color: #353b48;
    border-radius: 30px;
    display: none;
}
.search_icon {
    height: 40px;
    width: 40px;
    border-radius: 50%;
    color: white;
    float: right;
    display: flex;
    justify-content: center;
    align-items: center;
}
.edit-button:hover > .search_input {
    padding:0 10px;
    width: 100px;
    caret-color:red;
    transition: width 0.4s linear;
}
.edit-button:hover >  .search_icon {
    background: #fff;
    color: #e74c3c;
}
.edit-button:not(:hover) >  .search_icon {
    color: #fff;
}

.search_input {
    color: white;
    border:0;
    outline: 0;
    background:none;
    width: 0;
    caret-color:transparent;
    line-height: 40px;
    transition: width 0.4s linear;
}
.person-card .person-img-db{
    width: 10em;
    height: 9.5em;
    position: absolute;
    top: -4em;
    left: 50%;
    margin-left: -4em;
    border-radius: 100%;
    overflow: hidden;
    background-color: white;
}
#picture {
    display: none;
}

</style>
<div class="card person-card">
    <div class="card-body">
        <span class="fa fa-mosque"></span>
        @if(Auth::user()->member->picture == null || file_exists('images/'.Auth::user()->member->picture) == false)
        <img id="img_sex" class="person-img" onclick="chgImage()"
            src="{{ asset('images/security-guard.png') }}" data-toggle="tooltip" title="Perbaharui Gambar! Klik disini.">
        @else
        <img id="img_sex" class="person-img-db" onclick="chgImage()"
            src="{{ asset('images/'.Auth::user()->member->picture) }}" data-toggle="tooltip" title="Perbaharui Gambar! Klik disini.">
        @endif
        <div id="who_message" class="card-title">{{ Auth::user()->name }}</div>
        <div class="col-md-12 text-center" >
        	( {{ ucwords(Auth::user()->position->description) }} )
            @if(Auth::user()->member->join_date != null)
            <br>
            <span class="profile-userpict-position">Joined at {{\Carbon\Carbon::parse(Auth::user()->member->join_date)->format('d-M-Y')}} ({{\Carbon\Carbon::parse(Auth::user()->member->join_date)->diffForHumans(null, true)}} terdaftar)</span>
            @endif
            <hr>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="text-center tooltips" data-container="body" data-placement="top" data-html="true" title="Tanggal Lahir">
                @if(Auth::user()->member->dob != null)
                <i class="fa fa-birthday-cake md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                   {{Carbon\Carbon::parse(Auth::user()->member->dob)->diffForHumans(null, true)}}
                </span >
                @else
                <i class="fa fa-birthday-cake md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                   Edit Tanggal Lahir
                </span >
                @endif
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="text-center tooltips" data-container="body" data-placement="top" data-html="true" title="Jenis Kelamin">
                @if(Auth::user()->member->gender != null)
                @if(Auth::user()->member->gender != 'Perempuan')
                     <i class="fa fa-mars md-36"></i>
                @else
                     <i class="fa fa-venus md-36"></i>
                @endif
                <span id="grade-status" class="help-block" aria-hidden="true">
                    {{ ucwords(Auth::user()->member->gender) }}
                </span >
                 @else
                <i class="fa fa-mars md-36"></i>
                <i class="fa fa-venus md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                    Edit Gender
                </span >
                @endif
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="text-center tooltips" data-container="body" data-placement="top" data-html="true" title="Agama">
                <i class="fa fa-refresh md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                @if(Auth::user()->member->religion!= null)
                    {{ ucwords(Auth::user()->member->religion) }}
                @else
                    Edit Agama
                @endif
                </span >
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="text-center tooltips" data-container="body" data-placement="top" data-html="true" title="NIK BSP">
                <i class="fa fa-vcard-o md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                @if(Auth::user()->member->nik_bsp!= null)
                    {{ ucwords(Auth::user()->member->nik_bsp) }}
                @else
                    Edit NIK BSP
                @endif
                </span >
            </div>
        </div>
         <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="text-center tooltips" data-container="body" data-placement="top" data-html="true" title="NIK Koperasi">
                <i class="fa fa-building-o md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                @if(Auth::user()->member->nik_koperasi!= null)
                    {{ ucwords(Auth::user()->member->nik_koperasi) }}
                @else
                    Edit NIK Koperasi
                @endif
                </span >
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="text-center tooltips" data-container="body" data-placement="top" data-html="true" title="No. Telp">
                <i class="glyphicon glyphicon-phone-alt md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                @if(Auth::user()->member->phone_number!= null)
                    {{ ucwords(Auth::user()->member->phone_number) }}
                @else
                    Edit No. Telp
                @endif
                </span >
            </div>
        </div>
         <div class="col-md-12" id="garis">
            {{-- input file picture --}}
            <input type="file" class="form-control" name="picture" id="picture">
            <hr>
        </div>
        <div class="col-md-12">
        <div class="tabbable-line tabbable-full-width profile">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1-1" data-toggle="tab" aria-expanded="true"> Informasi Data Diri </a>
            </li>
            <li>
                <a href="#tab_1-2" data-toggle="tab" aria-expanded="true"> Staff Info </a>
            </li>
        </ul>
        <div class="tab-content profile">
        <div id="tab_1-2" class="tab-pane">
             <div class="col-md-6">
                <label for="" class="label_head"><span class="fa fa-vcard-o"></span> Informasi Pekerjaan</label>
             <div class="col-md-12 no-padding">
                <label for="">Nama Area</label>
                @if ($spcMember->region_id !='' || $spcMember->region_id != null)
                    <p class="">{{ ucwords($spcMember->region->name_area ) }}</p>
                @endif
             </div>
             <div class="space"></div>
             <div class="col-md-12 no-padding">
                 <label>Cabang</label>
                 @if ($spcMember->branch_id !='' || $spcMember->branch_id != null)
                    <p class="">{{ ucwords($spcMember->branch->branch_name ) }}</p>
                 @endif
             </div>
             <div class="space"></div>
             <div class="col-md-12 no-padding">
                 <label>Proyek</label>
                 @if ($spcMember->project_id !='' || $spcMember->project_id != null)
                    <p class="">{{ ucwords($spcMember->project->project_name ) }}</p>
                @endif
             </div>
            </div>
        </div>
        <div id="tab_1-1" class="tab-pane active">
        <form action="" class="edit_data_profile" id="edit_data_profile">
         <div class="col-md-6">
            <label for="" class="label_head"><span class="fa fa-address-book-o"></span> Informasi Pribadi</label>
            <br>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Nama Depan</label>
             <p class="show_default">{{ ucwords(Auth::user()->member->first_name) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="Nama Depan" name="first_name" value="{{ Auth::user()->member->first_name }}">
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">Nama Belakang</label>
             <p class="show_default">{{ ucwords(Auth::user()->member->last_name) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="Nama Belakang" name="last_name" value="{{ Auth::user()->member->last_name }}">

         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Jenis Kelamin</label>
             <p class="show_default">{{ ucwords(Auth::user()->member->gender) }}</p>
             <select name="gender" class="form-control defaultHide">
                @if(Auth::user()->member->gender == "Perempuan")
                    <option selected>Perempuan</option>
                    <option>Laki-laki</option>
                @else
                    <option selected>Laki-laki</option>
                    <option>Perempuan</option>
                @endif
            </select>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
            <label for="">Agama</label>
            <p class="show_default">{{ ucwords(Auth::user()->member->religion) }}</p>
            <select name="religion" class="form-control defaultHide">
            @if(Auth::user()->member->religion == "Buddha")
                <option selected value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif(Auth::user()->member->religion == "Katolik")
                <option value="Buddha">Buddha</option>
                <option selected value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif(Auth::user()->member->religion == "Protestan")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option selected value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif(Auth::user()->member->religion == "Hindu")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option selected value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif(Auth::user()->member->religion == "Islam")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option selected value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif(Auth::user()->member->religion == "Konghucu")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option selected value="Konghucu">Konghucu</option>
            @elseif(Auth::user()->member->religion == "")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @endif
            </select>
         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Tanggal Lahir</label>
             <p class="show_default">{{\Carbon\Carbon::parse(Auth::user()->member->dob)->format('l, d-M-Y')}}</p>
             <input id="datepicker" value="{{ Auth::user()->member->dob }}" name="dob" title="datepicker" style="width: 100%" class="defaultHide" />
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">No. Telp</label>
             <p class="show_default">{{ ucwords(Auth::user()->member->phone_number) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="No. Telp" name="phone_number" value="{{ Auth::user()->member->phone_number }}">
         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Alamat Email</label>
             <p>{{ ucwords(Auth::user()->member->email) }}</p>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">Nomor Induk Kependudukan</label>
             <p class="show_default">{{ ucwords(Auth::user()->member->nik) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="Nomor induk" name="nik" value="{{ Auth::user()->member->nik }}">
         </div>
         <div class="space"></div>
         <div class="col-md-12 no-padding col-sm-12 col-xs-12">
             <label for="">Alamat Rumah</label>
             <p class="show_default">{{ ucwords(Auth::user()->member->address) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="Alamat" name="address" value="{{ Auth::user()->member->address }}">
         </div>
         </div>

         <div class="col-md-6">
            <label for="" class="label_head"><span class="fa fa-vcard-o"></span> Member Info</label>
            <br>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">NIK Koperasi</label>
             <p>{{ ucwords(Auth::user()->member->nik_koperasi) }}</p>
            {{--  <input type="text" class="form-control defaultHide" placeholder="NIK Koperasi" name="nik_koperasi" value="{{ Auth::user()->member->nik_koperasi }}"> --}}
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">NIK BSP</label>
             <p>{{ ucwords(Auth::user()->member->nik_bsp) }}</p>
             {{-- <input type="text" class="form-control defaultHide" placeholder="NIK BSP" name="nik_bsp" value="{{ Auth::user()->member->nik_bsp }}"> --}}
         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Posisi</label>
            <p>{{ ucwords(Auth::user()->position->description) }}</p>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">Mulai Bergabung</label>
             <p>{{\Carbon\Carbon::parse(Auth::user()->member->join_date)->format('d-M-Y')}}</p>
         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Awal Kontrak</label>
             <p>{{\Carbon\Carbon::parse(Auth::user()->member->start_date)->format('d-M-Y')}}</p>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">Kontrak Berakhir</label>
             <p>{{\Carbon\Carbon::parse(Auth::user()->member->end_date)->format('d-M-Y')}}</p>
         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Status</label>
             @if(Auth::user()->member->is_active == 1)
              <p>Aktif</p>
             @else
             <p>Tidak Aktif</p>
             @endif
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
         </div>
         <div class="col-md-12 no-padding col-sm-12 col-xs-12">
         <label for="" class="label_head"><span class="fa fa-credit-card"></span> Informasi Bank</label>
         </div>
		@if(isset($bank_member->bank_name))
			<div class="col-md-6 no-padding col-sm-6 col-xs-6">
				<label for="">Nama Bank</label>
				<p class="show_default">{{ $bank_member->bank_name}}</p>
				<input type="text" class="form-control defaultHide" placeholder="Nama Bank" name="bank_name" value="{{ $bank_member->bank_name}}">
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<label for="">Nama Pemegang Kartu</label>
				<p class="show_default">{{ $bank_member->bank_account_name}}</p>
				<input type="text" class="form-control defaultHide" placeholder="Nama Pemegang Kartu" name="bank_account_name" value="{{ $bank_member->bank_account_name}}">
			</div>
			<div class="space"></div>
			<div class="col-md-6 no-padding col-sm-6 col-xs-6">
				<label for="">No. Rekening</label>
				<p class="show_default">{{ $bank_member->bank_account_number}}</p>
				<input type="text" class="form-control defaultHide" placeholder="No. Rekening" name="bank_account_number" value="{{ $bank_member->bank_account_number}}">
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<label for="">Cabang</label>
				<p class="show_default">{{ $bank_member->bank_branch}}</p>
				<input type="text" class="form-control defaultHide" placeholder="Cabang" name="bank_branch" value="{{ $bank_member->bank_branch}}">
			</div>
		 @else
			<div class="col-md-6 no-padding col-sm-6 col-xs-6">
				<label for="">Nama Bank</label>
				<p class="show_default"></p>
				<input type="text" class="form-control defaultHide" placeholder="Nama Bank" name="bank_name" value="">
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<label for="">Nama Pemegang Kartu</label>
				<p class="show_default"></p>
				<input type="text" class="form-control defaultHide" placeholder="Nama Pemegang Kartu" name="bank_account_name" value="">
			</div>
			<div class="space"></div>
			<div class="col-md-6 no-padding col-sm-6 col-xs-6">
				<label for="">No. Rekening</label>
				<p class="show_default"></p>
				<input type="text" class="form-control defaultHide" placeholder="No. Rekening" name="bank_account_number" value="">
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6">
				<label for="">Cabang</label>
				<p class="show_default"></p>
				<input type="text" class="form-control defaultHide" placeholder="Cabang" name="bank_branch" value="">
			</div>
		 @endif
         <div class="space"></div>
         <div class="col-md-12 no-padding col-sm-6 col-xs-6 defaultHide">
         <br>
         <button type="button" class="btn btn-default margin-right-10" onclick="reset_form()">Batal</button>
         <button type="submit" class="btn btn-warning">Perbaharui</button>
         <p>* Periksa kembali data yang anda masukkan sebelum memencet tombol perbaharui</p>
         </form>
         </div>

         </div>
        </div>
        </div>
        </div>
    </div>
  </div>
  <div class="edit-button">
      <input type="text" class="search_input defaultHide" placeholder="Edit Profile" disabled="">
      <a href="#" class="search_icon" onclick="edit_profile()"><i class="fa fa-cog md-36"></i></a>
  </div>
@stop
@section('appjs')
<script>
    function chgImage() {
        $("[name='picture']").trigger('click');
    }
    $("[name='picture']").bind('change', function() {
     var ext          = $("[name='picture']").val().split('.').pop();
     let fileContract = this.files[0].size;
     if (fileContract > 2097152){
        PNotify.error({
                title: 'Error',
                text: 'File gagal ditambahkan.  Ukuran file tidak boleh lebih dari 2 MB',
              });
        $("[name='picture']").val('');
        } else if( ext == 'jpg' || ext == 'jpeg' || ext == 'png') {
            var file_data = $("[name='picture']").prop('files')[0];
            var form_data = new FormData();
            var change    = 'form_image';
            form_data.append('_token', "{{csrf_token()}}");
            form_data.append('attach', file_data);
            form_data.append('change_image', change);
            // process the form
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : '{{url('update-profile')}}', // the url where we want to POST
                data        : form_data, // our data object
                processData : false,
                contentType : false,
            })
                // using the done promise callback
            .done(function(data) {
                 PNotify.success({
                        title: 'Success!',
                        text: data.msg,
                      });
                    $( "#overlay" ).show(3000);
                    $('form')[0].reset();
                    setTimeout(function(){ window.location.href= '{{url("profile")}}'; }, 5000);
            });
        } else {
            PNotify.error({
                title: 'Error',
                text: 'File gagal ditambahkan.  File extension hanya Jpeg, Jpg, Png yang diperbolehkan',
              });
        $("[name='picture']").val('');
        }
    });
    $(document).ready(function() {
    $('.edit-button').show()
    $('.defaultHide').hide();
    $('select').hide();
    // create DatePicker from input HTML element
    $("#datepicker").kendoDatePicker({
        // display month and year in the input
        format: "yyyy-MM-dd",

        // specifies that DateInput is used for masking the input element
        dateInput: true
      });
    });
    function edit_profile(){
     if($('.show_default').is(':hidden') == true){
        $('.show_default').show();
        $('.defaultHide').hide();
        $('select').hide();
     } else {
        $('.show_default').hide();
        $('.defaultHide').show();
        $('select').show();
     }
    }
    function reset_form() {
        $('#edit_data_profile')[0].reset();
        edit_profile();
    }
    // process the form
    $('form').submit(function(e) {
    $( "#overlay" ).show(3000);
   // process the form
    $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : '{{url('update-profile')}}', // the url where we want to POST
        data        : $('form').serialize(), // our data object
        dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
    })
        // using the done promise callback
        .done(function(data) {
             PNotify.success({
                    title: 'Success!',
                    text: data.msg,
                  });
                $( "#overlay" ).show(3000);
                $('form')[0].reset();
                setTimeout(function(){ window.location.href= '{{url("profile")}}'; }, 5000);
        });

    // stop the form from submitting the normal way and refreshing the page
    e.preventDefault();
    });
</script>
@stop
