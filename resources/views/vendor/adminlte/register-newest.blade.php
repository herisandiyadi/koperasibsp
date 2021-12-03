@extends('adminlte::master-register-new')

@section('adminlte_css')
    @yield('css')
@stop
@section('body')


	<!-- Top content -->
	<div class="top-content">

		<div class="inner-bg">
			<div class="container">

				<div class="row">
					<div class="col-sm-6 col-sm-offset-3 form-box">

						<form role="form" method="post" id="form-register" class="registration-form">

							<fieldset>
								<div class="form-top">
									<div class="form-top-left">
										<h3>Step 1 / 2</h3>
										<p>Info Pegawai dan Akun :</p>
									</div>
									<div class="form-top-right">
										<i class="fa fa-user"></i>
									</div>
								</div>

								<div class="form-bottom">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="sr-only" for="ktp">No KTP</label>
											<input type="number" name="ktp" placeholder="No KTP" class="form-no-ktp form-control" id="form-no-ktp">
										</div>
										<div class="form-group col-md-6">
											<label class="sr-only" for="nik_bsp">Nomor Pegawai</label>
											<input type="number" name="nik_bsp" placeholder="Nomor Pegawai" class="form-no-pegawai form-control" id="form-no-pegawai">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-12">
											<label class="sr-only" for="fullname">Nama Lengkap</label>
											<input type="text" name="fullname" placeholder="Nama Lengkap" class="form-first-name form-control" id="form-first-name">
										</div>
									</div>
									<div class="form-group col-md-12">
										<label class="sr-only" for="email">Email</label>
										<input type="email" name="email" placeholder="Email" class="form-email form-control" id="form-email">
									</div>
									<div class="form-group col-md-12">
										<label class="sr-only" for="password">Password</label>
										<input type="password" name="password" placeholder="Password" class="form-password form-control" id="form-password">
									</div>

									<button type="button" class="btn btn-next">Selanjutnya</button>
								</div>
							</fieldset>

							<fieldset>
								<div class="form-top">
									<div class="form-top-left">
										<h3>Step 2 / 2</h3>
										<p>Set up your account:</p>
									</div>
									<div class="form-top-right">
										<i class="fa fa-key"></i>
									</div>
								</div>
								<div class="form-bottom">

									<div class="form-group">
										<label class="sr-only" for="wajib">Wajib</label>
										<select name="wajib" id="wajib" class="form-control-wajib form-control" required="required" onchange="selectWajib(this);">
											<option value="">Pilih Simpanan Wajib</option>
											<option value="50000">Rp. 50.000</option>
											<option value="100000">Rp. 100.000</option>
											<option value="200000">Rp. 200.000</option>
											<option value="other">Nominal Lain</option>

										</select>

										<div id="nominalLain" style="display: none; padding-top: 15px">
											<label class="sr-only" for="wajib">Wajib Lain</label>
											<input type="text" id="wajibLain" name="wajib" class="form-control" placeholder="Masukan nominal wajib" />
										</div>

									</div>
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
									<div class="form-group">
										<div class="col-md-6" style="padding: 0px;">
											<input type="checkbox" id="agree" /> <label>Persyaratan anggota baru, setuju?</label>
										</div>
										<div class="col-md-6" style="padding: 0px; text-align:right;">
											<label>Sudah punya akun ? <a href="{{ asset('login') }}" style="color:#6ec7d1"> login disini !</a></label>
										</div>
									</div>
									<button type="button" class="btn btn-previous">Kembali</button>
									<button type="submit" id="submit_postcode" value="submit" class="btn">Daftar</button>
								</div>
							</fieldset>


						</form>

					</div>
				</div>
			</div>
		</div>

	</div>
@stop

@section('adminlte_js')
	@yield('js')
	<script>
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
