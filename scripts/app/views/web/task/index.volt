{%extends 'layouts/main.volt' %}

{%block page_content%}
<div class="page-content-wrapper">
    <div class="page-content-header">
        <div class="d-flex justify-content-between align-items-center w-100  border-bottom">
            <h3 class="mb-0 text-muted font-weight-bold d-flex align-items-center">
                <div class="p-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-add-task bg-transparent border-slate"><b><i
                                    class="icon-plus22"></i></b> Add Task</button>
                        <button type="button" class="btn btn-xs bg-transparent border-slate dropdown-toggle"
                            data-toggle="dropdown"></button>
                        <div class="dropdown-menu dropdown-menu-condensed">
                            <a class="dropdown-item btn-add-section"><i class="icon-menu7"></i> Add Section</a>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-block">
                    <div class="breadcrumb h4 mb-0 d-flex align-items-center">

                    </div>
                </div>
            </h3>
            <div class="pr-1 pr-md-3">
                <!-- <button class="btn btn-light btn-upload-file mr-1 btn-sm tooltips cursor-pointer" title="Upload Video"><i class="icon-plus22 text-muted "></i> Video</button>
                <button class="btn btn-light btn-new-folder btn-sm tooltips cursor-pointer" title="Folder Baru"><i class="icon-plus22 text-muted "></i> Folder</button> -->
            </div>
        </div>
        <div class="taskman-header">
            <div class="flex-grow-1">Task Name</div>
            <div class="task-header-date">Due Date</div>
            <div class="task-header-action"><i class="icon-menu"></i></div>
        </div>
    </div>
    <div class="page-content-body">
        <div id="task-manager">
            <div class="task-groups">
                {%for g in 1..3%}
                <div class="tm-sec task-group" id="g-{{g}}" data-id="{{g}}">
                    <div class="task-group-title">
                        <div class="group-toggle">
                            <a data-toggle="collapse" class="btn-group-toggle" href="#gt-{{g}}"></a>
                        </div>
                        <div class="group-title-wrapper">
                            <div class="group-title-frame">
                                <div class="group-title-frame-border" style=""></div>
                                <span class="group-title-text" style="">Section {{g}}</span>
                            </div>
                            <input class="task-group-input" value="Section {{g}}" placeholder="">
                        </div>
                        <div class="group-action-wrapper"></div>
                    </div>

                    <ul class="task-list collapse show" id="gt-{{g}}">
                        {%for x in 1..5 %}
                        {%set itemId = g ~ x%}
                        <li class="tm-sec-ti task-item" draggable="true" id="t-{{itemId}}" data-id="{{itemId}}">
                            <ul class="task-list collapse" id="st-{{x}}"></ul>
                            <div class="task-row">
                                <div class="task-row-front">
                                    <span class="task-status">
                                        <i class="far fa-check-circle"></i>
                                    </span>
                                    <span class="subtask-toggle">
                                        <a data-toggle="collapse" class="btn-task-toggle collapsed" href="#st-{{itemId}}"></a>
                                    </span>
                                    <!-- <i class="tree-toggle i-btn x30"></i><i
                                        class="i-btn x20 task-complete icon-circle-o lb iconc-50 "></i><i
                                        class="i-btn x18 task-priority icon-arrow_up"></i> -->
                                </div>
                                <div class="task-row-content">
                                    <div class="task-name-wrapper">
                                        <div class="task-name-frame">
                                            <div class="task-name-frame-border" style=""></div>
                                            <span class="task-name" style="">Task Section {{g}} Item {{itemId}}</span>
                                        </div>
                                        <input class="task-input" maxlength="1024" value="Task Section {{g}} Item {{itemId}}" placeholder="">
                                    </div>
                                    <div class="task-date-wrapper">asd</div>
                                    <div class="task-action-wrapper">asdasd</div>
                                </div>
                            </div>
                        </li>
                        {%endfor%}
                    </ul>

                </div>
                {%endfor%}
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



        <!-- <div id="task-managers">
            <div class="tm-sec task-group" id="g-123">
                <div class="task-group-title">
                    <div class="group-toggle">
                        <a data-toggle="collapse" class="btn-group-toggle" href="#gt-123"></a>
                    </div>
                    <div class="group-title-text">
                        Section
                    </div>
                </div>
                <div class="task-group-tasks collapse show" id="gt-123">
                    <div class="sheet-row task-item">
                        <div draggable="true">
                            <div class="sheet-column col-task">
                                <div class="col-task-status">
                                    <span class="btn-task-status"><i class="far fa-check-circle"></i></span>
                                </div>
                                <div class="col-task-name">
                                    <input class="input-task-name" value="asdasdasdasdasd">
                                </div>
                            </div>
                            <div style="width: 150px;">Due Date</div>
                            <div style="width: 150px;">Project</div>
                        </div>
                        <div>

                        </div>
                    </div>



                    <div class="sheet-row task-item">
                        <div class="sheet-column col-task">
                            <div class="col-task-status">
                                <span class="btn-task-status"><i class="far fa-check-circle"></i></span>
                            </div>
                            <div class="col-task-name">
                                <input class="input-task-name" value="asdasdasdasdasd">
                            </div>
                        </div>
                        <div style="width: 150px;">Due Date</div>
                        <div style="width: 150px;">Project</div>
                    </div>
                    <div class="sheet-row task-item">
                        <div class="sheet-column col-task">
                            <div class="col-task-status">
                                <span class="btn-task-status"><i class="far fa-check-circle"></i></span>
                            </div>
                            <div class="col-task-name">
                                <input class="input-task-name" value="asdasdasdasdasd">
                            </div>
                        </div>
                        <div style="width: 150px;">Due Date</div>
                        <div style="width: 150px;">Project</div>
                    </div>
                    <div class="sheet-row task-item-action">
                        <div class="sheet-column add-task-item">
                            <a class="btn-new-task">
                                Add Task ...
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tm-sec-action task-group">
                <div class="task-group-title">
                    <a class="btn-new-section">
                        <i class="icon-plus22"></i>
                        Add Section
                    </a>
                </div>
            </div>
        </div> -->
    </div>

    <div class="task-detail-wrapper">
        <div class="w-100">

        </div>
    </div>

</div>
{%endblock%}

{%block page_scripts%}
<script>
    jQuery(document).ready(function () {
        TaskHome.init();
    });
</script>
{%endblock%}