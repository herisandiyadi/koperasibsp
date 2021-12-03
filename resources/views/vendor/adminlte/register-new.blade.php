@extends('adminlte::master-register')

@section('adminlte_css')
    @yield('css')
@stop
@section('body')

	<div class="wrapper">
		<form action="" id="wizard">
			<!-- SECTION 1 -->
			<h2></h2>
			<section>
				<div class="inner">
					<div class="image-holder">
						<img src="images/form-wizard-1.jpg" alt="">
					</div>
					<div class="form-content" >
						<div class="form-header">
							<h3>Registration</h3>
						</div>
						<p>Informasi Pegawai</p>
						<div class="form-row">
							<div class="form-holder" style="width: 100%">
								<input type="text" placeholder="Nama Lengkap" id="nama" class="form-control" required="required">
							</div>

						</div>
						<div class="form-row">
							<div class="form-holder">
								<input type="number" placeholder="Nomor KTP" class="form-control" required="required">
							</div>
							<div class="form-holder">
								<input type="text" placeholder="Nomor Kepegawaian" class="form-control" required="required">
							</div>
						</div>
						<div class="form-row">
							<div class="form-holder">
								<input type="email" placeholder="Email" class="form-control" required="required">
							</div>
							<div class="form-holder">
								<input type="password" placeholder="Password" class="form-control" required="required">
							</div>
						</div>

					</div>
				</div>
			</section>

			<!-- SECTION 2 -->
			<h2></h2>
			<section>
				<div class="inner">
					<div class="image-holder">
						<img src="images/form-wizard-2.jpg" alt="">
					</div>
					<div class="form-content">
						<div class="form-header">
							<h3>Registration</h3>
						</div>
						<p>Informasi Simpanan</p>
						<div class="form-row">
							<div class="select">
								<div class="form-holder">
									<div class="select-control">Simpanan Wajib Sebesar</div>
									<i class="zmdi zmdi-caret-down"></i>
								</div>
								<ul class="dropdown">
									<li rel="United States">United States</li>
									<li rel="United Kingdom">United Kingdom</li>
									<li rel="Viet Nam">Viet Nam</li>
								</ul>
							</div>
							<div class="select">
								<div class="form-holder">
									<div class="select-control">Potongan Wajib</div>
									<i class="zmdi zmdi-caret-down"></i>
								</div>
								<ul class="dropdown">
									<li rel="United States">United States</li>
									<li rel="United Kingdom">United Kingdom</li>
									<li rel="Viet Nam">Viet Nam</li>
								</ul>
							</div>
						</div>
						<div class="form-row">
							<div class="form-holder w-100">
								<input type="number" placeholder="Jumlah Simpanan" class="form-control">
							</div>
						</div>

					</div>
				</div>
			</section>


		</form>
	</div>
@stop
