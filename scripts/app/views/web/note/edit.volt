{% extends 'layouts/main.volt' %}

{% block page_content %}

<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <h4># Edit Note</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="subject" class="form-label">Title</label>
                <input type="text" name="title" id="subject" placeholder="Title" class="form-control" value="{{note.title}}" required>
            </div>
            <div class="mb-3">
                <label for="project" class="form-label">Project</label>
                <select name="project_id" id="project" class="form-select" required>
                    <option>-- Pilih Project --</option>
                    {% for project in projects %}
                        <option value="{{project.id}}" {{project.id === note.Project.id ? 'selected' : ''}}>{{project.name}}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea name="note" id="note" cols="30" rows="10" class="form-control">{{note.note}}</textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Lampiran (multiple)</label>
                <input type="file" name="file[]" id="file" class="form-control" multiple="multiple">
            </div>
            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">Save Note <span class="iconify" data-icon="eva:file-add-fill"></span></button>
        </form>
    </div>
    <?php $cf = count($note->getFiles()); ?>
    {% if cf !== 0 %}
    <div class="mt-3">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    {% for file in note.Files %}
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-wrap gap-2 justify-content-between bg-label-secondary p-2 rounded mb-3">
                            <a href="{{file.getUrl()}}" target="blank" class="d-inline-flex align-items-center gap-2"><span class="iconify" data-icon="bi:file-earmark-fill"></span>{{file.name}}</a>
                            <a href="/file/delete/{{file.id}}" class="d-inline-flex align-items-center justify-content-center"><span class="iconify" data-icon="fluent:delete-24-filled"></span></a>
                        </div>
                    </div>
                    {% endfor %}
                </div>
            </div>
        </div>
    </div>
    {% endif %}
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