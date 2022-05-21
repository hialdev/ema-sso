{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="row py-3">
        <div class="col-12">
            <a href="{{url('knowladge')}}" class="d-inline-flex align-items-center gap-2 mb-3"><span class="iconify fs-2" data-icon="ion:arrow-back-circle"></span> Back to Knowladges</a>
            <img src="{{blog.getImageUrl()}}" alt="{{blog.title}} image" class="mb-3 d-block w-100 rounded" style="max-height:35em; object-fit:cover">
        </div>
        <div class="col-12 col-lg-8">
            <p>{{blog.created}}</p>
            <h1>{{blog.title}}</h1>
            <div class="card mt-2 mb-4">
                <div class="card-body">
                    {{blog.content}}
                </div>
            </div>
        </div>
        {% if blogs !== null %}
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5># See Other</h5>
                    <div class="list-group">
                        {% for b in blogs %}
                        <a href="/knowladge/{{b.slug}}" class="list-group-item list-group-item-action d-flex gap-2 align-items-center">
                            <img src="{{b.getImageUrl()}}" alt="{{b.title}} image" class="d-block rounded" style="width: 10em;height: 5em;object-fit: cover;">
                            {{b.title}}
                        </a>
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>
        {% endif %}
    </div>
</div>
{% endblock %}