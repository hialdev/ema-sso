{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
    
    {% if ticket.status !== '3' %}
    <!-- Modals -->
    <div class="modal fade" id="confirmClose" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCloseTitle">Close Ticket {{ticket.subject}}?</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>Setelah anda menutup ticket ini, anda masih bisa membukanya dengan membuat balasan baru.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <a href="/ticket/{{ticket.slug}}/close" class="btn btn-success">Close Ticket</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modals -->
    {% endif %}

    <div class="py-3">
        <div>
            <div class="d-flex gap-2 mb-2"><span class="badge bg-primary">{{ticket.no}}</span>
                <span class="badge bg-{{ticket.Priority.css}}">{{ticket.Priority.name}}</span>
                <span class="badge bg-label-{{ticket.Status.css}}">{{ticket.Status.name}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>{{ticket.subject}}</h2>
                    <div>Project : <strong>{{ticket.Project.name}}</strong></div>
                </div>
                {% if ticket.status !== '3' %}
                <div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmClose">Close Ticket</button>
                </div>
                {% endif %}
            </div>
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
            
            <form method="POST">
                <div class="card">
                    <h6 class="card-header">Add New Reply</h6>
                    <div class="card-body">
                        <textarea name="content" rows="10" class="form-control mb-3" id="editor"></textarea>
                        <input type="file" name="file" class="form-control mb-3 mt-3" multiple>
                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">Send <span class="iconify" data-icon="fa6-solid:paper-plane"></span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{% endblock %}

{% block js %}
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ),{
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        } )
        .catch( error => {
            console.error( error );
        } );
</script>
{% endblock %}
