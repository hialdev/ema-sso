{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <a href="{{url(backUrl)}}" class="d-inline-flex align-items-center gap-2 mb-3"><span class="iconify fs-2" data-icon="ion:arrow-back-circle"></span> Back</a>
        <img src="{{note.Project.image}}" alt="" class="mb-3 d-block w-100 rounded" style="max-height:20em; object-fit:cover">
        <div class="d-flex gap-2 align-items-center justify-content-between">
            <div>
                <div class="d-flex gap-2">
                    <span class="badge bg-label-{{note.Project.Status.css}}">{{note.Project.Status.name}}</span>
                </div>
                <h1>{{note.Project.name}}</h1>
                <p>{{note.Project.excerpt}}</p>
            </div>
            <a href="{{url('note/add')}}" class="btn btn-primary d-inline-flex align-items-center gap-2 ">Add New</a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2">
                            <span style="font-size:10px">{{note.modified}} WIB last modified by <strong>{{note.Modifer.name}}</strong></span>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/note/{{note.slug}}/edit"
                                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                    >
                                    <a class="text-danger dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#confirmDelete">
                                        <i class="bx bx-trash me-1"></i> Delete</a
                                    >
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4>{{note.title}}</h4>
                            <div>
                                {{note.note}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {% if note.Files !== null %}
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            {% for file in note.Files %}
                                <a href="{{file.getUrl()}}" target="blank" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded"><span class="iconify" data-icon="bi:file-earmark-fill"></span>{{file.name}}</a>
                            {% endfor %}
                        </div>
                    </div>
                </div>
            </div>
            {% endif %}

        </div>
        <!-- Modals -->
        <div class="modal fade" id="confirmDelete" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteTitle">Menghapus Note {{note.title}}?</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <p>Note / Catatan yang dihapus tidak dapat dikembalikan lagi.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <a href="/note/{{note.slug}}/delete" class="btn btn-primary">Ya, Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modals -->
    </div>
</div>
{% endblock %}