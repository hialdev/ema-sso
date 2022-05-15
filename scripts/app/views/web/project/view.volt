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
                <h4># Active Ticket {{project.name}}<span class="badge badge-center rounded-pill bg-primary ms-2">{{count}}</span></h4>
                <form action="">
                    <input
                        name="q"
                        value = "{{query['q']}}"
                        type="text"
                        class="form-control border-0 mb-2"
                        placeholder="Search No Ticket"
                        aria-label="Search No Ticket"
                        aria-describedby="button-addon2"
                    />
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3 justify-content-between mb-3">
                        <div class="d-flex gap-2">
                            <select name="priority" id="priority" class="form-select border-0">
                                <option value="0">All Priority</option>
                                {% for o in opt['p'] %}
                                    <option value="{{o.id}}" {{o.id === query['p'] ? 'selected' : ''}} >{{o.name}}</option>
                                {% endfor %}
                            </select>
                            <select name="status" id="status" class="form-select border-0">
                                <option value="0">All Status</option>
                                {% for o in opt['s'] %}
                                <option value="{{o.id}}" {{o.id === query['s'] ? 'selected' : ''}} >{{o.name}}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit" id="button-addon2"><span class="iconify me-2" data-icon="fe:search"></span>Terapkan / Cari</button>
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
                                {% for ticket in tickets %}
                                <tr>
                                    <td class="border-0"><strong>{{ticket.no}}</strong></td>
                                    <td class="border-0">
                                        {{ticket.subject}}
                                        <br>
                                        <span class="text-secondary" style="font-size: 12px;">{{ticket.created}}</span>
                                    </td class="border-0">
                                    <td class="border-0">{{ticket.Project.name}}</td>
                                    <td class="border-0">
                                        <span class="badge bg-{{ticket.Priority.css}}">{{ticket.Priority.name}}</span>
                                    </td>
                                    <td class="border-0">
                                        <span class="badge bg-label-{{ticket.Status.css}}">{{ticket.Status.name}}</span>
                                    </td>
                                    <td class="border-0"><a href="/ticket/v/{{ticket.slug}}" class="btn btn-primary">View</a></td>
                                </tr>
                                <tr>
                                    <td colspan="10">
                                        <div class="card bg-light">
                                            <div class="card-header bg-label-secondary py-1">
                                                Latest Reply
                                            </div>
                                            <div class="card-body py-3">
                                                <p style="word-wrap: break-word;white-space: normal;"><?= $ticket->getChat()->getLast()->content ?></p>
                                                <h6>- <?= $ticket->getChat()->getLast()->getAccount()->name ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {% else %}
                                <tr>
                                    <td colspan="10">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                Tidak ada ticket untuk ditampilkan, Mohon sesuaikan filter pencarian anda.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {% endfor %}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}