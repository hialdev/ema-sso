{%extends 'layouts/main.volt' %}

{%block page_content%}
<div class="row">
    {%for item in list_applications %}
    {%if item.id != application.id %}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="assets/images/logo_only.png" class="mb-3 mt-1" height="80">

                <h3 class="card-title mb-1">{{item.name}}</h3>
                <p class="mb-3 text-muted" style="min-height: 50px;">{{item.description}}</p>
                <a href="{{item.url}}" class="btn btn-sm btn-outline-danger rounded-round"  target="_blank" class="">
                    Visit
                    <i class="icon-arrow-right13"></i>
                </a>
            </div>
        </div>
    </div>
    {%endif%}
    {%endfor%}
</div>
{%endblock%}

{%block page_scripts%}
{%endblock%}