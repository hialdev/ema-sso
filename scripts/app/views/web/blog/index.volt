{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="row py-3">
        <div class="col-12">
            <div class="d-flex mb-3 align-items-center justify-content-between">
                <h4># All Knowladges</h4>
                <a href="{{url('knowladge/add')}}" class="btn btn-primary">Add New</a>
            </div>
            <form action="">
                <div class="input-group my-3">
                    <input
                        name="q"
                        value="{{qq}}"
                        type="text"
                        class="form-control border-0"
                        placeholder="Search Knowladge.."
                        aria-label="Search Knowladge.."
                        aria-describedby="button-addon2"
                    />
                    <button class="btn btn-primary" type="button" id="button-addon2"><span class="iconify" data-icon="fe:search"></span></button>
                </div>
            </form>
            {% if blogs.getFirst() !== blogs.getLast() %}
            <!-- Basic Pagination -->
            <nav aria-label="Page navigation">
                <div>Page {{blogs.getCurrent()}} of {{blogs.getLast()}}</div>
                <ul class="pagination">
                    <li class="page-item first">
                        <a class="page-link" href="/knowladge"
                        ><i class="tf-icon bx bx-chevrons-left"></i
                        ></a>
                    </li>
                    <li class="page-item prev">
                        <a class="page-link" href="/knowladge?page={{blogs.getPrevious()}}"
                        ><i class="tf-icon bx bx-chevron-left"></i
                        ></a>
                    </li>
                    <li class="page-item next">
                        <a class="page-link" href="/knowladge?page={{blogs.getNext()}}"
                        ><i class="tf-icon bx bx-chevron-right"></i
                        ></a>
                    </li>
                    <li class="page-item last">
                        <a class="page-link" href="/knowladge?page={{blogs.getLast()}}"
                        ><i class="tf-icon bx bx-chevrons-right"></i
                        ></a>
                    </li>
                </ul>
            </nav>
            <!--/ Basic Pagination -->
            {% endif %}
            
        </div>
        
        {% for blog in blogs.getItems() %}
        <div class="col-12 col-lg-6">
            <div class="card mb-4">
                <div class="row card-body p-2 py-3">
                    <div class="col-2">
                        <img src="{{blog['image']}}" alt="" class="rounded w-100">
                    </div>
                    <div class="col-9">
                        <h6>{{blog['title']}}</h6>
                        <p style="font-size:10px">{{blog['created']}}</p>
                        <p>{{blog['excerpt']}}</p>
                    </div>
                    <div class="col-1">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/knowladge/{{blog['slug']}}"
                                    ><i class="bx bx-brain me-1"></i> View</a
                                >
                                <a class="dropdown-item" href="/knowladge/{{blog['slug']}}/edit"
                                    ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                >
                                <a class="text-danger dropdown-item" href="" data-bs-toggle="modal"
                                    data-bs-target="#confirmDelete-{{blog['id']}}">
                                    <i class="bx bx-trash me-1"></i> Delete</a
                                >
                            </div>
                        </div>
                    </div>
                    <!-- Modals -->
                    <div class="modal fade" id="confirmDelete-{{blog['id']}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteTitle">Menghapus {{blog['title']}}?</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    ></button>
                                </div>
                                <div class="modal-body">
                                    <p>Knowladge yang dihapus tidak dapat dikembalikan lagi.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <a href="/knowladge/{{blog['slug']}}/delete" class="btn btn-primary">Ya, Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end Modals -->
                </div>
            </div>
        </div>
        {% else %}
            
        {% endfor %}

    </div>
</div>
{% endblock %}