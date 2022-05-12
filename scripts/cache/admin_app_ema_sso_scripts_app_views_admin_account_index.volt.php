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
				<a href="" class="d-inline-block logo text-dark">
					<img src="assets/images/logo_only.png" alt="">
					<span class="app-title"><b>Admin Console</b></span>
				</a>
			</div>
		</div>
		<!-- /header with logos -->


		<!-- Mobile controls -->
		<div class="d-flex flex-1 d-md-none">
			<div class="navbar-brand mr-auto">
				<a href="" class="d-inline-block logo  text-dark">
					<img src="assets/images/logo_only.png" alt="">
					<span class="app-title"><b>Admin Console</b></span>
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
						<img src="<?= $accountUrl ?>pic/acc/<?= $account->uid ?>" class="rounded-circle mr-2" height="28" alt="">
						<span><?= $account->name ?></span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?= $accountUrl ?>account" target="_blank"  class="dropdown-item"><i class="far fa-user-circle"></i> My Account</a>
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
								<img src="<?= $accountUrl ?>pic/acc/<?= $account->uid ?>" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
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
								<a href="<?= $accountUrl ?>account" target="_blank" class="nav-link">
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
			<div class="content pt-2">

				
<div class="multi-view" id="datagrid-view" style="display: none">
    <div class="card">
        <div class="card-header header-elements-inline with-border-light">
            <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                <i class="fas fa-users mr-1"></i> Account List
            </h6>
            <div class="header-elements">
                <button type="button" class="btn btn-xs btn-primary btn-add-akun tooltips" title="Tambah Akun User"><i class="icon-plus2 mr-1"></i> Tambah</button>
            </div>
        </div>

        <div class="card-body pt-0">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <select class="form-control select2-with-icon select-app filter-select" data-fouc data-placeholder="Aplikasi..." data-allow-clear="true">
                                <option value=""></option>
                                <?php foreach ($list_aplikasi as $item) { ?>
                                    <option data-icon="<?= $item['icon'] ?>" value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <select class="form-control select2-with-icon select-role filter-select" data-fouc data-placeholder="Role..." data-allow-clear="true">
                                <option value=""></option>
                                <?php foreach ($list_role as $item) { ?>
                                    <option data-icon="<?= $item['icon'] ?>" value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-1">
                            <select class="form-control select2 select-status filter-select" data-fouc data-placeholder="Status..." data-allow-clear="true">
                                <option value=""></option>
                                <?php foreach ($list_status as $item) { ?>
                                    <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-10">
                        <div class="form-group mb-1">
                            <input class="form-control input-nama filter-input" placeholder="Nama / Email">
                        </div>
                    </div>
                    <div class="col-md-1 col-1">
                        <button class="btn btn-filter btn-outline-primary"><i class="icon-search4"></i> </button>
                    </div>
                </div>

            </form>
        </div>

        <div class="w-100 table-container">
            <table class="table table-account table-condensed table-hover table-cursor">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th width="250">Nama Akun</th>
                        <th>Email</th>
                        <th width="120">Role</th>
                        <th width="130">Tanggal Gabung</th>
                        <th width="50">Status</th>
                        <th width="30"><i class="icon-iphone text-muted"></i></th>
                        <th width="40"><i class="icon-menu"></i></th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

<div class="multi-view" id="create-view" style="display: none">
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-200 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Navigation -->
                <div class="card">
                    <div class="d-none d-sm-block">
                        <button type="button" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple  text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
                    </div>

                    <div class="card-body text-center card-img-top">
                        <div class="card-img-actions d-inline-block p-3">
                            <i class="far fa-address-book fa-5x text-muted"></i>
                        </div>

                        <div class="mb-1 font-weight-bold">
                            AKUN USER BARU
                        </div>

                    </div>

                </div>
                <!-- /navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->

        <div class="d-md-none">
            <button type="button" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
        </div>

        <div class="card card-body d-md-none p-1">

            <div class="d-flex justify-content-start">
                <div class="mr-3 p-2">
                    <i class="far fa-address-book fa-2x text-muted"></i>
                </div>

                <div class="d-flex align-items-center">
                    <div class="mb-1 font-weight-bold">
                        AKUN USER BARU
                    </div>
                </div>

            </div>
        </div>

        <!-- Right content -->
        <div class="tab-content tab-application w-100">

            <div class="tab-pane fade active show"  >
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-profile mr-1"></i>
                            Tambah Akun User
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="form_create">
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Nama  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="text" name="name" class="form-control" placeholder="Nama Akun"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Username  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="text" name="username" class="form-control" placeholder="Username Akun"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Email  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="email" name="email" class="form-control" placeholder="Alamat Email"></textarea>
                                    <div class="text-muted mt-1"><b>Password</b> dan <b>Kode Verifikasi</b> akan dikirimkan ke alamat email ini</div>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Status  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-status" name="status" data-fouc data-placeholder="Status Akun...">
                                        <option></option>
                                        <?php foreach ($list_status as $item) { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-4">
                                <div class="offset-md-3 col-md-3">
                                    <button type="button" class="btn btn-primary btn-block btn-save btn-sm legitRipple"><span>Simpan</span> <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
        <!-- /right content -->

    </div>

</div>

<div class="multi-view" id="detail-view" style="display: none">
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-200 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Navigation -->
                <div class="card">
                    <div class="d-none d-sm-block">
                        <button type="submit" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple  text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
                    </div>

                    <div class="card-body text-center card-img-top">
                        <div class="card-img-actions d-inline-block p-3">
                            <!-- <i class="far fa-address-book fa-5x text-muted"></i> -->
                            <img class="account-avatar img-fluid rounded-circle" src="<?= $accountUrl ?>/pic/acc/0" width="170" height="170" alt="">
                        </div>

                        <div class="mb-1">
                            <div class="font-weight-bold text-uppercase" data="name"></div>
                            <div class="small" data="email"></div>
                        </div>

                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2 nav-application">
                            <li class="nav-item-header">Menu</li>
                            <li class="nav-item  first">
                                <a href="javascript:;" data-tab="informasi" class="nav-link legitRipple active show" data-toggle="tab"><i class="icon-file-empty"></i> Informasi</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="role" class="nav-link legitRipple" data-toggle="tab"><i class="icon-puzzle2"></i> Role Akun</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="akses" class="nav-link legitRipple" data-toggle="tab"><i class="fas fa-user-check"></i> Akses Aplikasi</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="perangkat" class="nav-link legitRipple" data-toggle="tab"><i class="icon-iphone"></i> Perangkat</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="setting" class="nav-link legitRipple" data-toggle="tab"><i class="fas fa-user-cog"></i> Setting</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->

        <div class="d-md-none">
            <button type="submit" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
        </div>

        <div class="card card-body d-md-none p-1">

            <div class="d-flex justify-content-start">
                <div class="mr-3 p-2">
                    <!-- <i class="far fa-address-book fa-2x text-muted"></i> -->
                    <img class="account-avatar img-fluid rounded-circle" src="<?= $accountUrl ?>/pic/acc/0" width="100" height="100" alt="">
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <div class="font-weight-bold text-uppercase" data="name"></div>
                        <div class="small" data="email"></div>
                    </div>
                </div>

            </div>
        </div>

        <ul class="nav nav-tabs nav-application nav-tabs-highlight d-md-none nav-tabs-header">
            <li class="nav-item first"><a href="javascript:;" data-tab="informasi" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab">Akun</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="role" class="nav-link text-uppercase font-weight-bold" data-toggle="tab">Role</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="akses" class="nav-link text-uppercase font-weight-bold" data-toggle="tab">Akses</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="perangkat" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><i class="icon-iphone"></i></a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="setting" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><i class="fas fa-user-cog"></i></a></li>
        </ul>

        <!-- Right content -->
        <div class="tab-content tab-application w-100">

            <div class="tab-pane fade" id="informasi">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-profile mr-1"></i>
                            Informasi Akun
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form" id="form_edit">
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Username</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext with-border" data="username"></div>
                                </div>
                            </div>
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Nama</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext with-border" data="name"></div>
                                </div>
                            </div>
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Email</label>
                                <div class="col-md-8">
                                    <!-- <div class="form-control-plaintext with-border" data="email"></div> -->
                                    <div class="form-control-plaintext with-border email-verified ">
                                        <div class="d-flex justify-content-between">
                                            <span data="email">Email belum di set</span>
                                            <a href="javascript:;" class="btn pull-right btn-xs btn-default btn-edit-email btn-flat tooltips" title="Edit Email"><i class="fa fa-edit"></i></a>
                                        </div>
                                    </div>

                                    <div class="form-control-plaintext with-border email-unverified">
                                        <div class="d-flex justify-content-between">
                                            <span data="email_unverified"></span>
                                            <div>
                                                <i class='pull-right text-muted' style="padding-right: 10px;">Belum diverifikasi</i>
                                                <a href="javascript:;"  class="btn pull-right btn-xs btn-default btn-edit-email btn-flat tooltips" title="Edit Email"><i class="fa fa-edit"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Nomor Telepon</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext with-border phone-verified">
                                        <div class="d-flex justify-content-between">
                                            <span data="phone">Nomor Telpon belum di set</span>
                                            <a href="javascript:;" class="btn pull-right btn-xs btn-default btn-edit-phone btn-flat tooltips" title="Edit Nomor Telpon"><i class="fa fa-edit"></i></a>
                                        </div>
                                    </div>

                                    <div class="form-control-plaintext with-border phone-unverified">
                                        <div class="d-flex justify-content-between">
                                            <span data="phone_unverified"></span>
                                            <div>
                                                <i class='pull-right text-muted' style="padding-right: 10px;">Belum diverifikasi</i>
                                                <a href="javascript:;" class="btn pull-right btn-xs btn-default btn-edit-phone btn-flat tooltips" title="Edit Nomor Telpon"><i class="fa fa-edit"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Jenis Kelamin</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext with-border" data="gender_txt"></div>
                                </div>
                            </div>
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Alamat</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext with-border" data="address"></div>
                                </div>
                            </div> -->
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Google Account</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext with-border" data="google_account"></div>
                                </div>
                            </div>
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Tanggal Bergabung</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext with-border" data="date_joined_txt"></div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="role">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-puzzle2 mr-1"></i>
                            Role Akun
                        </h6>
                        <div class="header-elements">
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle" data-toggle="dropdown"><i class="icon-user-plus mr-1"></i> Set Role Akun</button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="javascript:;" data-target="#select-staff-modal" data-toggle="modal" class="dropdown-item"><i class="fas fa-user-tie mr-1"></i> Set Role Staff </a>
                                    <!-- <a href="javascript:;" data-target="#select-unit-modal" data-toggle="modal" class="dropdown-item"><i class="far fa-user mr-1"></i> Set Role Kepala Unit</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 table-container">
                        <table class="table table-role-akun table-condensed">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th width="150">Nama Role</th>
                                    <th>Description</th>
                                    <th width="40"><i class="icon-menu"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="akses">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="fas fa-user-check mr-1"></i>
                            Akses Aplikasi
                        </h6>
                        <div class="header-elements">
                            <button type="button" data-target="#select-aplikasi-modal" data-toggle="modal" class="btn btn-xs btn-outline-primary"><i class="icon-plus2 mr-1"></i> Tambah Akses</button>
                        </div>
                    </div>
                    <div class="w-100 table-container">
                        <table class="table table-akses-akun table-condensed">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>Nama Aplikasi</th>
                                    <th width="200">Akses Level</th>
                                    <th width="100">Status</th>
                                    <th width="40"><i class="icon-menu"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="perangkat">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-iphone mr-1"></i>
                            Perangkat Yang Terhubung
                        </h6>
                    </div>
                    <div class="card-body pb-0">
                        <div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input readonly type="text" class="form-control input-bulan cursor-pointer" placeholder="Periode">
                                        <div class="input-group-append border-bottom ml-0">
                                            <span class="input-group-text"><i class="icon-arrow-down22"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control select2 select-aplikasi" data-fouc data-placeholder="Aplikasi ..." data-allow-clear="true">
                                        <option></option>
                                        <?php foreach ($list_aplikasi as $item) { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-10">
                                    <input type="search" class="form-control input-query" placeholder="Nama Perangkat / OS ...">
                                </div>
                                <div class="col-md-1 col-1">
                                    <button class="btn btn-filter btn-outline-primary"><i class="icon-search4"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 table-container">
                        <table class="table table-perangkat-akun table-condensed">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th width="150">Nama Aplikasi</th>
                                    <th>Perangkat</th>
                                    <th width="200">OS / Versi</th>
                                    <th width="140">Tanggal Update</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="setting">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="fas fa-user-cog mr-1"></i>
                            Setting Akun
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Status Akun</label>
                                <div class="col-md-8">
                                    <select class="form-control select2 select-akun-status" name="status" data-fouc data-placeholder="Status Akun...">
                                        <?php foreach ($list_status as $item) { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Reset Password</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="akun_password" placeholder="Masukkan Password">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-generate-pwd btn-flat">Generate</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-md-2">
                                <div class="offset-md-3 col-md-8">
                                    Password akan kirimkan melalui email (yang sudah diverifikasi)
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <div class="offset-md-3 col-md-3">
                                    <button type="button" class="btn btn-sm btn-reset-akun btn-block btn-danger"><i class="icon-reload-alt"></i>
                                        Reset Password </button>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Hapus Akun</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext">
                                        Hapus akun berikut data akun keluarga.<br>Data akun anak. anggota keluarga, data siswa tidak akan
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <div class="offset-md-3 col-md-3">
                                    <button type="button" class="btn btn-sm btn-delete-akun btn-block btn-danger"><i class="icon-trash mr-2"></i>Hapus Akun</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
        <!-- /right content -->
    </div>
</div>

<div class="modal" data-keyboard="true" id="select-staff-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="form-group row mb-0">
                    <div class="col-3 d-flex align-items-center">
                        <h5 class="modal-title font-weight-bold"><i class="icon-file-check mr-1"></i> Employee</h5>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control select2 mr-2 select-department filter-select" data-placeholder="Department ..." data-allow-clear="true">
                            <option></option>
                            <?php foreach ($list_department as $item) { ?>
                                <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="search" placeholder="Cari ..." class="form-control input-query">
                            <div class="input-group-append ml-0">
                                <span class="input-group-text small"><i class="icon-search4"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="w-100 table-container" style="min-height: 400px;">
                <table class="table table-staff table-condensed table-cursor table-hover">
                    <thead>
                        <tr>
                            <th width="200">Name</th>
                            <th>Department / Designation</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="modal-footer">
                <div>
                    <button type="button" data-dismiss="modal" class="btn btn-default  btn-xs"><i class="icon-cross"></i> Batal</button>
                    <button type="button" data-role="staff" data-role-name="Staff" class="btn btn-primary btn-set-role btn-xs"><i class="icon-paperplane mr-2"></i> Pilih Staff</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-keyboard="true" id="select-aplikasi-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header align-items-center d-flex">
                <h5 class="modal-title font-weight-bold"><i class="icon-file-check mr-1"></i> Pilih Aplikasi</h5>
                <div class="d-flex justify-content-between">
                    <div class="input-group">
                        <input type="search" placeholder="Cari ..." class="form-control pb-0 input-query">
                        <div class="input-group-append ml-0">
                            <span class="input-group-text small"><i class="icon-search4"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-100 table-container" style="min-height: 400px;">
                <table class="table table-aplikasi table-condensed table-cursor table-hover">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th width="200">Nama Aplikasi</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="modal-footer">
                <div>
                    <button type="button" data-dismiss="modal" class="btn btn-default  btn-xs"><i class="icon-cross"></i> Batal</button>
                    <button type="button" class="btn btn-primary btn-set-akses btn-xs"><i class="icon-paperplane mr-2"></i> Pilih Aplikasi</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-backdrop="false" data-keyboard="true" id="akseslevel-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header align-items-center d-flex">
                <h5 class="modal-title font-weight-bold"><i class="icon-file-check mr-1"></i> Set Akses Level</h5>
                <div class="d-flex justify-content-between">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="form_akses_user">
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-semibold pb-0 mb-0">Nama User</label>
                        <div class="form-control-plaintext with-border-light" data="nama_akun"></div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-semibold pb-0 mb-0">Nama Aplikasi</label>
                        <div class="form-control-plaintext with-border-light nama-aplikasi" data="name"></div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-semibold pb-0 mb-0">Akses Level</label>
                        <select required class="form-control select2 select-akseslevel" name="access_level_id" data-placeholder="Pilih Akses Level" data-allow-clear="true">
                            <option value=""></option>
                        </select>
                    </div>
                    <input type="hidden" name="appid">
                    <input type="hidden" name="account_id">
                    <input type="hidden" name="action" value="set">
                </form>
            </div>

            <div class="modal-footer">
                <div>
                    <button type="button" data-dismiss="modal" class="btn btn-default  btn-xs"><i class="icon-cross"></i> Batal</button>
                    <button type="button" class="btn btn-primary btn-save-modal btn-xs"><i class="icon-paperplane mr-2"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" data-keyboard="true" id="modal-email">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-pencil7"></i> Edit Email</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form" id="form_email">
                <input type="hidden" name="account_id">
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

<div class="modal" data-keyboard="true" id="modal-phone">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><i class="icon-pencil7"></i> Edit Nomor Telpon</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body" style="min-height:100px;">
            <form class="form" role="form" id="form_phone">
                <input type="hidden" name="account_id">
                <input type="hidden" value="phone" name="type">
                <p>Masukkan nomor telepon</p>
                <div class="mb-2">
                    <input type="phone" name="value" class="form-control contact-value" placeholder="Nomor Telepon">
                </div>
                <p>Kode verifikasi akan dikirimkan melalui ke nomor telepon diatas</p>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn cancel btn-xs btn"><i class="fa fa-undo"></i> Batal</button>
            <button type="button" class="btn btn-save-modal btn-xs btn bg-blue"><i class="fa fa-save"></i> Simpan</button>
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

	
<script>
    jQuery(document).ready(function() {ManageAccount.init();});
</script>

</body>
</html>
