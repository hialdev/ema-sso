{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <h4># Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">{{cat}}</span></h4>
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
{% endblock %}