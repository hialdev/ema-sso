{%extends 'layouts/email.volt' %}

{%block email_body%}
<div>{{date}}</div>
{%if due_date is not empty %}
<div>{{updated.name}} has changed due date Task <a href="task/{{project.slug}}/{{task.id}}">{{task.name}}</a> in Project <a href="p/{{project.slug}}">{{project.name}}</a></div>
<div style="padding: 5px;">
    Due Date = <b>{{due_date}}</b>
</div>
{%else%}
<div>{{updated.name}} has removed due date Task <a href="task/{{project.slug}}/{{task.id}}">{{task.name}}</a> in Project <a href="p/{{project.slug}}">{{project.name}}</a></div>
{%endif%}

{%endblock%}