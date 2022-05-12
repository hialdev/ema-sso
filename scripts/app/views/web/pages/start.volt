{%extends 'layouts/main.nosidebar.volt' %}

{%block page_content%}
<div class="d-flex justify-content-center align-items-md-center h-100">
    <div class="col-md-6">
        <div class="card mt-3 mt-md-0 rounded-top">
            <div class="card-header bg-slate-400 d-flex justify-content-between align-items-end">
                <div class="p-2">
                    <i class="icon-puzzle4 icon-5x"></i>
                </div>
                <div>
                    <h1 class="font-weight-bold">Create Workspace and Project</h1>
                </div>
            </div>
            <div class="card-body p-4">

                <?php if ($this->flash->has()) { ?>
                    {% for type, messages in flash.getMessages() %}
                        {% for message in messages %}
                            <div class="text-pink-700 mb-2 text-center">{{ message }}</div>
                        {% endfor %}
                    {% endfor %}
                <?php } ?>

                <form autocomplete="off" method="post">
                    <div class="form-group">
                        <h4 class="">Workspace</h4>
                        <input required name="workspace" class="form-control" placeholder="Workspace Name" value="{{account.name}}">
                    </div>
                    <div class="form-group">
                        <h4 class="">Project</h4>
                        <input required name="project" class="form-control" placeholder="Project Name" value="{{account.name}} Project">
                    </div>
                    <div class="form-group mt-4 mb-0 row">
                        <div class="offset-md-9 col-md-3">
                            <button class="btn btn-sm btn-primary btn-block">Continue <i class="ml-3 icon-arrow-right8"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{%endblock%}

{%block page_scripts%}
<script>
    /* jQuery(document).ready(function() {Dashboard.init();}); */
</script>
{%endblock%}