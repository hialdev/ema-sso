{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">  
    <div class="py-3">
        <a href="{{url('project')}}" class="d-inline-flex align-items-center gap-2 mb-3"><span class="iconify fs-2" data-icon="ion:arrow-back-circle"></span> Back to Projects</a>
        <img src="{{project.image}}" alt="" class="mb-3 d-block w-100 rounded" style="max-height:20em; object-fit:cover">
        <div class="d-flex flex-column flex-md-row gap-2 align-items-start justify-content-between">
            <div>
                <div class="d-flex gap-2">
                    <span class="badge bg-label-{{project.Status.css}}">{{project.Status.name}}</span>
                </div>
                <h1>{{project.name}}</h1>
                <p>{{project.excerpt}}</p>
                <p>{{project.created}}</p>
            </div>
            <a href="/note/p/{{project.slug}}" class="btn btn-secondary d-inline-flex align-items-center gap-2 "><span class="iconify" data-icon="ic:round-sticky-note-2"></span> Notes</a>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4># Task List Status</h4>
                        <hr>
                        <ul class="list-unstyled">
                            {% for task in project.Tasks %}
                            <li class="mb-2 d-flex align-items-center gap-2 text-{{task.Status.css}}">
                                <span class="iconify" data-icon="{{task.Status.icon}}"></span>
                                {{task.name}}
                                <span class="text-{{task.Status.css}} fst-italic align-self-end" style="font-size:10px">{{task.created}}</span>
                            </li>
                            {% else %}
                            <li>
                                <div class="p-3 text-center">Belum ada task</div>
                            </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5># Statistik</h5>
                        <hr>
                        <ul class="list-unstyled">
                            <li class="mb-2">Task : <span class="fw-bold">4</span></li>
                            {% for s in status %}
                            <li class="mb-2 d-flex align-items-center gap-2 text-{{s.css}}">
                                <span class="iconify" data-icon="{{s.icon}}"></span>
                                {{s.name}} <span class="badge bg-label-{{s.css}} rounded-circle">{{project.countStatus(project,s.id)}}</span>
                            </li>
                            {% endfor %}
                            <li class="pt-2">
                                <div class="progress" style="height: 10px">
                                    <div
                                        class="progress-bar shadow-none"
                                        role="progressbar"
                                        style="width: {{project.bar(project)}}%"
                                        aria-valuenow="{{project.bar(project)}}"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    ></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <h4># Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">2</span></h4>
                <form action="">
                    <div class="input-group my-3">
                        <input
                        type="text"
                        class="form-control border-0"
                        placeholder="Search Ticket... ( Can be INV, Subject, Priority, Status )"
                        aria-label="Search Ticket... ( Can be INV, Subject, Priority, Status )"
                        aria-describedby="button-addon2"
                        />
                        <button class="btn btn-primary" type="button" id="button-addon2"><span class="iconify" data-icon="fe:search"></span></button>
                    </div>
                </form>

                <div class="card">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td class="border-0"><strong>TICEMA675243</strong></td>
                                    <td class="border-0">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis sunt, corporis molestias.
                                        <br>
                                        <span class="text-secondary" style="font-size: 12px;">08/02/21, 14:23 WIB</span>
                                    </td class="border-0">
                                    <td class="border-0"><span class="badge bg-label-danger">High</span></td>
                                    <td class="border-0"><span class="badge bg-label-info me-1">Answered</span></td>
                                    <td class="border-0"><a href="{{url('ticket/v/ticket-dummy')}}" class="btn btn-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td colspan="10">
                                        <div class="card bg-light">
                                            <div class="card-header bg-label-secondary py-1">
                                                Latest Reply
                                            </div>
                                            <div class="card-body py-3">
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit eveniet officia</p>
                                                <h6>- OM JT</h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0"><strong>TICEMA675123</strong></td>
                                    <td class="border-0">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis sunt, corporis molestias.
                                        <br>
                                        <span class="text-secondary" style="font-size: 12px;">08/02/21, 14:23 WIB</span>
                                    </td class="border-0">
                                    <td class="border-0"><span class="badge bg-label-danger">High</span></td>
                                    <td class="border-0"><span class="badge bg-label-secondary me-1">Waiting</span></td>
                                    <td class="border-0"><a href="{{url('ticket/v/ticket-dummy')}}" class="btn btn-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td colspan="10">
                                        <div class="card bg-light">
                                            <div class="card-header bg-label-secondary py-1">
                                                Latest Reply
                                            </div>
                                            <div class="card-body py-3">
                                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit eveniet officia</p>
                                                <h6>- You</h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}