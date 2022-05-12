{%extends 'layouts/main.volt' %}

{%block page_header%}
{%endblock%}

{%block page_content%}
<params data-id="{{project.id}}"></params>
<div class="d-flex justify-content-center p-0 p-md-5 mb-4 mb-md-0" id="project-setting">

    <div class="col-md-9 pt-3 pt-md-0">
        <div class="d-flex border-bottom pb-3 mb-3">
            <div class="mr-3">
                <div class="bg-primary d-flex justify-content-center align-items-center" style="height: 80px; width:80px;;">
                    <h1 class="mb-0"><i class="icon-cube2 icon-3x"></i></h1>
                </div>
            </div>
            <div>
                <div>{{workspace.name}}</div>
                <h1 class="font-weight-bold mb-0" data="name">{{project.name}}</h1>
                <div class="text-muted">Created at {{project.getCreatedAtText()}}</div>
            </div>
        </div>

        <div class="ml-2 mr-2 ml-md-0 mr-md-0 mb-5">
            <h4 class="mb-3">Project Profile
                {%if IsAdminProject%}
                <button class="btn btn-xs btn-icon ml-3 text-muted tooltips btn-edit-profile" title="Edit Profile"><i class="icon-pencil3"></i></button>
                {%endif%}
            </h4>
            <div>
                <div class="form-group row mb-md-0">
                    <label class="col-form-label pb-0 col-md-2 font-weight-bold text-lg-right">Name</label>
                    <div class="col-md-7">
                        <div class="form-control-plaintext f-name" data="name">{{project.name}}</div>
                    </div>
                </div>
                <div class="form-group row mb-md-0">
                    <label class="col-form-label pb-0 col-md-2 font-weight-bold text-lg-right">Description</label>
                    <div class="col-md-7">
                        <div class="form-control-plaintext f-description" data="description">{{project.description}}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ml-2 mr-2 ml-md-0 mr-md-0 mb-5">
            <h4 class="mb-3">Project Members</h4>
            <div class="mb-3 member-list">
            {%for _member in project.getMembers()%}
            {%set owner = _member.member_role == 'owner'%}
            <div class="item-member border-bottom pb-1 pt-1" data-id="{{_member.account.id}}" >
                <div class="row">
                    <div class="col-md-9 col-8 d-flex align-items-center">
                        <img src="{{_member.account.getAvatarUrl()}}/30/30" class="rounded-circle mr-2">
                        <!-- <i class="far fa-user mr-2 text-muted"></i> -->
                        <span class="member-name">{{_member.account.name}}</span>
                    </div>
                    <div class="col-2 text-right d-flex align-items-center justify-content-end">
                        {%if IsAdminProject AND (_member.account.id != account.id ) AND !owner %}
                        <a href="#" class="member-role text-dark dropdown-toggle" data-toggle="dropdown" >{{_member.member_role}}</a>
                        <div class="dropdown-menu">
                            <a data-role="contributor" class="dropdown-item btn-role-member">Contributor</a>
                            <a data-role="guest" class="dropdown-item btn-role-member">Guest</a>
                            <a data-role="admin" class="dropdown-item btn-role-member">Admin</a>
                        </div>
                        {%else%}
                        <span class="member-role mr-3 {%if owner%}font-weight-bold{%endif%}">{{_member.member_role}}</span>
                        {%endif%}
                    </div>
                    {%if IsAdminProject AND (_member.account.id != account.id ) AND !owner%}
                    <div class="col-md-1 col-2  d-flex align-items-center justify-content-end">
                        <button class="btn border btn-xs btn-icon btn-delete-member tooltips" title="Remove Member" type="button"><i class="icon-cross3"></i></button>
                    </div>
                    {%endif%}
                </div>
            </div>
            {%endfor%}
            </div>
            {%if IsAdminProject%}
            <div>
                <button class="btn btn-xs btn-outline-primary btn-add-member" type="button">Add Member</button>
            </div>
            {%endif%}
        </div>

        <div class="ml-2 mr-2 ml-md-0 mr-md-0 mb-5">
            <h4 class="mb-3">Project Url
                {%if IsAdminProject%}
                <button class="btn btn-xs btn-icon ml-3 text-muted tooltips btn-edit-url" title="Edit Url"><i class="icon-pencil3"></i></button>
                {%endif%}
            </h4>
            <div>
                <div class="form-group row mb-0">
                    <label class="col-form-label col-md-2 font-weight-bold text-lg-right">Task Url</label>
                    <div class="col-md-7">
                        <div class="form-control-plaintext">
                            <span>{{domain}}task/</span>
                            <span class="font-weight-bold f-slug" data="slug">{{project.slug}}</span>
                        </div>
                    </div>
                </div>
           </div>
        </div>

        {%if IsAdminProject%}
        <div class="ml-2 mr-2 ml-md-0 mr-md-0">
            <h4 class="mb-3">Remove Project</h4>
            <div class="form-group mb-0">
                <button class="btn btn-sm btn-danger tooltips btn-delete-project"><i class="icon-trash"></i> Delete Project</button></h4>
            </div>
        </div>
        {%endif%}
    </div>

</div>
{%endblock%}

{%block page_scripts%}
<script>
    jQuery(document).ready(function() {ProjectSetting.init();});
</script>
{%endblock%}