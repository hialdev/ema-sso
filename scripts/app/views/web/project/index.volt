{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">       
    <div class="row py-3">
        <div class="col-12">
            <h4># All Projects  <span class="badge badge-center rounded-pill bg-primary ms-2">3</span></h4>
            <form action="">
                <input
                    name="q"
                    value = "{{query['q']}}"
                    type="text"
                    class="form-control border-0 mb-2"
                    placeholder="Search Project Name"
                    aria-label="Search Project Name"
                    aria-describedby="button-addon2"
                />
                <div class="d-flex flex-column flex-md-row align-items-center gap-3 justify-content-between mb-3">
                    <div class="d-flex gap-2">
                        <select name="status" id="status" class="form-select border-0">
                            <option value="0">All Status</option>
                            {% for o in opt['s'] %}
                            <option value="{{o.id}}" {{o.id === query['s'] ? 'selected' : ''}} >{{o.name}}</option>
                            {% endfor %}
                        </select>
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
        <div class="col-12 col-md-6 col-lg-4">
            <!-- Modals -->
            <div class="modal fade" id="confirmDelete-{{project.id}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteTitle">Menghapus Project : {{project.name}}?</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <div class="modal-body">
                            <p>Project yang dihapus tidak dapat dikembalikan lagi. (Seluruh task, note, file pada project juga akan dihapus)</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <a href="/project/{{project.slug}}/delete" class="btn btn-primary">Ya, Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Modals -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-dark">
                        <a href="/project/{{project.slug}}" class="text-decoration-none text-dark d-block">
                            <img src="{{project.getImageUrl()}}" alt="img-project" class="mb-3 d-block w-100 rounded">
                            <h4>{{project.name}}</h4>
                        </a>
                        <p>Status : <span class="badge bg-label-{{project.Status.css}} ms-2">{{project.Status.name}}</span></p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center mb-3 gap-3">
                                <img src="{{project.Account.getAvatarUrl()}}" alt="" class="d-block rounded-circle" style="width: 3em;height:3em;object-fit: cover;">
                                <div>
                                    <strong>{{project.Account.name}}</strong>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/project/{{project.slug}}/edit"
                                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                    >
                                    <a class="dropdown-item" href="/project/{{project.slug}}">
                                        <i class="bx bx-list-ol me-1"></i> Manage Task</a
                                    >
                                    <a class="text-danger dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                    data-bs-target="#confirmDelete-{{project.id}}">
                                        <i class="bx bx-trash me-1"></i> Delete</a
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="progress" style="height: 10px">
                            <div
                                class="progress-bar shadow-none"
                                role="progressbar"
                                style="width: {{project.bar(project)}}%"
                                aria-valuenow="{{project.bar(project)}}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <div class="d-flex">
                            <a href="/note/p/{{project.slug}}" class="mt-3 me-2 btn btn-outline-secondary w-100 d-flex align-items-center gap-2 justify-content-center"><span class="iconify" data-icon="ic:round-sticky-note-2"></span> Note</a>
                            <a href="/project/{{project.slug}}" class="mt-3 btn btn-primary w-100 d-flex align-items-center gap-2 justify-content-center">Detail <span class="iconify" data-icon="bi:arrow-right-circle-fill"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {% else %}
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    Tidak ada data project untuk ditampilkan.
                </div>
            </div>
        </div>
        {% endfor %}
        
    </div>
</div>
{% endblock %}