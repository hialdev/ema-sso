{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">       
    <div class="row py-3">
        <div class="col-12">
            <h4># Your Projects  <span class="badge badge-center rounded-pill bg-primary ms-2">3</span></h4>
            <form action="">
                <div class="input-group my-3">
                    <input
                        type="text"
                        class="form-control border-0"
                        placeholder="Search Project... ( Can be Name or Status )"
                        aria-label="Search Project... ( Can be Name or Status )"
                        aria-describedby="button-addon2"
                    />
                    <button class="btn btn-primary" type="button" id="button-addon2"><span class="iconify" data-icon="fe:search"></span></button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{url('project/project-dummy')}}" class="text-dark text-decoration-none d-block">
                        <img src="/assets/img/backgrounds/18.jpg" alt="img-project" class="mb-3 d-block w-100 rounded">
                        <h4>Project Name</h4>
                        <p>Status : <span class="badge bg-label-success ms-2">Completed</span></p>
                        <div class="progress" style="height: 10px">
                            <div
                                class="progress-bar shadow-none"
                                role="progressbar"
                                style="width: 100%"
                                aria-valuenow="100"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <div class="d-flex">
                            <a href="{{url('note/p/project-dummy')}}" class="mt-3 me-2 btn btn-outline-secondary w-100 d-flex align-items-center gap-2 justify-content-center"><span class="iconify" data-icon="ic:round-sticky-note-2"></span> Note</a>
                            <a href="{{url('project/project-dummy')}}" class="mt-3 btn btn-primary w-100 d-flex align-items-center gap-2 justify-content-center">Detail <span class="iconify" data-icon="bi:arrow-right-circle-fill"></span></a>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{url('project/project-dummy')}}" class="text-dark text-decoration-none d-block">
                        <img src="/assets/img/backgrounds/18.jpg" alt="img-project" class="mb-3 d-block w-100 rounded">
                        <h4>Project Name</h4>
                        <p>Status : <span class="badge bg-label-info ms-2">On Going</span></p>
                        <div class="progress" style="height: 10px">
                            <div
                                class="progress-bar shadow-none"
                                role="progressbar"
                                style="width: 75%"
                                aria-valuenow="75"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <div class="d-flex">
                            <a href="{{url('note/p/project-dummy')}}" class="mt-3 me-2 btn btn-outline-secondary w-100 d-flex align-items-center gap-2 justify-content-center"><span class="iconify" data-icon="ic:round-sticky-note-2"></span> Note</a>
                            <a href="{{url('project/project-dummy')}}" class="mt-3 btn btn-primary w-100 d-flex align-items-center gap-2 justify-content-center">Detail <span class="iconify" data-icon="bi:arrow-right-circle-fill"></span></a>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{url('project/project-dummy')}}" class="text-dark text-decoration-none d-block">
                        <img src="/assets/img/backgrounds/18.jpg" alt="img-project" class="mb-3 d-block w-100 rounded">
                        <h4>Project Name</h4>
                        <p>Status : <span class="badge bg-label-secondary ms-2">Waiting</span></p>
                        <div class="progress" style="height: 10px">
                            <div
                                class="progress-bar shadow-none bg-secondary"
                                role="progressbar"
                                style="width: 5%"
                                aria-valuenow="5"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <div class="d-flex">
                            <a href="{{url('note/p/project-dummy')}}" class="mt-3 me-2 btn btn-outline-secondary w-100 d-flex align-items-center gap-2 justify-content-center"><span class="iconify" data-icon="ic:round-sticky-note-2"></span> Note</a>
                            <a href="{{url('project/project-dummy')}}" class="mt-3 btn btn-primary w-100 d-flex align-items-center gap-2 justify-content-center">Detail <span class="iconify" data-icon="bi:arrow-right-circle-fill"></span></a>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}