{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <h4># Active Ticket <span class="badge badge-center rounded-pill bg-primary ms-2">1</span></h4>
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
                            <td class="border-0">Project 1</td>
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
{% endblock %}