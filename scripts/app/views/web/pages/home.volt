{%extends 'layouts/main.volt' %}

{%block page_content%}
<div class="p-3">

    <div class="text-center mb-3">
        <h4 class="mb-0">{{tanggal}}</h4>
        <h1 class="font-weight-bold">{{greeting}}, {{account.name}}</h1>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card card-softened card-rounded">
                <div class="card-header header-elements-inline with-border-light">
                    <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                        <i class="far fa-calendar-check mr-1"></i> My Task Priorities
                    </h6>
                </div>
                <div class="card-body" style="min-height: 300px;" >
                    <div class="list-tasks pl-md-1 pr-md-1">
                        <!-- <div class="d-flex align-items-center border-bottom">
                            <div><i class="far fa-check-circle text-muted"></i></div>
                            <div class="flex-grow-1 ml-2">
                                <input class="form-control nohilite border-0 pb-0 pt-0" placeholder="add new task here ...">
                            </div>
                        </div> -->
                        {%if myPriorities is not empty%}
                        {%for p in myPriorities %}
                        {%set link = p.task.id_project == 0 ? "mytask/" ~ p.task.id : "task/" ~ p.project.slug ~ "/" ~ p.task.id%}
                        <a href="{{link}}" class="home-task-item">
                            <div>{{p.task.getStatusIcon()}}</div>
                            <div class="flex-grow-1 ml-2 pt-2 pb-2 tooltips" title="{{p.task.getDueDateText()}}">
                                <div>{{p.task.name}}</div>
                            </div>
                            <div>{{p.task.getPriorityIcon()}}</div>
                        </a>
                        {%endfor%}
                        {%else%}
                        <div class="text-muted font-italic">
                            No High and Urget Priorities Task assigned or created for you.
                        </div>
                        {%endif%}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-softened card-rounded">
                <div class="card-header header-elements-inline with-border-light">
                    <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                        <i class="icon-puzzle2 mr-1"></i> My Projects
                    </h6>
                </div>
                <div class="card-body" style="min-height: 300px;" >
                    <div class="row">
                        <!-- <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="border rounded mr-2 d-flex align-items-center justify-content-center" style="height: 50px; width: 50px;">
                                    <i class="icon-plus3 text-muted"></i>
                                </div>
                                <div>
                                    <div class="font-weight-semibold text-muted">New Project</div>
                                </div>
                            </div>
                        </div> -->
                        {%for _project in projectList%}
                        <div class="col-md-6 mt-2">
                            <a class="project-item" href="task/{{_project['slug']}}">
                                <div class="project-icon-wrapper">
                                    <div class="project-icon">
                                        <i class="icon-list text-muted"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-semibold">{{_project['name']}}</div>
                                    <div class="small">{{_project['workspace']}}</div>
                                </div>
                            </a>
                        </div>
                        {%endfor%}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{%endblock%}

{%block page_scripts%}
<script>
    /* jQuery(document).ready(function() {Dashboard.init();}); */
</script>
{%endblock%}