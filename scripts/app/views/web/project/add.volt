{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">  
    <div class="py-3">
        <a href="{{url('project')}}" class="d-flex align-items-center gap-3 mb-3">
            <span class="iconify" data-icon="bi:arrow-left-circle-fill"></span> Back to Projects
        </a>
        <h4># Add New Project</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-9">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Project Name</label>
                        <input type="text" name="name" id="subject" placeholder="Project Name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Project</label> <br>
                        {% for s in ps %}
                        <div class="form-check form-check-inline mt-3 p-3 rounded bg-label-{{s.css}}">
                            <input type="radio" name="status" id="status" class="form-check-input" value="{{s.id}}">
                            <label for="status" class="form-check-label">{{s.name}}</label>
                        </div>
                        {% endfor %}
                    </div>
                    <div class="mb-3">
                        <label for="client" class="form-label">Client</label>
                        <select name="client_id" id="client" class="form-select">
                            <option value="0">-- Pilih Client --</option>
                            {% for client in clients %}
                                <option value="{{client.id}}">{{client.name}}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="note" class="form-label">Excerpt / Deskripsi Singkat</label>
                        <textarea name="excerpt" id="note" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="mb-3">
                        <label for="file" class="form-label">Cover Image</label>
                        <input type="file" name="file" id="file" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preview</label>
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQMAAADCCAMAAAB6zFdcAAAAQlBMVEX///+hoaGenp6ampr39/fHx8fOzs7j4+P8/Pyvr6/d3d3FxcX29va6urqYmJjs7OzU1NSlpaW1tbWtra3n5+e/v78TS0zBAAACkUlEQVR4nO3b63KCMBCGYUwUUVEO6v3fagWVY4LYZMbZnff51xaZ5jON7CZNEgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQb5tvI8qzX4/nH84XG5Upfj2ir2V2E5fZ/XpIX9saMnhkYLIkiyRJjdgMoiEDMmiQgfwM8rSu77ew2wnPoLTmwdZBs0J2BuXrYckcQm4nOoP+WcmWAbcTnUHZPy9eA24nOoN7n0HI54ToDM5k8PjluwyqgNuJzqDoaugPg8gWZ4noDAYLwuIg75fLeeHHsjNIzrZJwWwW+0DNsmEWPjiEZ5AcD8ZUu8VZ8HyQMifvBdIz+PS33i8adu+7Qn4Gn1Tdupl7rlCfQb9seosK7RkcBy1o30iVZ5CPOtDW3WhQnsF13IV3v0p3BqfJRoSpXVepzmA/24+yqeMyzRm4tqOs44lSUwa3yfgOri25av5CPRnklR33VlPnrqSZV09qMsiqSWV082xOz1uPajJ49pTM/f115k6guWa6JGjJ4N1lt8fXN2rv/vysjFaSQdFXBc/KKF04ptFPliclGVR9Bu27XCyeVOkmy5OODAZN9rYyyip/AIPJ8qIig+PoXbf7YdPdncFoSdCQQT4ZceV+MhiFMBy0hgyu0yGvOLI17KwpyGBaHK5jtt0N5GcwLw7XZdB31sRn8O+ziqYro8Vn4CwOV+k6a9Iz+PwRsKC7h+gMfMXhKu/OmuwM/MXhKq8yWnYG/uJw5Uxoy2jRGZTBZ/jboxuSM1guDtdNhKazJjiDbNMe0AxzKUVnkO+jEJxBxNtJzWCTxlNLzSB8KehJ/H+mJGYAjaDjzj9SnHZRuXZiAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAECXP1XDHv7U4SNFAAAAAElFTkSuQmCC" id="image-preview" alt="Preview image" class="d-block w-100 rounded">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">Add Project <span class="iconify" data-icon="ic:baseline-work"></span></button>
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