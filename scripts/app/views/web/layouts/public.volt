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
<body>
	<!-- Page content -->
	<div class="page-content">
		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			{% block page_content %}
			{% endblock %}
			<!-- /content area -->
		</div>
		<!-- /main content -->
	</div>
	<!-- /page content -->

	<script src="assets/js/{{assets['vendor.js']}}"></script>

	{%block page_scripts%}
	{%endblock%}
</body>
</html>