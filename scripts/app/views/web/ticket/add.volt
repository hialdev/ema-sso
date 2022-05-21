{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="py-3">
        <h4># Add New Ticket</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="subject" class="form-label">Subject / Topic Masalah</label>
                <input type="text" name="subject" id="subject" placeholder="Subject / Topic Masalah" class="form-control">
            </div>
            <div class="d-flex gap-3 mb-3">
                <div>
                    <label for="project" class="form-label">Project</label>
                    <select name="project_id" id="project" class="form-select">
                        <option value="0">-- Pilih Project --</option>
                        {% for project in projects %}
                            <option value="{{project.id}}" {{project.id === sid ? 'selected' : ''}}>{{project.name}}</option>
                        {% endfor %}
                    </select>
                </div>
                <div>
                    <label for="project" class="form-label">Priority</label>
                    <select name="priority" id="project" class="form-select">
                        <option value="0">-- Pilih Priority --</option>
                        {% for priority in prioritys %}
                            <option value="{{priority.id}}">{{priority.name}}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="editor" class="form-label">Messages</label>
                <textarea name="content" id="editor" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Attach Files</label>
                <div><small>Multiple File with support extensions : pdf,png,jpg,jpeg,giff,zip</small></div>
                <input type="file" name="file[]" id="file" class="form-control" multiple="multiple">
            </div>
            <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">Send Ticket <span class="iconify" data-icon="fa6-solid:paper-plane"></span></button>
        </form>
    </div>
</div>
{% endblock %}

{% block js %}
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ),{
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        } )
        .catch( error => {
            console.error( error );
        } );
</script>
{% endblock %}