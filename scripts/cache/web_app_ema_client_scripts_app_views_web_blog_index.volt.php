<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="/assets/"
  data-template="vertical-menu-template-free"
>
	<head>
		<meta charset="utf-8" />
		<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
		/>

		<title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

		<meta name="description" content="" />

		<!-- Favicon -->
		<link rel="icon" type="image/x-icon" href="/assets/img/ema-favicon.png" />

		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link
		href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
		rel="stylesheet"
		/>

		<!-- Icons. Uncomment required icon fonts -->
		<link rel="stylesheet" href="/assets/vendor/fonts/boxicons.css" />

		<!-- Core CSS -->
		<link rel="stylesheet" href="/assets/vendor/css/core.css" class="template-customizer-core-css" />
		<link rel="stylesheet" href="/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
		<link rel="stylesheet" href="/assets/css/demo.css" />

		<!-- Vendors CSS -->
		<link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

		<link rel="stylesheet" href="/assets/vendor/libs/apex-charts/apex-charts.css" />

		<!-- Page CSS -->

		<!-- Helpers -->
		<script src="/assets/vendor/js/helpers.js"></script>

		<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
		<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
		<script src="/assets/js/config.js"></script>
	</head>

	<body>
		<!-- Layout wrapper -->
		<div class="layout-wrapper layout-content-navbar">
			<div class="layout-container">
				<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <img src="/assets/img/logo.png" alt="" style="height: 4.3em;width: 100%;">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item <?= ($uri === '/' ? 'active' : '') ?>">
            <a href="/" class="menu-link">
                <span class="iconify menu-icon" data-icon="ic:round-dashboard"></span>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Content Management -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Project Management</span>
        </li>

        <li class="menu-item <?= ($uri === 'project' ? 'active' : '') ?>">
            <a href="<?= $this->url->get('project') ?>" class="menu-link">
                <span class="iconify menu-icon" data-icon="ic:round-work"></span>
                <div data-i18n="Projects">Projects</div>
            </a>
        </li>

        <li class="menu-item <?= ($uri === 'ticket' ? 'active' : '') ?>">
            <a href="" class="menu-link menu-toggle">
                <span class="iconify menu-icon" data-icon="ion:ticket"></span>
                <div data-i18n="Categories">Ticket</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= $this->url->get('ticket/active') ?>" class="menu-link">
                        <div data-i18n="All Categories">Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2"><?= $cat ?></span></div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= $this->url->get('ticket/add') ?>" class="menu-link">
                        <div data-i18n="Add Cateogry">Add Ticket</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= $this->url->get('ticket') ?>" class="menu-link">
                        <div data-i18n="Add Cateogry">Your Ticket</div>
                    </a>
                </li>
            </ul>
        </li>
        
        <!-- Documentation -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Documentation</span>
        </li>

        <li class="menu-item <?= ($uri === 'note' ? 'active' : '') ?>">
            <a href="" class="menu-link menu-toggle">
                <span class="iconify menu-icon" data-icon="ic:round-sticky-note-2"></span>
                <div data-i18n="Notes">Notes</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= $this->url->get('note') ?>" class="menu-link">
                        <div data-i18n="All Notes">View</span></div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= $this->url->get('note/add') ?>" class="menu-link">
                        <div data-i18n="Add Cateogry">Add Note</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item <?= ($uri === 'knowladge' ? 'active' : '') ?>">
            <a href="<?= $this->url->get('knowladge') ?>" class="menu-link">
                <span class="iconify menu-icon" data-icon="fluent:brain-circuit-24-filled"></span>
                <div data-i18n="Projects">Knowladge</div>
            </a>
        </li>
    </ul>
</aside>
<!-- / Menu -->

				<!-- Layout container -->
				<div class="layout-page">
					<!-- Navbar -->

    <nav
        class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme shadow-none"
        style="background-color: transparent !important;"
        id="layout-navbar"
    >
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
        </div>

        

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown shadow rounded-circle">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="/assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">John Doe</span>
                                <small class="text-muted">Admin</small>
                            </div>
                        </div>
                    </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="auth-login-basic.html">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle text-danger">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </nav>

<!-- / Navbar -->

					<!-- Content wrapper -->
					<div class="content-wrapper">
						<!-- Content -->
						<?= $this->flash->output() ?>
						
						
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="row py-3">
        <div class="col-12">
            <h4># Based Knowladge</h4>
            <form action="">
                <div class="input-group my-3">
                    <input
                        name="q"
                        value="<?= $q ?>"
                        type="text"
                        class="form-control border-0"
                        placeholder="Search your problem.."
                        aria-label="Search your problem.."
                        aria-describedby="button-addon2"
                    />
                    <button class="btn btn-primary" type="button" id="button-addon2"><span class="iconify" data-icon="fe:search"></span></button>
                </div>
            </form>
        </div>
        <?php $v146260222752340137781iterated = false; ?><?php foreach ($blogs as $blog) { ?><?php $v146260222752340137781iterated = true; ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="/knowladge/<?= $blog->slug ?>" class="text-dark text-decoration-none d-block">
                        <img src="<?= $blog->image ?>" alt="<?= $blog->title ?> image" class="mb-3 d-block w-100 rounded">
                        <h4><?= $blog->title ?></h4>
                        <p><?= $blog->excerpt ?></p>
                    </a>
                </div>
            </div>
        </div>
        <?php } if (!$v146260222752340137781iterated) { ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    Belum ada data knowladges.
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

						<!-- / Content -->

						<div class="content-backdrop fade"></div>
					</div>
					<!-- Content wrapper -->
					
					<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            © PT Elang Merah Api
            <script>
                document.write(new Date().getFullYear());
            </script>
            , made with ❤️ by
            <a href="https://hialdev.com" target="_blank" class="footer-link fw-bolder">Hialdev</a>
        </div>
    </div>
</footer>
<!-- / Footer -->
				</div>
				<!-- / Layout page -->
				
			</div>

			<!-- Overlay -->
			<div class="layout-overlay layout-menu-toggle"></div>
		</div>
		<!-- / Layout wrapper -->

		<div class="buy-now">
		<a
			href="https://wa.me/6289671052050/"
			target="_blank"
			class="btn btn-success btn-buy-now"
			>Live Chat</a
		>
		</div>

		<!-- Core JS -->
		<!-- build:js /assets/vendor/js/core.js -->
		<script src="/assets/vendor/libs/jquery/jquery.js"></script>
		<script src="/assets/vendor/libs/popper/popper.js"></script>
		<script src="/assets/vendor/js/bootstrap.js"></script>
		<script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

		<script src="/assets/vendor/js/menu.js"></script>
		<!-- endbuild -->

		<!-- Vendors JS -->
		<script src="/assets/vendor/libs/apex-charts/apexcharts.js"></script>
		<script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
		
		

		<!-- Main JS -->
		<script src="/assets/js/main.js"></script>

		<!-- Page JS -->
		<script src="/assets/js/dashboards-analytics.js"></script>

		<!-- Place this tag in your head or just before your close body tag. -->
		<script async defer src="https://buttons.github.io/buttons.js"></script>
	</body>
</html>
