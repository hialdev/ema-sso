{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="row py-3">
        <div class="col-12">
            <a href="{{url('knowladge')}}" class="d-inline-flex align-items-center gap-2 mb-3"><span class="iconify fs-2" data-icon="ion:arrow-back-circle"></span> Back to Knowladges</a>
            <img src="/assets/img/backgrounds/18.jpg" alt="" class="mb-3 d-block w-100 rounded" style="max-height:35em; object-fit:cover">
        </div>
        <div class="col-12 col-lg-8">
            <p>Kamis, 08 Mei 2022</p>
            <h1>{{slug}}</h1>
            <div class="card mt-2 mb-4">
                <div class="card-body">
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatem et, minus aliquid voluptates qui eos voluptate culpa minima obcaecati velit eaque asperiores porro ducimus natus, quam cumque perspiciatis debitis mollitia?</p>
                    <p>
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit rem vel nam neque, tenetur ut architecto ratione dolorum dignissimos dicta, modi ducimus perspiciatis accusantium, aliquid suscipit distinctio. Nobis, id ipsum.
                        Iste quaerat architecto fugit, reiciendis at, perferendis, ducimus ipsum sit quasi ab debitis praesentium ullam minima! Tenetur rem, expedita asperiores omnis odio cumque placeat harum, eveniet est odit doloremque accusamus.
                        Assumenda, consectetur quos? Consequatur totam dolorum error quibusdam suscipit amet architecto harum alias porro, quos eius minus nemo, illo vitae id labore neque. Aliquam magnam vel provident repudiandae aut unde.
                        Aliquam, delectus. In iste cumque odio pariatur ad laboriosam provident, asperiores vitae, sunt sint doloremque mollitia consectetur facilis magnam. Dolores illum consequatur, adipisci officia magnam facilis quaerat harum voluptatem amet.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5># See Other</h5>
                    <div class="list-group">
                        <a href="{{url('knowladge/knowladge-dummy')}}" class="list-group-item list-group-item-action d-flex gap-2 align-items-center">
                            <img src="/assets/img/backgrounds/18.jpg" alt="" class="d-block rounded" style="width: 10em;height: 5em;object-fit: cover;">
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        </a>
                        <a href="{{url('knowladge/knowladge-dummy')}}" class="list-group-item list-group-item-action d-flex gap-2 align-items-center">
                            <img src="/assets/img/backgrounds/18.jpg" alt="" class="d-block rounded" style="width: 10em;height: 5em;object-fit: cover;">
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        </a>
                        <a href="{{url('knowladge/knowladge-dummy')}}" class="list-group-item list-group-item-action d-flex gap-2 align-items-center">
                            <img src="/assets/img/backgrounds/18.jpg" alt="" class="d-block rounded" style="width: 10em;height: 5em;object-fit: cover;">
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}