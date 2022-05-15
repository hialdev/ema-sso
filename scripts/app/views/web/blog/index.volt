{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="row py-3">
        <div class="col-12">
            <h4># Based Knowladge</h4>
            <form action="">
                <div class="input-group my-3">
                    <input
                        name="q"
                        value="{{q}}"
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
        {% for blog in blogs %}
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <a href="/knowladge/{{blog.slug}}" class="text-dark text-decoration-none d-block">
                        <img src="{{blog.image}}" alt="{{blog.title}} image" class="mb-3 d-block w-100 rounded">
                        <h4>{{blog.title}}</h4>
                        <p>{{blog.excerpt}}</p>
                    </a>
                </div>
            </div>
        </div>
        {% else %}
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    Belum ada data knowladges.
                </div>
            </div>
        </div>
        {% endfor %}
    </div>
</div>
{% endblock %}