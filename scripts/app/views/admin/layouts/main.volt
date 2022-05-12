<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>{{ appTitle }} | {{ pageTitle }}</title>
		<base href="{{domain}}">

		<link rel="stylesheet" href="assets/css/{{assets['vendor.css']}}">
		<link rel="stylesheet" href="assets/css/{{assets['app.css']}}">

		<link rel="apple-touch-icon" sizes="180x180" href="assets/icon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="assets/icon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="assets/icon/favicon-16x16.png">
		<link rel="manifest" href="assets/icon/site.webmanifest">

		{%block page_styles%}
		{%endblock%}
</head>
<body data-init="{{appData}}" class="navbar-top {{dark_theme}}">

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
						<img src="{{accountUrl}}pic/acc/{{account.uid}}" class="rounded-circle mr-2" height="28" alt="">
						<span>{{account.name}}</span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="{{accountUrl}}account" target="_blank"  class="dropdown-item"><i class="far fa-user-circle"></i> My Account</a>
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
								<img src="{{accountUrl}}pic/acc/{{account.uid}}" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
							</a>
							<h4 class="mb-0 text-white">{{account.name}}</h4>
							<span class="font-size-sm text-white">{{account.email}}</span>
						</div>

						<div class="sidebar-user-material-footer">
							<a href="#user-nav" class="d-flex justify-content-between align-items-center dropdown-toggle" data-toggle="collapse"><span>My account</span></a>
						</div>
					</div>

					<div class="collapse" id="user-nav">
						<ul class="nav nav-sidebar">
							<li class="nav-item">
								<a href="{{accountUrl}}account" target="_blank" class="nav-link">
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

						{% for menu in list_menus %}
						{%if menu['show'] %}

							{%if menu['header'] %}
							<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">{{ menu['title'] }}</div> <i class="icon-menu" title="{{ menu['title'] }}"></i></li>
							{%else%}
							<li class="nav-item {%if menu['menu']%}nav-item-submenu{%endif%} {%if menu['active'] == 'active'  and menu['menu'] %}nav-item-open{%endif%}">
								<a href="{{ menu['url'] }}" class="nav-link {{ menu['active'] }}">
									<i class="{{ menu['icon'] }}"></i> <span>{{ menu['title'] }}</span>
								</a>

								{%if menu['menu']%}
								<ul class="nav nav-group-sub" data-submenu-title="{{ menu['title'] }}" {%if menu['active'] == 'active'%}style="display:block;"{%endif%}>
								{% for submenu in menu['menu'] %}
									{%if submenu['show'] %}
										{%if submenu['menu']%}
											<li class="nav-item nav-item-submenu {%if submenu['active'] == 'active'%}nav-item-open{%endif%}">
												<a href="#" class="nav-link {{ submenu['active'] }}">{{submenu['title']}}</a>
												<ul class="nav nav-group-sub"  {%if submenu['active'] == 'active'%}style="display:block;"{%endif%}>
													{% for childmenu in submenu['menu'] %}
													<li class="nav-item"><a href="{{childmenu['url']}}" class="nav-link {{ childmenu['active'] }}">{{childmenu['title']}}</a></li>
													{%endfor%}
												</ul>
											</li>
										{%else%}
											<li class="nav-item"><a href="{{submenu['url']}}" class="nav-link {{ submenu['active'] }}">{{submenu['title']}}</a></li>
										{%endif%}
									{%endif%}
								{%endfor%}
								</ul>
								{%endif%}
							</li>
							{%endif%}

						{%endif%}
						{%endfor%}

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
			{%block page_header%}
			<div class="page-header">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex pt-2 pb-2">
						<h4><i class="{{page['icon']}} mr-2"></i> <span class="font-weight-semibold">{{pageTitle}}</span></h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			{%endblock%}
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-2">

				{%block page_content%}
				{%endblock%}

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<footer class="navbar navbar-expand-lg navbar-light">
				<div class="d-flex justify-content-between align-items-center w-100" id="navbar-footer">
					<span class="navbar-text">
						<b>{{appTitle}}</b> © 2022
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


	<script src="assets/js/{{assets['vendor.js']}}"></script>
    <script src="assets/js/{{assets['app.js']}}"></script>

	{%block page_scripts%}
	{%endblock%}
</body>
</html>
