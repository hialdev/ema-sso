var ManageApplication = function () {

    var datatable = {};
    var datalist = {};
    var dataAplikasi;
    var elMainView = $("#datagrid-view");
    var elCreateView = $("#create-view");
    var elDetailView = $("#detail-view");
    var loaded = false;
    var dataLoaded  = {};
    var application_id;
    var tabActions = ['informasi','akses', 'user', 'setting'];
    var tabActive;
    var elTable = $('.table-application');
    var elTableUser = $('.table-application-user');
    var elTableRole = $('.table-application-role');
    var elTableAkses = $('.table-application-akseslevel');
    var resetPaging = true;
    var listAppAccess = [];
    var accessLevelModal = $("#akseslevel-modal");

    var roleIcons = {
        'generic'       : 'far fa-user',
        'ortu'          : 'fas fa-user-friends',
        'calon-ortu'    : 'fas fa-user-friends text-muted',
        'staff'         : 'fas fa-user-tie',
        'siswa'         : 'fas fa-user-graduate',
        'calon-siswa'   : 'fas fa-user-graduate text-muted',
        'ananda'        : 'fas fa-user-astronaut',
    }

    var ajaxParams = {};
    var f_tipe, f_status, f_url, f_query;

    var submitFilter = function(){
        var _tipe = $('.select-tipe').val();
        var _status = $('.select-status').val();
        var _query = $('.input-nama').val();
        var _url = $('.input-url').val();

        var data = {t: _tipe, s:_status, u:_url, q:_query , rdm: Math.random()};
        location.hash=$.param(data);
    }

    var setAjaxParam = function(name, value) {
        ajaxParams[name] = value;
    }

    var setFilterParams = function (){
        $(".select-tipe").val(f_tipe).trigger('change.select2');
        $(".select-status").val(f_status).trigger('change.select2');
        $(".input-url").val(f_url);
        $(".input-nama").val(f_query);

        setAjaxParam('tipe', f_tipe);
        setAjaxParam('status', f_status);
        setAjaxParam('url', f_url);
        setAjaxParam('query', f_query);
    }

    var handleEvents = function (){
        $(".btn-back").on('click', function(e){
            e.preventDefault();
            resetPaging = false;
            submitFilter();
        })

        $(".nav-link", elDetailView).on('click', function(e){
            e.preventDefault();
            var _tab = $(this).attr("data-tab");
            if (_tab) location.hash = 'detail/'+dataAplikasi.appid+'/'+_tab;
        })

        $(".filter-select", elMainView).on('change', function(e){
            e.preventDefault();
            submitFilter();
        })

        $(".btn-filter", elMainView).on('click', function(e){
            e.preventDefault();
            submitFilter();
        })
        $(".filter-input", elMainView).on('keyup', function(e){
            if (e.keyCode == 13){
                submitFilter();
                return false;
            }
        })

        $("#user .filter-select").on('change', function(e){
            e.preventDefault();
            dataLoaded.appuser = false;
            loadDataUserAplikasi();
        })

        $("#user .btn-filter").on('click', function(e){
            e.preventDefault();
            dataLoaded.appuser = false;
            loadDataUserAplikasi();
        })

        $("#user .filter-input").on('keyup', function(e){
            if (e.keyCode == 13){
                dataLoaded.appuser = false;
                loadDataUserAplikasi();
                return false;
            }
        })

        $(".select-app-otp", elDetailView).on('change', function(e){
            e.preventDefault();

            saveSettingAplikasi('use_otp', $(this).val(), function (data){
                Application.successNotification("Setting OTP berhasil disimpan")
            });
        })

        $(".select-app-status", elDetailView).on('change', function(e){
            e.preventDefault();
            saveSettingAplikasi('status', $(this).val(), function (data){
                Application.successNotification("Status Aplikasi berhasil disimpan")
            });
        })

        $(".btn-add-aplikasi").on('click', function(e){
            e.preventDefault();
            location.hash = 'create';
        })

        $(".select-app-type").on('change', function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            var type = $(this).val();

            $(".app-url", form).css('display', type == 'app' ? 'none' : '');
        })

        $(".btn-save").on('click', function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            form.submit();
        })

        $(".btn-delete-aplikasi").on('click', function(e){
            e.preventDefault();

            var message = "Aplikasi berikut akan di hapus <br><br>"+
            '<b>Nama</b>: '+(dataAplikasi.name)+'<br>'+
            '<b>Tipe</b>: '+(dataAplikasi.type_txt)+' <br><br>'+
            'Lanjutkan ?';

            Application.confirmDialog({
                title: 'Hapus Aplikasi',
                message : message,
                label:{
                    yes: 'HAPUS APLIKASI',
                    no: 'Cancel',
                },
                callback: function (){
                    Application.post({
                        container: '.content',
                        url: 'application/delete',
                        data: {appid : dataAplikasi.appid},
                        useAlert: false,
                        success: function (data) {
                            Application.infoDialog({
                                message: 'Aplikasi <b>'+dataAplikasi.name+'</b> berhasil di hapus',
                                callback: function(){
                                    submitFilter();
                                }
                            })
                        },
                        failed: function (message) {
                            Application.errorNotification(message);
                        }
                    });
                }
            })
        })

        $(".btn-add-role").on('click', function(e){
            setAplikasiData('application/setrole', $(".table-role"),datalist.role, function(data){
                Application.successNotification("Role Akses Aplikasi berhasil di tambahkan");
                datatable.role.ajax.reload(null, false);
            })
        })

        $(".btn-add-akseslevel").on('click', function(e){
            setAplikasiData('application/setakses', $(".table-akseslevel"),datalist.akses, function(data){
                Application.successNotification("Level Akses Aplikasi berhasil di tambahkan");
                datatable.akseslevel.ajax.reload(null, false);
                dataLoaded.appuser = false;
            })
        })

        $(".btn-set-akseslevel").on('click', function(e){
            $("form", accessLevelModal).submit();
        })

        $(".btn-add-user").on('click', function(e){
            setAplikasiData('application/setuser', $(".table-user"), datalist.user, function(data){
                Application.successNotification("User Aplikasi berhasil di tambahkan");
                datatable.user.ajax.reload(null, false);
            })
        })

        $(".app-link").on('click', function(e){
            var appUrl = $(this).text();
            window.open(appUrl, "_blank");
        })

        Application.validateForm({
            container: '#form_create',
            rules: {
                name: { required: true },
                status: { required: true},
                type: { required: true},
                url: { required: {
                    depends: function (element){
                        return $("#form_create [name=type]").val() != 'app';
                    }}},
            },
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'application/create',
                    data: $('form#form_create').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        Application.infoDialog({
                            message: "Aplikasi berhasil di simpan.",
                            callback: function (){
                                location.hash="detail/"+data.appid;
                            }
                        })
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        Application.validateForm({
            container: '#form_edit',
            rules: {
                name: { required: true },
                status: { required: true},
                type: { required: true},
                url: { required: {
                    depends: function (element){
                        return $("#form_edit [name=type]").val() != 'app';
                    }}},
            },
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'application/save',
                    data: $('form#form_edit').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        Application.successNotification("Aplikasi berhasil disimpan.");
                        Application.fillDataValue("#detail-view", data);

                        Application.scrollTo();
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        Application.validateForm({
            container: '#form_akses_user',
            onSubmit: function (form){
                Application.post({
                    container: '#akseslevel-modal',
                    url: 'application/setuser',
                    data: $('form#form_akses_user').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        Application.successNotification("Akses Level berhasil diubah");
                        datatable.user.ajax.reload(null, false);
                        accessLevelModal.modal('hide');
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        $("#select-role-modal .input-query").on('keyup', function(e){
            if (e.keyCode == 13){
                loadTableRole();
                return false;
            }
        })

        $("#select-akseslevel-modal .input-query").on('keyup', function(e){
            if (e.keyCode == 13){
                loadTableAkses();
                return false;
            }
        })

        $("#select-user-modal .filter-select").on('change', function(e){
            e.preventDefault();
            loadTableUser();
        })

        $("#select-user-modal .input-query").on('keyup', function(e){
            if (e.keyCode == 13){
                loadTableUser();
                return false;
            }
        })

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            Application.scrollTop();
        });

        $('#select-role-modal').on('shown.bs.modal', function (e) {
            $("#select-role-modal .input-query").val('');
            loadTableRole();
        });

        $('#select-akseslevel-modal').on('shown.bs.modal', function (e) {
            $("#select-akseslevel-modal .input-query").val('');
            loadTableAkses();
        });

        $('#select-user-modal').on('shown.bs.modal', function (e) {
            $("#select-user-modal .input-query").val('');
            $("#select-user-modal .select-role").val(null).trigger('change.select2');
            loadTableUser();
        });

        accessLevelModal.on('show.bs.modal', function (e) {
            $(".form-group",accessLevelModal).removeClass('has-error');
        });

        $('#select-role-modal, #select-akseslevel-modal, #select-user-modal').on('hidden.bs.modal', function (e) {
            $(this).find("table tr.selected").removeClass('selected');
        });

        onHashChange();
    }

    var activateTab = function (tab){
        $(".nav-application .nav-link", elDetailView).removeClass('active show');
        $(".nav-application .nav-link[data-tab="+tab+"]", elDetailView).addClass('active show');
        $(".tab-application > .tab-pane", elDetailView).removeClass('active show');
        $(".tab-application > .tab-pane#"+tab, elDetailView).addClass('active show');
    }

	var onHashChange = function (){

        $(window).on('hashchange', function(){
			var _aHash = $(location.hash.split('/'));
            var _mode = _aHash.get(0).substring(1);

            if (_aHash.length >= 1 && _mode == 'detail') {
                var _application_id = _aHash.get(1);
                tabActive = _aHash.get(2);
                if (!tabActions.includes(tabActive)) tabActive = tabActions[0];

                if (_application_id)
                {
                    if (loaded && application_id == _application_id){
                        Application.switchView(_mode);
                        activateTab(tabActive);
                        handleViewData();
                    }else{
                        Application.post({
                            container: '.content',
                            url: 'application/get',
                            data: { appid: _application_id},
                            useBlockUI: tabActive == 'informasi',
                            useAlert: false,
                            success: function (data) {
                                application_id = _application_id;
                                loaded = true;
                                Application.switchView(_mode);
                                handleViewDetail(data);
                                activateTab(tabActive);
                            },
                            failed: function () {
                                location.hash = "";
                                return false;
                            }
                        });
                    }
                    return false;
                }
            }else if (_mode == 'create') {
                Application.switchView('create');
                handleViewCreate();
            }else{
                var _params = Application.parseParams(_mode);

                f_tipe = ('t' in _params) ? _params.t : '';
                f_status = ('s' in _params) ? _params.s : '';
                f_url = ('u' in _params) ? _params.u : '';
                f_query = ('q' in _params) ? _params.q : '';

                setFilterParams();

                loaded = false;
                Application.switchView('datagrid');
                loadDataAplikasi();
			}
        });

        $(window).hashchange();
    }

    var saveSettingAplikasi = function (field, value, successCallback){
        Application.post({
            container: "#setting",
            url: 'application/savesetting',
            data: {
                appid: dataAplikasi.appid,
                field: field,
                value: value
            },
            useAlert: false,
            success: function (data) {
                successCallback(data);
            },
            failed: function (message) {
                Application.errorNotification(message);
            }
        });
    }

    var setAplikasiData = function (actionUrl, elTable, datatable, successCallback){
        var data = datatable.rows( { selected: true } ).data();
        var elDialog = elTable.closest(".modal");
        if (data.length == 0){
            Application.errorNotification("Silakan pilih terlebih dahulu");
            return;
        }

        var items = [];
        $.each(data, function(i, item){
            items.push(item.id);
        })

        var params = {
            appid : dataAplikasi.appid,
            action: 'add',
            items : items
        }

        Application.post({
            container: $(".modal-dialog", elDialog),
            url: actionUrl,
            data: params,
            useAlert: false,
            success: function (data) {
                elDialog.modal('hide');
                successCallback(data);
            },
            failed: function (message) {
                Application.errorNotification(message);
            }
        });
    }

    var handleViewCreate = function (){
        $("form#form_create", elCreateView)[0].reset();
        $(".select-app-type", elCreateView).change();
        $(".select-use-otp", elCreateView).change();
        $(".select-status", elCreateView).change();
    }

    var handleViewDetail = function (data, dontchange){
        dataAplikasi = data;

        Application.fillDataValue(elDetailView, dataAplikasi, 'data', '-');
        Application.fillFormData(elDetailView, dataAplikasi);

        $(".select-app-type", elDetailView).change();

        $(".select-app-otp", elDetailView).trigger('change.select2');
        $(".select-app-status", elDetailView).trigger('change.select2');
        $(".select-use-otp", elDetailView).trigger('change.select2');

        dataLoaded.approle = false;
        dataLoaded.appuser = false;
        dataLoaded.akseslevel = false;

        loadDataRoleAplikasi();
        loadDataUserAplikasi();
        loadDataAksesAplikasi();
        //handleViewData();

        Application.scrollTop();
    }

    var handleViewData = function (){
        if (tabActive == 'akses'){
            loadDataRoleAplikasi();
            loadDataAksesAplikasi();
        }else if (tabActive == 'user') loadDataUserAplikasi();
    }

    var showDetailChild = function (row){
        var detail = 'No detail';
        var data = row.data();
        if (data){
            let roles = '';
            $.each(data.roles.split(","), function(i, role){
                roles += '<span class="mr-1 small border p-1"><i class="icon-lock mr-1 text-muted"></i>'+role+'</span>';
            });

            detail = '<div class="detil-info p-2">'+
            '<div class="row">'+
            '<div class="col-md-2"><b>Role Aplikasi</b></div>'+
            '<div class="col-md-8 mt-md-0 mt-1">'+roles+'</div>'+
            '</div>'+
            '</div>';
        }
        row.child( '<div class="row-detail-data" style="margin-left:20px;">'+detail+'</div>', 'row-child bg-light').show();
    }

    var loadDataAplikasi = function (){
        var tableContainer = elTable.closest('.table-container');

        if (datatable.main){
            datatable.main.ajax.reload(function(){
                resetPaging = true;
            }, resetPaging);
            return;
        }

        datatable.main = Application.dataTable({
            element: '.table-application',
            url: 'application/datatable',
            ajax:{
                data: function(data){
                    $.each(ajaxParams, function(key, value) {
                        data[key] = value;
                    });

                    Application.blockUI({
                        message: "Loading",
                        target: tableContainer,
                        overlayColor: 'silver',
                        cenrerY: true,
                    })
                },
            },
            columns: [
                { "data": null,                 'className' : 'group-control text-center'},
                { "data": "name",               'className' : 'align-middle', 'name': 'name'},
                { "data": "description",        'className' : 'align-middle', 'name': 'description'},
                { "data": "url",                'className' : 'align-middle font-italic'},
                { "data": "status_txt",         'className' : 'text-center'},
                { "data": null ,                'className' : 'text-center'},
            ],
            columnDefs: [
                {
                    "render": function ( data, type, row ) {
                        return '<div class="row-detail text-center">&nbsp;</<div>';
                    },
                    'visible': false,
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        let icon = row.type =='web' ? 'icon-browser' : ( row.type =='app' ? 'icon-iphone' : 'icon-windows2');
                        return '<i class="'+icon+' text-muted mr-2 tooltips" style="font-size:.8rem;" title="'+row.type_txt+'"></i>'+row.name;
                    },
                    "targets": 1
                },
                {
                    "render": function ( data, type, row ) {
                        if (Application.isEmpty(row.url)) return '-';
                        else return '<a href="'+row.url+'" target="_blank" class="tooltips" title="Buka Aplikasi">'+data+'</a>';
                    },
                    "targets": 3
                },
                {
                    "render": function (data, type, row) {
                        let clr = row.status == 1 ? 'icon-checkmark-circle text-success' : 'icon-minus-circle2 text-danger';
                        return '<i class="'+clr+' tooltips" title="'+data+'"></i>';

                    },
                    "targets": -2
                },
                {
                    "render": function (data, type, row) {
                        return '<a href="javascript:;" class="btn-detail tooltips" title="Detail Aplikasi"><i class="icon-pencil7"></i></a>';
                    },
                    "targets": -1
                },
                {
                    'orderable': false,
                    'targets':  [0,2,3,4,5]
                }
            ],
            order: [],
            lengthMenu: [[20, 50, 100], [20, 50, 100]],
            ordering: true
        })

        elTable.on('click', 'tr > td.group-control', function () {
            var tr = $(this).parents('tr');
            var row = datatable.main.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                showDetailChild(row);
                tr.addClass('shown');
            }

        });

        elTable.on('click', 'tr > td:not(:has(a)), tr td a.btn-detail', function () {
            var tr = $(this).parents('tr');
            var data = datatable.main.row( tr ).data();

            if (data)
                location.hash = 'detail/'+ data.appid;
        });
    }

    var loadDataRoleAplikasi = function (){
        if (dataLoaded.approle) return;

        if (datatable.role){
            datatable.role.ajax.reload();
            return;
        }

        datatable.role = Application.dataTable({
            dom: '<"datatable-scroll"t>',
            element: '.table-application-role',
            url: 'application/datatablerole',
            onSubmit: function (data){
                data['appid'] = dataAplikasi.appid;
                return data;
            },
            onSuccess: function (res){
                dataLoaded.approle = true;
            },
            columns: [
                { "data": null,                 'className' : 'text-center'},
                { "data": "name",               'className' : 'align-middle', 'name': 'name'},
                { "data": "description",        'className' : 'align-middle'},
                { "data": null ,                'className' : 'text-center'},
            ],
            columnDefs: [
                {
                    'render': function (data, type, row, meta) {
                        //return meta.row + meta.settings._iDisplayStart + 1;
                        return '<i class="'+row.icon+' text-muted"  style="font-size:.8rem;"></i>';
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return '<a href="javascript:;" class="btn-delete tooltips" title="Hapus Akses Role"><i class="icon-trash"></i></a>';
                    },
                    "targets": -1
                },
                {
                    'orderable': false,
                    'targets':  [0, 2, -1]
                }
            ],
            order: [],
            paging: false,
            ordering: true
        })

        elTableRole.on('click', 'tr td a.btn-delete', function () {
            var tr = $(this).parents('tr');
            var data = datatable.role.row( tr ).data();

            if (data){
                Application.confirmDialog({
                    title: "Hapus Role Aplikasi",
                    message: "Akses Role <b>"+data.name+"</b> akan <b>DIHAPUS</b> <br>dari Aplikasi <b>"+dataAplikasi.name+"</b><br><br>Lanjutkan ?",
                    label:{
                        yes: 'Hapus Role',
                        no:'Batal'
                    },
                    callback: function (){
                        Application.post({
                            container: '.table-application-role',
                            url: 'application/setrole',
                            data:{
                                appid: dataAplikasi.appid,
                                role_id: data.id,
                                action:'remove'
                            },
                            useAlert: false,
                            success: function (data) {
                                Application.successNotification('Akses Role berhasil di hapus');
                                datatable.role.ajax.reload(null, false);
                            },
                            failed: function (message) {
                                Application.errorNotification(message);
                            }
                        });
                    }
                })
            }
        });
    }

    var loadDataAksesAplikasi = function (){
        if (dataLoaded.akseslevel) return;
        //var tableContainer = elTableRole.closest('.table-container');

        if (datatable.akseslevel){
            datatable.akseslevel.ajax.reload();
            return;
        }

        datatable.akseslevel = Application.dataTable({
            dom: '<"datatable-scroll"t>',
            element: '.table-application-akseslevel',
            url: 'application/datatableakseslevel',
            onSubmit: function (data){
                data['appid'] = dataAplikasi.appid;
                return data;
            },
            onSuccess: function (res){
                listAppAccess = res.data;
                Application.populateSelect($(".select-akses"), listAppAccess);
                $(".select-akses").select2({language:'id'});
                dataLoaded.akseslevel = true;
            },
            columns: [
                { "data": null,                 'className' : 'text-center'},
                { "data": "name",               'className' : 'align-middle', 'name': 'name'},
                { "data": "description",        'className' : 'align-middle'},
                { "data": null ,                'className' : 'text-center'},
            ],
            columnDefs: [
                {
                    'render': function (data, type, row, meta) {
                        //return meta.row + meta.settings._iDisplayStart + 1;
                        return '<i class="fas fa-user-lock text-muted" style="font-size:.8rem;"></i>';
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return '<a href="javascript:;" class="btn-delete tooltips" title="Hapus Akses Level"><i class="icon-trash"></i></a>';
                    },
                    "targets": -1
                },
                {
                    'orderable': false,
                    'targets':  [0, 2, -1]
                }
            ],
            order: [],
            paging: false,
            ordering: true
        })

        elTableAkses.on('click', 'tr td a.btn-delete', function () {
            var tr = $(this).parents('tr');
            var data = datatable.akseslevel.row( tr ).data();

            if (data){
                Application.confirmDialog({
                    title: "Hapus Akses Level Aplikasi",
                    message: "Akses Level <b>"+data.name+"</b> akan <b>DIHAPUS</b> <br>dari Aplikasi <b>"+dataAplikasi.name+"</b><br><br>Lanjutkan ?",
                    label:{
                        yes: 'Hapus Akses Level',
                        no:'Batal'
                    },
                    callback: function (){
                        Application.post({
                            container: '.table-application-akseslevel',
                            url: 'application/setakses',
                            data:{
                                appid: dataAplikasi.appid,
                                access_level_id: data.id,
                                action:'remove'
                            },
                            useAlert: false,
                            success: function (data) {
                                Application.successNotification('Akses Level berhasil di hapus');
                                datatable.akseslevel.ajax.reload(null, false);
                                dataLoaded.appuser = false;
                            },
                            failed: function (message) {
                                Application.errorNotification(message);
                            }
                        });
                    }
                })
            }
        });
    }

    var loadDataUserAplikasi = function (){
        //var tableContainer = elTableUser.closest('.table-container');
        if (dataLoaded.appuser) return;

        if (datatable.user){
            datatable.user.ajax.reload();
            return;
        }

        datatable.user = Application.dataTable({
            element: '.table-application-user',
            url: 'application/datatableuser',
            onSubmit: function (data){
                data['access_level_id'] = $("#user .select-akses").val();
                data['query'] = $("#user .input-nama-user").val()
                data['appid'] = dataAplikasi.appid;
                return data;
            },
            onSuccess: function (res){
                dataLoaded.appuser = true;
            },
            columns: [
                { "data": null,                 'className' : 'text-center'},
                { "data": "name",               'className' : 'align-middle', 'name': 'name'},
                { "data": "akses_level_txt",    'className' : 'align-middle'},
                { "data": "status_txt",         'className' : 'text-center'},
                { "data": null ,                'className' : 'text-center'},
            ],
            columnDefs: [
                {
                    'render': function (data, type, row, meta) {
                        //return meta.row + meta.settings._iDisplayStart + 1;
                        return '<i class="far fa-user text-muted" style="font-size:.8rem;"></i>';
                    },
                    "targets": 0
                },
                {
                    'render': function (data, type, row, meta) {
                        return '<a href="account#detail/'+row.uid+'" target="_blank" class="tooltips text-uppercase" title="Klik untuk Detail Akun">'+row.name+'</a>';
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        let akses_level = "- belum di set";
                        let cls = "text-muted font-italic";
                        if (row.valid_access == 1){
                            akses_level = row.akses_level_txt;
                            cls = "";
                        }
                        /* var cls = 'default text-muted';
                        if (row.valid_access == 1) cls = 'primary';
                        else data = '- Belum di set -';
                        return '<button class="btn btn-xs btn-'+cls+' btn-block d-flex btn-select-akses justify-content-between tooltips" title="Klik untuk ubah akses level">'+
                        '<div>'+data+'</div>'+
                        '<i class="ml-2 fas fa-angle-down"></i></button>'; */
                        return '<div class="cursor-pointer cursor-hover btn-select-akses d-flex justify-content-between border p-1  tooltips" title="Klik untuk ubah akses level">'+
                            '<div class="'+cls+'">'+akses_level+'</div>'+
                            '<div class="d-flex align-items-center"><i class="fas fa-angle-down text-muted"></i></div>'+
                            '</div>';
                    },
                    "targets": 2
                },
                {
                    "render": function (data, type, row) {
                        var cls = 'default';
                        if (row.status == 1) cls = 'success';
                        return '<button class="btn btn-xs btn-'+cls+' btn-block btn-select-status tooltips" title="Klik untuk ubah status">'+data+'</button>';
                    },
                    "targets": 3
                },
                {
                    "render": function (data, type, row) {
                        return '<a href="javascript:;" class="btn-delete tooltips" title="Hapus Akses User"><i class="icon-trash"></i></a>';
                    },
                    "targets": -1
                },
                {
                    'orderable': false,
                    'targets':  [0, 2,3,4]
                }
            ],
            order: [],
            lengthMenu: [[20, 50, 100], [20, 50, 100]],
            ordering: true
        })

        elTableUser.on('click', 'tr td .btn-select-akses', function () {
            var tr = $(this).parents('tr');
            var data = datatable.user.row( tr ).data();
            data.nama_aplikasi = dataAplikasi.name;
            Application.fillDataValue(accessLevelModal, data);
            $(".select-akses", accessLevelModal).val(data.access_level_id).trigger('change.select2');
            $("[name=account_id]", accessLevelModal).val(data.account_id);
            $("[name=appid]", accessLevelModal).val(dataAplikasi.appid);

            accessLevelModal.modal();
        });

        elTableUser.on('click', 'tr td .btn-select-status', function () {
            var tr = $(this).parents('tr');
            var data = datatable.user.row( tr ).data();

            if (data){
                let status_txt = data.status == 1 ? "Non Atifkan" :"Aktifkan";
                Application.confirmDialog({
                    title: "Status Akses User",
                    message: "Akses User <b>"+data.name+"</b> <br>ke Aplikasi <b>"+dataAplikasi.name+"</b> akan <b>di "+status_txt+"</b><br><br>Lanjutkan?",
                    label:{
                        yes: status_txt+' Akses User',
                        no:'Batal'
                    },
                    callback: function (){

                        Application.post({
                            container: '.table-application-user',
                            url: 'application/setuser',
                            data:{
                                appid: dataAplikasi.appid,
                                account_id: data.account_id,
                                status: data.status == 1 ? 0 : 1,
                                action:'status'
                            },
                            useAlert: false,
                            success: function (data) {
                                Application.successNotification('Status Akses User berhasil di ubah');
                                datatable.user.ajax.reload(null, false);
                            },
                            failed: function (message) {
                                Application.errorNotification(message);
                            }
                        });
                    }
                })
            }
        });

        elTableUser.on('click', 'tr td a.btn-delete', function () {
            var tr = $(this).parents('tr');
            var data = datatable.user.row( tr ).data();

            if (data){
                Application.confirmDialog({
                    title: "Hapus Akses User",
                    message: "Akses User <b>"+data.name+"</b> <br>ke Aplikasi <b>"+dataAplikasi.name+"</b> akan <b>DIHAPUS</b><br><br>Lanjutkan?",
                    label:{
                        yes: 'Hapus Akses User',
                        no:'Batal'
                    },
                    callback: function (){

                        Application.post({
                            container: '.table-application-user',
                            url: 'application/setuser',
                            data:{
                                appid: dataAplikasi.appid,
                                account_id: data.account_id,
                                action:'remove'
                            },
                            useAlert: false,
                            success: function (data) {
                                Application.successNotification('Akses User berhasil di hapus');
                                datatable.user.ajax.reload(null, false);
                            },
                            failed: function (message) {
                                Application.errorNotification(message);
                            }
                        });
                    }
                })
            }
        });
    }

    var loadTableRole = function (){
        if (datalist.role){
            datalist.role.ajax.reload();
            return;
        }
        datalist.role = Application.dataTable({
            element: '.table-role',
            dom: '<"datatable-scroll"t>',
            url: 'application/listrole',
            onSubmit: function (data){
                data['id'] = dataAplikasi.id;
                data['query'] = $("#select-role-modal .input-query").val();
                return data;
            },
            columns: [{
                "data": 'id', 'className' : 'text-center'
                },{
                "data": 'name',
                },{
                "data": 'description'
            }],
            columnDefs: [{
                "render": function (data, type, row) {
                    return row.disabled == 1 ? '<i class="icon-checkbox-checked text-muted"></i>' : '';
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    let cls = rowData.disabled == 1 ? 'pl-1 disabled' : 'select-checkbox';
                    $(td).addClass(cls);
                },
                "targets": 0
            },{
                'render': function (data, type, row, meta) {
                    return '<i class="'+row.icon+' mr-1 text-muted" style="font-size:.8rem;"></i>'+row.name;
                },
                "targets": 1
            },{
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.disabled == 1) $(td).addClass('disabled text-muted font-italic');
                },
                "targets": [1,2]
            }],
            select: {
                style:    'multi',
                selector: 'td:not(.disabled)',
            },
            paging: false,
            scrollY: '350px',
            scrollCollapse: true,
        })
    }

    var loadTableAkses = function (){
        if (datalist.akses){
            datalist.akses.ajax.reload();
            return;
        }
        datalist.akses = Application.dataTable({
            element: '.table-akseslevel',
            dom: '<"datatable-scroll"t>',
            url: 'application/listakses',
            onSubmit: function (data){
                data['id'] = dataAplikasi.id;
                data['query'] = $("#select-akseslevel-modal .input-query").val();
                return data;
            },
            columns: [{
                "data": 'id', 'className' : 'text-center'
                },{
                "data": 'name',
                },{
                "data": 'description'
            }],
            columnDefs: [{
                "render": function (data, type, row) {
                    return row.disabled == 1 ? '<i class="icon-checkbox-checked text-muted"></i>' : '';
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    let cls = rowData.disabled == 1 ? 'disabled' : 'select-checkbox';
                    $(td).addClass(cls);
                },
                "targets": 0
            },{
                'render': function (data, type, row, meta) {
                    return '<i class="fas fa-user-lock mr-1 text-muted" style="font-size:.8rem;"></i>'+row.name;
                },
                "targets": 1
            },{
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.disabled == 1) $(td).addClass('disabled text-muted font-italic');
                },
                "targets": [1,2]
            }],
            select: {
                style:    'multi',
                selector: 'td:not(.disabled)',
            },
            paging: false,
            scrollY: '350px',
            scrollCollapse: true,
        })
    }

    var loadTableUser = function (){
        if (datalist.user){
            datalist.user.ajax.reload();
            return;
        }
        datalist.user = Application.dataTable({
            element: '.table-user',
            dom: '<"datatable-scroll"t><"datatable-footer"p>',
            url: 'application/listuser',
            onSubmit: function (data){
                data['id'] = dataAplikasi.id;
                data['role_id'] = $("#select-user-modal .select-role").val();
                data['query'] = $("#select-user-modal .input-query").val();
                return data;
            },
            columns: [{
                "data": 'id', 'className' : 'text-center'
                },{
                "data": 'name', 'name' : 'name'
                },{
                "data": 'email', 'name' : 'email'
                },{
                "data": null
            }],
            columnDefs: [{
                "render": function (data, type, row) {
                    return row.disabled == 1 ? '<i class="icon-checkbox-checked text-muted"></i>' : '';
                },
                "createdCell": function (td, cellData, rowData, row, col) {
                    let cls = rowData.disabled == 1 ? 'disabled' : 'select-checkbox';
                    $(td).addClass(cls);
                },
                "targets": 0
            },{
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.disabled == 1) $(td).addClass('disabled text-muted font-italic');
                },
                "targets": [1,2,3]
            },{
                "render": function (data, type, row) {
                    return '<div class="text-truncate text-uppercase" title="'+data+'" style="width:200px;">'+data+'</div>';
                },
                "targets": 1
            },{
                "render": function (data, type, row) {
                    let slugs = row.roles_slug.split(",");
                    let names = row.roles.split(",");
                    data = '';
                    $.each(names, function(i, role){
                        let icon = roleIcons[slugs[i]];
                        data += '<span class="mr-1 tooltips" title="'+role+'"><i class="'+icon+'" style="font-size:.8rem !important;"></i></span>';
                    });

                    return data

                },
                "targets": -1
            },{
                "orderable": false,
                "targets": [0,-1]
            }],
            ordering:true,
            select: {
                style:    'multi',
                selector: 'td:not(.disabled)',
            },
            pageLength: 10,
            scrollY: '400px',
            scrollCollapse: true,
        })
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();