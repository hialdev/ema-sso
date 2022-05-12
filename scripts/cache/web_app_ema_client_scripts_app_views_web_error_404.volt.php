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
<body>
	<!-- Page content -->
	<div class="page-content">
		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			
<div class="content d-flex justify-content-center align-items-center">

    <!-- Login form -->
    <form class="login-form" method="POST">
        <div class="card mb-0">
            <div class="card-body">

                <div class="text-center mb-2">
                    <div class="mb-1"><img class="logo-on-card" src="assets/images/logo.png" ></div>
                    <h5 class="mb-2"><?= $appTitle ?></h5>
                </div>

                <div class="text-center mb-2">
                    <h4 class="font-weight-bold m-0 text-info">[ 404 ]</h4>
                    <div class=" text-error">Halaman yang anda tuju tidak ditemukan</div>
                </div>

                <div class="text-center">
                    <a href="<?= $domain ?>" class="text-center">Kembali</a>  ke halaman utama
                </div>

            </div>
        </div>

        <div class="text-center mt-2 footer">
            <strong>Copyright &copy; <?= date('Y') ?></strong> All rights reserved.
        </div>
    </form>
</div>

			<!-- /content area -->
		</div>
		<!-- /main content -->
	</div>
	<!-- /page content -->

	<script src="assets/js/<?= $assets['vendor.js'] ?>"></script>

	
	
</body>
</html>