{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <a href="{{url('ticket')}}" class="d-inline-flex align-items-center gap-2 mb-3"><span class="iconify fs-2" data-icon="ion:arrow-back-circle"></span> Back to Ticket</a>
        <h4># History Answer</h4>
        <form>
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
                    <select name="client" id="client" class="form-select border-0">
                        <option value="0">All Staff</option>
                        {% for o in opt['u'] %}
                        <option value="{{o.id}}" {{o.id === query['u'] ? 'selected' : ''}} >{{o.name}}</option>
                        {% endfor %}
                    </select>
                </div>
                <button class="btn btn-primary" type="submit" id="button-addon2"><span class="iconify me-2" data-icon="fe:search"></span>Terapkan / Cari</button>
            </div>
        </form>

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <tbody class="table-border-bottom-0">
                        
                        {% for chat in chats %}
                        <tr>
                            <td colspan="10">
                                <div class="card bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center mb-3 gap-3">
                                            <img src="{{chat.Account.getAvatarUrl()}}" alt="{{chat.Account.name}} image" class="d-block rounded-circle" style="width: 3em;height:3em;object-fit: cover;">
                                            <div>
                                                <strong>{{chat.Account.name}}</strong>
                                            </div>
                                        </div>
                                        <a href="/ticket/v/{{chat.Ticket.slug}}" class="btn btn-primary">View</a>
                                    </div>
                                    <div class="card-header fw-bold bg-label-{{chat.Ticket.Status.css}} py-1">
                                        {{chat.Ticket.no}} - {{chat.Ticket.subject}}
                                    </div>
                                    <div class="card-body py-3">
                                        <p style="white-space: normal;">{{chat.content}}</p>
                                        <p style="font-size: 10px">{{chat.created}}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {% else %}
                        <tr>
                            <td colspan="10">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        Tidak ada data history answer untuk ditampilkan.
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