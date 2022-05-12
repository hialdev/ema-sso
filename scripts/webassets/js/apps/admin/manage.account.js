var ManageAccount = function () {

    var datatable = {};
    var datalist = {};
    var dataAkun;
    var elMainView = $("#datagrid-view");
    var elCreateView = $("#create-view");
    var elDetailView = $("#detail-view");
    var loaded = false;
    var dataLoaded  = {};
    var uid;
    var tabActions = ['informasi','role', 'akses', 'perangkat', 'setting'];
    var tabActive;
    var elTable = $('.table-account');
    var elTableAkses = $('.table-akses-akun');
    var elTableRole = $('.table-role-akun');
    var elTableDevice = $('.table-perangkat-akun');
    var resetPaging = true;

    var elEmailModal = $("#modal-email");
    var elPhoneModal = $("#modal-phone");
    var elStaffModal = $("#select-staff-modal");
    var elAplikasiModal = $("#select-aplikasi-modal");
    var elAccessLevelModal = $("#akseslevel-modal");
    var elResetPassword = $("#setting [name=akun_password]", elDetailView);

    var roleIcons = {
        'generic'       : 'far fa-user',
        'staff'         : 'fas fa-user-tie',
    }

    var statusIcons = {
        '1'             : 'icon-checkmark-circle text-success',
        '0'             : 'icon-minus-circle2',
        '-1'            : 'icon-blocked text-danger',
    }

    var ajaxParams = {};
    var f_aplikasi, f_role, f_status, f_query;

    var submitFilter = function(){
        var _app = $('.select-app').val();
        var _role = $('.select-role').val();
        var _status = $('.select-status').val();
        var _query = $('.input-nama').val();

        var data = {a: _app, r:_role, s:_status, q:_query , rdm: Math.random()};
        location.hash=$.param(data);
    }

    var setAjaxParam = function(name, value) {
        ajaxParams[name] = value;
    }

    var setFilterParams = function (){
        $(".select-app").val(f_aplikasi).trigger('change.select2');
        $(".select-role").val(f_role).trigger('change.select2');
        $(".select-status").val(f_status).trigger('change.select2');
        $(".input-nama").val(f_query);

        setAjaxParam('application_id', f_aplikasi);
        setAjaxParam('role_id', f_role);
        setAjaxParam('status', f_status);
        setAjaxParam('query', f_query);
    }

    var handleEvents = function (){
        $(".btn-back").on('click', function(e){
            e.preventDefault();
            resetPaging = false;
            $(".account-avatar").attr("src", Application.accountUrl()+'/pic/acc/0');
            submitFilter();
        })

        $(".nav-link", elDetailView).on('click', function(e){
            e.preventDefault();
            var _tab = $(this).attr("data-tab");
            if (_tab) location.hash = 'detail/'+dataAkun.uid+'/'+_tab;
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

        $(".btn-add-akun").on('click', function(e){
            e.preventDefault();
            location.hash = 'create';
        })

        $(".btn-delete-akun").on('click', function(e){
            e.preventDefault();

            var message = "Akun berikut akan di hapus <br><br>"+
            '<b>Nama</b>: '+(dataAkun.name)+'<br>'+
            '<b>Username</b>: '+(dataAkun.username)+'<br>'+
            '<b>Email</b>: '+(dataAkun.email)+' <br><br>'+
            'Lanjutkan ?';

            Application.confirmDialog({
                title: 'Hapus Akun',
                message : message,
                label:{
                    yes: 'HAPUS AKUN',
                    no: 'Cancel',
                },
                callback: function (){
                    Application.post({
                        container: '.content',
                        url: 'account/delete',
                        data: {account_id : dataAkun.id},
                        useAlert: false,
                        success: function (data) {
                            Application.infoDialog({
                                message: 'Akun <b>'+dataAkun.name+'</b> berhasil di hapus',
                                callback: function(){
                                    resetPaging = true;
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

        $(".btn-save").on('click', function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            form.submit();
        })

        $(".btn-save-modal").on('click', function(e){
            e.preventDefault();
            var modal = $(this).closest('.modal');
            var form = $('form', modal);
            form.submit();
        })

        Application.validateForm({
            container: '#form_create',
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'account/create',
                    data: $('form#form_create').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        Application.infoDialog({
                            message: "Akun baru berhasil di simpan.",
                            callback: function (){
                                location.hash="detail/"+data.uid;
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
            container: '#form_akses_user',
            onSubmit: function (form){
                Application.post({
                    container: '#akseslevel-modal',
                    url: 'application/setuser',
                    data: $('form#form_akses_user').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        let aplikasi = $(".nama-aplikasi", elAccessLevelModal).text();
                        Application.successNotification("Akses Level Akun <b>"+dataAkun.name+"</b> ke Aplikasi <b>"+aplikasi+"</b> berhasil diubah");
                        datatable.akses.ajax.reload(null, false);
                        elAccessLevelModal.modal('hide');
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        Application.validateForm({
            container: '#form_email',
            rules: {
                value: { required: true, email:true },
            },
            onSubmit: function (form){

                var input_email = $('.contact-value', elEmailModal).val();
                if ( input_email == dataAkun.email || input_email == dataAkun.email_unverified){
                    Application.errorNotification('Alamat email tidak ada perubahan');
                    return;
                }

                Application.post({
                    container: '.content',
                    url: 'account/updatecontact',
                    data: $('form#form_email').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        elEmailModal.modal('hide');
                        dataAkun.email_unverified = data.value;
                        handleContactInfo();
                        Application.successNotification("Alamat email berhasil di simpan. Kode Verifikasi akan dikirimkan melalui Email");
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        Application.validateForm({
            container: '#form_phone',
            rules: {
                value: { required: true, number:true },
            },
            onSubmit: function (form){

                var input_phone = $('.contact-value', elPhoneModal).val();
                if ( input_phone == dataAkun.phone || input_phone == dataAkun.phone_unverified){
                    Application.errorNotification('Nomor Telpon tidak ada perubahan');
                    return;
                }

                Application.post({
                    container: '.content',
                    url: 'account/updatecontact',
                    data: $('form#form_phone').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        elPhoneModal.modal('hide');
                        dataAkun.phone_unverified = data.value;
                        handleContactInfo();
                        Application.successNotification("Nomor Telpon berhasil di simpan. Kode Verifikasi akan dikirimkan ke nomor "+data.value);
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })


        $(".btn-edit-email", elDetailView).on('click', function(e){
            e.preventDefault();
            $(".contact-value", elEmailModal).val(!Application.isEmpty(dataAkun.email_unverified) ? dataAkun.email_unverified : dataAkun.email);
            $("[name=account_id]", elEmailModal).val(dataAkun.id);
            elEmailModal.modal();
        });

        $(".btn-edit-phone", elDetailView).on('click', function(e){
            e.preventDefault();
            $(".contact-value", elPhoneModal).val(!Application.isEmpty(dataAkun.phone_unverified) ? dataAkun.phone_unverified : dataAkun.phone);
            $("[name=account_id]", elPhoneModal).val(dataAkun.id);
            elPhoneModal.modal();
        });

        elStaffModal.on('shown.bs.modal', function (e) {
            $(".input-query", elStaffModal).val('');
            //$(".select-yayasan", elStaffModal).val(yayasan ? yayasan.id_yayasan : null).trigger('change.select2');
            loadTableStaff();
        });

        elAplikasiModal.on('shown.bs.modal', function (e) {
            $(".input-query", elAplikasiModal).val('');
            loadTableAplikasi();
        });

        elAccessLevelModal.on('show.bs.modal', function (e) {
            $(".form-group",elAccessLevelModal).removeClass('has-error');
        });

        $("#perangkat .input-bulan").datepicker( {
            autoclose: true,
            clearBtn: true,
            language: 'id',
            format: "MM yyyy",
            startView: 1,
            minViewMode: 1
        }).on('changeDate', function(e) {
            dataLoaded.perangkat = false;
            loadDataPerangkatAkun();
        });

        $("#perangkat .input-query").on('keyup', function(e){
            if (e.keyCode == 13){
                dataLoaded.perangkat = false;
                loadDataPerangkatAkun();
                return false;
            }
        })

        $("#perangkat .select-aplikasi").on('change', function(e){
            e.preventDefault();
            dataLoaded.perangkat = false;
            loadDataPerangkatAkun();
        })

        $("#perangkat .btn-filter").on('click', function(e){
            e.preventDefault();
            dataLoaded.perangkat = false;
            loadDataPerangkatAkun();
        })

        $(".input-query", elStaffModal).on('keyup', function(e){
            if (e.keyCode == 13){
                loadTableStaff();
                return false;
            }
        })

        $(".select-department", elStaffModal).on('change', function(e){
            e.preventDefault();
            loadTableStaff();
        })

        $(".input-query", elAplikasiModal).on('keyup', function(e){
            if (e.keyCode == 13){
                loadTableAplikasi();
                return false;
            }
        })

        $(".btn-set-role").on('click', function(e){
            setRoleAkun(this);
        })

        $(".btn-set-akses").on('click', function(e){
            setAksesAkun();
        })

        $(".select-akun-status", elDetailView).on('change', function(e){
            e.preventDefault();
            saveSettingAkun('status', $(this).val(), function (data){
                Application.successNotification("Status Akun <b>"+dataAkun.name+"</b> berhasil disimpan")
            });
        })

        $("#setting .btn-generate-pwd", elDetailView).on('click', function(e){
            var pwd = Application.randomString(8);
            elResetPassword.val(pwd);
        })

        $(".btn-reset-akun", elDetailView).on('click', function(e){
            e.preventDefault();
            var password = elResetPassword.val();

            if (dataAkun.id == Application.account().id){
                Application.alertDialog({
                    message: "Akun yang sedang aktif login tidak bisa di reset"
                })
                return;
            }

            if (Application.isEmpty(password)){
                Application.alertDialog({
                    message: "Silakan masukkan password akun",
                    callback: function(){
                        elResetPassword.focus();
                    }
                })
                return;
            }
            var message = "Password Akun berikut akan di reset <br><br>"+
                '<b>Username</b>: '+(dataAkun.username)+'<br>'+
                '<b>Nama</b>: '+(dataAkun.name)+'<br><br>';

            if (Application.isEmpty(dataAkun.email)){
                message += "";
            }else{

                message += 'Password akan dikirimkan ke alamat email <b>'+(dataAkun.email)+'</b> <br><br>';
            }

            message += "Lanjutkan ?";

            Application.confirmDialog({
                title: 'Reset Password Akun',
                message : message,
                label:{
                    yes: 'Reset Password',
                    no: 'Batal',
                },
                callback: function (){
                    Application.post({
                        container: '.content',
                        url: 'account/resetpassword',
                        data: {account_id : dataAkun.id, password: password},
                        useAlert: false,
                        success: function (data) {
                            elResetPassword.val('');
                            var info = 'Password Akun berhasil di reset.<br>';
                            if (!Application.isEmpty(dataAkun.email))
                                info += 'Password dikirimkan ke alamat email <b>'+(dataAkun.email)+'</b>';
                            Application.successNotification(info);
                        },
                        failed: function (message) {
                            Application.errorNotification(message);
                        }
                    });
                }
            })

        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            Application.scrollTop();
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
                var _uid = _aHash.get(1);
                tabActive = _aHash.get(2);
                if (!tabActions.includes(tabActive)) tabActive = tabActions[0];

                if (_uid)
                {
                    if (loaded && uid == _uid){
                        Application.switchView(_mode);
                        activateTab(tabActive);
                        handleViewData();
                    }else{
                        Application.post({
                            container: '.content',
                            url: 'account/get',
                            data: { uid: _uid},
                            useBlockUI: tabActive == 'informasi',
                            useAlert: false,
                            success: function (data) {
                                uid = _uid;
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

                f_aplikasi = ('a' in _params) ? _params.a : '';
                f_role = ('r' in _params) ? _params.r : '';
                f_status = ('s' in _params) ? _params.s : '';
                f_query = ('q' in _params) ? _params.q : '';

                setFilterParams();

                loaded = false;
                Application.switchView('datagrid');
                loadDataMain();
			}
        });

        $(window).hashchange();
    }

    var setRoleAkun = function (element){
        let role = $(element).attr("data-role");
        let title = $(element).attr("data-role-name");

        if (role == 'staff' && dataAkun.id == Application.account().id){
            Application.alertDialog({
                message: "Akun yang sedang aktif login tidak bisa mengubah role akun staff"
            })
            return;
        }

        var data = datalist[role].rows( { selected: true } ).data();
        var elDialog = $("#select-"+role+"-modal")
        if (data.length == 0){
            Application.errorNotification("Silakan pilih terlebih dahulu");
            return;
        }

        let object_id;
        let role_slug;
        if (role == 'staff'){
            object_id = data[0].id;
            role_slug = 'staff';
        }

        var params = {
            account_id : dataAkun.id,
            action: 'add',
            role_slug: role_slug,
            object_id : object_id
        }

        Application.post({
            container: "#select-"+role+"-modal",
            url: 'account/setrole',
            data: params,
            useAlert: false,
            success: function (data) {
                elDialog.modal('hide');
                Application.successNotification("Role "+title+" berhasil di tambahkan");
                datatable.role.ajax.reload();
            },
            failed: function (message) {
                Application.errorNotification(message);
            }
        });
    }

    var setAksesAkun = function (){
        var data = datalist.aplikasi.rows( { selected: true } ).data();
        if (data.length == 0){
            Application.errorNotification("Silakan pilih terlebih dahulu");
            return;
        }

        var items = [];
        $.each(data, function(i, item){
            items.push(item.id);
        })

        var params = {
            account_id : dataAkun.id,
            action: 'add',
            items : items
        }

        Application.post({
            container: $(".modal-dialog", elAplikasiModal),
            url: 'account/setakses',
            data: params,
            useAlert: false,
            success: function (data) {
                elAplikasiModal.modal('hide');
                Application.successNotification("Akses Aplikasi berhasil di tambahkan");
                datatable.akses.ajax.reload();

            },
            failed: function (message) {
                Application.errorNotification(message);
            }
        });
    }

    var saveSettingAkun = function (field, value, successCallback){
        Application.post({
            container: "#setting",
            url: 'account/savesetting',
            data: {
                account_id: dataAkun.id,
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

    var populateAksesAplikasi = function (application_id, access_level_id){
        let selectElement = $(".select-akseslevel", elAccessLevelModal);
        Application.post({
            container: '.content',
            url: 'data/akseslevel',
            data: {application_id: application_id},
            useBlockUI: false,
            useAlert: false,
            success: function (data) {
                Application.populateSelect(selectElement, data, access_level_id);
                selectElement.select2({language: "id"});
            },
            failed: function (message) {

            }
        });
    }

    var handleViewCreate = function (){
        $("form#form_create", elCreateView)[0].reset();
        $(".select-status", elCreateView).trigger('change.select2');
    }

    var handleContactInfo = function (){
        Application.fillDataValue(elDetailView, dataAkun, 'data', '-');

        var email_required = Application.isEmpty(dataAkun.email) && !Application.isEmpty(dataAkun.email_unverified);
        var phone_required = Application.isEmpty(dataAkun.phone) && !Application.isEmpty(dataAkun.phone_unverified);

        $(".email-verified", elDetailView).css('display', email_required ? 'none' : '')
        $(".phone-verified", elDetailView).css('display', phone_required ? 'none' : '')

        $(".email-verified .btn-edit-email", elDetailView).css('display', email_required || !Application.isEmpty(dataAkun.email_unverified) ? 'none' : '')
        $(".phone-verified .btn-edit-phone", elDetailView).css('display', phone_required || !Application.isEmpty(dataAkun.phone_unverified)? 'none' : '')

        $(".email-unverified", elDetailView).css('display', Application.isEmpty(dataAkun.email_unverified) ? 'none' : '')
        $(".phone-unverified", elDetailView).css('display', Application.isEmpty(dataAkun.phone_unverified)  ? 'none' : '')
    }

    var handleViewDetail = function (data, dontchange){
        dataAkun = data;

        Application.fillFormData(elDetailView, dataAkun);

        handleContactInfo();

        elResetPassword.val('');
        $(".select-akun-status", elDetailView).trigger('change.select2');

        $(".account-avatar").attr("src", Application.accountUrl()+'/pic/acc/'+dataAkun.uid);

        dataLoaded.role = false;
        dataLoaded.akses = false;
        dataLoaded.perangkat = false;

        loadDataRoleAkun();
        loadDataAksesAkun();
        loadDataPerangkatAkun();
        //handleViewData();

        Application.scrollTop();
    }

    var handleViewData = function (){
        if (tabActive == 'role') loadDataRoleAkun();
        else if (tabActive == 'akses') loadDataAksesAkun();
        else if (tabActive == 'perangkat') loadDataPerangkatAkun();
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

    var loadDataMain = function (){
        if (datatable.main){
            datatable.main.ajax.reload(function(){
                resetPaging = true;
            }, resetPaging);
            return;
        }

        datatable.main = Application.dataTable({
            element: '.table-account',
            url: 'account/datatable',
            onSubmit: function (data){
                $.each(ajaxParams, function(key, value) {
                    data[key] = value;
                });
            },
            columns: [
                { "data": null,                 'className' : 'group-control text-center'},
                { "data": "name",               'className' : 'align-middle', 'name': 'name'},
                { "data": "email",              'className' : 'align-middle', 'name': 'email'},
                { "data": "roles.description",  'className' : 'align-middle'},
                { "data": "date_joined_txt",    'className' : 'align-middle'},
                { "data": "status_txt",         'className' : 'text-center'},
                { "data": "status",             'className' : 'text-center'},
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
                        return '<div class="text-truncate" style="width:250px;"><i class="far fa-user text-muted mr-2"></i>'+row.name+'</div>';
                    },
                    "targets": 1
                },
                {
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
                    "targets": 3
                },
                {
                    "render": function (data, type, row) {
                        let cls = statusIcons[row.status];
                        return '<i class="'+cls+' tooltips" title="'+data+'"></i>';

                    },
                    "targets": -3
                },
                {
                    "render": function (data, type, row) {
                        return row.user_device > 0 ? '<i class="icon-iphone text-muted tooltips" title="Terhubung ke perangkat"></i>':'';

                    },
                    "targets": -2
                },
                {
                    "render": function (data, type, row) {
                        return '<a href="javascript:;" class="btn-detail tooltips" title="Detail Akun"><i class="icon-pencil7"></i></a>';
                    },
                    "targets": -1
                },
                {
                    'orderable': false,
                    'targets':  [0,2,3,4,5,-3,-2,-1]
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
                location.hash = 'detail/'+ data.uid;
        });
    }

    var loadDataRoleAkun = function (){
        if (dataLoaded.role) return;

        if (datatable.role){
            datatable.role.ajax.reload();
            return;
        }

        datatable.role = Application.dataTable({
            element: '.table-role-akun',
            url: 'account/roledatatable',
            onSubmit: function (data){
                data['account_id'] = dataAkun.id;
                return data;
            },
            onSuccess: function (res){
                dataLoaded.role = true;
            },
            columns: [
                { "data": null,                 'className' : 'text-center'},
                { "data": "role_name",          'className' : 'align-middle'},
                { "data": "deskripsi",          'className' : 'align-middle'},
                { "data": null ,                'className' : 'text-center'},
            ],
            columnDefs: [
                {
                    'render': function (data, type, row, meta) {
                        return '<i class="'+row.icon+' text-muted"  style="font-size:.8rem;"></i>';
                    },
                    "targets": 0
                },
                {
                    'render': function (data, type, row, meta) {
                        if (row.employee != false){
                            data = '<div class=""><b>Nama:</b> '+row.employee.name+'</div>'+
                                   '<div class=""><b>Department:</b> '+row.employee.department+'</div>'+
                                   '<div class=""><b>Position:</b> '+row.employee.designation+'</div>';
                        }
                        return data;
                    },
                    "targets": 2
                },
                {
                    "render": function (data, type, row) {
                        return row.slug != 'generic' ? '<a href="javascript:;" class="btn-delete tooltips" title="Hapus Akses Role"><i class="icon-trash"></i></a>' : '';
                    },
                    "targets": -1
                },
            ],
            paging: false,
        })

        elTableRole.on('click', 'tr td a.btn-delete', function () {
            var tr = $(this).parents('tr');
            var data = datatable.role.row( tr ).data();

            if (data){
                if (data.slug == 'staff' && dataAkun.id == Application.account().id){
                    Application.alertDialog({
                        message: "Akun yang sedang aktif login tidak bisa mengubah role akun staff"
                    })
                    return;
                }

                Application.confirmDialog({
                    title: "Hapus Role Akun",
                    message: "Role <b>"+data.role_name+"</b> akan dihapus dari Akun <b>"+dataAkun.name+"</b><br>Lanjutkan ?",
                    label:{
                        yes: 'Hapus Role',
                        no:'Batal'
                    },
                    callback: function (){
                        Application.post({
                            container: '.table-role-akun',
                            url: 'account/setrole',
                            data:{
                                account_id: dataAkun.id,
                                role_id: data.role_id,
                                action:'remove'
                            },
                            useAlert: false,
                            success: function (d) {
                                Application.successNotification('Role <b>'+data.role_name+'</b> berhasil dihapus dari Akun <b>'+dataAkun.name+'</b>');
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

    var loadDataAksesAkun = function (){
        if (dataLoaded.akses) return;

        if (datatable.akses){
            datatable.akses.ajax.reload();
            return;
        }

        datatable.akses = Application.dataTable({
            element: '.table-akses-akun',
            url: 'account/accessdatatable',
            onSubmit: function (data){
                data['account_id'] = dataAkun.id;
                return data;
            },
            onSuccess: function (res){
                dataLoaded.akses = true;
            },
            columns: [
                { "data": null,                 'className' : 'text-center'},
                { "data": "name",               'className' : 'align-middle', 'name': 'name'},
                { "data": 'akses_level_txt',    'className' : 'align-middle'},
                { "data": "status_txt",         'className' : 'text-center'},
                { "data": null ,                'className' : 'text-center'},
            ],
            columnDefs: [
                {
                    'render': function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        let akses_level = "- belum di set";
                        let cls = "text-muted font-italic";
                        if (row.valid_access == 1){
                            akses_level = row.akses_level_txt;
                            cls = "";
                        }
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
            paging:false,
            ordering: true
        })

        elTableAkses.on('click', 'tr td .btn-select-akses', function () {
            var tr = $(this).parents('tr');
            var data = datatable.akses.row( tr ).data();
            data.nama_akun = dataAkun.name;

            populateAksesAplikasi(data.application_id, data.access_level_id);

            Application.fillDataValue(elAccessLevelModal, data);
            $(".select-akses", elAccessLevelModal).val(data.access_level_id).trigger('change.select2');
            $("[name=account_id]", elAccessLevelModal).val(dataAkun.id);
            $("[name=appid]", elAccessLevelModal).val(data.appid);

            elAccessLevelModal.modal();
        });

        elTableAkses.on('click', 'tr td .btn-select-status', function () {
            var tr = $(this).parents('tr');
            var data = datatable.akses.row( tr ).data();

            if (data){
                let status_txt = data.status == 1 ? "Non Atifkan" :"Aktifkan";
                Application.confirmDialog({
                    title: "Status Akses",
                    message: "Akses Akun <b>"+dataAkun.name+"</b> ke Aplikasi <b>"+data.name+"</b>  akan <b>di "+status_txt+"</b><br><br>Lanjutkan?",
                    label:{
                        yes: status_txt+' Akses',
                        no:'Batal'
                    },
                    callback: function (){

                        Application.post({
                            container: '.table-akses-akun',
                            url: 'application/setuser',
                            data:{
                                account_id: dataAkun.id,
                                appid: data.appid,
                                status: data.status == 1 ? 0 : 1,
                                action:'status'
                            },
                            useAlert: false,
                            success: function (d) {
                                Application.successNotification('Status Akses Akun <b>'+dataAkun.name+'</b> ke Aplikasi <b>'+data.name+'</b> berhasil di ubah');
                                datatable.akses.ajax.reload(null, false);
                            },
                            failed: function (message) {
                                Application.errorNotification(message);
                            }
                        });
                    }
                })
            }
        });

        elTableAkses.on('click', 'tr td a.btn-delete', function () {
            var tr = $(this).parents('tr');
            var data = datatable.akses.row( tr ).data();

            if (data){
                Application.confirmDialog({
                    title: "Hapus Akses",
                    message: "Akses Akun <b>"+dataAkun.name+"</b> ke Aplikasi <b>"+data.name+"</b> akan <b>DIHAPUS</b><br><br>Lanjutkan?",
                    label:{
                        yes: 'Hapus Akses',
                        no:'Batal'
                    },
                    callback: function (){

                        Application.post({
                            container: '.table-akses-akun',
                            url: 'application/setuser',
                            data:{
                                appid: data.appid,
                                account_id: dataAkun.id,
                                action:'remove'
                            },
                            useAlert: false,
                            success: function (d) {
                                Application.successNotification("Akses Akun <b>"+dataAkun.name+"</b> ke Aplikasi <b>"+data.name+"</b> berhasil di hapus");
                                datatable.akses.ajax.reload(null, false);
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

    var loadDataPerangkatAkun = function (){
        if (dataLoaded.perangkat) return;

        if (datatable.perangkat){
            datatable.perangkat.ajax.reload();
            return;
        }

        datatable.perangkat = Application.dataTable({
            element: '.table-perangkat-akun',
            url: 'account/devicedatatable',
            onSubmit: function (data){
                let periode = $('#perangkat .input-bulan').datepicker('getDate');
                data['periode'] = periode ? moment(periode).format("YYYY-MM-01") : null;
                data['application_id'] = $("#perangkat .select-aplikasi").val();
                data['query'] = $("#perangkat .input-query").val();
                data['account_id'] = dataAkun.id;
                return data;
            },
            onSuccess: function (res){
                dataLoaded.perangkat = true;
            },
            columns: [
                { "data": null,                 'className' : 'text-center'},
                { "data": "nama_aplikasi",      'className' : 'align-middle', 'name': 'name'},
                { "data": "device_name",        'className' : 'align-middle', 'name': 'device_name'},
                { "data": "os",                 'className' : 'align-middle'},
                { "data": 'modified_txt' ,      'className' : 'align-middle', 'name': 'modified'},
            ],
            columnDefs: [
                {
                    'render': function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    "targets": 0
                },
                {
                    'orderable': false,
                    'targets':  [0, 3]
                }
            ],
            order: [[4, 'desc']],
            lengthMenu: [[20, 50, 100], [20, 50, 100]],
            ordering: true
        })
    }

    var loadTableStaff = function (){
        if (datalist.staff){
            datalist.staff.ajax.reload();
            return;
        }
        datalist.staff = Application.dataTable({
            element: '.table-staff',
            dom: '<"datatable-scroll"t><"datatable-footer"p>',
            url: 'employee/datatable',
            onSubmit: function (data){
                data['department'] = $("#select-staff-modal .select-department").val();
                data['query'] = $("#select-staff-modal .input-query").val();
                return data;
            },
            columns: [{
                "data": 'full_name'
                },{
                "data": 'department'
            }],
            columnDefs: [{
                "render": function (data, type, row) {
                    return row.department+ ' / ' + row.title;
                },
                "targets": [1]
            },{
                "orderable": false,
                "targets": [0,1]
            }],
            select: {
                style: 'single',
                selector: 'td:not(.disabled)',
            },
            ordering: true,
            pageLength: 10,
            scrollY: '350px',
            scrollCollapse: true,
        })
    }

    var loadTableAplikasi = function (){
        if (datalist.aplikasi){
            datalist.aplikasi.ajax.reload();
            return;
        }
        datalist.aplikasi = Application.dataTable({
            element: '.table-aplikasi',
            dom: '<"datatable-scroll"t>',
            url: 'application/listapp',
            onSubmit: function (data){
                data['account_id'] = dataAkun.id;
                data['query'] = $("#select-aplikasi-modal .input-query").val();
                return data;
            },
            columns: [{
                "data": null,
                },{
                "data": 'name', 'name' :'name'
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
                "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.disabled == 1) $(td).addClass('disabled text-muted font-italic');
                },
                "targets": [1,2]
            },{
                "orderable": false,
                "targets": [0,-1]
            }],
            ordering:true,
            select: {
                style: 'multi',
                selector: 'td:not(.disabled)',
            },
            paging: false,
            scrollY: '350px',
            scrollCollapse: true,
        })
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();