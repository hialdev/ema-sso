{%extends 'layouts/main.volt' %}

{%block page_content%}
<div id="task-manager" data-id="{{project.id}}" data-workspace-id="{{project.id_workspace}}">
    <div class="taskman-content">

        <div class="task-groups">
            {%for section in sections%}
            <div class="tm-sec task-group" id="g-{{section.id}}" data-id="{{section.id}}">
                <div class="task-group-title">
                    <div class="group-toggle">
                        <a data-toggle="collapse" class="btn-group-toggle" href="#gt-{{section.id}}"></a>
                    </div>
                    <div class="group-title-wrapper">
                        <div class="group-title-frame">
                            <div class="group-title-frame-border"></div>
                            <span class="group-title-text">{{section.name}}</span>
                        </div>
                        <input class="task-group-input" value="{{section.name}}" placeholder="">
                    </div>
                    <div class="group-action-wrapper"></div>
                </div>

                <ul class="task-list collapse show" id="gt-{{section.id}}">
                    {%for task in section.getTasks()%}
                    <li class="tm-sec-ti task-item" draggable="true" id="t-{{task.id}}" data-id="{{task.id}}">
                        <ul class="task-list collapse" id="st-{{task.id}}"></ul>
                        <div class="task-row">
                            <div class="task-row-front">
                                <span class="task-status">
                                    <i class="far fa-check-circle"></i>
                                </span>
                                <span class="subtask-toggle">
                                    <a data-toggle="collapse" class="btn-task-toggle collapsed" href="#st-{{task.id}}"></a>
                                </span>
                            </div>
                            <div class="task-row-content">
                                <div class="task-name-wrapper">
                                    <div class="task-name-frame">
                                        <div class="task-name-frame-border"></div>
                                        <span class="task-name">{{task.name}}</span>
                                    </div>
                                    <input class="task-input" maxlength="1024" value="{{task.name}}" placeholder="">
                                </div>
                                <div class="task-date-wrapper"></div>
                                <div class="task-action-wrapper"></div>
                            </div>
                        </div>
                    </li>
                    {%endfor%}
                </ul>
            </div>

            {%endfor%}
        </div>
        <!-- <div class="task-groups">
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
        </div> -->

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
                    <div class="flex-grow-1 justify-content-end d-flex align-items-center">
                        <a class="dropdown-toggle cursor-pointer btn text-muted" data-toggle="dropdown"><i class="icon-arrow-up7 text-muted"></i></a>
                        <div class="dropdown-menu dropdown-menu-condensed dropdown-menu-right">
                            <a class="dropdown-item btn-action-task" data-priority="3" data-action="priority"><i class="icon-arrow-up7 text-danger"></i> Urgent</a>
                            <a class="dropdown-item btn-action-task" data-priority="2" data-action="priority"><i class="icon-arrow-up7 text-warning"></i> High</a>
                            <a class="dropdown-item btn-action-task" data-priority="1" data-action="priority"><i class="icon-arrow-up7 text-muted"></i> Normal</a>
                            <a class="dropdown-item btn-action-task" data-priority="0" data-action="priority"><i class="icon-arrow-down7 text-success"></i> Low</a>
                        </div>
                        <a class="btn cursor-pointer btn-attachment text-muted"><i class="icon-attachment"></i></a>
                    </div>
                    <div class="border-left">
                        <a class="btn cursor-pointer btn-close-detail text-muted"><i class="icon-move-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="task-title-wrapper">
                    <div class="task-status-cont">
                        <a class="dropdown-toggle cursor-pointer text-muted" data-toggle="dropdown"><i class="icon-radio-unchecked"></i></a>
                        <div class="dropdown-menu dropdown-menu-condensed">
                            <a class="dropdown-item btn-action-task" data-status="0" data-action="status"><i class="icon-radio-unchecked"></i> Todo</a>
                            <a class="dropdown-item btn-action-task" data-status="2" data-action="status"><i class="icon-circle2 text-info"></i> In Progress</a>
                            <a class="dropdown-item btn-action-task" data-status="1" data-action="status"><i class="icon-checkmark-circle text-success"></i> Complete</a>
                        </div>
                    </div>
                    <div class="task-title-cont">
                        <div class="task-title-frame t-name">Task Name</div>
                        <textarea class="text-title text-autosize" placeholder="Task Name"></textarea>
                    </div>
                </div>
                <div class="task-action-wrapper">
                    <div class="task-assignee-cont">
                        <div class="task-icon-label"><i class="far fa-user text-muted"></i></div>
                        <div>Add Assignee</div>
                    </div>
                    <div class="div-border"></div>
                    <div class="task-date-cont">
                        <div class="task-icon-label"><i class="far fa-calendar-check text-muted"></i></div>
                        <div>Due Date</div>
                    </div>
                </div>
                <div class="task-tag-wrapper">
                    <div class="task-assignee-cont">
                        <div class="task-icon-label"><i class="far fa-user text-muted"></i></div>
                        <div>Add Assignee</div>
                    </div>
                </div>
                <div class="task-description-wrapper">
                    <div class="task-description-frame">Add description</div>
                    <textarea class="text-description text-autosize" placeholder="Add description"></textarea>
                </div>
                <div class="task-comments-wrapper">

                </div>
            </div>
            <div class="section-footer border-top">
                <div class="task-comment-box-wrapper">
                    <div class="">
                        <img src="{{accountUrl}}pic/acc/{{account.uid}}" class="img-fluid rounded-circle border" width="40" height="40" alt="">
                    </div>
                    <div class="task-comment-box">
                        <textarea class="text-comment text-autosize" placeholder="Add Comment"></textarea>
                        <div class="task-comment-action-wrapper">
                            <div>
                                <button type="button" disabled class="btn btn-send-comment btn-xs btn-success">Send</button>
                                <button type="button" class="btn btn-cancel-comment btn-xs btn-outline bg-slate-600 text-slate-600 border-slate-600">Cancel</button>
                            </div>
                            <button type="button" class="btn btn-xs">Attachment <i class="icon-attachment"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- <div class="page-content-wrapper">
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

    </div>

    <div class="task-detail-wrapper">
        <div class="w-100">

        </div>
    </div>

</div> -->
{%endblock%}

{%block page_scripts%}
<script>
    jQuery(document).ready(function () {
        TaskProject.init();
    });
</script>
{%endblock%}