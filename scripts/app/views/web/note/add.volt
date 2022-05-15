{% extends 'layouts/main.volt' %}

{% block page_content %}

{{ flash.output() }}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <h4># Add New Note</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="subject" class="form-label">Title</label>
                <input type="text" name="title" id="subject" placeholder="Title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="project" class="form-label">Project</label>
                <select name="project_id" id="project" class="form-select" required>
                    <option>-- Pilih Project --</option>
                    {% for project in projects %}
                        <option value="{{project.id}}" {{project.id === sid ? 'selected' : ''}}>{{project.name}}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea name="note" id="note" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Lampiran (multiple)</label>
                <input type="file" name="file" id="file" class="form-control" multiple="multiple">
            </div>
            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">Add Note <span class="iconify" data-icon="eva:file-add-fill"></span></button>
        </form>
    </div>
</div>
{% endblock %}

{% block js %}
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#note' ),{
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        } )
        .catch( error => {
            console.error( error );
        } );
</script>
{% endblock %}