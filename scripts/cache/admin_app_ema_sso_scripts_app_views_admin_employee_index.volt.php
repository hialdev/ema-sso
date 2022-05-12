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
                <i class="fas fa-user-friends mr-1"></i> Employee List
            </h6>
            <div class="header-elements">
                <button type="button" class="btn btn-xs btn-primary btn-add-employee tooltips" title="Tambah Akun User"><i class="icon-plus2 mr-1"></i> Tambah</button>
            </div>
        </div>

        <div class="card-body pt-0">
            <form>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group mb-1">
                            <input class="form-control input-nama filter-input" placeholder="Employee Name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <select class="form-control select2-with-icon select-department filter-select" data-fouc data-placeholder="Department ..." data-allow-clear="true">
                                <option value=""></option>
                                <?php foreach ($list_department as $item) { ?>
                                    <option data-icon="<?= $item['icon'] ?>" value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-10">
                        <div class="form-group mb-1">
                            <select class="form-control select2-with-icon select-employment-type filter-select" data-fouc data-placeholder="Employment Type ..." data-allow-clear="true">
                                <option value=""></option>
                                <?php foreach ($list_employment_type as $item) { ?>
                                    <option data-icon="<?= $item['icon'] ?>" value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                <?php } ?>
                            </select>
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
                        <th>Employee Name</th>
                        <th width="200">Department</th>
                        <th width="200">Position / Title</th>
                        <th width="150">Phone</th>
                        <th width="100">Type</th>
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
                            <i class="fas fa-user-tie fa-5x text-muted"></i>
                        </div>

                        <div class="mb-1 text-uppercase font-weight-bold">
                            New Employee
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
                    <div class="mb-1 text-uppercase font-weight-bold">
                        New Employee
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
                            Add New Employee
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="form_create" autocomplete="off">
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Name  <span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input required type="text" name="first_name" class="form-control" placeholder="First Name"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Department  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-department" name="id_department" data-fouc data-placeholder="Department ...">
                                        <option></option>
                                        <?php foreach ($list_department as $item) { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Designation  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-designation" name="id_designation" data-fouc data-placeholder="Job Designation ...">
                                        <option></option>
                                        <?php foreach ($list_designation as $item) { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Phone  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="tel" name="phone" class="form-control" placeholder="Phone"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Email  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="email" name="email" class="form-control" placeholder="Email"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Employment Type  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-employment-type" name="employment_type" data-fouc data-placeholder="Employment Type...">
                                        <option></option>
                                        <?php foreach ($list_employment_type as $item) { ?>
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
                            <div class="font-weight-bold text-uppercase" data="full_name"></div>
                            <div class="" data="department"></div>
                            <div class="small" data="designation"></div>
                        </div>

                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2 nav-application">
                            <li class="nav-item-header">Menu</li>
                            <li class="nav-item  first">
                                <a href="javascript:;" data-tab="informasi" class="nav-link legitRipple active show" data-toggle="tab"><i class="icon-file-empty"></i> Informasi</a>
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
                        <div class="font-weight-bold text-uppercase" data="full_name"></div>
                        <div class="" data="department"></div>
                        <div class="small" data="designation"></div>
                    </div>
                </div>

            </div>
        </div>

        <ul class="nav nav-tabs nav-application nav-tabs-highlight d-md-none nav-tabs-header">
            <li class="nav-item first"><a href="javascript:;" data-tab="informasi" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab">Employee</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="setting" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><i class="fas fa-user-cog"></i></a></li>
        </ul>

        <!-- Right content -->
        <div class="tab-content tab-application w-100">

            <div class="tab-pane fade" id="informasi">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-profile mr-1"></i>
                            Employee Detail
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form" id="form_edit" autocomplete="off">
                            <input type="hidden" name="id">
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Name  <span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input required type="text" name="first_name" class="form-control" placeholder="First Name"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Department  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-department" name="id_department" data-fouc data-placeholder="Department ...">
                                        <option></option>
                                        <?php foreach ($list_department as $item) { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Designation  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-designation" name="id_designation" data-fouc data-placeholder="Job Designation ...">
                                        <option></option>
                                        <?php foreach ($list_designation as $item) { ?>
                                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Phone  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="tel" name="phone" class="form-control" placeholder="Phone"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Email  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="email" name="email" class="form-control" placeholder="Email"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Employment Type  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-employment-type" name="employment_type" data-fouc data-placeholder="Employment Type...">
                                        <option></option>
                                        <?php foreach ($list_employment_type as $item) { ?>
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

            <div class="tab-pane fade" id="setting">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="fas fa-user-cog mr-1"></i>
                            Setting
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form">

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Delete Employee Data</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext">
                                        Delete employee data including all related information.<br>
                                        The process can not be undone.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <div class="offset-md-3 col-md-3">
                                    <button type="button" class="btn btn-sm btn-delete-employee btn-block btn-danger"><i class="icon-trash mr-2"></i>Delete Employee</button>
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
    jQuery(document).ready(function() {ManageEmployee.init();});
</script>

</body>
</html>
