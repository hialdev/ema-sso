{%extends 'layouts/main.volt' %}

{%block page_content%}
<params  data-task-id="{{params}}"></params>
<div id="task-manager"
    data-name="My Task" data-id="{{project.id}}" data-workspace-id="{{project.id_workspace}}">
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
                    {%for task in section.getTasks(taskStatus, taskPriority)%}
                    {%set assignee = task.getAssignee()%}
                    {%set _project = task.getProject()%}
                    <li class="tm-sec-ti task-item" draggable="true" id="t-{{task.id}}"
                        data-id="{{task.id}}" data-status="{{task.status}}" data-priority="{{task.priority}}" data-due-date="{{task.due_date}}"
                        {%if assignee is not empty%}
                        data-assignee-id="{{assignee.id}}" data-assignee-name="{{assignee.name}}" data-assignee-avatar="{{assignee.getAvatarUrl()}}"
                        {%endif%}
                        >
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
                                <div class="task-project-wrapper d-md-block text-truncate d-none">
                                    {%if _project is not empty%}
                                    <span class="task-project">{{_project.name}}</span>
                                    {%endif%}
                                </div>
                                <div class="task-tag-wrapper d-md-block d-none"><span class="task-tag"></span></div>
                                <div class="task-assignee-wrapper d-md-block d-none"><span class="task-assignee"></span></div>
                                <div class="task-date-wrapper d-md-block d-none"><span class="task-due-date"></span></div>
                                <div class="task-action-wrapper d-md-block d-none"><span class="task-priority"></span></div>
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

    {% include 'pages/partials.task.detail.volt' %}

</div>
{%endblock%}

{%block page_scripts%}
<script>
    jQuery(document).ready(function () {
        MyTask.init();
    });
</script>
{%endblock%}