{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <h2># Select Project</h2>
        <div class="row">
            {% for project in projects %}
            <div class="col-6 col-md-6 col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <a href="/note/p/{{project.slug}}" class="text-dark text-decoration-none d-block">
                            <img src="{{project.image}}" alt="img-project" class="mb-3 d-block w-100 rounded">
                            <h4>{{project.name}}</h4>
                        </a>
                    </div>
                </div>
            </div>
            {% else %}
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        Belum ada project untuk anda.
                    </div>
                </div>
            </div>
            {% endfor %}
        </div>
        <h4># Discover Notes</h4>
        <div class="row">
            <div class="col-12">
                <form action="">
                    <div class="input-group my-3">
                        <input
                            name = "q"
                            type="text"
                            value = "{{q}}"
                            class="form-control border-0"
                            placeholder="Search Title Note..."
                            aria-label="Search Title Note..."
                            aria-describedby="button-addon2"
                        />
                        <button class="btn btn-primary" type="submit" id="button-addon2"><span class="iconify" data-icon="fe:search"></span></button>
                    </div>
                </form>
            </div>
            {% for note in notes %}
            <div class="col-12 col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2">
                            <div>
                                Project : <strong>{{note.Project.name}}</strong>
                                <br><span style="font-size:10px">{{note.modified}} WIB last modified with <strong>{{note.Modifer.name}}</strong></span>
                            </div>
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
                        <hr>
                        <a href="/note/v/{{note.slug}}" class="d-block text-dark">
                            <h4>{{note.title}}</h4>
                            <div>
                                {{note.note}}
                            </div>
                            {% if note.file !== null %}
                            <div class="d-flex flex-wrap gap-2">
                                {% for key,path in note.files(note) %}
                                    <a href="{{path}}" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded"><span class="iconify" data-icon="bi:file-earmark-fill"></span>{{key}}</a>
                                {% endfor %}
                            </div>
                            {% endif %}
                        </a>
                    </div>
                </div>
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
            {% else %}
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        Tidak ada data note untuk ditampilkan.
                    </div>
                </div>
            </div>
            {% endfor %}
        </div>
    </div>
</div>
{% endblock %}