{%extends 'layouts/main.volt' %}

{%block page_content%}
<div class="multi-view" id="detail-view">
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-200 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Navigation -->
                <div class="card">
                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2 nav-application">
                            <li class="nav-item-header">Menu</li>
                            <li class="nav-item  first">
                                <a href="javascript:;" data-tab="informasi" class="nav-link legitRipple active show" data-toggle="tab"><i class="far fa-bell"></i> Notification</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /navigation -->

            </div>

            <!-- /sidebar content -->
        </div>
        <!-- /left sidebar component -->

        <!-- Right content -->
        <div class="tab-content tab-application w-100">

            <div class="tab-pane fade show active" id="informasi">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="far fa-bell mr-1"></i>
                            Notification Setting
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form" id="form_edit" autocomplete="off">
                            <input type="hidden" name="id">
                            <div class="form-group row  mb-4">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Whatsapp Session</label>
                                <div class="col-md-6">
                                    <div class="form-control-plaintext">
                                        OTP verification will use Whatsapp.<br>
                                        To Use Whatsapp connect Whatsapp Account to this app.
                                    </div>
                                    <div class="wa-logs font-italic bg-light border p-2">
                                        Checking ...
                                    </div>
                                    <div class="wa-qrcode" style="display: none;">
                                        <img id="qrcode" src="">
                                        <div class="mt-1">Please scan QR Code above to link your Whatsapp</div>
                                    </div>
                                    <div class="wa-info p-2 border" style="display: none;">
                                        <i class="icon-checkmark-circle text-success"></i> Whatsapp connected.
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
        <!-- /right content -->
    </div>
</div>
{%endblock%}

{%block page_scripts%}
<script>
    var HOST = "{{apiUrl}}";
    jQuery(document).ready(function() {Setting.init();});
</script>
{%endblock%}