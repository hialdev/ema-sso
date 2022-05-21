{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
                    
    <div class="py-3">
        <a href="./knowladge.html" class="d-flex align-items-center gap-3 mb-3">
            <span class="iconify" data-icon="bi:arrow-left-circle-fill"></span> Back to Knowladges
        </a>
        <h4># Edit Knowladge : {{blog.title}}</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-9">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" value="{{blog.title}}" placeholder="Title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <textarea name="excerpt" id="excerpt" cols="30" rows="6" class="form-control">{{blog.excerpt}}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{blog.content}}</textarea>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label for="file" class="form-label">Thumbnail</label>
                        <input type="file" name="file" id="file" class="form-control" multiple="multiple" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preview</label>
                        <img src="{{blog.getImageUrl()}}" id="image-preview" alt="Preview image" class="d-block w-100 rounded">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">Update Knowladge <span class="iconify" data-icon="fluent:brain-circuit-24-filled"></span></button>
        </form>
    </div>
</div>
{% endblock %}

{% block js %}
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#content' ),{
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        } )
        .catch( error => {
            console.error( error );
        } );
        
</script>
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