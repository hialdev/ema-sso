{% extends 'layouts/main.volt' %}

{% block page_content %}
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="py-3">
        <h4># Add New Ticket</h4>
        <form action="">
            <div class="mb-3">
                <label for="subject" class="form-label">Subject / Topic Masalah</label>
                <input type="text" name="" id="subject" placeholder="Subject / Topic Masalah" class="form-control">
            </div>
            <div class="mb-3">
                <label for="project" class="form-label">Project</label>
                <select name="" id="project" class="form-select">
                    <option value="0">-- Pilih Project --</option>
                    <option value="1">Project 1</option>
                    <option value="2">Project 2</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Messages</label>
                <textarea name="" id="desc" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Attach Files</label>
                <input type="file" name="" id="file" class="form-control" multiple="multiple">
            </div>
            <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100">Send Ticket <span class="iconify" data-icon="fa6-solid:paper-plane"></span></button>
        </form>
    </div>
</div>
{% endblock %}