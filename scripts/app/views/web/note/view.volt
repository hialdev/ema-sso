{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <a href="{{url(backUrl)}}" class="d-inline-flex align-items-center gap-2 mb-3"><span class="iconify fs-2" data-icon="ion:arrow-back-circle"></span> Back</a>
        <img src="/assets/img/backgrounds/18.jpg" alt="" class="mb-3 d-block w-100 rounded" style="max-height:20em; object-fit:cover">
        <div class="d-flex gap-2 align-items-center justify-content-between">
            <div>
                <div class="d-flex gap-2">
                    <span class="badge bg-label-info">ON GOING</span>
                </div>
                <h1>Project Name</h1>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatem et, minus aliquid voluptates qui eos voluptate culpa minima obcaecati velit eaque asperiores porro ducimus natus, quam cumque perspiciatis debitis mollitia?</p>
            </div>
            <a href="" class="btn btn-primary d-inline-flex align-items-center gap-2 ">Add New</a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2">
                            <span style="font-size:10px">Kam, 08 Mei 22 - 18:32 WIB last modified</span>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"
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
                            <h4>{{slug}}</h4>
                            <div>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium inventore, nam veniam et labore fuga ducimus nihil nobis repudiandae. Repellat iste dolore quasi magnam expedita, doloribus ea eos nulla est?
                                Reprehenderit culpa repudiandae ea quo maxime harum voluptatibus rem repellat ab, beatae impedit! Iure quaerat recusandae non est voluptates minima, eveniet amet asperiores ducimus molestias, iste reiciendis aspernatur natus fugiat!
                                Aspernatur assumenda autem tenetur pariatur quam nesciunt, ipsa, in possimus esse facere tempore dolorem suscipit inventore sunt. Veniam, recusandae vel, tempora nemo eius, sequi dolores quo repellendus libero suscipit omnis?
                                Quasi ipsum blanditiis praesentium voluptatum repellendus dolor inventore voluptas exercitationem sunt expedita molestias amet, minus ipsam eos repudiandae natus nostrum architecto eveniet, aliquid ut quae, porro in. Harum, dolores vel.
                                Corrupti, dicta cumque. Eos laboriosam ab deserunt eaque perferendis vel voluptates odit harum beatae atque aperiam quaerat laborum voluptatum, natus at officia perspiciatis quia placeat repudiandae, non expedita nobis sint.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}