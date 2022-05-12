var ManageEmployee = function () {

    var datatable = {};
    var dataEmployee;
    var elMainView = $("#datagrid-view");
    var elCreateView = $("#create-view");
    var elDetailView = $("#detail-view");
    var loaded = false;
    var dataLoaded  = {};
    var employeeId;
    var tabActions = ['informasi','role', 'akses', 'perangkat', 'setting'];
    var tabActive;
    var elTable = $('.table-account');
    var resetPaging = true;
    var formCreate = $("#form_create");
    var formEdit = $("#form_edit");

    var ajaxParams = {};
    var f_department, f_type, f_query;

    var submitFilter = function(){
        var _department = $('.select-department').val();
        var _type = $('.select-employment-type').val();
        var _query = $('.input-nama').val();

        var data = {d: _department, t:_type, q:_query , rdm: Math.random()};
        location.hash=$.param(data);
    }

    var setAjaxParam = function(name, value) {
        ajaxParams[name] = value;
    }

    var setFilterParams = function (){
        $(".select-department").val(f_department).trigger('change.select2');
        $(".select-employment-type").val(f_type).trigger('change.select2');
        $(".input-nama").val(f_query);

        setAjaxParam('department', f_department);
        setAjaxParam('employment_type', f_type);
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
            if (_tab) location.hash = 'detail/'+dataEmployee.id+'/'+_tab;
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

        $(".btn-add-employee").on('click', function(e){
            e.preventDefault();
            location.hash = 'create';
        })

        $(".btn-delete-employee").on('click', function(e){
            e.preventDefault();

            var message = "Employee berikut akan di hapus <br><br>"+
            '<b>Nama</b>: '+(dataEmployee.full_name)+'<br>'+
            '<b>Department</b>: '+(dataEmployee.department)+'<br>'+
            '<b>Designation</b>: '+(dataEmployee.designation)+' <br><br>'+
            'Lanjutkan ?';

            Application.confirmDialog({
                title: 'Delete Employee Data',
                message : message,
                label:{
                    yes: 'DELETE',
                    no: 'Cancel',
                },
                callback: function (){
                    Application.post({
                        container: '.content',
                        url: 'employee/delete',
                        data: {account_id : dataEmployee.id},
                        useAlert: false,
                        success: function (data) {
                            Application.infoDialog({
                                message: 'Akun <b>'+dataEmployee.name+'</b> berhasil di hapus',
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

        Application.validateForm({
            container: '#form_create',
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'employee/create',
                    data: $('form#form_create').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        Application.infoDialog({
                            message: "Akun baru berhasil di simpan.",
                            callback: function (){
                                location.hash="detail/"+data.id;
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
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'employee/edit',
                    data: $('form#form_edit').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        dataEmployee = data;
                        Application.fillDataValue(elDetailView, dataEmployee);
                        Application.successNotification("Data has been successfully saved");
                        Application.scrollTop();
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

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
                var _id = _aHash.get(1);
                tabActive = _aHash.get(2);
                if (!tabActions.includes(tabActive)) tabActive = tabActions[0];

                if (_id)
                {
                    if (loaded && employeeId == _id){
                        Application.switchView(_mode);
                        activateTab(tabActive);
                        handleViewData();
                    }else{
                        Application.post({
                            container: '.content',
                            url: 'employee/get',
                            data: { id: _id},
                            useBlockUI: tabActive == 'informasi',
                            useAlert: false,
                            success: function (data) {
                                employeeId = _id;
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

                f_department = ('d' in _params) ? _params.d : '';
                f_type = ('t' in _params) ? _params.t : '';
                f_query = ('q' in _params) ? _params.q : '';

                setFilterParams();

                loaded = false;
                Application.switchView('datagrid');
                loadDataMain();
			}
        });

        $(window).hashchange();
    }

    var handleViewCreate = function (){
        formCreate[0].reset();
        formCreate.find('.form-group').removeClass('has-error');
        formCreate.find('.select2').trigger('change.select2');

    }

    var handleViewDetail = function (data, dontchange){
        dataEmployee = data;
        formEdit.find('.form-group').removeClass('has-error');

        Application.fillDataValue(elDetailView, dataEmployee);
        Application.fillFormData(elDetailView, dataEmployee);

        formEdit.find('.select2').trigger('change.select2');

        $(".account-avatar").attr("src", Application.accountUrl()+'/pic/acc/'+dataEmployee.id);

        Application.scrollTop();
    }

    var handleViewData = function (){

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
            url: 'employee/datatable',
            onSubmit: function (data){
                $.each(ajaxParams, function(key, value) {
                    data[key] = value;
                });
            },
            columns: [
                { "data": null,                 'className' : 'group-control text-center'},
                { "data": "full_name",          'className' : 'align-middle'},
                { "data": "department",         'className' : 'align-middle'},
                { "data": "title",              'className' : 'align-middle'},
                { "data": "phone",              'className' : 'align-middle'},
                { "data": "employment_type_txt",'className' : 'align-middle'},
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
                    "render": function (data, type, row) {
                        return '<a href="javascript:;" class="btn-detail tooltips" title="Detail"><i class="icon-pencil7"></i></a>';
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
                location.hash = 'detail/'+ data.id;
        });
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();