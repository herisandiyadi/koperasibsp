@extends('adminlte::master')

@section('adminlte_css')
    @yield('css')
@stop

@section('body_class', 'register-page')

@section('body')

<div class="container" style="margin-top: 2em; margin-bottom: 2em;">
    <!-- Sign up form -->
    <form method="POST"  id="form-register">

        <!-- Sign up card -->
        <div class="card person-card">
            <div class="card-body">
                <!-- Sex image -->
                <img id="img_sex" class="person-img"
                    src="{{{asset('images/bsp.png')}}}">
                <div id="who_message" class="card-title">Pendaftaran Anggota Koperasi</div>
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
            </div>
        </div>

        <div class="row">
            <div class="col-md-4" style="padding=0.5em;">
                <div class="card">
                    <div class="card-body">
						<div class="card-title">Informasi Kepegawaian</div>
						<hr/>

						<div class="form-group">
							<label>Nomor KTP</label>
							<input type="text" class="form-control" name="nik"  id="nik" placeholder="Masukkan nomor ktp" required />
						</div>
						<div class="form-group">
							<label>NIK BSP</label>
							<input type="text" class="form-control" name="nik_bsp"  id="nik_bsp" placeholder="Masukkan nik BSP" required />
							{{csrf_field()}}
						</div>
						<div class="form-group">
							<label>Nama Lengkap</label>
							<input type="text" class="form-control" name="fullname" placeholder="Masukkan nama Lengkap" required />
						</div>
{{--						<div class="form-group">--}}
{{--							<label>Jabatan</label>--}}
{{--							<select name="position_id" id="position_id" class="form-control" required>--}}
{{--								<option value="">Pilih Jabatan</option>--}}
{{--								@foreach ($position as $item)--}}
{{--									<option value="{{ $item->name }}">{{ $item->name }}</option>--}}
{{--								@endforeach--}}
{{--							</select>--}}
{{--						</div>--}}

						<!-- <div class="form-group">
							<label>Area Kerja</label>
							<select name="region_id" id="region_id" class="form-control" required>
								<option value="">Pilih Area Kerja</option>
								@foreach ($region as $item)
									<option value="{{ $item->id }}">{{ $item->name_area }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label>Cabang</label>
							<select name="branch_id" id="branch_id" class="form-control" required>
								<option value="">Pilih Lokasi Cabang</option>
							</select>
						</div>

						<div class="form-group">
							<label>Proyek</label>
							<select name="project_id" id="project_id" class="form-control" required>
								<option value="">Pilih Lokasi Proyek</option>
							</select>
						</div> -->
						</div>
		                </div>
			</div>
			<div class="col-md-4" style="padding=0.5em;">

			<div class="card">
		                    <div class="card-body">
								<div class="card-title">Informasi Simpanan</div>
								<hr/>
								<div class="form-group">
									Mendaftar sebagai anggota koperasi Security BSP bersedia membayar simpanan pokok sebesar Rp. {{ number_format($pokok->deposit_minimal) }}
									dibayar melalui pemotongan gaji :<br/><br/>
									<label>Simpanan pokok sebesar :</label>
									<select name="pemotongan" id="pemotongan" class="form-control" required>
										<option value="">Pilih Pemotongan</option>
										<option value="1">1X Gaji</option>
										<option value="2">2X Gaji</option>
									</select>
								</div>
								<div style="background: #ededed !important; padding: 10px;">
								<div class="form-group">
									<label>Simpanan wajib sebesar :</label>
									<select name="wajib" id="wajib" class="form-control" required onchange="selectWajib(this);">
										<option value="">Pilih Simpanan Wajib</option>
										<option value="50000">Rp. 50.000</option>
										<option value="100000">Rp. 100.000</option>
										<option value="200000">Rp. 200.000</option>
										<option value="other">Nominal Lain</option>

									</select>

									<div id="nominalLain" style="display: none;">
									<br/>
										<label>Nominal Lain</label>
										<input type="text" id="wajibLain" name="wajib" class="form-control" placeholder="Masukan nominal wajib" />
									</div>
								</div>
								</div>
								<div class="form-group">
									<label>Simpanan sukarela sebesar :</label>
									<input type="number" class="form-control" name="sukarela" placeholder="Masukan nominal sukarela" value='0' required />
								</div>
		                    </div>
						</div>
            </div>
            <div class="col-md-4">
              <!--   <div class="card">
                    <div class="card-body">
						<div class="card-title">Informasi Simpanan</div>
						<hr/>
						<div style="background: #ededed !important; padding: 10px;">
						<div class="form-group">
							<label>Simpanan wajib sebesar :</label>
							<select name="wajib" id="wajib" class="form-control" required onchange="selectWajib(this);">
								<option value="">Pilih Simpanan Wajib</option>
								<option value="50000">Rp. 50.000</option>
								<option value="100000">Rp. 100.000</option>
								<option value="200000">Rp. 200.000</option>
								<option value="other">Nominal Lain</option>

							</select>

							<div id="nominalLain" style="display: none;">
							<br/>
								<label>Nominal Lain</label>
								<input type="text" id="wajibLain" name="wajib" class="form-control" placeholder="Masukan nominal wajib" />
							</div>
						</div>
						</div>
						<br/>
                        <div class="form-group">
							Mendaftar sebagai anggota koperasi Security BSP bersedia membayar simpanan pokok sebesar Rp. {{ number_format($pokok->deposit_minimal) }}
							dibayar melalui pemotongan gaji :<br/><br/>
							<label>Simpanan pokok sebesar :</label>
							<select name="pemotongan" id="pemotongan" class="form-control" required>
								<option value="">Pilih Pemotongan</option>
								<option value="1">1X Gaji</option>
								<option value="2">2X Gaji</option>
							</select>
						</div>
						<div class="form-group">
							<label>Simpanan sukarela sebesar :</label>
							<input type="number" class="form-control" name="sukarela" placeholder="Masukan nominal sukarela" value='0' required />
						</div>
                    </div>
				</div> -->

				<div class="card">
                    <div class="card-body">
						<div class="card-title">Informasi Akun Login</div>
						<hr/>
                        <div class="form-group">
							<label>Email</label>
							<input type="text" class="form-control" name="email" id="email" placeholder="Masukkan alamat email" required />
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" name="password" id="password" placeholder="password" required />
						</div>


						<div class="form-group">
							<div class="col-md-12" style="padding: 0px;">
							<input type="checkbox" id="agree" /> <label data-toggle="modal" data-target="#exampleModal">Persyaratan anggota baru, setuju ?</label>
							</div>
						</div>
						<br/>
						<br/>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block" id="submit_postcode" value="submit">Daftar</button>
						</div>
						<div class="form-group">
							<div class="col-md-12" style="padding: 0px; text-align:right;">
								<label>Sudah punya akun ? <a href="{{ asset('login') }}" style="color:#6ec7d1"> login disini !</a></label>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $policy->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $policy->description !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button"  onclick="setuju()" class="btn btn-primary">Setuju</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('adminlte_js')
    @yield('js')
    <script>

        function setuju() {
            document.getElementById("agree").checked = true;
            $('#submit_postcode').removeAttr('disabled');
        }
		function selectWajib(that) {
				if (that.value == "other") {
					document.getElementById("nominalLain").style.display = "block";
					document.getElementById("nominalLain").setAttribute = "required"
					document.getElementById("wajibLain").removeAttribute("disabled");
					document.getElementById("wajib").removeAttribute("required");
					document.getElementById("wajibLain").setAttribute("required", "required");


				} else {
					document.getElementById("nominalLain").style.display = "none";
					document.getElementById("nominalLain").setAttribute = "";
					document.getElementById("wajibLain").setAttribute("disabled", "disabled");

				}
			}
        $(document).ready(function(){

			$('#submit_postcode').attr('disabled', 'disabled');
         $('#agree').change(function () {
            if(this.checked) {
                $('#submit_postcode').removeAttr('disabled');
            }else{
                $('#submit_postcode').attr('disabled', 'disabled');
            }
         });
          // process the form
        $('form').submit(function(e) {
        	$( "#overlay" ).show(3000);
           // process the form
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : '{{url('daftar')}}', // the url where we want to POST
                // data        : {
                // 	logo:new FormData($("form")[0])
                // }
                data        : $('form').serialize(),
                // $('form').serialize(), // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                            encode          : true,
                // using the done promise callback
			    success:function(data) {
			      if(data.error == 1){
                        PNotify.error({
                        title: 'Oh No!',
                        text: data.msg
                      });
                      $( "#overlay" ).hide(3000);
                      if(data.status == 'email'){
                          $('#email').val('');
                          $('#password').val('');
                        }else{
                          $('#nik_bsp').val('');
                        }

                      $('#submit_postcode').prop("disabled", false);
                      $('#submit_postcode').html('Daftar');
                      $('#submit_postcode').removeClass('submitted');
                    } else {
                        PNotify.success({
                            title: 'Success!',
                            text: data.msg,
                          });
                        $( "#overlay" ).show(3000);
                        $('form')[0].reset();
                        setTimeout(function(){ window.location.href= '{{route("login")}}'; }, 5000);
                    }
			    },
			    // handling error code
			    error: function (data) {
			      $( '#overlay' ).hide(3000);
			        PNotify.error({
			            title: 'Terjadi anomali.',
			            text: 'Mohon hubungi pengembang aplikasi untuk mengatasi masalah ini.',
			            type: 'error'
			        });
                    $('#submit_postcode').prop("disabled", false);
                    $('#submit_postcode').html('Daftar');
                    $('#submit_postcode').removeClass('submitted');
			      }
            });
            // stop the form from submitting the normal way and refreshing the page
            e.preventDefault();
        });

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
							console.log(data);
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


        });
        initSelect("select");
        $('#form-register input').on('change', function(){
            var potongan = $('input[name=potong]:checked', '#form-register').val();
            if(potongan != 0){
                $('#nominal_lainnya').css('display', 'none');
            }else{
                $('#nominal_lainnya').css('display', 'block');
            }
        });
    </script>
@stop
