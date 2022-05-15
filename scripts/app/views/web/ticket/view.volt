{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <div>
            <div class="d-flex gap-2 mb-2"><span class="badge bg-primary">{{ticket.no}}</span>
                <span class="badge bg-{{ticket.Priority.css}}">{{ticket.Priority.name}}</span>
                <span class="badge bg-label-{{ticket.Status.css}}">{{ticket.Status.name}}</span>
            </div>
            <h2>{{ticket.subject}}</h2>
            <div>Project : <strong>{{ticket.Project.name}}</strong></div>
            {% if ticket.status === '3' %}
            <div class="alert alert-warning mt-2" role="alert">
                Ticket ini telah ditutup, Anda dapat membukanya dengan cara membuat balasan baru
            </div>
            {% endif %}
        </div>
        <hr>
        <div class="d-flex flex-column gap-3">
            
            {% for chat in ticket.chat %}
            <div class="card {{ chat.Account.uid !== uid ? 'border-top border-primary' : ''}}">
                <div class="card-body">
                    <div>
                        <p>{{chat.content}}</p>
                        <h6
                        class="
                        {{ chat.Account.uid !== uid ? 'text-primary' : ''}}
                        ">- {{chat.Account.name}} <span class="fs-6 fw-normal fst-italic ms-2">{{chat.created}}</span></h6>
                    </div>
                    {% if chat.file !== null %}
                    <div class="d-flex flex-wrap gap-2">
                        <a href="" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded"><span class="iconify" data-icon="bi:file-earmark-fill"></span>Namafile.jpg</a>
                        <a href="" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded"><span class="iconify" data-icon="bi:file-earmark-fill"></span>Namafile.jpg</a>
                    </div>
                    {% endif %}
                </div>
            </div>
            {% endfor %}
            
            <form action="">
                <div class="card">
                    <h6 class="card-header">Add New Reply</h6>
                    <div class="card-body">
                        <textarea name="" rows="10" class="form-control mb-3"></textarea>
                        <input type="file" name="" class="form-control mb-3" multiple>
                        <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">Send <span class="iconify" data-icon="fa6-solid:paper-plane"></span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{% endblock %}