{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="p-2 rounded d-inline-flex align-items-center justify-content-center bg-primary text-white">
                                <span class="iconify" data-icon="ic:round-work" style="width: 1.4em;height:1.4em"></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="./projects.html">View</a>
                                <a class="dropdown-item" href="https://wa.me/6289671052050">Add New</a>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mb-1">Projects</span>
                    <h3 class="card-title text-nowrap mb-2 fw-bolder fs-1">10</h3>
                </div>
            </div>
        </div>
        <div class="col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="p-2 rounded d-inline-flex align-items-center justify-content-center bg-primary text-white">
                                <span class="iconify" data-icon="ion:ticket" style="width: 1.4em;height:1.4em"></span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="./ticket-active.html">Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">1</span></a>
                                <a class="dropdown-item" href="./ticket-add.html">Add New</a>
                            </div>
                        </div>
                    </div>
                    <span class="d-block mb-1">Ticket</span>
                    <h3 class="card-title text-nowrap mb-2 fw-bolder fs-1">10</h3>
                </div>
            </div>
        </div>

    </div>
    <div class="py-3">
        <h4># Your Ticket</h4>
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
                        <tr>
                            <td class="border-0"><strong>TICEMA675243</strong></td>
                            <td class="border-0">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis sunt, corporis molestias.
                                <br>
                                <span class="text-secondary" style="font-size: 12px;">08/02/21, 14:23 WIB</span>
                            </td class="border-0">
                            <td class="border-0">Project 2</td>
                            <td class="border-0"><span class="badge bg-label-danger">High</span></td>
                            <td class="border-0"><span class="badge bg-label-info me-1">Answered</span></td>
                            <td class="border-0"><a href="./ticket-view.html" class="btn btn-primary">View</a></td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <div class="card bg-light">
                                    <div class="card-header bg-label-secondary py-1">
                                        Latest Reply
                                    </div>
                                    <div class="card-body py-3">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit eveniet officia</p>
                                        <h6>- OM JT</h6>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-0"><strong>TICEMA675123</strong></td>
                            <td class="border-0">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis sunt, corporis molestias.
                                <br>
                                <span class="text-secondary" style="font-size: 12px;">08/02/21, 14:23 WIB</span>
                            </td class="border-0">
                            <td class="border-0">Project 1</td>
                            <td class="border-0"><span class="badge bg-label-danger">High</span></td>
                            <td class="border-0"><span class="badge bg-label-secondary me-1">Waiting</span></td>
                            <td class="border-0"><a href="./ticket-view.html" class="btn btn-primary">View</a></td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <div class="card bg-light">
                                    <div class="card-header bg-label-secondary py-1">
                                        Latest Reply
                                    </div>
                                    <div class="card-body py-3">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit eveniet officia</p>
                                        <h6>- You</h6>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{% endblock %}