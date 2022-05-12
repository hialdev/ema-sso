{%extends 'layouts/main.volt' %}

{%block page_content%}
<div class="multi-view" id="datagrid-view" style="display: none">
    <div class="card">
        <div class="card-header header-elements-inline with-border-light">
            <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                <i class="fas fa-user-friends mr-1"></i> Employee List
            </h6>
            <div class="header-elements">
                <button type="button" class="btn btn-xs btn-primary btn-add-employee tooltips" title="Tambah Akun User"><i class="icon-plus2 mr-1"></i> Tambah</button>
            </div>
        </div>

        <div class="card-body pt-0">
            <form>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group mb-1">
                            <input class="form-control input-nama filter-input" placeholder="Employee Name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <select class="form-control select2-with-icon select-department filter-select" data-fouc data-placeholder="Department ..." data-allow-clear="true">
                                <option value=""></option>
                                {%for item in list_department %}
                                    <option data-icon="{{item['icon']}}" value="{{item['id']}}">{{item['name']}}</option>
                                {%endfor%}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-10">
                        <div class="form-group mb-1">
                            <select class="form-control select2-with-icon select-employment-type filter-select" data-fouc data-placeholder="Employment Type ..." data-allow-clear="true">
                                <option value=""></option>
                                {%for item in list_employment_type %}
                                    <option data-icon="{{item['icon']}}" value="{{item['id']}}">{{item['name']}}</option>
                                {%endfor%}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-1">
                        <button class="btn btn-filter btn-outline-primary"><i class="icon-search4"></i> </button>
                    </div>
                </div>

            </form>
        </div>

        <div class="w-100 table-container">
            <table class="table table-account table-condensed table-hover table-cursor">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Employee Name</th>
                        <th width="200">Department</th>
                        <th width="200">Position / Title</th>
                        <th width="150">Phone</th>
                        <th width="100">Type</th>
                        <th width="40"><i class="icon-menu"></i></th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

<div class="multi-view" id="create-view" style="display: none">
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-200 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Navigation -->
                <div class="card">
                    <div class="d-none d-sm-block">
                        <button type="button" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple  text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
                    </div>

                    <div class="card-body text-center card-img-top">
                        <div class="card-img-actions d-inline-block p-3">
                            <i class="fas fa-user-tie fa-5x text-muted"></i>
                        </div>

                        <div class="mb-1 text-uppercase font-weight-bold">
                            New Employee
                        </div>

                    </div>

                </div>
                <!-- /navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->

        <div class="d-md-none">
            <button type="button" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
        </div>

        <div class="card card-body d-md-none p-1">

            <div class="d-flex justify-content-start">
                <div class="mr-3 p-2">
                    <i class="far fa-address-book fa-2x text-muted"></i>
                </div>

                <div class="d-flex align-items-center">
                    <div class="mb-1 text-uppercase font-weight-bold">
                        New Employee
                    </div>
                </div>

            </div>
        </div>

        <!-- Right content -->
        <div class="tab-content tab-application w-100">

            <div class="tab-pane fade active show"  >
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-profile mr-1"></i>
                            Add New Employee
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="form_create" autocomplete="off">
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Name  <span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input required type="text" name="first_name" class="form-control" placeholder="First Name"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Department  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-department" name="id_department" data-fouc data-placeholder="Department ...">
                                        <option></option>
                                        {%for item in list_department %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Designation  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-designation" name="id_designation" data-fouc data-placeholder="Job Designation ...">
                                        <option></option>
                                        {%for item in list_designation %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Phone  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="tel" name="phone" class="form-control" placeholder="Phone"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Email  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="email" name="email" class="form-control" placeholder="Email"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Employment Type  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-employment-type" name="employment_type" data-fouc data-placeholder="Employment Type...">
                                        <option></option>
                                        {%for item in list_employment_type %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-4">
                                <div class="offset-md-3 col-md-3">
                                    <button type="button" class="btn btn-primary btn-block btn-save btn-sm legitRipple"><span>Simpan</span> <i class="icon-paperplane ml-2"></i></button>
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

<div class="multi-view" id="detail-view" style="display: none">
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-200 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Navigation -->
                <div class="card">
                    <div class="d-none d-sm-block">
                        <button type="submit" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple  text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
                    </div>

                    <div class="card-body text-center card-img-top">
                        <div class="card-img-actions d-inline-block p-3">
                            <!-- <i class="far fa-address-book fa-5x text-muted"></i> -->
                            <img class="account-avatar img-fluid rounded-circle" src="{{accountUrl}}/pic/acc/0" width="170" height="170" alt="">
                        </div>

                        <div class="mb-1">
                            <div class="font-weight-bold text-uppercase" data="full_name"></div>
                            <div class="" data="department"></div>
                            <div class="small" data="designation"></div>
                        </div>

                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2 nav-application">
                            <li class="nav-item-header">Menu</li>
                            <li class="nav-item  first">
                                <a href="javascript:;" data-tab="informasi" class="nav-link legitRipple active show" data-toggle="tab"><i class="icon-file-empty"></i> Informasi</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="setting" class="nav-link legitRipple" data-toggle="tab"><i class="fas fa-user-cog"></i> Setting</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->

        <div class="d-md-none">
            <button type="submit" class="btn btn-gainsboro btn-back btn-block btn-sm legitRipple text-left"> <i class="icon-arrow-left12"></i> Kembali</button>
        </div>

        <div class="card card-body d-md-none p-1">

            <div class="d-flex justify-content-start">
                <div class="mr-3 p-2">
                    <!-- <i class="far fa-address-book fa-2x text-muted"></i> -->
                    <img class="account-avatar img-fluid rounded-circle" src="{{accountUrl}}/pic/acc/0" width="100" height="100" alt="">
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <div class="font-weight-bold text-uppercase" data="full_name"></div>
                        <div class="" data="department"></div>
                        <div class="small" data="designation"></div>
                    </div>
                </div>

            </div>
        </div>

        <ul class="nav nav-tabs nav-application nav-tabs-highlight d-md-none nav-tabs-header">
            <li class="nav-item first"><a href="javascript:;" data-tab="informasi" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab">Employee</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="setting" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><i class="fas fa-user-cog"></i></a></li>
        </ul>

        <!-- Right content -->
        <div class="tab-content tab-application w-100">

            <div class="tab-pane fade" id="informasi">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-profile mr-1"></i>
                            Employee Detail
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form" id="form_edit" autocomplete="off">
                            <input type="hidden" name="id">
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Name  <span class="text-danger">*</span></label>
                                <div class="col-md-4">
                                    <input required type="text" name="first_name" class="form-control" placeholder="First Name"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Department  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-department" name="id_department" data-fouc data-placeholder="Department ...">
                                        <option></option>
                                        {%for item in list_department %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Designation  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-designation" name="id_designation" data-fouc data-placeholder="Job Designation ...">
                                        <option></option>
                                        {%for item in list_designation %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Phone  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="tel" name="phone" class="form-control" placeholder="Phone"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Email  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input required type="email" name="email" class="form-control" placeholder="Email"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Employment Type  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select required class="form-control select2 select-employment-type" name="employment_type" data-fouc data-placeholder="Employment Type...">
                                        <option></option>
                                        {%for item in list_employment_type %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-4">
                                <div class="offset-md-3 col-md-3">
                                    <button type="button" class="btn btn-primary btn-block btn-save btn-sm legitRipple"><span>Simpan</span> <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="setting">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="fas fa-user-cog mr-1"></i>
                            Setting
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form">

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Delete Employee Data</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext">
                                        Delete employee data including all related information.<br>
                                        The process can not be undone.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <div class="offset-md-3 col-md-3">
                                    <button type="button" class="btn btn-sm btn-delete-employee btn-block btn-danger"><i class="icon-trash mr-2"></i>Delete Employee</button>
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
    jQuery(document).ready(function() {ManageEmployee.init();});
</script>
{%endblock%}