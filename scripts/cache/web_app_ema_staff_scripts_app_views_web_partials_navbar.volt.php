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
                        <img src="<?= $profile['avatar'] ?>" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="<?= $profile['avatar'] ?>" alt class="w-px-40 h-auto rounded-circle" />
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