{%extends 'layouts/email.volt' %}

{%block email_body%}
<div style="margin-bottom: 5px;color:grey;"><small>{{date}}</small></div>
<div>
    Task assignment <a style="font-weight: bold; color: maroon;" href="{{baseUrl}}task/{{project.slug}}/{{task.id}}">{{task.name}}</a> in Project <a style="font-weight: bold; color: maroon;" href="{{baseUrl}}p/{{project.slug}}">{{project.name}}</a>
    has been revoked by {{account.name}}
</div>

{%endblock%}