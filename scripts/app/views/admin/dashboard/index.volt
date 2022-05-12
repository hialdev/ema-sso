{%extends 'layouts/main.volt' %}

{%block page_content%}
<!-- <div class="mb-3">
    <h4 class="mb-0 font-weight-semibold">
        <i class="icon-home4 mr-1"></i>
        Dashboard
    </h4>
    <span class="text-muted d-block">Summary dan Statistik</span>
</div> -->

<div class="row statistic">
    <div class="col-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="fas fa-users fa-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0" data="user_total">0</h3>
                    <span class="text-uppercase font-size-xs">Accounts</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card card-body bg-primary-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-grid2 fa-3x opacity-75"></i>
                </div>

                <div class="media-body text-right">
                    <h3 class="mb-0" data="app_total">0</h3>
                    <span class="text-uppercase font-size-xs">Applications</span>
                </div>
            </div>
        </div>
    </div>
</div>
{%endblock%}

{%block page_scripts%}
<script>
    jQuery(document).ready(function() {Dashboard.init();});
</script>
{%endblock%}