{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">  
    <div class="py-3">
        <a href="{{url('project')}}" class="d-flex align-items-center gap-3 mb-3">
            <span class="iconify" data-icon="bi:arrow-left-circle-fill"></span> Back to Projects
        </a>
        <h4># Edit Project : {{project.name}}</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-9">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Project Name</label>
                        <input type="text" name="name" id="subject" value="{{project.name}}" placeholder="Project Name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Project</label> <br>
                        {% for s in ps %}
                        <div class="form-check form-check-inline mt-3 p-3 rounded bg-label-{{s.css}}">
                            <input type="radio" name="status" id="subject" class="form-check-input" value="{{s.id}}" {{s.id === project.status ? 'checked' : ''}}>
                            <label for="subject" class="form-check-label">{{s.name}}</label>
                        </div>
                        {% endfor %}
                    </div>
                    <div class="mb-3">
                        <label for="client" class="form-label">Client</label>
                        <select name="client_id" id="client" class="form-select">
                            <option value="0">-- Pilih Client --</option>
                            {% for client in clients %}
                                <option value="{{client.id}}" {{client.id === project.Account.id?'selected':''}}>{{client.name}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">Excerpt / Deskripsi Singkat</label>
                        <textarea name="excerpt" id="note" cols="30" rows="5" class="form-control">{{project.excerpt}}</textarea>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="file" class="form-label">Cover Image</label>
                        <input type="file" name="file" id="file" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preview</label>
                        <img src="{{project.getImageUrl()}}" id="image-preview" alt="Preview image" class="d-block w-100 rounded">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">Update Project <span class="iconify" data-icon="ic:baseline-work"></span></button>
        </form>
    </div>
</div>
{% endblock %}

{% block js %}
<script>
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
        $('#image-preview').attr('src', e.target.result);
        $('#image-preview').hide();
        $('#image-preview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
    }

    $("#file").change(function() {
    readURL(this);
    });
</script>
{% endblock %}