<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?= $appTitle ?> | <?= $pageTitle ?></title>
		<base href="<?= $domain ?>">

		<link rel="stylesheet" href="assets/css/<?= $assets['vendor.css'] ?>">
		<link rel="stylesheet" href="assets/css/<?= $assets['app.css'] ?>">

		<link rel="apple-touch-icon" sizes="180x180" href="assets/icon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="assets/icon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="assets/icon/favicon-16x16.png">
		<link rel="manifest" href="assets/icon/site.webmanifest">

		

</head>
<body data-init="<?= $appData ?>" class="navbar-top <?= $dark_theme ?>">

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-light navbar-static fixed-top navbar-bottomline">

		<!-- Header with logos -->
		<div class="navbar-header d-none d-md-flex align-items-md-center">
			<div class="navbar-brand">
				<a href="" class="d-inline-block logo  text-dark">
					<img src="assets/images/logo_only.png" alt="">
					<span class="app-title"><b>Account</b></span>
				</a>
			</div>
		</div>
		<!-- /header with logos -->


		<!-- Mobile controls -->
		<div class="d-flex flex-1 d-md-none">
			<div class="navbar-brand mr-auto">
				<a href="" class="d-inline-block logo  text-dark">
					<img src="assets/images/logo_only.png" alt="">
					<span class="app-title"><b>Account</b></span>
				</a>
			</div>

			<!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button> -->

			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>
		<!-- /mobile controls -->


		<!-- Navbar content -->
		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>

			<div class="ml-md-3 mr-md-auto"></div>

			<ul class="navbar-nav">
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<img src="pic/acc/<?= $account->uid ?>" class="rounded-circle mr-2" height="28" alt="">
						<span><?= $account->name ?></span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="account" class="dropdown-item"><i class="far fa-user-circle"></i> My Account</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item doLogoutApp"><i class="icon-switch2"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>
		<!-- /navbar content -->

	</div>
	<!-- /main navbar -->

	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-light sidebar-main sidebar-fixed sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user-material">
					<div class="sidebar-user-material-body d-md-none">
						<div class="card-body text-center">
							<a href="#">
								<img src="pic/acc/<?= $account->uid ?>" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
							</a>
							<h4 class="mb-0 text-white"><?= $account->name ?></h4>
							<span class="font-size-sm text-white"><?= $account->email ?></span>
						</div>

						<div class="sidebar-user-material-footer">
							<a href="#user-nav" class="d-flex justify-content-between align-items-center dropdown-toggle" data-toggle="collapse"><span>My account</span></a>
						</div>
					</div>

					<div class="collapse" id="user-nav">
						<ul class="nav nav-sidebar">
							<li class="nav-item">
								<a href="account" class="nav-link">
									<i class="far fa-user-circle"></i>
									<span>My Account</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link doLogoutApp">
									<i class="icon-switch2"></i>
									<span>Logout</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- /user menu -->

				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<?php foreach ($list_menus as $menu) { ?>
						<?php if ($menu['show']) { ?>

							<?php if ($menu['header']) { ?>
							<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs"><?= $menu['title'] ?></div> <i class="icon-menu" title="<?= $menu['title'] ?>"></i></li>
							<?php } else { ?>
							<li class="nav-item <?php if ($menu['menu']) { ?>nav-item-submenu<?php } ?> <?php if ($menu['active'] == 'active' && $menu['menu']) { ?>nav-item-open<?php } ?>">
								<a href="<?= $menu['url'] ?>" class="nav-link <?= $menu['active'] ?>">
									<i class="<?= $menu['icon'] ?>"></i> <span><?= $menu['title'] ?></span>
								</a>

								<?php if ($menu['menu']) { ?>
								<ul class="nav nav-group-sub" data-submenu-title="<?= $menu['title'] ?>" <?php if ($menu['active'] == 'active') { ?>style="display:block;"<?php } ?>>
								<?php foreach ($menu['menu'] as $submenu) { ?>
									<?php if ($submenu['show']) { ?>
										<?php if ($submenu['menu']) { ?>
											<li class="nav-item nav-item-submenu <?php if ($submenu['active'] == 'active') { ?>nav-item-open<?php } ?>">
												<a href="#" class="nav-link <?= $submenu['active'] ?>"><?= $submenu['title'] ?></a>
												<ul class="nav nav-group-sub"  <?php if ($submenu['active'] == 'active') { ?>style="display:block;"<?php } ?>>
													<?php foreach ($submenu['menu'] as $childmenu) { ?>
													<li class="nav-item"><a href="<?= $childmenu['url'] ?>" class="nav-link <?= $childmenu['active'] ?>"><?= $childmenu['title'] ?></a></li>
													<?php } ?>
												</ul>
											</li>
										<?php } else { ?>
											<li class="nav-item"><a href="<?= $submenu['url'] ?>" class="nav-link <?= $submenu['active'] ?>"><?= $submenu['title'] ?></a></li>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								</ul>
								<?php } ?>
							</li>
							<?php } ?>

						<?php } ?>
						<?php } ?>

					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->

		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			
			<div class="page-header">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex pt-2 pb-2">
						<h4><i class="<?= $page['icon'] ?> mr-2"></i> <span class="font-weight-semibold"><?= $pageTitle ?></span></h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">

				

<div class="d-md-flex align-items-md-start">

    <!-- Left sidebar component -->
    <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-200 border-0 shadow-0 sidebar-expand-md">

        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Navigation -->
            <div class="card">
                <div class="card-body bg-grey text-center card-img-top">
                    <div class="card-img-actions d-inline-block mb-3">
                        <img class="account-avatar img-fluid rounded-circle" src="pic/acc/<?= $account->uid ?>" width="170" height="170" alt="">

                        <div class="card-img-actions-overlay rounded-circle">
                            <a data-field="avatar" href="javascript:;" class="btn btn-outline bg-white btn-set-image acc-avatar text-white border-white border-2 btn-icon rounded-round">
                                <i class="icon-pencil3"></i>
                            </a>
                            <a data-field="avatar" href="javascript:;" class="btn ml-1 btn-outline bg-white btn-remove-image acc-avatar text-white border-white border-2 btn-icon rounded-round">
                                <i class="icon-trash"></i>
                            </a>
                        </div>

                    </div>

                    <h6 class="font-weight-semibold mb-0" data="name"><?= $account->name ?></h6>
                    <span class="d-block opacity-75" data="email"><?= $account->email ?></span>


                </div>

                <div class="card-body p-0">
                    <ul class="nav nav-sidebar mb-2">
                        <li class="nav-item-header">Menu</li>
                        <li class="nav-item">
                            <a href="#profile" class="nav-link legitRipple active show" data-toggle="tab"><i class="icon-user"></i> Informasi Akun</a>
                        </li>
                        <li class="nav-item">
                            <a href="#password" class="nav-link legitRipple" data-toggle="tab"><i class="icon-lock"></i> Ubah Password </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /navigation -->

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /left sidebar component -->

    <div class="card d-md-none">
        <div class="card-body bg-grey text-center card-img-top">
            <div class="card-img-actions d-inline-block">
                <img class="account-avatar img-fluid rounded-circle" src="pic/acc/<?= $account->uid ?>" width="170" height="170" alt="">

                <div class="card-img-actions-overlay rounded-circle">
                    <a data-field="avatar" href="javascript:;" class="btn btn-outline bg-white btn-set-image acc-avatar text-white border-white border-2 btn-icon rounded-round">
                        <i class="icon-pencil3"></i>
                    </a>
                    <a data-field="avatar" href="javascript:;" class="btn ml-1 btn-outline bg-white btn-remove-image acc-avatar text-white border-white border-2 btn-icon rounded-round">
                        <i class="icon-trash"></i>
                    </a>
                </div>

            </div>
            <div class="d-flex justify-content-center mb-3 mt-1">
                <a data-field="avatar" class="text-white cursor-pointer btn-set-image acc-avatar">
                    Upload Photo
                </a>
                <a data-field="avatar" class="text-white cursor-pointer btn-remove-image acc-avatar ml-2">
                    Hapus
                </a>
            </div>


            <h6 class="font-weight-semibold mb-0" data="name"><?= $account->name ?></h6>
            <span class="d-block opacity-75" data="email"><?= $account->email ?></span>


        </div>
    </div>

    <ul class="nav nav-tabs nav-tabs-highlight d-md-none nav-tabs-header">
        <li class="nav-item"><a href="#profile" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab"><!-- <i class="icon-user"></i>  -->Informasi Akun</a></li>
        <li class="nav-item"><a href="#password" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><!-- <i class="icon-lock"></i>  -->Ubah Password</a></li>
    </ul>

    <!-- Right content -->
    <div class="tab-content w-100 overflow-auto">

        <div class="tab-pane fade active show" id="profile">

            <div class="card">
                <div class="card-header header-elements-inline with-border-light d-none d-sm-block">
                    <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">Informasi Akun</h6>
                </div>
                <div class="card-body with-padding-20">

                    <form class="">
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right">Username</label>
                                    <div class="col-lg-9">
                                        <div class="form-control-plaintext with-border"><?= $account->username ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right">Nama Akun</label>
                                    <div class="col-lg-9">
                                        <div class="form-control-plaintext with-border">
                                            <span data="name">.</span>
                                            <a href="javascript:;" class="float-right btn-edit-nama btn-flat tooltips" title="Edit Nama AKun"><i class="icon-pencil7"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right">Email</label>
                                    <div class="col-lg-9">
                                        <div class="form-control-plaintext email-verified with-border">
                                            <span data="email">.</span>
                                            <a href="javascript:;" class="float-right btn-edit-email btn-flat tooltips" title="Edit Alamat Email"><i class="icon-pencil7"></i></a>
                                        </div>

                                        <div class="form-control-plaintext email-unverified with-border" style="display: none;">
                                            <span data="email_unverified">.</span>
                                            <a href="javascript:;" class="float-right btn-edit-email btn-flat tooltips" title="Edit Alamat Email"><i class="icon-pencil7"></i></a>
                                            <a href="javascript:;" style="padding-right: 5px;" data-type="email" class=" float-right btn-remove btn-flat tooltips" title="Hapus"><i class="icon-trash"></i></a>
                                            <a href="javascript:;" style="padding-right: 10px;" data-type="email" class="float-right btn-verifikasi btn-flat tooltips" title="Verifikasi Alamat Email"><i class="icon-check"></i>Verifikasi</a href="javascript:;">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right">Nomor Telpon</label>
                                    <div class="col-lg-9">
                                        <div class="form-control-plaintext phone-verified with-border">
                                            <span data="phone">.</span>
                                            <a href="javascript:;"  class=" float-right btn-edit-phone btn-flat tooltips" title="Edit Nomor Telpon"><i class="icon-pencil7"></i></a>
                                        </div>

                                        <div class="form-control-plaintext phone-unverified with-border" style="display: none;">
                                            <span data="phone_unverified">.</span>
                                            <a href="javascript:;" class=" float-right btn-edit-phone btn-flat tooltips" title="Edit Nomor Telpon"><i class="icon-pencil7"></i></a>
                                            <a href="javascript:;" style="padding-right: 5px;" data-type="phone" class=" float-right btn-remove btn-flat tooltips" title="Hapus"><i class="icon-trash"></i></a>
                                            <a href="javascript:;" style="padding-right: 10px;" data-type="phone"  class=" float-right btn-verifikasi btn-flat tooltips" title="Verifikasi Nomor Telpon"><i class="icon-check"></i>Verifikasi</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row mb-3">
                                    <label class="col-form-label col-lg-3 text-lg-right text-pink-700 text-uppercase font-weight-bold">Verifikasi OTP</label>
                                    <div class="col-lg-9">
                                        <select class="form-control" id="select-otp-media" name="otp_media">
                                            <?php foreach ($list_otp as $item) { ?>
                                            <?php if ($item['id'] == 'none') { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                            <?php } elseif ($item['id'] != 'oauth') { ?>
                                            <option value="<?= $item['id'] ?>">Dikirim via <?= $item['name'] ?></option>
                                            <?php } else { ?>
                                            <option value="<?= $item['id'] ?>">Menggunakan <?= $item['name'] ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="offset-lg-3 col-lg-9">
                                        <span class="text-muted">Verifikasi OTP akan digunakan jika Aplikasi juga menggunakan OTP</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="offset-lg-3 col-lg-9 otp-media oauth" style="display: none;">
                                        <a id="view-oauth-code" href="javascript:;">Klik disini untuk melihat QR Code</a>
                                    </div>
                                    <div class="offset-lg-3 col-lg-9 otp-media non-oauth" style="display: none;">
                                        <span class="text-muted">Email atau Nomor Telpon harus terverifikasi terlebih dahulu jika digunakan sebagai media OTP.</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>

                </div>
            </div>


        </div>

        <div class="tab-pane fade" id="password">

            <div class="card">
                <div class="card-header header-elements-inline with-border-light d-none d-sm-block">
                    <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">Ubah Password</h6>
                </div>
                <div class="card-body with-padding-20">

                    <form class="" id="form_password">
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right">Password Lama</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="Password Lama">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right">Password Baru</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="password" id="input_password" placeholder="Password Baru">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right">Konfirmasi Password</label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Konfirmasi Password Baru">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-10 mx-lg-auto">
                                <div class="row">
                                    <label class="col-form-label col-lg-3 text-lg-right"></label>
                                    <div class="col-lg-9">
                                        <button type="button" class="btn btn-save btn-sm btn-primary">Simpan <i class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
    <!-- /right content -->

</div>

<div class="modal fade" id="modal-account">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-pencil7"></i> Edit Nama Akun</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form" id="form_name">
                <input type="hidden" value="name" name="field">
                <p>Masukkan nama akun</p>
                <div class="mb-2">
                    <input type="text" name="value" class="form-control contact-value" placeholder="Nama Akun">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i> Batal</button>
            <button type="button" class="btn btn-save-modal btn-xs btn bg-blue"><i class="fa fa-save"></i> Simpan</button>
    </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-email">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-pencil7"></i> Edit Email</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form" id="form_email">
                <input type="hidden" value="email" name="type">
                <p>Masukkan alamat email</p>
                <div class="mb-2">
                    <input type="email" name="value" class="form-control contact-value" placeholder="Alamat Email">
                </div>
                <p>Kode verifikasi akan dikirimkan ke alamat email</p>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i> Batal</button>
            <button type="button" class="btn btn-save-modal btn-xs btn bg-blue"><i class="fa fa-save"></i> Simpan</button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="modal-phone">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-pencil7"></i> Edit Nomor Telpon</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form" id="form_phone">
                <input type="hidden" value="phone" name="type">
                <p>Masukkan nomor telepon</p>
                <div class="mb-2">
                    <input type="phone" name="value" class="form-control contact-value" placeholder="Nomor Telepon">
                </div>
                <p>Kode verifikasi akan dikirimkan ke nomor telepon diatas</p>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i> Batal</button>
            <button type="button" class="btn btn-save-modal btn-xs btn bg-blue"><i class="fa fa-save"></i> Simpan</button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="modal-Verifikasi">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-check"></i> Verifikasi</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form" id="form_verify">
                <input type="hidden" value="" name="type">
                <p>
                    Masukkan Kode Verifikasi yang telah dikirimkan melalui <span class="verifikasi_media"></span>
                </p>
                <div class="mb-2">
                    <input type="text" name="token" class="form-control code-value  text-center input-box" placeholder="Kode Verifikasi">
                </div>
                <a href="javascript:;" class="btn-resend-verifikasi"> Kirim Ulang Kode <i class="icon-paperplane"></i></a>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i> Batal</button>
            <button type="button" class="btn btn-save-modal btn-xs btn bg-blue"><i class="fa fa-save"></i> Verifikasi</button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="modal-oauth">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-check"></i> Verifikasi OTP Authenticator</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form" id="form_oauth">
                <p>
                    Silakan unduh <b>Aplikasi Authenticator</b> (Seperti <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google Authenticator</a> atau <a target="_blank" href="https://play.google.com/store/apps/details?id=com.azure.authenticator">Microsoft Authenticator</a>) di HP anda<br>kemudian scan QR Code berikut<br>
                </p>
                <div class="text-center">
                    <img id="img-oauth" src="" class="img-responsive">
                </div>
                <div class="mb-2">
                    <input type="text"  name="token" class="form-control code-value text-center input-box" placeholder="Kode Verifikasi">
                </div>
                <p>
                    Masukkan Kode yang anda dapat di Aplikasi Authenticator untuk verifikasi<br>
                </p>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i> Batal</button>
            <button type="button" class="btn btn-save-modal btn-xs btn bg-blue"><i class="fa fa-save"></i> Verifikasi</button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="modal-oauth-code">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-check"></i> OTP Authenticator QR Code</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form">
                <p>
                    Silakan unduh <b>Aplikasi Authenticator</b> (Seperti <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google Authenticator</a> atau <a target="_blank" href="https://play.google.com/store/apps/details?id=com.azure.authenticator">Microsoft Authenticator</a>) di HP anda<br>kemudian scan QR Code berikut<br>
                </p>
                <div class="text-center mb-2">
                    <img id="img-oauth-code" src="" class="img-responsive">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i> Tutup</button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="modal-upload" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="icon-check"></i>
                    Upload <span>Photo Akun</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="min-height:100px;">
                <form action="#" id="form-upload">
                    <input type="hidden" name="field">
                    <input type="file" style="display: none;" name="upload" class="upload-file" accept="image/*">

                    <div class="upload-box">
                        <div class="upload-preview">
                            <img class="">
                        </div>
                        <div class="upload-info">
                            <span>Klik untuk memilih berkas</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-block">
                <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i>
                    Batal</button>
                <button type="button" class="btn btn-upload btn-xs float-right btn-primary"><i class="icon-paperplane"></i>
                    Simpan</button>
                <button type="button" class="btn btn-reset-upload float-right btn-success btn-xs btn"><i class="icon-reset"></i>
                    Reset</button>
            </div>
        </div>
    </div>
</div>


			</div>
			<!-- /content area -->


			<!-- Footer -->
			<footer class="navbar navbar-expand-lg navbar-light">
				<div class="d-flex justify-content-between align-items-center w-100" id="navbar-footer">
					<span class="navbar-text">
						<b><?= $appTitle ?></b> © 2022
					</span>
					<span class="ml-lg-auto">
						Developed By <b>ElangMerah</b>
					</span>
				</div>
			</footer>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	<script src="assets/js/<?= $assets['vendor.js'] ?>"></script>
    <script src="assets/js/<?= $assets['app.js'] ?>"></script>

	
<script>jQuery(document).ready(function() {AccountProfile.init();});</script>

</body>
</html>
