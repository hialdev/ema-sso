{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <div>
            <div class="d-flex gap-2 mb-2"><span class="badge bg-primary">#TICEMA123232</span><span class="badge bg-info">Answered</span><span class="badge bg-label-danger">high</span></div>
            <h2>{{slug}}!</h2>
            <div>Project : <strong>Project 1</strong></div>
            <div class="alert alert-warning mt-2" role="alert">
                Ticket ini telah ditutup, Anda dapat membukanya dengan cara membuat balasan baru
            </div>
        </div>
        <hr>
        <div class="d-flex flex-column gap-3">
            <div class="card">
                <div class="card-body">
                    <div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, itaque? Porro ea harum repellat numquam natus fugit ab qui aperiam iusto aut eaque sunt quam, enim minus. Reprehenderit, laboriosam repudiandae.</p>
                        <h6>- You <span class="fs-6 fw-normal fst-italic ms-2">08 Des 21, 18:32 WIB</span></h6>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded"><span class="iconify" data-icon="bi:file-earmark-fill"></span>Namafile.jpg</a>
                        <a href="" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded"><span class="iconify" data-icon="bi:file-earmark-fill"></span>Namafile.jpg</a>
                    </div>
                </div>
            </div>
            <div class="card border-top border-primary">
                <div class="card-body">
                    <div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, itaque? Porro ea harum repellat numquam natus fugit ab qui aperiam iusto aut eaque sunt quam, enim minus. Reprehenderit, laboriosam repudiandae.</p>
                        <h6 class="text-primary">- OM JT <span class="fs-6 fw-normal fst-italic ms-2">08 Des 21, 18:32 WIB</span></h6>
                    </div>
                </div>
            </div>
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