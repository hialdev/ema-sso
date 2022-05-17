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
		<meta name="theme-color" content="#04053E">

		<!-- SEO -->
		<title><?= $meta['title'] ?></title>
		<meta name="description" content="<?= $meta['desc'] ?>" />
		<meta property="og:url" content="<?= $urlNow ?>">
		<meta property="og:description" content="<?= $meta['desc'] ?>">
		<?php if ($meta['image'] !== null) { ?>
		<meta property="og:image" content="<?= $meta['image'] ?>">
		<meta name="twitter:card" content="<?= $meta['image'] ?>">
		<?php } ?>
		
		<link rel="canonical" href="<?= $urlNow ?>" />
		
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
                                    <img src="/assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block"><?= $profile['user']['name'] ?></span>
                                <small class="text-muted">
                                    <?php foreach ($profile['roles'] as $role) { ?>
                                        <span class="badge bg-label-secondary"><?= $role ?></span>
                                    <?php } ?>
                                </small>
                            </div>
                        </div>
                    </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= $this->url->get($accUrl) ?>" target="_blank">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirmLogout">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle text-danger">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </nav>
    <!-- Modals -->
    <div class="modal fade" id="confirmLogout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLogoutTitle">Confirm Logout</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan keluar dari seluruh aplikasi Elang Merah Api.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <a href="<?= $this->url->get('sso/logout') ?>" class="btn btn-primary">Ya, Keluar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modals -->
<!-- / Navbar -->

					<!-- Content wrapper -->
					<div class="content-wrapper">
						<!-- Content -->
						<?= $this->flash->output() ?>
						
						
<div class="container-xxl flex-grow-1 container-p-y">
    
    <?php if ($ticket->status !== '3') { ?>
    <!-- Modals -->
    <div class="modal fade" id="confirmClose" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCloseTitle">Close Ticket <?= $ticket->subject ?>?</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>Setelah anda menutup ticket ini, anda masih bisa membukanya dengan membuat balasan baru.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <a href="/ticket/<?= $ticket->slug ?>/close" class="btn btn-success">Close Ticket</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modals -->
    <?php } ?>

    <div class="py-3">
        <div>
            <div class="d-flex gap-2 mb-2"><span class="badge bg-primary"><?= $ticket->no ?></span>
                <span class="badge bg-<?= $ticket->Priority->css ?>"><?= $ticket->Priority->name ?></span>
                <span class="badge bg-label-<?= $ticket->Status->css ?>"><?= $ticket->Status->name ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><?= $ticket->subject ?></h2>
                    <div>Project : <strong><?= $ticket->Project->name ?></strong></div>
                </div>
                <?php if ($ticket->status !== '3') { ?>
                <div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmClose">Close Ticket</button>
                </div>
                <?php } ?>
            </div>
            <?php if ($ticket->status === '3') { ?>
            <div class="alert alert-warning mt-2" role="alert">
                Ticket ini telah ditutup, Anda dapat membukanya dengan cara membuat balasan baru
            </div>
            <?php } ?>
        </div>
        <hr>
        <div class="d-flex flex-column gap-3">
            
            <?php foreach ($ticket->chat as $chat) { ?>
            <div class="card <?= ($chat->Account->uid !== $uid ? 'border-top border-primary' : '') ?>">
                <div class="card-body">
                    <div>
                        <p><?= $chat->content ?></p>
                        <h6
                        class="
                        <?= ($chat->Account->uid !== $uid ? 'text-primary' : '') ?>
                        ">- <?= $chat->Account->name ?> <span class="fs-6 fw-normal fst-italic ms-2"><?= $chat->created ?></span></h6>
                    </div>
                    <?php $ccf = count($chat->getFiles()); ?>
                    <?php if ($ccf !== 0) { ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($chat->Files as $file) { ?>
                        <a href="<?= $file->getUrl() ?>" target="blank" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded me-1 mb-1"><span class="iconify" data-icon="bi:file-earmark-fill"></span><?= $file->name ?></a>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="card">
                    <h6 class="card-header">Add New Reply</h6>
                    <div class="card-body">
                        <textarea name="content" rows="10" class="form-control mb-3" id="editor"></textarea>
                        <div><small>Multiple File with support extensions : pdf,png,jpg,jpeg,giff,zip</small></div>
                        <input type="file" name="file[]" class="form-control mb-3 mt-3" multiple>
                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">Send <span class="iconify" data-icon="fa6-solid:paper-plane"></span></button>
                    </div>
                </div>
            </form>
        </div>
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
		
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ),{
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        } )
        .catch( error => {
            console.error( error );
        } );
</script>


		<!-- Main JS -->
		<script src="/assets/js/main.js"></script>

		<!-- Page JS -->
		<script src="/assets/js/dashboards-analytics.js"></script>

		<!-- Place this tag in your head or just before your close body tag. -->
		<script async defer src="https://buttons.github.io/buttons.js"></script>
	</body>
</html>
