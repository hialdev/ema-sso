{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">  
    <div class="py-3">
        <a href="projects.html" class="d-inline-flex align-items-center gap-2 mb-3"><span class="iconify fs-2" data-icon="ion:arrow-back-circle"></span> Back to Projects</a>
        <img src="/assets/img/backgrounds/18.jpg" alt="" class="mb-3 d-block w-100 rounded" style="max-height:20em; object-fit:cover">
        <div class="d-flex flex-column flex-md-row gap-2 align-items-start justify-content-between">
            <div>
                <div class="d-flex gap-2">
                    <span class="badge bg-label-info">ON GOING</span>
                </div>
                <h1>Project Name</h1>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatem et, minus aliquid voluptates qui eos voluptate culpa minima obcaecati velit eaque asperiores porro ducimus natus, quam cumque perspiciatis debitis mollitia?</p>
                <p>Kamis, 08 Mei 2022</p>
            </div>
            <a href="{{url('note/p/project-dummy')}}" class="btn btn-secondary d-inline-flex align-items-center gap-2 "><span class="iconify" data-icon="ic:round-sticky-note-2"></span> Notes</a>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h4># Task List Status</h4>
                        <hr>
                        <ul class="list-unstyled">
                            <li class="mb-2 d-flex align-items-center gap-2 text-secondary">
                                <span class="iconify" data-icon="ant-design:clock-circle-filled"></span>
                                Task / Fitur Project yang waiting
                                <span class="text-secondary fst-italic align-self-end" style="font-size:10px">08/04/22,14:23 WIB</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center gap-2 text-secondary">
                                <span class="iconify" data-icon="ant-design:clock-circle-filled"></span>
                                Task / Fitur Project yang waiting
                                <span class="text-secondary fst-italic align-self-end" style="font-size:10px">08/04/22,14:23 WIB</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center gap-2 text-success">
                                <span class="iconify" data-icon="akar-icons:circle-check-fill"></span>
                                Task / Fitur Project yang complete
                                <span class="text-secondary fst-italic align-self-end" style="font-size:10px">08/04/22,14:23 WIB</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center gap-2 text-info">
                                <span class="iconify" data-icon="fluent:sound-wave-circle-24-filled"></span>
                                Task / Fitur Project yang on going
                                <span class="text-secondary fst-italic align-self-end" style="font-size:10px">08/04/22,14:23 WIB</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5># Statistik</h5>
                        <hr>
                        <ul class="list-unstyled">
                            <li class="mb-2">Task : <span class="fw-bold">4</span></li>
                            <li class="mb-2 d-flex align-items-center gap-2 text-success">
                                <span class="iconify" data-icon="akar-icons:circle-check-fill"></span>
                                complete <span class="badge bg-label-success rounded-circle">1</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center gap-2 text-info">
                                <span class="iconify" data-icon="fluent:sound-wave-circle-24-filled"></span>
                                on going <span class="badge bg-label-info rounded-circle">1</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center gap-2 text-secondary">
                                <span class="iconify" data-icon="ant-design:clock-circle-filled"></span>
                                waiting  <span class="badge bg-label-secondary rounded-circle">2</span>
                            </li>
                            <li class="pt-2">
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
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <h4># Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">2</span></h4>
                <form action="">
                    <div class="input-group my-3">
                        <input
                        type="text"
                        class="form-control border-0"
                        placeholder="Search Ticket... ( Can be INV, Subject, Priority, Status )"
                        aria-label="Search Ticket... ( Can be INV, Subject, Priority, Status )"
                        aria-describedby="button-addon2"
                        />
                        <button class="btn btn-primary" type="button" id="button-addon2"><span class="iconify" data-icon="fe:search"></span></button>
                    </div>
                </form>

                <div class="card">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
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
                                    <td class="border-0"><span class="badge bg-label-danger">High</span></td>
                                    <td class="border-0"><span class="badge bg-label-info me-1">Answered</span></td>
                                    <td class="border-0"><a href="{{url('ticket/v/ticket-dummy')}}" class="btn btn-primary">View</a></td>
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
                                    <td class="border-0"><span class="badge bg-label-danger">High</span></td>
                                    <td class="border-0"><span class="badge bg-label-secondary me-1">Waiting</span></td>
                                    <td class="border-0"><a href="{{url('ticket/v/ticket-dummy')}}" class="btn btn-primary">View</a></td>
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
    </div>
</div>
{% endblock %}