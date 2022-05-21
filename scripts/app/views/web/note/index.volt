{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <h2># Select Project</h2>
        <div class="row mb-4">
            <div class="col-12">
                <form>
                    <input
                        name="pq"
                        value = "{{query['pq']}}"
                        type="text"
                        class="form-control border-0 mb-2"
                        placeholder="Search Project Name"
                        aria-label="Search Project Name"
                        aria-describedby="button-addon2"
                    />
                    <div class="d-flex flex-column flex-md-row align-items-center gap-3 justify-content-between mb-3">
                        <div class="d-flex gap-2">
                            <select name="client" id="status" class="form-select border-0">
                                <option value="0">All Client</option>
                                {% for o in opt['c'] %}
                                <option value="{{o.id}}" {{o.id === query['c'] ? 'selected' : ''}} >{{o.name}}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit" id="button-addon2"><span class="iconify me-2" data-icon="fe:search"></span>Terapkan / Cari</button>
                    </div>
                </form>
            </div>
            {% for project in projects %}
            <div class="col-6 col-md-6 col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <a href="/note/p/{{project.slug}}" class="text-dark text-decoration-none d-block">
                            <img src="{{project.getImageUrl()}}" alt="{{project.name}} image" class="mb-3 d-block w-100 rounded">
                            <h4>{{project.name}}</h4>
                        </a>
                        <div class="d-flex align-items-center mb-3 gap-3">
                            <img src="{{project.Account.getAvatarUrl()}}" alt="" class="d-block rounded-circle" style="width: 3em;height:3em;object-fit: cover;">
                            <div>
                                <strong>{{project.Account.name}}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {% else %}
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        Tidak ada project untuk ditampilkan.
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
                                    data-bs-target="#confirmDelete-{{note.id}}">
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
                            {% if note.Files !== null %}
                            <div class="d-flex flex-wrap">
                                {% for file in note.Files %}
                                    <a href="{{file.getUrl()}}" target="blank" class="d-inline-flex align-items-center gap-2 bg-label-secondary p-2 rounded me-1 mb-1"><span class="iconify" data-icon="bi:file-earmark-fill"></span>{{file.name}}</a>
                                {% endfor %}
                            </div>
                            {% endif %}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modals -->
            <div class="modal fade" id="confirmDelete-{{note.id}}" tabindex="-1" aria-hidden="true">
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