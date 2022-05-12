{%extends 'layouts/email.volt' %}

{%block email_body%}
<div style="margin-bottom: 5px;color:grey;"><small>{{date}}</small></div>
<div>{{commentor.name}} has commented on Task <a style="font-weight: bold; color: maroon;" href="{{baseUrl}}task/{{project.slug}}/{{task.id}}">{{task.name}}</a> in Project <a style="font-weight: bold; color: maroon;" href="{{baseUrl}}p/{{project.slug}}">{{project.name}}</a></div>
<div style="margin-top:10px; padding: 5px;border:1px solid #c0c0c0">
    <i>{{comment|nl2br}}</i>
</div>

{%endblock%}