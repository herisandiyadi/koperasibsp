@extends('adminlte::page')
@section('title', 'Lihat data member')

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
.edit-staff {
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
        @if($getData->picture == null || file_exists('images/'.$getData->picture) == false)
        <img id="img_sex" class="person-img"
            src="{{ asset('images/security-guard.png') }}" data-toggle="tooltip" title="{{ $getData->first_name.' '.$getData->last_name }}">
        @else
        <img id="img_sex" class="person-img-db"
            src="{{ asset('images/'.$getData->picture) }}" data-toggle="tooltip" title="{{ $getData->first_name.' '.$getData->last_name }}">
        @endif
        <div id="who_message" class="card-title">{{ $getData->first_name.' '.$getData->last_name }}</div>
        <div class="col-md-12 text-center" >
        	( {{ ucwords($getData->description) }} )
            @if($getData->join_date != null)
            <br>
            <span class="profile-userpict-position">Joined at {{\Carbon\Carbon::parse($getData->join_date)->format('d-M-Y')}} ({{\Carbon\Carbon::parse($getData->join_date)->diffForHumans(null, true)}} terdaftar)</span>
            @endif
            <hr>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            <div class="text-center tooltips" data-container="body" data-placement="top" data-html="true" title="Tanggal Lahir">
                @if($getData->dob != null)
                <i class="fa fa-birthday-cake md-36"></i>
                <span id="grade-status" class="help-block" aria-hidden="true">
                   {{Carbon\Carbon::parse($getData->dob)->diffForHumans(null, true)}}
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
                @if($getData->gender != null)
                @if($getData->gender != 'Perempuan')
                     <i class="fa fa-mars md-36"></i>
                @else
                     <i class="fa fa-venus md-36"></i>
                @endif
                <span id="grade-status" class="help-block" aria-hidden="true">
                    {{ ucwords($getData->gender) }}
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
                @if($getData->religion!= null)
                    {{ ucwords($getData->religion) }}
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
                @if($getData->nik_bsp!= null)
                    {{ ucwords($getData->nik_bsp) }}
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
                @if($getData->nik_koperasi!= null)
                    {{ ucwords($getData->nik_koperasi) }}
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
                @if($getData->phone_number!= null)
                    {{ ucwords($getData->phone_number) }}
                @else
                    Edit No. Telp
                @endif
                </span >
            </div>
        </div>
         <div class="col-md-12" id="garis">
            {{-- input file picture --}}
            <hr>
        </div>
        <div class="col-md-12">
        <div class="tabbable-line tabbable-full-width profile">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1-1" data-toggle="tab" aria-expanded="true" onclick="rst_tab()"> Informasi Data Diri </a>
            </li>
            <li>
                <a href="#tab_1-2" data-toggle="tab" aria-expanded="true" onclick="rst_tab1()"> Staff Info </a>
            </li>
        </ul>
        <div class="tab-content profile">
         <div id="tab_1-2" class="tab-pane">
            <div class="col-md-6">
             <form action="" class="edit_data_staff" id="edit_data_staff">
                <label for="" class="label_head"><span class="fa fa-vcard-o"></span> Informasi Pekerjaan</label>
             <div class="col-md-12 no-padding">
                <label for="">Nama Area</label>
                @if ($spcMember->region_id !='' || $spcMember->region_id != null)
                    <p class="">{{ ucwords($spcMember->region->name_area ) }}</p>
                @endif
                 <select name="region_id" id="region_id" class="form-control " required>
                    <option value="">Pilih Area Kerja</option>
                    @foreach ($region as $item)
                        <option value="{{ $item->id }}">{{ $item->name_area }}</option>
                    @endforeach
                 </select>
             </div>
             <div class="space"></div>
             <div class="col-md-12 no-padding">
                 <label>Cabang</label>
                 @if ($spcMember->branch_id !='' || $spcMember->branch_id != null)
                    <p class="">{{ ucwords($spcMember->branch->branch_name ) }}</p>
                 @endif
                 <select name="branch_id" id="branch_id" class="form-control " required>
                        <option value="">Pilih Lokasi Cabang</option>
                 </select>
             </div>
             <div class="space"></div>
             <div class="col-md-12 no-padding">
                 <label>Proyek</label>
                 @if ($spcMember->project_id !='' || $spcMember->project_id != null)
                    <p class="">{{ ucwords($spcMember->project->project_name ) }}</p>
                @endif
                 <select name="project_id" id="project_id" class="form-control " required>
                    <option value="">Pilih Lokasi Proyek</option>
                 </select>
             </div>
             <div class="space"></div>
             <div class="col-md-12 no-padding">
                <br>
                <button type="reset" class="btn btn-default margin-right-10" id="hidden">Batal</button>
                <button type="button" onclick="update_staff()" class="btn btn-warning" id="hidden">Perbaharui</button>
             </div>
             </form>
             <br>
            </div>
        </div>
        <div id="tab_1-1" class="tab-pane active">
         <div class="col-md-6">
         <form action="" class="edit_data_profile" id="edit_data_profile">
            <label for="" class="label_head"><span class="fa fa-address-book-o"></span> Informasi Pribadi</label>
            <br>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Nama Depan</label>
             <p class="show_default">{{ ucwords($getData->first_name) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="Nama Depan" name="first_name" value="{{ $getData->first_name }}">
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">Nama Belakang</label>
             <p class="show_default">{{ ucwords($getData->last_name) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="Nama Belakang" name="last_name" value="{{ $getData->last_name }}">

         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
            <label for="">Jenis Kelamin</label>
            <p class="show_default">{{ ucwords($getData->gender) }}</p>
            <select name="gender" class="form-control defaultHide">
                @if($getData->gender == "Perempuan")
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
            <p class="show_default">{{ ucwords($getData->religion) }}</p>
            <select name="religion" class="form-control defaultHide">
            @if($getData->religion == "Buddha")
                <option selected value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif($getData->religion == "Katolik")
                <option value="Buddha">Buddha</option>
                <option selected value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif($getData->religion == "Protestan")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option selected value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif($getData->religion == "Hindu")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option selected value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif($getData->religion == "Islam")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option selected value="Islam">Islam</option>
                <option value="Konghucu">Konghucu</option>
            @elseif($getData->religion == "Konghucu")
                <option value="Buddha">Buddha</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Islam">Islam</option>
                <option selected value="Konghucu">Konghucu</option>
            @elseif($getData->religion == "")
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
            @if($getData->dob != null)
                <p class="show_default">{{\Carbon\Carbon::parse($getData->dob)->format('l, d-M-Y')}}</p>
            @else
            <p></p>
            @endif
            @php
                if($getData->dob == null){
                   $getData->dob = now();
                }
            @endphp
             <input id="datepickerDOB" value="{{ $getData->dob }}" name="dob" title="datepicker" style="width: 100%" class="defaultHide" /> 
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
            <label for="">No. Telp</label>
            <p class="show_default">{{ ucwords($getData->phone_number) }}</p>
             <input type="text" class="form-control defaultHide" placeholder="No. Telp" name="phone_number" value="{{ $getData->phone_number }}">
         </div>
             @can('view.member.profile')
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Alamat Email</label>
             <p>{{ ucwords($getData->email) }}</p>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
            <label for="">Nomor Induk Kependudukan</label>
            <p class="show_default">{{ ucwords($getData->nik) }}</p>
            <input type="text" class="form-control defaultHide" placeholder="Nomor induk" name="nik" value="{{ $getData->nik }}">
         </div>
         <div class="space"></div>
         <div class="col-md-12 no-padding col-sm-12 col-xs-12">
            <label for="">Alamat Rumah</label>
            <p class="show_default">{{ ucwords($getData->address) }}</p>
            <input type="text" class="form-control defaultHide" placeholder="Alamat" name="address" value="{{ $getData->address }}">
         </div>
                 @endcan
         </div>

         <div class="col-md-6">
            <label for="" class="label_head"><span class="fa fa-vcard-o"></span> Member Info</label>
            <br>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">NIK Koperasi</label>
             <p>{{ ucwords($getData->nik_koperasi) }}</p>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">NIK BSP</label>
             <p>{{ ucwords($getData->nik_bsp) }}</p>
         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Posisi</label>
             <p class="show_default">{{ ucwords($getData->description) }}</p>
             <select name="position_id" class="form-control defaultHide" required>
                <option value="">Pilih Jabatan</option>
                @foreach ($pst as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">Mulai Bergabung</label>
             <p>{{\Carbon\Carbon::parse($getData->join_date)->format('d-M-Y')}}</p>
         </div>
         <div class="space"></div>
         <div class="col-md-6  no-padding col-sm-6 col-xs-6">
             <label for="">Awal Kontrak</label>
             <p class="show_default">{{\Carbon\Carbon::parse($getData->start_date)->format('d-M-Y')}}</p>
             @php
                if($getData->start_date == null){
                   $getData->start_date = now();
                }
            @endphp
             <input id="datepicker" value="{{ $getData->start_date }}" name="start_date" title="datepicker" style="width: 100%" class="defaultHide" />
             <input type="hidden" name="update_data" value="{{ $getData->email }}">
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
             <label for="">Kontrak Berakhir</label>
             @php
                if($getData->end_date == null){
                   $getData->end_date = now();
                }
            @endphp
             <p class="show_default">{{\Carbon\Carbon::parse($getData->end_date)->format('d-M-Y')}}</p>
             <input id="datepicker1" value="{{ $getData->end_date }}" name="end_date" title="datepicker" style="width: 100%" class="defaultHide" />
         </div>
         <div class="space"></div>
         <div class="col-md-6 no-padding col-sm-6 col-xs-6">
             <label for="">Status</label>
             @if($getData->is_active == 1)
              <p class="show_default">Aktif</p>
             @else
             <p class="show_default">Belum Aktif</p>
             @endif
             <select name="is_active" class="form-control defaultHide">
                @if($getData->is_active == 1)
                    <option selected value="1">Aktif</option>
                    <option value="zero">Belum Aktif</option>
                @else
                    <option selected value="zero">Belum Aktif</option>
                    <option value="1">Aktif</option>
                @endif
            </select>
         </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
         </div>
             @can('view.member.bank')
         <div class="col-md-12 no-padding col-sm-12 col-xs-12">
         <label for="" class="label_head"><span class="fa fa-credit-card"></span> Informasi Bank</label>
         </div>
		<div class="col-md-6 no-padding col-sm-6 col-xs-6">
			<label for="">Nama Bank</label>
            <p class="show_default">{{ $getData->bank_name}}</p>
            <input type="text" class="form-control defaultHide" placeholder="Nama Bank" name="bank_name" value="{{ $getData->bank_name}}">
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6">
			<label for="">Nama Pemegang Kartu</label>
			<p class="show_default">{{ $getData->bank_account_name}}</p>
            <input type="text" class="form-control defaultHide" placeholder="Nama Pemegang Kartu" name="bank_account_name" value="{{ $getData->bank_account_name}}">
		</div>
		<div class="space"></div>
		<div class="col-md-6 no-padding col-sm-6 col-xs-6">
			<label for="">No. Rekening</label>
			<p class="show_default">{{ $getData->bank_account_number}}</p>
            <input type="text" class="form-control defaultHide" placeholder="No. Rekening" name="bank_account_number" value="{{ $getData->bank_account_number}}">
		</div>
		<div class="col-md-6 col-sm-6 col-xs-6">
			<label for="">Cabang</label>
			<p class="show_default">{{ $getData->bank_branch}}</p>
            <input type="text" class="form-control defaultHide" placeholder="Cabang" name="bank_branch" value="{{ $getData->bank_branch}}">
		</div>
             @endcan
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
    @can('update.member.profile')
<div class="edit-button">
  <input type="text" class="search_input" placeholder="Perbaharui data" disabled="">
  <a href="#" class="search_icon" onclick="edit_profile()"><i class="fa fa-cog md-36"></i></a>
</div>
    @endcan
    @can('update.member.staffinfo')
<div class="edit-staff">
  <input type="text" class="search_input" placeholder="Perbaharui data" disabled="">
  <a href="#" class="search_icon" onclick="edit_staff()"><i class="fa fa-cog md-36"></i></a>
</div>
    @endcan
@stop
@section('appjs')
<script>
$(document).ready(function() {
$('.edit-button').show();
// create DatePicker from input HTML element
$("#datepickerDOB").kendoDatePicker({
    // display month and year in the input
    format: "yyyy-MM-dd",

    // specifies that DateInput is used for masking the input element
    dateInput: true
  });
// create DatePicker from input HTML element
$("#datepicker").kendoDatePicker({
    // display month and year in the input
    format: "yyyy-MM-dd",

    // specifies that DateInput is used for masking the input element
    dateInput: true
  });
$("#datepicker1").kendoDatePicker({
    // display month and year in the input
    format: "yyyy-MM-dd",

    // specifies that DateInput is used for masking the input element
    dateInput: true
  });
});
$('.defaultHide').hide();
function edit_profile(){
 if($('.defaultHide').is(':hidden') == true){
    $('.show_default').hide();
    $('.defaultHide').show();
 } else {
    $('.show_default').show();
    $('.defaultHide').hide();
 }
}
function reset_form() {
    $('#edit_data_profile')[0].reset();
    $('#edit_data_staff')[0].reset();
    edit_profile();
}

// process the form
$('form').submit(function(e) {
    loading('show');
   // process the form
    $.ajax({
    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url         : '{{url('update-profile')}}', // the url where we want to POST
    data        : $('form').serialize(), // our data object
        // using the done promise callback
        success:function(data) {
        if(data.error == 1){
            PNotify.error({
                title: 'Oh No!',
                text: data.msg
            });
            setTimeout(function(){ location.reload(); }, 5000);
        } else {
            PNotify.success({
                title: 'Success!',
                text: data.msg,
              });
            loading('hide');
            $('form')[0].reset();
            setTimeout(function(){ location.reload(); }, 3000);
        }
        },
        // handling error code
        error: function (data) {
        loading('hide')
        PNotify.error({
            title: 'Terjadi anomali.',
            text: 'Mohon hubungi pengembang aplikasi untuk mengatasi masalah ini.',
            type: 'error'
        });
      }
    });
    // stop the form from submitting the normal way and refreshing the page
    e.preventDefault();
});
// process the form
function update_staff(e){
    loading('show');
   // process the form
    $.ajax({
    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url         : '{{url('update-staff')}}', // the url where we want to POST
    data        : {
                    "_token":"{{ csrf_token() }}",
                    "region_id":$('#region_id').val(),
                    "branch_id":$('#branch_id').val(),
                    "project_id":$('#project_id').val(),
                    "email":"{{ $getData->email }}",
                    },
        // using the done promise callback
        success:function(data) {
        if(data.error == 1){
            PNotify.error({
                title: 'Oh No!',
                text: data.msg
            });
            setTimeout(function(){ location.reload(); }, 5000);
        } else {
            PNotify.success({
                title: 'Success!',
                text: data.msg,
              });
            loading('hide');
            setTimeout(function(){ location.reload(); }, 3000);
        }
        },
        // handling error code
        error: function (data) {
        loading('hide')
        PNotify.error({
            title: 'Terjadi anomali.',
            text: 'Mohon hubungi pengembang aplikasi untuk mengatasi masalah ini.',
            type: 'error'
        });
      }
    });
    // stop the form from submitting the normal way and refreshing the page
};
function rst_tab(argument) {
    $('.show_default').show();
    $('.defaultHide').hide();
    $('.edit-button').show();
    $('.edit-staff').hide();
}
function rst_tab1(argument) {
    $('#edit_data_staff select').hide();
    $('#edit_data_staff #hidden').hide();
    $('.edit-button').hide();
    $('.edit-staff').show();
    $('.show_default').show();
    $('.defaultHide').hide();
}
function edit_staff(el) {
    if($('#edit_data_staff select').is(':hidden') == true){
        $('#edit_data_staff select').show();
        $('#edit_data_staff #hidden').show();
     } else {
       $('#edit_data_staff select').hide();
       $('#edit_data_staff #hidden').hide();
     }
}
$('#region_id').on('change', function() {
var region_id = $(this).val();

if(region_id) {
    $.ajax({
        url: '{{url('get/projects')}}',
        type: "GET",
        data : {
            "_token":"{{ csrf_token() }}",
            "region_id": region_id
        },
        dataType: "json",
        success:function(data) {
            if(data.project){
                $('#project_id').empty();
                $('#project_id').focus;
                $('#project_id').append('<option value="">Pilih Lokasi Proyek</option>');
                $.each(data.project, function(key, value){
                    $('select[name="project_id"]').append('<option value="'+ value.id +'">'+ value.project_code +' - '+ value.project_name + '</option>');
                });
            }else{
                $('#project_id').empty();
            }

        if(data.branch){
            $('#branch_id').empty();
            $('#branch_id').focus;
            $('#branch_id').append('<option value="">Pilih Lokasi Cabang</option>');
            $.each(data.branch, function(keys, values){
                $('select[name="branch_id"]').append('<option value="'+ values.id +'">'+ values.branch_code +' - '+ values.branch_name + '</option>');
            });
        }else{
            $('#branch_id').empty();
        }
    }
    });
}else{
$('#project_id').empty();
$('#branch_id').empty();

}
});
</script>
@stop
