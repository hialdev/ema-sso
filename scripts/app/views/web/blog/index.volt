{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="row py-3">
        <div class="col-12">
            <h4># Based Knowladge</h4>
            <form action="">
                <div class="input-group my-3">
                    <input
                        type="text"
                        class="form-control border-0"
                        placeholder="Search your problem.."
                        aria-label="Search your problem.."
                        aria-describedby="button-addon2"
                    />
                    <button class="btn btn-primary" type="button" id="button-addon2"><span class="iconify" data-icon="fe:search"></span></button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{url('knowladge/knowladge-dummy')}}" class="text-dark text-decoration-none d-block">
                        <img src="/assets/img/backgrounds/18.jpg" alt="img-project" class="mb-3 d-block w-100 rounded">
                        <h4>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h4>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vitae unde saepe culpa laudantium ratione iste perspiciatis itaque accusantium beatae expedita, explicabo dolore inventore eius necessitatibus velit quidem sapiente autem officiis.</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{url('knowladge/knowladge-dummy')}}" class="text-dark text-decoration-none d-block">
                        <img src="/assets/img/backgrounds/18.jpg" alt="img-project" class="mb-3 d-block w-100 rounded">
                        <h4>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h4>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vitae unde saepe culpa laudantium ratione iste perspiciatis itaque accusantium beatae expedita, explicabo dolore inventore eius necessitatibus velit quidem sapiente autem officiis.</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{url('knowladge/knowladge-dummy')}}" class="text-dark text-decoration-none d-block">
                        <img src="/assets/img/backgrounds/18.jpg" alt="img-project" class="mb-3 d-block w-100 rounded">
                        <h4>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h4>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vitae unde saepe culpa laudantium ratione iste perspiciatis itaque accusantium beatae expedita, explicabo dolore inventore eius necessitatibus velit quidem sapiente autem officiis.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}