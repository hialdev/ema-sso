<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?= $pageTitle ?> | <?= $appTitle ?></title>
		<base href="<?= $domain ?>">

		<link rel="stylesheet" href="assets/css/<?= $assets['vendor.css'] ?>">
		<link rel="stylesheet" href="assets/css/<?= $assets['app.css'] ?>">

		<link rel="apple-touch-icon" sizes="180x180" href="assets/icon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="assets/icon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="assets/icon/favicon-16x16.png">
		<link rel="manifest" href="assets/icon/site.webmanifest">

		
		
</head>
<body data-init="<?= $appData ?>" class="navbar-top bg-white">

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-light navbar-static fixed-top">

		<!-- Header with logos -->
		<div class="navbar-header d-none d-md-flex align-items-md-center">
			<div class="navbar-brand">
				<a href="" class="d-inline-block logo text-dark">
					<img src="assets/images/logo_only.png" alt="">
					<span class="app-title"><b><?= $appTitle ?></b></span>
				</a>
			</div>
		</div>
		<!-- /header with logos -->


		<!-- Mobile controls -->
		<div class="d-flex flex-1 d-md-none">
			<div class="navbar-brand mr-auto">
				<a href="" class="d-inline-block logo text-dark">
					<img src="assets/images/logo_only.png" alt="">
					<span class="app-title"><b><?= $appTitle ?></b></span>
				</a>
			</div>

			<div class="mr-2">
				<button class="navbar-toggler mr-2 dropdown-toggle caret-0" type="button"  data-toggle="dropdown">
					<i class="icon-plus3"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item btn-new-workspace"><i class="icon-puzzle2"></i> New Workspace</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item btn-new-project"><i class="icon-cube2"></i> New Project</a>
				</div>
			</div>

			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>
		<!-- /mobile controls -->


		<!-- Navbar content -->
		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-hide d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
			</ul>

			<?php if (!empty($taskProject)) { ?>
			<div class="ml-md-2 mr-md-auto">
				<div class="page-title pt-2 pb-2">
					<a href="#" class="h4 mb-0 text-dark font-weight-semibold dropdown-toggle" data-toggle="dropdown">
						<?= $project->name ?>
					</a>
					<div class="dropdown-menu">
						<a href="w/<?= $workspace->slug ?>" class="dropdown-item"><i class="icon-puzzle2"></i> Workspace Detail ...</a>
						<div class="dropdown-divider"></div>
						<a href="p/<?= $project->slug ?>" class="dropdown-item"><i class="icon-cube2"></i> Project Detail ...</a>
					</div>
				</div>
			</div>
			<?php } elseif (!empty($project) && $project->id != 0) { ?>
			<div class="ml-md-2 mr-md-auto">
				<div class="page-title pt-2 pb-2">
					<a href="#" class="h4 mb-0 text-dark font-weight-semibold dropdown-toggle" data-toggle="dropdown">
						<i class="icon-cube2 mr-1"></i>
						<?= $project->name ?>
					</a>
					<div class="dropdown-menu">
						<a href="w/<?= $workspace->slug ?>" class="dropdown-item"><i class="icon-puzzle2"></i> Workspace Detail ...</a>
					</div>
				</div>
			</div>
			<?php } elseif (!empty($workspace)) { ?>
			<div class="ml-md-2 mr-md-auto">
				<div class="page-title pt-2 pb-2">
					<a class="h4 mb-0 text-dark font-weight-semibold">
						<i class="icon-puzzle2 mr-1"></i>
						<?= $workspace->name ?>
					</a>
					<!-- <div class="dropdown-menu">
						<a href="w/<?= $workspace->slug ?>" class="dropdown-item"><i class="icon-cog3"></i> Workspace Settings...</a>
					 </div>-->
				</div>
			</div>
			<?php } else { ?>
			<div class="ml-md-2 mr-md-auto">
				<div class="page-title pt-2 pb-2">
					<h4><span class="font-weight-semibold"><?= $pageTitle ?></span></h4>
				</div>
			</div>
			<?php } ?>
			<ul class="navbar-nav">
				<li class="nav-item dropdown dropdown-action">
					<a href="#" class="navbar-nav-link d-flex caret-0 align-items-center dropdown-toggle" data-toggle="dropdown">
						<i class="icon-plus3"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item btn-new-workspace"><i class="icon-puzzle2"></i> New Workspace</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item btn-new-project"><i class="icon-cube2"></i> New Project</a>
					</div>
				</li>
			</ul>
			<div class="div-border h-50"></div>
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
				<div class="card card-sidebar-mobile mt-1 mb-2 border-bottom">
					<ul class="nav nav-sidebar nav-condensed" data-nav-type="accordion">

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
				<!-- <div class="card mb-2 card-collapsed">
					<div class="card-header bg-transparent header-elements-inline pb-2 pt-1">
						<span class="text-uppercase font-size-sm font-weight-semibold">Bookmarks</span>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item rotate-180" data-action="collapse"></a>
	                		</div>
                		</div>
					</div>
				</div> -->
				<?php $hasProject = !empty($project); ?>
				<div class="card mb-2">
					<div class="card-header bg-transparent header-elements-inline pb-2 pt-1 mb-1">
						<span class="text-uppercase font-size-sm font-weight-bold">Workspace</span>
						<div class="header-elements">
							<div class="list-icons">
		                		<a class="list-icons-item"></a>
	                		</div>
                		</div>
					</div>

					<div class="card-body p-0">
						<ul class="nav nav-sidebar nav-workspace" data-nav-type="accordion">
							<?php $id_workspace = ($hasProject ? $project->id_workspace : 0); ?>
							<?php $id_project = ($hasProject ? $project->id : 0); ?>

							<?php foreach ($workspaces as $_workspace) { ?>
							<!-- <li class="nav-item">
								<a href="#" class="nav-link">
									<i class="icon-user-plus"></i>
									Assign users
									<span class="badge bg-primary badge-pill ml-auto">94 online</span>
								</a>
							</li> -->
							<?php $workspaceAktif = $_workspace['id'] == $id_workspace; ?>
							<li class="nav-item nav-item-submenu <?php if ($workspaceAktif) { ?>nav-item-open<?php } ?>"><!-- nav-item-open -->
								<a href="#" class="nav-link d-flex">
									<span class="flex-grow-1 font-weight-bold"><?= $_workspace['name'] ?></span>
									<span data-slug="<?= $_workspace['slug'] ?>" class="btn-workspace-menu btn-menu-sidebar tooltips" title="Workspace Detail"><i class="icon-cog3 text-muted mr-0"></i></span>
								</a>
								<ul class="nav nav-group-sub" data-submenu-title="<?= $_workspace['name'] ?>" <?php if ($workspaceAktif) { ?>style="display:block;"<?php } ?>>
									<?php foreach ($_workspace['projects'] as $_project) { ?>
									<li class="nav-item">
										<a href="task/<?= $_project['slug'] ?>" class="nav-link d-flex <?php if ($_project['id'] == $id_project) { ?>active<?php } ?>">
											<span class="flex-grow-1"><?= $_project['name'] ?></span>
											<span data-slug="<?= $_project['slug'] ?>" class="btn-project-menu btn-menu-sidebar tooltips" title="Project Detail"><i class="icon-cog3 text-muted mr-0"></i></span>
										</a>
									</li>
									<?php } ?>
								</ul>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>

			</div>
			<!-- /sidebar content -->


			<div class="sidebar-footer text-center">
				<span class="">
					<b>ElangMerah</b> © <?= date('Y') ?>
				</span>
			</div>

		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			
			<div class="page-header d-md-none">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex pt-2 pb-2">
						<h4><span class="font-weight-semibold"><?= $pageTitle ?></span></h4>
						<?php if (!empty($taskProject) && $taskProject == true) { ?>
						<a href="#" class="caret-0 header-elements-toggle text-default d-md-none dropdown-toggle" data-toggle="dropdown"><i class="icon-more"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="w/<?= $workspace->slug ?>" class="dropdown-item"><i class="icon-puzzle2"></i> Workspace Detail ...</a>
							<div class="dropdown-divider"></div>
							<a href="p/<?= $project->slug ?>" class="dropdown-item"><i class="icon-cube2"></i> Project Detail ...</a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			
			<!-- /page header -->


			<!-- Content area -->
			<div class="content p-0">

				
<params  data-task-id="<?= $params ?>"></params>
<div id="task-manager"
    data-id="<?= $project->id ?>" data-name="<?= $project->name ?>" data-workspace-id="<?= $project->id_workspace ?>" data-slug="<?= $project->slug ?>">
    <div class="taskman-content">

        <div class="task-groups">
            <?php foreach ($sections as $section) { ?>
            <div class="tm-sec task-group" id="g-<?= $section->id ?>" data-id="<?= $section->id ?>">
                <div class="task-group-title">
                    <div class="group-toggle">
                        <a data-toggle="collapse" class="btn-group-toggle" href="#gt-<?= $section->id ?>"></a>
                    </div>
                    <div class="group-title-wrapper">
                        <div class="group-title-frame">
                            <div class="group-title-frame-border"></div>
                            <span class="group-title-text"><?= $section->name ?></span>
                        </div>
                        <input class="task-group-input" value="<?= $section->name ?>" placeholder="">
                    </div>
                    <div class="group-action-wrapper"></div>
                </div>

                <ul class="task-list collapse show" id="gt-<?= $section->id ?>">
                    <?php foreach ($section->getTasks($taskStatus, $taskPriority) as $task) { ?>
                    <?php $assignee = $task->getAssignee(); ?>
                    <li class="tm-sec-ti task-item" draggable="true" id="t-<?= $task->id ?>"
                        data-id="<?= $task->id ?>" data-status="<?= $task->status ?>" data-priority="<?= $task->priority ?>" data-due-date="<?= $task->due_date ?>"
                        <?php if (!empty($assignee)) { ?>
                        data-assignee-id="<?= $assignee->id ?>" data-assignee-name="<?= $assignee->name ?>" data-assignee-avatar="<?= $assignee->getAvatarUrl() ?>"
                        <?php } ?>
                        >
                        <ul class="task-list collapse" id="st-<?= $task->id ?>"></ul>
                        <div class="task-row">
                            <div class="task-row-front">
                                <span class="task-status">
                                    <i class="far fa-check-circle"></i>
                                </span>
                                <span class="subtask-toggle">
                                    <a data-toggle="collapse" class="btn-task-toggle collapsed" href="#st-<?= $task->id ?>"></a>
                                </span>
                            </div>
                            <div class="task-row-content">
                                <div class="task-name-wrapper">
                                    <div class="task-name-frame">
                                        <div class="task-name-frame-border"></div>
                                        <span class="task-name"><?= $task->name ?></span>
                                    </div>
                                    <input class="task-input" maxlength="1024" value="<?= $task->name ?>" placeholder="">
                                </div>
                                <div class="task-project-wrapper d-md-block d-none"><span class="task-project"></span></div>
                                <div class="task-tag-wrapper d-md-block d-none"><span class="task-tag"></span></div>
                                <div class="task-assignee-wrapper d-md-block d-none"><span class="task-assignee"></span></div>
                                <div class="task-date-wrapper d-md-block d-none"><span class="task-due-date"></span></div>
                                <div class="task-action-wrapper d-md-block d-none"><span class="task-priority"></span></div>
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>

            <?php } ?>
        </div>

        <div class="tm-sec-action task-group">
            <div class="task-group-title">
                <a class="btn-new-section">
                    <i class="icon-plus22"></i>
                    Add Section
                </a>
            </div>
        </div>

    </div>

    <div class="task-detail task-detail-wrapper">
    <div class="section-wrapper">
        <div class="section-header border-bottom">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 justify-content-start d-flex align-items-center">
                    <a href="javascript:;" class="dropdown-toggle btn-task-priority cursor-pointer btn text-capitalize" data-toggle="dropdown"><i class="icon-arrow-up7 text-muted"></i></a>
                    <div class="dropdown-menu dropdown-menu-condensed">
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="3" data-action="priority"><i class="icon-arrow-up7 text-danger"></i> Urgent</a>
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="2" data-action="priority"><i class="icon-arrow-up7 text-warning"></i> High</a>
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="1" data-action="priority"><i class="icon-arrow-up7 text-muted"></i> Normal</a>
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="0" data-action="priority"><i class="icon-arrow-down7 text-success"></i> Low</a>
                    </div>
                    <a class="btn cursor-pointer btn-attachment text-muted" title="Add File"><i class="icon-attachment"></i></a>
                </div>
                <div class="border-left">
                    <a class="btn cursor-pointer btn-close-detail text-muted" title="Close Detail"><i class="icon-cross2"></i></a>
                </div>
            </div>
        </div>
        <div class="section-content">
            <div class="task-title-wrapper">
                <div class="task-status-cont">
                    <a href="javascript:;" class="dropdown-toggle btn-task-status btn-task-detail-action cursor-pointer text-muted" data-toggle="dropdown"><i class="far fa-check-circle text-muted"></i></a>
                    <div class="dropdown-menu dropdown-menu-condensed">
                        <a class="dropdown-item btn-action-task menu-item-status" data-status="0" data-action="status"><i class="far fa-check-circle text-muted"></i> Todo</a>
                        <a class="dropdown-item btn-action-task menu-item-status" data-status="2" data-action="status"><i class="icon-circle2 text-info"></i> In Progress</a>
                        <a class="dropdown-item btn-action-task menu-item-status" data-status="1" data-action="status"><i class="icon-checkmark-circle text-success"></i> Complete</a>
                    </div>
                </div>
                <div class="task-title-cont">
                    <div class="task-title-frame t-name">Task Name</div>
                    <textarea class="text-title text-autosize" placeholder="Task Name"></textarea>
                </div>
            </div>
            <div class="task-action-wrapper">
                <div class="task-assigne-cont">
                    <a href="javascript:;" data-target="assigneeMenu"  id="dropdownMenuAssignee" aria-haspopup="true" aria-expanded="false" class="task-assignee-cont btn-task-detail-action dropdown-toggle"  data-toggle="dropdown">
                        <div class="task-icon-label mr-1"><i class="far fa-user text-muted"></i></div>
                        <div class="task-assignee">Add Assignee</div>
                    </a>
                    <div class="dropdown-menu" id="assigneeMenu" aria-labelledby="dropdownMenuAssignee">
                        <div class="text-muted small p-4 text-center">
                            coming soon
                        </div>
                    </div>
                </div>
                <div class="div-border"></div>
                <div>
                    <a href="javascript:;" data-target="duedateMenu"  id="dropdownMenuDate" aria-haspopup="true" aria-expanded="false" class="task-date-cont btn-task-detail-action dropdown-toggle"  data-toggle="dropdown">
                        <div class="task-icon-label mr-1"><i class="far fa-calendar-check text-muted"></i></div>
                        <div class="task-due-date">Due Date</div>
                    </a>
                    <div class="dropdown-menu" id="duedateMenu" aria-labelledby="dropdownMenuDate">
                        <div class="p-1">
                            <div class="TaskDetail_datePickerMenu mb-2"></div>
                            <div class="d-flex justify-content-between p-2">
                                <button data-action="today" class="border btn btn-xs btn-due-date">Today</button>
                                <button data-action="tomorrow" class="border btn btn-xs btn-due-date">Tomorrow</button>
                                <button data-action="none" class="border btn btn-xs btn-due-date">None</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="task-tag-wrapper d-none">
                <a href="javascript:;" data-target="tagMenu"  id="dropdownMenuTag" aria-haspopup="true" aria-expanded="false" class="task-tag-cont btn-task-detail-action dropdown-toggle"  data-toggle="dropdown">
                    <div class="task-icon-label mr-1"><i class="icon-price-tag3 text-muted"></i></div>
                    <div class="task-tag">Add Tag</div>
                </a>
                <div class="dropdown-menu" id="tagMenu" aria-labelledby="dropdownMenuTag">
                    <div class="text-muted small p-4 text-center">
                        coming soon
                    </div>
                </div>
            </div>
            <div class="task-description-wrapper mb-2">
                <div class="task-description-frame t-description">Add description</div>
                <textarea class="text-description text-autosize" placeholder="Add description"></textarea>
                <div class="task-description-action-wrapper">
                    <div>
                        <button type="button" disabled class="btn btn-save-description btn-xs btn-success">Save</button>
                        <button type="button" class="btn btn-cancel-description btn-xs btn-outline bg-slate-600 text-slate-600 border-slate-600">Cancel</button>
                    </div>
                </div>
            </div>
            <div class="task-files-wrapper mb-2">
                <div class="task-file-images row mb-2"></div>
                <div class="task-file-other"></div>
            </div>
            <div class="task-comments-wrapper"></div>
        </div>
        <div class="section-footer border-top">
            <div class="task-comment-box-wrapper">
                <div class="">
                    <img src="<?= $accountUrl ?>pic/acc/<?= $account->uid ?>/40/40" class="img-fluid rounded-circle border" width="40" height="40" alt="">
                </div>
                <div class="task-comment-box">
                    <textarea class="text-comment text-autosize" placeholder="Add Comment"></textarea>
                    <div class="task-comment-action-wrapper">
                        <div>
                            <button type="button" disabled class="btn btn-send-comment btn-xs btn-success">Send</button>
                            <button type="button" class="btn btn-cancel-comment btn-xs btn-outline bg-slate-600 text-slate-600 border-slate-600">Cancel</button>
                        </div>
                        <!-- <button type="button" class="btn btn-xs btn-attach-comment">Attachment <i class="icon-attachment"></i></button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>


			</div>
			<!-- /content area -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->


	<script src="assets/js/<?= $assets['vendor.js'] ?>"></script>
    <script src="assets/js/<?= $assets['app.js'] ?>"></script>

	
<script>
    jQuery(document).ready(function () {
        TaskProject.init();
    });
</script>

</body>
</html>