{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="p-2 rounded d-inline-flex align-items-center justify-content-center bg-primary text-white">
                                <span class="iconify" data-icon="ic:round-work" style="width: 1.4em;height:1.4em"></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="{{url('project')}}">View</a>
                                <a class="dropdown-item" href="{{url('project/add')}}">Add New</a>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mb-1">Projects</span>
                    <h3 class="card-title text-nowrap mb-2 fw-bolder fs-1">{{count['project']}}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="p-2 rounded d-inline-flex align-items-center justify-content-center bg-primary text-white">
                                <span class="iconify" data-icon="ion:ticket" style="width: 1.4em;height:1.4em"></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="{{url('ticket/active')}}">Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">{{cat}}</span></a>
                                <a class="dropdown-item" href="{{url('ticket/add')}}">Add New</a>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mb-1">Ticket</span>
                    <h3 class="card-title text-nowrap mb-2 fw-bolder fs-1">{{count['ticket']}}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="p-2 rounded d-inline-flex align-items-center justify-content-center bg-primary text-white">
                                <span class="iconify" data-icon="fa-solid:users" style="width: 1.4em;height:1.4em"></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="{{url('clients')}}">View</a>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mb-1">Clients</span>
                    <h3 class="card-title text-nowrap mb-2 fw-bolder fs-1">{{count['client']}}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="p-2 rounded d-inline-flex align-items-center justify-content-center bg-primary text-white">
                                <span class="iconify" data-icon="fluent:brain-circuit-24-filled" style="width: 1.4em;height:1.4em"></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="{{url('knowladge')}}">View</a>
                                <a class="dropdown-item" href="{{url('knowladge/add')}}">Add New</a>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mb-1">Knowladges</span>
                    <h3 class="card-title text-nowrap mb-2 fw-bolder fs-1">{{count['blog']}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="py-3">
        <h4># Latest Ticket</h4>
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Project</th>
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
                            <td class="border-0">{{ticket.getProject().name}}</td>
                            <td class="border-0">
                                {% if ticket.status === '1' %}
                                    <span class="badge bg-label-secondary">Low</span>
                                {% elseif ticket.status === '2' %}
                                    <span class="badge bg-label-info">Medium</span>
                                {% elseif ticket.status === '3' %}
                                    <span class="badge bg-label-danger">High</span>
                                {% endif %}
                            </td>
                            <td class="border-0">
                                {% if ticket.status === '1' %}
                                    <span class="badge bg-label-secondary">Waiting</span>
                                {% elseif ticket.status === '2' %}
                                    <span class="badge bg-label-info">Answered</span>
                                {% elseif ticket.status === '3' %}
                                    <span class="badge bg-label-success">Completed</span>
                                {% endif %} 
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
                        {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{% endblock %}