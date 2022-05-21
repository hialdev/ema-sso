{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <h4># All Your Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">{{count}}</span></h4>
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
                    <select name="client" id="client" class="form-select border-0">
                        <option value="0">All Client</option>
                        {% for o in opt['c'] %}
                        <option value="{{o.id}}" {{o.id === query['c'] ? 'selected' : ''}} >{{o.name}}</option>
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
                                <span class="badge bg-{{ticket.Priority.css}}">{{ticket.Priority.name}}</span>
                            </td>
                            <td class="border-0">
                                <span class="badge bg-label-{{ticket.Status.css}}">{{ticket.Status.name}}</span>
                            </td>
                            <td class="border-0"><a href="/ticket/v/{{ticket.slug}}" class="btn btn-primary">View</a></td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center mb-3 gap-3">
                                        <img src="{{ticket.Account.getAvatarUrl()}}" alt="" class="d-block rounded-circle" style="width: 3em;height:3em;object-fit: cover;">
                                        <div>
                                            <strong>{{ticket.Account.name}}</strong>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="./project-edit.html"
                                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                            >
                                            <a class="dropdown-item" href="javascript:void(0);">
                                                <i class="bx bx-list-ol me-1"></i> Manage Task</a
                                            >
                                        </div>
                                    </div>
                                </div>
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
                                        Tidak ada data untuk ditampilkan, Mohon sesuaikan filter pencarian anda.
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