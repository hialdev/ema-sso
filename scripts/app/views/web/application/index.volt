{%extends 'layouts/main.volt' %}

{%block page_content%}
<div class="multi-view" id="datagrid-view" style="display: none">
    <div class="card">
        <div class="card-header header-elements-inline with-border-light">
            <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                <i class="icon-grid2 mr-1"></i> Application List
            </h6>
            <div class="header-elements">
                <button type="button" class="btn btn-xs btn-primary btn-add-aplikasi tooltips" title="Tambah Aplikasi"><i class="icon-plus2 mr-1"></i> Tambah</button>
            </div>
        </div>

        <div class="card-body pt-0">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <select class="form-control select2-with-icon select-tipe filter-select" data-fouc data-placeholder="Tipe Aplikasi..." data-allow-clear="true">
                                <option value=""></option>
                                {%for item in list_tipe_aplikasi %}
                                    <option data-icon="{{item['icon']}}" value="{{item['id']}}">{{item['name']}}</option>
                                {%endfor%}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-1">
                            <select class="form-control select2 select-status filter-select" data-fouc data-placeholder="Status..." data-allow-clear="true">
                                <option value=""></option>
                                {%for item in list_status %}
                                    <option value="{{item['id']}}">{{item['name']}}</option>
                                {%endfor%}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <input class="form-control input-nama filter-input" placeholder="Nama / Deskripsi Aplikasi">
                        </div>
                    </div>
                    <div class="col-md-3 col-10">
                        <div class="form-group mb-1">
                            <input class="form-control input-url filter-input" placeholder="URL Aplikasi">
                        </div>
                    </div>
                    <div class="col-md-1 col-1">
                        <button class="btn btn-filter btn-outline-primary"><i class="icon-search4"></i> </button>
                    </div>
                </div>

            </form>
        </div>

        <div class="w-100 table-container">
            <table class="table table-application table-condensed table-hover table-cursor">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th width="250">Nama Aplikasi</th>
                        <th>Deskripsi</th>
                        <th width="150">URL</th>
                        <th width="50">Status</th>
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
                            <i class="icon-windows2 icon-5x text-muted"></i>
                        </div>

                        <div class="mb-1 font-weight-bold">
                            APLIKASI BARU
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
                    <i class="icon-windows2 icon-2x text-muted"></i>
                </div>

                <div class="d-flex align-items-center">
                    <div class="mb-1 font-weight-bold">
                        APLIKASI BARU
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
                            <i class="icon-grid2 mr-1"></i>
                            Tambah Aplikasi
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="form_create">
                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Nama  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Aplikasi"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Tipe  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2-with-icon select-app-type" name="type" data-fouc data-placeholder="Tipe Aplikasi...">
                                        <option></option>
                                        {%for item in list_tipe_aplikasi %}
                                            <option data-icon="{{item['icon']}}" value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2 app-url" style="display: none;">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">URL  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="url" name="url" class="form-control" placeholder="URL Aplikasi"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Status  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2 select-status" name="status" data-fouc data-placeholder="Status Aplikasi...">
                                        <option></option>
                                        {%for item in list_status %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Deskripsi</label>
                                <div class="col-md-8">
                                    <textarea name="description" class="form-control" placeholder="Deskripsi Aplikasi"></textarea>
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
                            <i class="icon-windows2 icon-5x text-muted"></i>
                        </div>

                        <div class="mb-1">
                            <div class="font-weight-bold text-uppercase" data="name"></div>
                            <div class="small app-link cursor-pointer text-primary" data="url"></div>
                        </div>

                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2 nav-application">
                            <li class="nav-item-header">Menu</li>
                            <li class="nav-item  first">
                                <a href="javascript:;" data-tab="informasi" class="nav-link legitRipple active show" data-toggle="tab"><i class="icon-file-empty"></i> Informasi</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="akses" class="nav-link legitRipple" data-toggle="tab"><i class="icon-puzzle2"></i> Akses Aplikasi</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="user" class="nav-link legitRipple" data-toggle="tab"><i class="icon-users"></i> User Aplikasi</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" data-tab="setting" class="nav-link legitRipple" data-toggle="tab"><i class="icon-cog3"></i> Setting</a>
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
                    <i class="icon-windows2 icon-2x text-muted"></i>
                </div>

                <div class="d-flex align-items-center">
                    <div>
                        <div class="font-weight-bold text-uppercase" data="name"></div>
                        <div class="small app-link cursor-pointer text-primary" data="url"></div>
                    </div>
                </div>

            </div>
        </div>

        <ul class="nav nav-tabs nav-application nav-tabs-highlight d-md-none nav-tabs-header">
            <li class="nav-item first"><a href="javascript:;" data-tab="informasi" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab">Informasi</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="akses" class="nav-link text-uppercase font-weight-bold" data-toggle="tab">Akses</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="user" class="nav-link text-uppercase font-weight-bold" data-toggle="tab">User</a></li>
            <li class="nav-item"><a href="javascript:;" data-tab="setting" class="nav-link text-uppercase font-weight-bold" data-toggle="tab">Setting</a></li>
        </ul>

        <!-- Right content -->
        <div class="tab-content tab-application w-100">

            <div class="tab-pane fade" id="informasi">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-grid2 mr-1"></i>
                            Informasi Aplikasi
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="form_edit">
                            <input type="hidden" name="appid">
                            <div class="border bg-light mb-2 p-2 p-md-0">
                                <div class="form-group row mb-0 mt-md-1">
                                    <label class="col-form-label pb-0 col-md-3 text-lg-right font-weight-semibold">App ID</label>
                                    <div class="col-md-8">
                                        <div class="form-control-plaintext" data="appid"></div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <label class="col-form-label pb-0 col-md-3 text-lg-right font-weight-semibold">App Key</label>
                                    <div class="col-md-8">
                                        <div class="form-control-plaintext" data="appkey"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Nama  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Aplikasi"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Tipe  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2-with-icon select-app-type" name="type" data-fouc data-placeholder="Tipe Aplikasi...">
                                        <option></option>
                                        {%for item in list_tipe_aplikasi %}
                                            <option data-icon="{{item['icon']}}" value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2 app-url" style="display: none;">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">URL  <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="url" name="url" class="form-control" placeholder="URL Aplikasi"></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Deskripsi</label>
                                <div class="col-md-8">
                                    <textarea name="description" class="form-control" placeholder="Deskripsi Aplikasi"></textarea>
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

            <div class="tab-pane fade" id="akses">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-puzzle2 mr-1"></i>
                            Role Aplikasi
                        </h6>
                        <div class="header-elements">
                            <button type="button" data-target="#select-role-modal" data-toggle="modal" class="btn btn-xs btn-outline-primary"><i class="icon-plus2 mr-1"></i> Tambah Role</button>
                        </div>
                    </div>
                    <div class="w-100 table-container mb-4">
                        <table class="table table-application-role table-condensed">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th width="250">Nama Role</th>
                                    <th>Deskripsi</th>
                                    <th width="40"><i class="icon-menu"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-unlocked2 mr-1"></i>
                            Akses Level Aplikasi
                        </h6>
                        <div class="header-elements">
                            <button type="button" data-target="#select-akseslevel-modal" data-toggle="modal" class="btn btn-xs btn-outline-primary"><i class="icon-plus2 mr-1"></i> Tambah Akses Level</button>
                        </div>
                    </div>
                    <div class="w-100 table-container mb-3">
                        <table class="table table-application-akseslevel table-condensed">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th width="250">Akses Level</th>
                                    <th>Deskripsi</th>
                                    <th width="40"><i class="icon-menu"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="user">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-users mr-1"></i>
                            User Aplikasi
                        </h6>
                        <div class="header-elements">
                            <button type="button" data-target="#select-user-modal" data-toggle="modal" class="btn btn-xs btn-outline-primary"><i class="icon-plus2 mr-1"></i> Tambah Akses User</button>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <select class="form-control select2-with-icon select-akses filter-select" name="type" data-fouc data-placeholder="Akses Level..." data-allow-clear="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-10">
                                    <input type="search" class="form-control input-nama-user filter-input" placeholder="Nama Akun User ...">
                                </div>
                                <div class="col-md-1 col-1">
                                    <button class="btn btn-filter btn-outline-primary"><i class="icon-search4"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 table-container">
                        <table class="table table-application-user table-condensed">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>Nama User</th>
                                    <th width="250">Akses Level</th>
                                    <th width="100">Status</th>
                                    <th width="40"><i class="icon-menu"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="setting">
                <div class="card">
                    <div class="card-header header-elements-inline with-border-light">
                        <h6 class="card-title text-pink-700 text-uppercase font-weight-bold">
                            <i class="icon-cog3 mr-1"></i>
                            Setting Aplikasi
                        </h6>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" role="form">

                            <div class="form-group row">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Verifikasi OTP</label>
                                <div class="col-md-8">
                                    <select class="form-control select2 select-app-otp" name="use_otp" data-fouc data-placeholder="OTP...">
                                        <option value="0">Tidak Menggunakan OTP</option>
                                        <option value="1">Menggunakan OTP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Status Aplikasi</label>
                                <div class="col-md-8">
                                    <select class="form-control select2 select-app-status" name="status" data-fouc data-placeholder="Status Aplikasi...">
                                        {%for item in list_status %}
                                            <option value="{{item['id']}}">{{item['name']}}</option>
                                        {%endfor%}
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row mb-md-2">
                                <label class="col-form-label col-md-3 text-lg-right font-weight-semibold">Hapus Aplikasi</label>
                                <div class="col-md-8">
                                    <div class="form-control-plaintext">Menghapus data Aplikasi akan menyebabkan aplikasi tidak akan bisa diakses</div>
                                </div>
                            </div>

                            <div class="form-group row mb-md-2">
                                <div class="offset-md-3 col-sm-3">
                                    <button type="button" class="btn btn-sm btn-delete-aplikasi btn-block btn-danger"><i class="icon-trash mr-2"></i>Hapus Aplikasi</button>
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

<div class="modal" data-backdrop="false" data-keyboard="true" id="select-role-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header align-items-center d-flex">
                <h5 class="modal-title font-weight-bold"><i class="icon-file-check mr-1"></i> Pilih Role Aplikasi</h5>
                <div class="d-flex justify-content-between">
                    <div class="input-group">
                        <input type="search" placeholder="Cari ..." class="form-control pb-0 input-query">
                        <div class="input-group-append ml-0">
                            <span class="input-group-text small"><i class="icon-search4"></i></span>
                        </div>
                    </div>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
                </div>
            </div>

            <div class="w-100 table-container" style="min-height: 400px;">
                <table class="table table-role table-condensed table-cursor table-hover">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th width="200">Nama Role</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="modal-footer">
                <div>
                    <button type="button" data-dismiss="modal" class="btn btn-default  btn-xs"><i class="icon-cross"></i> Batal</button>
                    <button type="button" class="btn btn-primary btn-add-role btn-xs"><i class="icon-paperplane mr-2"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-backdrop="false" data-keyboard="true" id="select-akseslevel-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header align-items-center d-flex">
                <h5 class="modal-title font-weight-bold"><i class="icon-file-check mr-1"></i> Pilih Akses Level Aplikasi</h5>
                <div class="d-flex justify-content-between">
                    <div class="input-group">
                        <input type="search" placeholder="Cari ..." class="form-control pb-0 input-query">
                        <div class="input-group-append ml-0">
                            <span class="input-group-text small"><i class="icon-search4"></i></span>
                        </div>
                    </div>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
                </div>
            </div>

            <div class="w-100 table-container" style="min-height: 400px;">
                <table class="table table-akseslevel table-condensed table-cursor table-hover">
                    <thead>
                        <tr>
                            <tr>
                                <th width="40"></th>
                                <th width="200">Akses Level</th>
                                <th>Deskripsi</th>
                            </tr>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="modal-footer">
                <div>
                    <button type="button" data-dismiss="modal" class="btn btn-default  btn-xs"><i class="icon-cross"></i> Batal</button>
                    <button type="button" class="btn btn-primary btn-add-akseslevel btn-xs"><i class="icon-paperplane mr-2"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-backdrop="false" data-keyboard="true" id="akseslevel-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header align-items-center d-flex">
                <h5 class="modal-title font-weight-bold"><i class="icon-file-check mr-1"></i> Set Akses Level</h5>
                <div class="d-flex justify-content-between">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="form_akses_user">
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-semibold pb-0 mb-0">Nama User</label>
                        <div class="form-control-plaintext with-border-light" data="name"></div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-semibold pb-0 mb-0">Nama Aplikasi</label>
                        <div class="form-control-plaintext with-border-light" data="nama_aplikasi"></div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-form-label font-weight-semibold pb-0 mb-0">Akses Level</label>
                        <select required class="form-control select2 select-akses" name="access_level_id" data-placeholder="Pilih Akses Level" data-allow-clear="true">
                            <option value=""></option>
                        </select>
                    </div>
                    <input type="hidden" name="appid">
                    <input type="hidden" name="account_id">
                    <input type="hidden" name="action" value="set">
                </form>
            </div>

            <div class="modal-footer">
                <div>
                    <button type="button" data-dismiss="modal" class="btn btn-default  btn-xs"><i class="icon-cross"></i> Batal</button>
                    <button type="button" class="btn btn-primary btn-set-akseslevel btn-xs"><i class="icon-paperplane mr-2"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-backdrop="false" data-keyboard="true" id="select-user-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="form-group row mb-0">
                    <div class="col-3 d-flex align-items-center">
                        <h5 class="modal-title font-weight-bold"><i class="icon-file-check mr-1"></i> Pilih User</h5>
                    </div>
                    <div class="col-md-5">
                        <select class="form-control select2-with-icon mr-2 select-role filter-select" data-placeholder="Role ..." data-allow-clear="true">
                            <option value=""></option>
                            {%for item in list_role %}
                                <option data-icon="{{item['icon']}}" value="{{item['id']}}">{{item['name']}}</option>
                            {%endfor%}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="search" placeholder="Cari ..." class="form-control input-query">
                            <div class="input-group-append ml-0">
                                <span class="input-group-text small"><i class="icon-search4"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-100 table-container" style="min-height: 400px;">
                <table class="table table-user table-condensed table-cursor table-hover">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th width="200">Nama User</th>
                            <th>Email</th>
                            <th width="100">Role</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="modal-footer">
                <div>
                    <button type="button" data-dismiss="modal" class="btn btn-default  btn-xs"><i class="icon-cross"></i> Batal</button>
                    <button type="button" class="btn btn-primary btn-add-user btn-xs"><i class="icon-paperplane mr-2"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

{%endblock%}

{%block page_scripts%}
<script>
    jQuery(document).ready(function() {ManageApplication.init();});
</script>
{%endblock%}