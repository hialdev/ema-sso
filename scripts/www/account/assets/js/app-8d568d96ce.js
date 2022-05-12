var Application = function() {

    var baseUrl;
    var appTitle;
    var accountUrl;
    var writable;

    var accountData = {
        username : '',
    };

    var handleAppData= function (){
        var initData = $("body").attr('data-init');
        if (initData){
            var appData = $.parseJSON(Base64.decode(Base64.decode(initData)));
            if (appData) {
                accountData = appData.account;
                appTitle = appData.appTitle;
                accountUrl = appData.accountUrl;
                writable = appData.writable;
            }
        }
        baseUrl = $('base').attr('href');
        //Application.checkInbox();
    }

    var handleExit = function () {
        $('.doLogoutApp').click(function(){
            console.log("x");
            Application.dialog({
                class: "modal-sm confirm-exit",
                message: "<div style='height:100px;display: flex; margin:auto; font-size:medium;' class='text-center'><p style='margin:auto;' class='text-center'>Anda akan keluar dari<br>semua aplikasi <b>Elang Merah</b></p></div>",
                title: "Konfirmasi",
                cancelButton: {
                    class: "btn-light",
                    label: 'Batal'
                },
                okButton: {
                    class: "btn-danger",
                    label: '<i class="icon-cross2"></i> keluar',
                    callback: function (){
                        $(location).attr('href','auth/exit');
                    }
                },
            });
        });
    }

    var handleToggleTheme = function (){
        $('.toggle-dark-theme').click(function(){
            var theme = $("body").hasClass('dark-mode') ? "light" :"dark";
            Application.post({
                container: '.content',
                url: 'akun/usertheme',
                data: {theme: theme},
                useAlert: false,
                useBlockUI: false,
                success: function (data) {
                    if (theme == 'dark') $("body").addClass("dark-mode");
                    else $("body").removeClass("dark-mode");
                },
                failed: function (message) {
                    Application.errorNotification(message);
                }
            });

        });
    }
    // Perfect scrollbar
    var _componentPerfectScrollbar = function() {
        if (typeof PerfectScrollbar == 'undefined') {
            console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
            return;
        }

        // Initialize
        var ps = new PerfectScrollbar('.sidebar-fixed .sidebar-content', {
            wheelSpeed: 2,
            wheelPropagation: true
        });
    };

    var  handleWidgets = function (){

        _componentPerfectScrollbar();


        $('.tooltips').tooltip({html: true});
        /* autosize($('textarea')); */
        $.trumbowyg.svgPath = '/assets/images/icons.svg';

        $('.select2').select2({
            language: 'id',
        });
        $('.select2-nosearch').select2({
            language: 'id',
            minimumResultsForSearch: Infinity
        });
        $('.select2-tag').select2({
            tags: true,
            language: {
                noResults: function(){
                    return "Ketik untuk menambah tag";
                }
            },
        });

        $('.select2-autowidth').select2({
            language: 'id',
            dropdownAutoWidth : true,
        })

        $('.select2-token').select2({
            tags: true,
            language: 'id',
            tokenSeparators: [',', ' ']
        });

        // Format icon
        function iconFormat(icon) {
            if (!icon.id) { return icon.text; }
            var $icon = '<i class="' + $(icon.element).data('icon') + ' text-muted"  style="font-size:.8rem;"></i>' + icon.text;
            return $icon;
        }

        $('.select2-with-icon').select2({
            templateResult: iconFormat,
            //minimumResultsForSearch: Infinity,
            templateSelection: iconFormat,
            escapeMarkup: function(m) { return m; }
        });

    }

    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

    return {
        init: function (){
            handleAppData();
            handleWidgets();

            handleToggleTheme();
            handleExit();
        },

        baseUrl: function(){
            return baseUrl;
        },

        accountUrl: function(){
            return accountUrl;
        },

        title: function(){
            return appTitle;
        },

        writable: function(){
            return writable;
        },

        // wrApplicationer function to scroll(focus) to an element
        scrollTo: function(el, offeset) {
            var pos = (el && el.size() > 0) ? el.offset().top : 0;

            if (el) {
                if ($('body').hasClass('page-header-fixed')) {
                    pos = pos - $('.page-header').height();
                } else if ($('body').hasClass('page-header-top-fixed')) {
                    pos = pos - $('.page-header-top').height();
                } else if ($('body').hasClass('page-header-menu-fixed')) {
                    pos = pos - $('.page-header-menu').height();
                }
                pos = pos + (offeset ? offeset : -1 * el.height());
            }

            $('html,body').animate({
                scrollTop: pos
            });

            /* $('html,body').animate({
                scrollTop: pos
            }, 'slow'); */
        },

        scrollTop: function() {
            Application.scrollTo();
        },

        // wrApper function to  block element(indicate loading)
        blockUI: function(options) {
            options = $.extend(true, {
                showloader: true,
            }, options);
            //var html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="assets/images/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';

            var html = options.showloader ? '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="assets/images/loading-spinner.gif" height="40" align=""></div>' : '';

            if (options.target) { // element blocking
                var el = $(options.target);
                if (el.height() <= ($(window).height())) {
                    options.cenrerY = true;
                }
                el.block({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 9999,
                    centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                    css: {
                        top: '10%',
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#000',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            } else { // page blocking
                $.blockUI({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 9999,
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#000',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            }
        },

        // wrApper function to  un-block element(finish loading)
        unblockUI: function(target) {
            if (target) {
                $(target).unblock({
                    onUnblock: function() {
                        $(target).css('position', '');
                        $(target).css('zoom', '');
                    }
                });
            } else {
                $.unblockUI();
            }
        },

        validateForm: function (option){

            jQuery.extend(jQuery.validator.messages, {
                required: "",
                email:"Silakan masukkan alamat email dengan benar",
                number:"Silakan masukkan angka / nomor",
                minlength: jQuery.validator.format("Minimum {0} karakter"),
                equalTo: "Konfirmasi password harus sama"
            });

            var setting = $.extend(true,{
                container: 'form',
                focusInvalid: true,
                errorClass: 'input-validation-error',
                errorElement: 'span',
                ignore: '',
                rules:{},
                messages:{},

                invalidHandler: function (event, validator){},
                onSubmit: function (form){},
                onBeforeSubmit:function (form){},
            },option);

            var form1 = $(setting.container);

            var validator = form1.validate({
                errorElement: setting.errorElement, //default input error message container
                errorClass: setting.errorClass, // default input error message class
                focusInvalid: setting.focusInvalid, // do not focus the last invalid input
                ignore: setting.ignore, // validate all fields including form hidden input
                messages: setting.messages,
                rules: setting.rules,
                invalidHandler: function(event, validator) { //display error alert on form submit
                    //error1.show();
                    //App.scrollTo(error1, -200);
                    setting.invalidHandler.call(undefined, event, validator);
                },

                errorPlacement: function(error, element) {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                },

                highlight: function(element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function(element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function(label) {
                    /* label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group */
                },

                submitHandler: function(form) {
                    //error1.hide();

                    //if ($(form).valid()) form.submit();

                    // call beforeSubmit in case, we have external validation checking
                    // if return is false, abort it
                    if (setting.onBeforeSubmit.call(undefined, form) == false)
                        return false;

                    setting.onSubmit.call(undefined, form);

                    //return false; // prevent normal form posting
                }
            });

            form1.find("select").on("select2:close", function (e) {
                $(this).valid();
            });

            return validator;
        },

        // seizazies newly added
        // -------------------------------------------------------------------------------

        clearValidation : function(formElement){
            //Internal $.validator is exposed through $(form).validate()
            var validator = $(formElement).validate();
            //Iterate through named elements inside of the form, and mark them as error free
            $('[name]',formElement).each(function(){
              validator.successList.push(this);//mark as error free
              validator.showErrors();//remove error messages if present
            });
            validator.resetForm();//remove error class on name elements and clear history
            validator.reset();//remove all error and success data
        },

        populateSelect: function(selectElement, data, defaultValue, donotclear){
            //$('option:not([value=""])', selectElement).remove();
            if (donotclear != true)
                Application.clearSelect(selectElement);

            $.each(data, function (i, option){
                var disabled = 'disabled' in option && option.disabled ? 'disabled' : '';
                selectElement.append('<option value="'+option.id+'" '+disabled+'>'+option.name+'</option>');
                //$("[value='"+option.id+"']", selectElement).data('data', option);
            });

            if (defaultValue != undefined)
                selectElement.val(defaultValue);
        },

        clearSelect: function(selectElement){
            $('option:not([value=""])', selectElement).remove();
        },

        dialog: function(dOptions) {
            var options = $.extend(true, {
                    class: "",
                    title: "Dialog Title",
                    message: "Dialog Message",
                    cancelButton: {
                        class: "",
                        label: '<i class="icon-reload-alt"></i> Batal',
                        callback: function (){}
                    },
                    okButton: {
                        class: "btn-success",
                        label: '<i class="icon-checkmark2"></i> OK',
                        callback: function (){}
                    },
                },dOptions);

            var tmpl = [

                '<div class="z-dialog modal" data-backdrop="static" tabindex="-1">',
					'<div class="modal-dialog ',options.class,'">',
						'<div class="modal-content">',
							'<div class="modal-header ">',
								'<h5 class="modal-title font-weight-bold"><i class="icon-question3"></i> ',options.title,'</h5>',
								'<button type="button" class="close" data-dismiss="modal">&times;</button>',
							'</div>',

							'<div class="modal-body">',
                                '<p>',options.message,'</p>',
							'</div>',

							'<div class="modal-footer">',
                                '<button type="button" data-dismiss="modal" class="btn canccel btn-xs ',options.cancelButton.class,'">',options.cancelButton.label,'</button>',
								'<button type="button" data-dismiss="modal" class="btn ok btn-xs ',options.okButton.class,'">',options.okButton.label,'</button>',
							'</div>',
						'</div>',
                    '</div>',
                '</div>'

              ].join('');

            var instModal = $(tmpl).modal();

            $(".z-dialog button.ok").one('click', function (){
                options.okButton.callback.call(undefined);
            });

            $(".z-dialog").one('hide.bs.modal', function (){
                options.cancelButton.callback.call(undefined);
                $(".z-dialog").remove();
            });

            return instModal;
        },

        deleteDialog: function (options){
            return Application.dialog({
                class: (options.class) ? options.class : "modal-sm",
                message: (options.message) ? options.message : "Are you sure want to delete this data ?",
                title: (options.title) ? options.title : "Delete Confirmation",
                cancelButton: {
                    class: "btn ",
                    label: '<i class="icon-reload-alt"></i> Confirm'
                },
                okButton: {
                    class: "btn bg-red",
                    label: '<i class="fa fa-trash"></i> Delete',
                    callback: options.callback
                },
            });
        },

        confirmDialog: function (dOptions){
            var opt = $.extend(true,{
                class: "modal-sm",
                title: "Confirmation",
                message: "Are you to proceed the process ?",
                callback: function (){},
                label: {
                    yes: 'Confirm',
                    no: 'Cancel'
                },
                buttonclass: {
                    yes: 'Confirm',
                    no: 'Cancel'
                }
            }, dOptions);


            var options = {
                class: opt.class,
                title: opt.title,
                message: opt.message,
                cancelButton: {
                    class: "btn "+opt.buttonclass.no,
                    label: '<i class="icon-reload-alt"></i> '+opt.label.no
                },
                okButton: {
                    class: "btn bg-blue "+opt.buttonclass.yes,
                    label: '<i class="icon-checkmark2"></i> '+opt.label.yes,
                    callback: opt.callback
                },
            };

            return Application.dialog(options);
        },

        infoDialog: function (dOptions) {
            var options = $.extend(true, {
                    icon: "icon-checkmark2",
                    class: "modal-sm",
                    title: "Information",
                    message: "Process successfully done.",
                    okButton: {
                        class: "bg-blue",
                        label: '<i class="icon-checkmark2"></i> OK'
                    },
                    callback: function (){}
                },dOptions);

            var tmpl = [
                '<div class="z-info-dialog modal" data-backdrop="static" tabindex="-1">',
					'<div class="modal-dialog ',options.class,'">',
						'<div class="modal-content">',
							'<div class="modal-header">',
								'<h5 class="modal-title font-weight-bold"><i class="',options.icon,'"></i> ',options.title,'</h5>',
								'<button type="button" class="close" data-dismiss="modal">&times;</button>',
							'</div>',

							'<div class="modal-body">',
                                '<p>',options.message,'</p>',
							'</div>',

							'<div class="modal-footer">',
								'<button type="button" data-dismiss="modal" class="btn ok btn-xs ',options.okButton.class,'">',options.okButton.label,'</button>',
							'</div>',
						'</div>',
                    '</div>',
                '</div>'


                /* '<div class="z-info-dialog modal " data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="zInfoDialogModal">',
                    '<div class="modal-dialog ',options.class,'">',
                        '<div class="modal-content">',
                            '<div class="modal-header">',
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">',
                                '<span aria-hidden="true">&times;</span></button>',
                                '<h4 class="modal-title"><i class="',options.icon,'"></i> ',options.title,'</h4>',
                            '</div>',
                            '<div class="modal-body">',
                                '<p>',options.message,'</p>',
                            '</div>',
                            '<div class="modal-footer">',
                                '<button type="button" data-dismiss="modal" class="btn ok btn-sm ',options.okButton.class,'">',options.okButton.label,'</button>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>' */
              ].join('');

            var instModal = $(tmpl).modal();
            $(".z-info-dialog").one('hidden.bs.modal', function (){
                options.callback.call(undefined);
            });

            return instModal;
        },

        alertDialog: function(dOptions) {
            var options = $.extend(true, {
                    icon: "icon-warning",
                    class: "modal-sm",
                    title: "Warning",
                    message: "An error occured while processing the request.",
                    okButton: {
                        class: "bg-blue",
                        label: '<i class="icon-checkmark2"></i> OK'
                    },
                    callback: function (){}
                },dOptions);

            var tmpl = [
                '<div class="z-alert-dialog modal" data-backdrop="static" tabindex="-1">',
					'<div class="modal-dialog ',options.class,'">',
						'<div class="modal-content">',
							'<div class="modal-header">',
								'<h5 class="modal-title font-weight-bold"><i class="',options.icon,'"></i> ',options.title,'</h5>',
								'<button type="button" class="close" data-dismiss="modal">&times;</button>',
							'</div>',

							'<div class="modal-body">',
                                '<p>',options.message,'</p>',
							'</div>',

							'<div class="modal-footer">',
								'<button type="button" data-dismiss="modal" class="btn ok btn-xs ',options.okButton.class,'">',options.okButton.label,'</button>',
							'</div>',
						'</div>',
                    '</div>',
                '</div>'

/*                 '<div class="z-alert-dialog modal " data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="zAlertDialogModal">',
                    '<div class="modal-dialog ',options.class,'">',
                        '<div class="modal-content">',
                            '<div class="modal-header">',
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">',
                                '<span aria-hidden="true">&times;</span></button>',
                                '<h4 class="modal-title"><i class="',options.icon,'"></i> ',options.title,'</h4>',
                            '</div>',
                            '<div class="modal-body">',
                                '<p>',options.message,'</p>',
                            '</div>',
                            '<div class="modal-footer">',
                                '<button type="button" data-dismiss="modal" class="btn ok btn-sm ',options.okButton.class,'">',options.okButton.label,'</button>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>'
 */
              ].join('');

              var instModal = $(tmpl).modal();

            $(".z-alert-dialog").one('hidden.bs.modal', function (){
                options.callback.call(undefined);
            });

            return instModal;
        },

        ajaxCall: function (options){
            var setting = $.extend(true,{
                container: '',
                url: '',
                async: true,
                data: {},
                loaderIn: '',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                processData: true,
                success: function (data){},
                failed: function (msg){},
                before:function (data){},
                done:function (data){},
                message: 'Processing ...',
                useAlert: false,
                useBlockUI: true,
                type: "POST",
            }, options);

            $.ajax({
                type: setting.type,
                data : setting.data,
                cache: false,
                async: setting.async,
                processData: setting.processData,
                contentType: setting.contentType,
                dataType: 'json',
                url: baseUrl+setting.url,
                beforeSend: function( xhr ) {
                    if (setting.useBlockUI){
                        Application.blockUI({
                            message: setting.message,
                            showloader: setting.loaderIn ? false: true,
                            target: setting.container,
                            //overlayColor: 'none',
                            overlayColor: '#000',
                            cenrerY: true,
                            //boxed: true
                        });
                    }

                    if (setting.loaderIn){
                        $(setting.loaderIn).append('<img src="assets/images/spinner.gif" height="30" class="image-loader ml-2">');
                    }

                    setting.before(xhr);
                },
                success: function(response){
                    if (setting.useBlockUI)
                    Application.unblockUI(setting.container);

                    if (setting.loaderIn){
                        $(".image-loader").remove();
                    }

                    var data = null;
                    if (response.status == 1){
                        data = ('data' in response) ? response.data: undefined;
                        setting.success(data);
                    }else{
                        if (response.message == 'ERROR_NOT_LOGGED')
                        {
                            loggedIn = false;
                            var url =  'login?dst='+window.location.pathname+location.hash.replace('#','.');
                            var msgError = 'ERROR: Session login sudah berakhir.</br>Silakan <a href="'+url+'" title="Klik disini untuk login" class="bold tooltips btn btn-xs dark btn-circle btn-outline uppercase">login</a> kembali untuk melanjutkan.';

                            if (setting.useAlert)
                            {
                                Application.alertDialog({
                                    message: msgError,
                                    callback: function (){
                                        window.location.href = 'login?dst='+window.location.pathname+location.hash.replace('#','.');
                                    }
                                });
                            }
                            else setting.failed(msgError);
                        }
                        else
                        {
                            var msgError = 'ERROR: '+ ((response.message) ? response.message : "Unknown response.");
                            if (setting.useAlert){
                                Application.alertDialog({
                                    message: msgError,
                                });
                            }
                            setting.failed(msgError);
                        }
                    }
                    return data;
                },
                error: function(o,t,e){
                    if (setting.useBlockUI)
                        Application.unblockUI(setting.container);

                    if (setting.loaderIn){
                        $(".image-loader").remove();
                    }

                    var msgError = 'ERROR: Unable to reach server. ' + o.responseText ;

                    if (setting.useAlert){
                        Application.alertDialog({
                            message: msgError,
                        });
                    }
                    setting.failed(msgError);
                    return false;
                }
            }).always(function() {
                setting.done();
            });
        },

        get: function (options){
            var setting = $.extend(true, {
                type: "GET",
            }, options);
            Application.ajaxCall(setting);
        },

        post: function (options){
            var setting = $.extend(true, {
                type: "POST",
            }, options);
            Application.ajaxCall(setting);
        },

        httpPost: function (url, data, container, success, failed, before, done){

            var setting = {
                container: container,
                url: url,
                data: data,
                callback: success,
                failed: failed,
                before: before,
                done: done
            };

            if (container == undefined) setting.useBlockUI = false;

            Application.post(setting);
        },

        httpGet: function (url, data, container, success, failed, before, done){

            var setting = {
                container: container,
                url: url,
                data: data,
                callback: success,
                failed: failed,
                before: before,
                done: done
            };

            if (container == undefined) setting.useBlockUI = false;

            Application.get(setting);
        },


        parseParams: function (queryString){
            return queryString.split('&').reduce(function (params, param) {
                var paramSplit = param.split('=').map(function (value) {
                    return decodeURIComponent(value.replace('+', ' '));
                });
                params[paramSplit[0]] = paramSplit[1];
                return params;
            }, {});
        },

        toLocalDate: function (stringDate){
            if ($.trim(stringDate).length == 0) return '';
            return stringDate.substr(5,2)+'-'+stringDate.substr(8,2)+'-'+stringDate.substr(0,4);
        },

        fillElementData: function (parentElement, objectData){
            if ($.isPlainObject(objectData)){
                $.each(objectData, function (id, value){
                    if (value == null) value = '-';
                    else if (Application.isEmpty(value)) value = '&nbsp;';
                    var _html = '<span data-id="'+id+'">'+value+'</span>';
                    $(parentElement).find("[data='"+id+"']").html(_html);
                })
            }
        },

        fillDataValue: function (parentElement, data, attribute, defaultvalue){
            if (attribute == undefined) attribute = "data";
            $('['+attribute+']',parentElement).each(function(i, element){
                var dataval = $(element).attr("data");
                var value;
                try{value = eval("data."+dataval);}catch(e){}
                if (Application.isEmpty(value)) value = defaultvalue == undefined ? '&nbsp;' : defaultvalue;
                $(element).html(value);
            });
        },

        fillFormData: function (element, data){
            $.each (data, function (field, value){
                $("[name="+field+"]", element).val(value);
            });
        },

        base64encode: function (string){
            return Base64.encode(string);
        },

        base64decode: function (string){
            return Base64.decode(string);
        },

        formatMoney: function (n, c, d, t){
            if (!$.isNumeric(n)) return n;

            var c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        },

        uuidv4: function() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        },

        isSelect2: function (element){
            return $(element).hasClass("select2-hidden-accessible");
        },

        select2Value: function (element){
            if (Application.isSelect2(element))
                return $(element).select2('val');
            else
                return "";
        },

        errorNotification: function (message, donotclear){
            if (donotclear != true) Noty.closeAll();
            new Noty({
                ////theme: ' alert alert-danger alert-styled-left p-0',
                theme: 'limitless',
                text: message,
                timeout:3000,
                type: 'error',
                //progressBar: false,
            }).show();
        },

        warningNotification: function (message, donotclear){
            if (donotclear != true) Noty.closeAll();
            new Noty({
                ////theme: ' alert alert-danger alert-styled-left p-0',
                theme: 'limitless',
                text: message,
                timeout:3000,
                type: 'warning',
                //progressBar: false,
            }).show();
        },

        successNotification: function (message, donotclear){
            if (donotclear != true) Noty.closeAll();
            new Noty({
                //theme: ' alert alert-success alert-styled-left p-0',
                theme: 'limitless',
                text: message,
                timeout:3000,
                type: 'success',
                progressBar: false,
            }).show();
        },

        setLookupDataAjax: function (options){
            var options = $.extend(true,{
                        url: "",
                        type: "POST",
                        cache: true,
                        dataType: 'json',
                        element: undefined,
                        usetemplate: false,
                        readonly: false,
                        extdata: [],
                        query: {},
                        select: {
                            placeholder: "Pilih ...",
                            allowClear: true,
                            dropdownAutoWidth : false,
                            width: '100%'
                        },
                        defaultValue: undefined
                },options);

                if (options.element === undefined)
                    return;

                $.ajax({
                    type: options.type,
                    cache: options.cache,
                    dataType: options.dataType,
                    data: options.query,
                    url: baseUrl+options.url,
                    success: function(response){
                        var hasDisplay = (response.length>0 && 'display' in response[0]);
                        var data = (options.extdata.length > 0) ? $.merge(options.extdata, response) : response;
                        var optselect = $.extend(true,{
                            data: data
                        },options.select);

                        $(options.element).empty();
                        if (options.select.allowClear) $(options.element).append("<option/>");
                        $(options.element).select2(optselect);

                        if (options.defaultValue) {
                            $(options.element).val(options.defaultValue).trigger('change.select2');
                        }
                    },
                    error: function(o,t,e){
                    }
                });
        },

        account: function(){
            return accountData;
        },

        accountAdministrator: function(){
            return accountData.role == 'admin' || accountData.role == 'superadmin';
        },

        initDataTable: function (){
            $.extend( $.fn.dataTable.defaults, {
                dom: '<"datatable-scroll"t><"datatable-footer"<"col-md-6"li><"col-md-6"p>>',
                language: {
                    emptyTable  : '<span class="text-muted font-italic">Tidak ada data</span>',
                    search      : '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu  : '<span>Show</span> _MENU_',
                    infoEmpty   : '<span class="label label-gray"></span>',
                    zeroRecords : "Data tidak ditemukan",
                    paginate    : {
                        'first': '<<',
                        'last': '>>',
                        'next': $('html').attr('dir') == 'rtl' ? '<' : '>',
                        'previous': $('html').attr('dir') == 'rtl' ? '>' : '<',
                        'info': 'Hal _INPUT_ dari _TOTAL_'
                    }
                },
                processing  : true,
                serverSide  : true,
                deferRender : true,
                autoWidth   : false,
                pagingType  : 'input',
                lengthChange: true,
                lengthMenu  : [[10, 25, 50], [10, 25, 50]],
                paging      : true,
                searching   : false,
                ordering    : false,
                info        : true,
                scrollX     : false,
            });
        },

        dataTable: function (option){
            var setting = $.extend(true,{
                element: '',
                useBlocking: true,
                url: '',
                onSubmit: function(data){return data;},
                onSuccess: function (resource){}
            },option);

            var tableContainer = $(setting.element).closest('.table-container');
            if (tableContainer.length == 0) tableContainer = setting.element;

            var datatableOptions = $.extend(true,{
                dom: '<"datatable-scroll"t><"datatable-footer"<"col-md-6"li><"col-md-6"p>>',
                language: {
                    emptyTable  : '<span class="text-muted font-italic">Tidak ada data</span>',
                    search      : '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu  : '<span>Show</span> _MENU_',
                    infoEmpty   : '<span class="label label-gray"></span>',
                    zeroRecords : "Data tidak ditemukan",
                    paginate    : {
                        'first': '<<',
                        'last': '>>',
                        'next': $('html').attr('dir') == 'rtl' ? '<' : '>',
                        'previous': $('html').attr('dir') == 'rtl' ? '>' : '<',
                        'info': 'Hal _INPUT_ dari _TOTAL_'
                    }
                },
                processing  : true,
                serverSide  : true,
                deferRender : true,
                autoWidth   : false,
                pagingType  : 'input',
                lengthChange: true,
                lengthMenu  : [[10, 25, 50], [10, 25, 50]],
                paging      : true,
                searching   : false,
                ordering    : false,
                info        : true,
                scrollX     : false,
                ajax: {
                    type: "POST",
                    url: baseUrl+setting.url,
                    data: function (data){
                        setting.onSubmit(data);

                        if (setting.useBlocking){
                            Application.blockUI({
                                message: "Loading",
                                target: tableContainer,
                                overlayColor: 'silver',
                                cenrerY: true,
                                //boxed: true
                            })
                        }
                    },
                    dataSrc: function (res){
                        if (setting.useBlocking) {
                            Application.unblockUI(tableContainer);
                        }
                        if (res.status == false && res.code == "NO_LOGIN"){
                            Application.alertDialog({
                                message:res.message,
                                callback: function (){
                                    window.location.reload();
                                }
                            })
                        }
                        else setting.onSuccess(res);
                        return res.data;
                    },
                    error: function (res){
                        if (setting.useBlocking) {
                            Application.unblockUI(tableContainer);
                        }
                    }
                },
                createdRow: function (row, data, index){
                    $(".tooltips", $(row)).tooltip({container: 'body', trigger: 'hover'});
                }
            }, setting);

            return $(setting.element).DataTable(datatableOptions);
        },

        dataTableFromJson: function (option){
            var setting = $.extend(true,{
                element: '',
                useBlocking: true,
                data: {}
            },option);

            var tableContainer = $(setting.element).closest('.table-container');
            if (tableContainer.length == 0) tableContainer = setting.element;

            var datatableOptions = $.extend(true,{
                dom: '<"datatable-scroll"t><"datatable-footer"<"col-md-6"li><"col-md-6"p>>',
                data: setting.data,
                language: {
                    emptyTable  : '<span class="text-muted font-italic">Tidak ada data</span>',
                    search      : '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu  : '<span>Show</span> _MENU_',
                    infoEmpty   : '<span class="label label-gray"></span>',
                    zeroRecords : "Data tidak ditemukan",
                    paginate    : {
                        'first': '<<',
                        'last': '>>',
                        'next': $('html').attr('dir') == 'rtl' ? '<' : '>',
                        'previous': $('html').attr('dir') == 'rtl' ? '>' : '<',
                        'info': 'Hal _INPUT_ dari _TOTAL_'
                    }
                },
                autoWidth   : false,
                pagingType  : 'input',
                lengthChange: true,
                lengthMenu  : [[10, 25, 50], [10, 25, 50]],
                paging      : true,
                searching   : false,
                ordering    : false,
                info        : true,
                scrollX     : false,
                createdRow: function (row, data, index){
                    $(".tooltips", $(row)).tooltip({container: 'body', trigger: 'hover'});
                }
            }, setting);

            return $(setting.element).DataTable(datatableOptions);
        },


        showElement: function (element, show){
            var elObject = $.type(element) == 'string' ? $(element) : element;
            if (show == undefined || show){
                elObject.removeClass('hide');
            }else{
                Application.hideElement(elObject);
            }
        },
        hideElement: function (element){
            var elObject = $.type(element) == 'string' ? $(element) : element;
            elObject.addClass('hide');
        },

        disableElement: function (element, disable){
            var elObject = $.type(element) == 'string' ? $(element) : element;
            if (disable == undefined) disable = true;
            elObject.prop('disabled', disable);
        },

        isEmpty: function (object){
            return object == undefined || object == null || object == '';
        },


        select2Remote: function (element, option){

            var setting = $.extend(true,{
                url: '',
                placeholder: 'Pilih ...',
                minimumInputLength: 3,
                templateResult: function (result){
                    if (result.loading) return "Mencari ...";

                    var markup = '<div class="select2-result-repository">' +
                            '<div class="select2-result-repository__title">' + result.nama + '</div>' +
                            '<div class="select2-result-repository__description">' + result.keterangan + '</div>' +
                        '</div>';

                    return markup;
                },
                templateSelection: function  (result) {
                    if (result.id === '') {
                        return setting.placeholder;
                    }
                    return result.text;
                }
            },option);

            $(element).select2({
                ajax: {
                    url: baseUrl+setting.url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {

                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },

                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: setting.minimumInputLength,
                templateResult: setting.templateResult,
                templateSelection: setting.templateSelection
            });

        },

        getApps: function (){
            Application.post({
                container: '.content',
                url: 'akun/apps',
                data: {},
                useAlert: false,
                useBlockUI: false,
                success: function (data) {
                    $(".app-list").empty();
                    if (data.length > 0){
                        $.each(data, function(i, app){
                            $(".app-list").append(
                                '<div class="col-6 col-md-6">'+
									'<a href="'+app.url+'" target="_blank" class="d-block text-default text-center ripple-dark rounded p-3 legitRipple">'+
                                        //'<i class="icon-shutter icon-2x text-simtama"></i>'+
                                        '<img src="assets/images/logo_only2.png" height="50">'+
										'<div class="font-size-sm font-weight-semibold text-uppercase mt-2">'+app.name+'</div>'+
									'</a>'+
								'</div>'
                            );
                        })
                    }else{
                        $(".app-list").append(
                            '<div class="col-12"><div class="text-muted p-3">Tidak ada aplikasi</div></div>'
                        );
                    }
                },
                failed: function () {}
            });
        },

        checkInbox: function (){
            Application.post({
                container: '.content',
                url: 'inbox/check',
                data: {},
                useAlert: false,
                useBlockUI: false,
                success: function (data) {
                    $(".notifikasi-badge").text(data.notifikasi.unread > 0 ? data.notifikasi.unread : '');
                    //$(".notifikasi-badge").css('display', data.notifikasi.unread > 0 ? '' :'none');
                    $(".dropdown-notifikasi .badge-pill").text(data.notifikasi.unread > 0 ? data.notifikasi.unread : '');

                    $(".dropdown-notifikasi .media-list").empty();

                    $(".media-list.inbox li.media").off('click');
                    if (data.notifikasi.unread > 0){
                        $.each(data.notifikasi.list, function (i, notifikasi){
                            $(".dropdown-notifikasi .media-list").append(
                                '<li class="media" data-url="inbox?notifikasi='+notifikasi.uuid+'">'+
                                //'<a href="inbox?notifikasi='+notifikasi.uuid+'">'+
                                '<div class="mr-3 position-relative">'+
                                    '<img src="assets/images/notification.png" width="36" height="36" class="rounded-circle" alt="">'+
                                '</div>'+
                                '<div class="media-body">'+
                                    '<div class="media-title">'+
                                            '<span class="font-weight-semibold">'+notifikasi.title+'</span>'+
                                            '<span class="text-muted float-right font-size-sm">'+notifikasi.time+'</span>'+
                                    '</div>'+
                                    '<span class="text-muted">'+notifikasi.message+'</span>'+
                                '</div>'+
                                //'</a>'+
                                '</li>'
                            )
                        });

                        $(".media-list.inbox li.media").on('click', function(e){
                            var url = $(this).attr('data-url');
                            location.href=url;
                        })

                    }else{
                        $(".dropdown-notifikasi .media-list").append(
                            '<li class="media"><div class="media-body"><span class="text-muted m-top-20">Tidak ada notifikasi</span></div></li>'
                        );
                    }

                },
                failed: function () {}
            });
        },

        num2word: function (s){
            var th_val = ['', 'ribu', 'juta', 'miliar', 'triliun'];
            // System for uncomment this line for Number of English
            // var th_val = ['','thousand','million', 'milliard','billion'];

            var dg_val = ['kosong', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
            var tn_val = ['sepuluh', 'sebelas', 'duabelas', 'tigabelas', 'empatbelas', 'limabelas', 'enambelas', 'tujuhbelas', 'delapanbelas', 'sembilanbelas'];
            var tw_val = ['duapuluh', 'tigapuluh', 'empatuluh', 'limauluh', 'enamuluh', 'tujuhuluh', 'delapanuluh', 'sembilanuluh'];

            s = s.toString();
            s = s.replace(/[\, ]/g, '');
            if (s != parseFloat(s))
                return ' ';
            var x_val = s.indexOf('.');
            if (x_val == -1)
                x_val = s.length;
            if (x_val > 15)
                return 'too big';
            var n_val = s.split('');
            var str_val = '';
            var sk_val = 0;
            for (var i = 0; i < x_val; i++) {
                if ((x_val - i) % 3 == 2) {
                    if (n_val[i] == '1') {
                        str_val += tn_val[Number(n_val[i + 1])] + ' ';
                        i++;
                        sk_val = 1;
                    } else if (n_val[i] != 0) {
                        str_val += tw_val[n_val[i] - 2] + ' ';
                        sk_val = 1;
                    }
                } else if (n_val[i] != 0) {
                    str_val += dg_val[n_val[i]] + ' ';
                    if ((x_val - i) % 3 == 0)
                        str_val += 'ratus ';
                    sk_val = 1;
                }
                if ((x_val - i) % 3 == 1) {
                    if (sk_val)
                        str_val += th_val[(x_val - i - 1) / 3] + ' ';
                    sk_val = 0;
                }
            }
            if (x_val != s.length) {
                var y_val = s.length;
                str_val += 'point ';
                for (var i = x_val + 1; i < y_val; i++)
                    str_val += dg_val[n_val[i]] + ' ';
            }

            //str_val = str_val.replace(/^satu /,'se');

            var mapObj = {
                'satu puluh': 'sepuluh',
                'satu ratus': 'seratus',
             };
             str_val = str_val.replace(/satu puluh|satu ratus/gi, function(matched){
               return mapObj[matched];
             });

            return str_val.replace(/\s+/g, ' ');
        },

        switchView: function (view){
            $(".multi-view").hide();
            $("#"+view+"-view").show();
        },
        randomString: function (length) {
            if (length == undefined) length = 10;
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
               result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        },
        validateEmail: function(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        slugify: function(str, separator) {
            if (separator == undefined) separator = "-";
            str = str.trim();
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            const from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
            const to = "aaaaaaeeeeiiiioooouuuunc------";

            for (let i = 0, l = from.length; i < l; i++) {
                str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
            }

            return str
                .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
                .replace(/\s+/g, "-") // collapse whitespace and replace by -
                .replace(/-+/g, "-") // collapse dashes
                .replace(/^-+/, "") // trim - from start of text
                .replace(/-+$/, "") // trim - from end of text
                .replace(/-/g, separator);
        },
        setVisibility: function (element, visible){
            element.css('display', visible ? '' : 'none');
        },

        getBulan: function (month){
            var data = ["Januari" , "Februari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember"];;

            return data[month];
        },

        getPageContent: function (options){
            var setting = $.extend(true, {
                container: '.content',
                loaderIn: '.page-title',
                useAlert: false,
            },options);

            Application.post(setting);
        },

        readableBytes: function(bytes) {
            var i = Math.floor(Math.log(bytes) / Math.log(1024)),
            sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            return (bytes / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + sizes[i];
        },

        copyToClipboard: function (text) {
            target = document.getElementById("inputcopy");
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = "inputcopy";
                //target.style.display = 'none';
                //target.disabled = true;
                document.body.appendChild(target);
            }
            var currentFocus = document.activeElement;
            target.textContent = text;
            target.select();
            target.setSelectionRange(0, target.value.length);

            // copy the selection
            var succeed;
            try {
                succeed = document.execCommand("copy");
            } catch(e) {
                succeed = false;
            }
            // restore original focus
            if (currentFocus && typeof currentFocus.focus === "function") {
                currentFocus.focus();
            }
            target.textContent = "";
            target.remove();
            Application.successNotification("Copied to clipboard.")
        },

        nFormatter: function (num, digits) {
            var si = [
              { value: 1, symbol: "" },
              { value: 1E3, symbol: "Rb" },
              { value: 1E6, symbol: "Jt" },
              { value: 1E9, symbol: "M" },
              { value: 1E12, symbol: "T" },
              { value: 1E15, symbol: "P" },
              { value: 1E18, symbol: "E" }
            ];
            var rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
            var i;
            for (i = si.length - 1; i > 0; i--) {
              if (num >= si[i].value) {
                break;
              }
            }
            return (num / si[i].value).toFixed(digits).replace(rx, "$1") + si[i].symbol;
        },

        setCheckbox: function (switchElement, checkedBool) {
            if((checkedBool && !switchElement.isChecked()) || (!checkedBool && switchElement.isChecked())) {
                switchElement.setPosition(true);
                switchElement.handleOnchange(true);
            }
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    Application.init();
});
var Dashboard = function () {

	var handleEvents = function (){
	}

	return {
		init: function () {
			handleEvents();
		},
	}
}();


jQuery(document).ready(function() {
    Dashboard.init();
});
var AccountProfile = function () {
    var account;
    var tabProfile
    var elEmailModal = $("#modal-email");
    var elPhoneModal = $("#modal-phone");
    var elAccountModal = $("#modal-account");
    var elVerifikasiModal = $("#modal-Verifikasi");
    var elOAuthModal = $("#modal-oauth");
    var elOAuthInfoModal = $("#modal-oauth-code");

    var elUploadModal = $("#modal-upload");

    var elUploadBox = $('.upload-box');
    var elUploadForm = $("#form-upload");
    var elFileInput = $("input.upload-file", elUploadForm);
    var elUploadTitle = $(".modal-title > span", elUploadModal);

    var setUploadBox = function (){

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.upload-preview>img', elUploadBox).attr('src', e.target.result);
                    elUploadBox.addClass('has-preview');
                    $(".btn-reset-upload").prop('disabled', false);
                    $(".btn-upload").prop('disabled', false);
                }
                reader.readAsDataURL(input.files[0]);
            }else{
                $(".btn-reset-upload").prop('disabled', true);
                $(".btn-upload").prop('disabled', true);
                $('.upload-preview>img', elUploadBox).attr('src', "");
                elUploadBox.removeClass('has-preview');
            }
        }

        elFileInput.on('change', function(e){
            readURL(this);
        });

        elUploadBox.on('click', function(e){
            elFileInput.click();
        })

        $(".btn-reset-upload").on('click', function(e){
            resetUpload();
        })

        $(".btn-upload").on('click', function(e){
            var formUploadData = new FormData(elUploadForm[0]);
            var field = $("[name=field]",elUploadForm).val();

            Application.post({
                container: '#modal-upload',
                url: 'account/uploadimage',
                data: formUploadData,
                contentType: false,
                processData: false,
                useAlert: false,
                success: function (data) {
                    account[field] = data[field];
                    Application.successNotification(elUploadTitle.text() + " berhasil disimpan");
                    setImageAvatar(true);
                    elUploadModal.modal('hide');
                },
                failed: function (message) {
                    Application.errorNotification(message);
                }
            });

        })
    }

    var resetUpload = function (){
        elFileInput.val('').trigger('change');
    }

    var openUploadBox = function (field, label){
        resetUpload();
        $("[name=field]",elUploadForm).val(field)
        elUploadTitle.text(label);
        elUploadModal.modal();
    }


    var handleEvents = function (){
        tabProfile = $("#profile");

        /* $('.form-check-input-styled').uniform(); */

        setUploadBox();

        $(".btn-set-image").on('click', function(e){
            var field = $(this).attr('data-field');
            var label = 'Akun Avatar';
            openUploadBox(field, label);
        })

        $(".btn-remove-image").on('click', function(e){
            e.preventDefault();
            var field = $(this).attr('data-field');
            var label = 'Akun Avatar';

            Application.confirmDialog({
                title: "Hapus "+ label,
                class: "modal-sm dlg-remove-photo",
                message : "Hapus "+label+" ?",
                label:{
                    yes: 'Hapus',
                    no: 'Batal',
                },
                callback: function (){
                    Application.post({
                        container: '.dlg-remove-photo',
                        url: 'account/removeimage',
                        data:{ field: field },
                        useAlert: false,
                        success: function (data) {
                            Application.successNotification(label +' berhasil dihapus');
                            account[field] = '';
                            setImageAvatar(true);
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

        $(".btn-edit-nama", tabProfile).on('click', function(e){
            e.preventDefault();
            $(".contact-value", elAccountModal).val(account.name);
            elAccountModal.modal();
        });

        $(".btn-edit-email", tabProfile).on('click', function(e){
            e.preventDefault();
            $(".contact-value", elEmailModal).val(!Application.isEmpty(account.email_unverified) ? account.email_unverified : account.email);
            elEmailModal.modal();
        });

        $(".btn-edit-phone", tabProfile).on('click', function(e){
            e.preventDefault();
            $(".contact-value", elPhoneModal).val(!Application.isEmpty(account.phone_unverified) ? account.phone_unverified : account.phone);
            elPhoneModal.modal();
        });

        $(".btn-verifikasi", tabProfile).on('click', function(e){
            e.preventDefault();
            var type = $(this).attr('data-type');
            $("[name=type]", elVerifikasiModal).val(type);
            $(".code-value", elVerifikasiModal).val('');
            $(".verifikasi_media", elVerifikasiModal).html(type == 'email' ? "Alamat Email <b>"+account.email_unverified+"</b>" : "Telegram dengan nomor telpon <b>"+account.phone_unverified+"</b>");
            elVerifikasiModal.modal();
        });

        $(".btn-resend-verifikasi", elVerifikasiModal).on('click', function(e){
            e.preventDefault();
            var type = $("[name=type]", elVerifikasiModal).val();
            var label = type == 'email' ? "Alamat Email" : "Nomor Telpon";

            Application.post({
                container: '#modal-verifikasi',
                url: 'account/resendverifikasi',
                data: {id : account.id, type: type},
                useAlert: false,
                success: function (data) {
                    Application.successNotification('Kode verifikasi '+label+' dalam proses pengiriman.');
                },
                failed: function (message) {
                    Application.errorNotification(message);
                }
            });
});

        $(".btn-remove", tabProfile).on('click', function(e){
            e.preventDefault();

            var type = $(this).attr('data-type');
            var label = type == 'email' ? "Alamat Email" : "Nomor Telpon";
            Application.confirmDialog({
                title: "Hapus perubahan",
                message : "Hapus perubahan "+label+" yang belum diverifikasi",
                label:{
                    yes: 'Hapus',
                    no: 'Batal',
                },
                callback: function (){
                    Application.post({
                        container: '.content',
                        url: 'account/removecontact',
                        data: {id : account.id, type: type},
                        useAlert: false,
                        success: function (data) {

                            if (type == 'email') account.email_unverified = '';
                            else account.phone_unverified = '';

                            showAccountInfo();
                            Application.successNotification('Perubahan '+label+' berhasil dihapus');
                        },
                        failed: function (message) {
                            Application.errorNotification(message);
                        }
                    });
                }
            })

        });

        $("#view-oauth-code", tabProfile).on('click', function(e){
            e.preventDefault();
            Application.post({
                container: '.content',
                url: 'account/oauth',
                data: {},
                useAlert: false,
                success: function (data) {
                    $("#img-oauth-code",elOAuthInfoModal).attr("src", data.uri);
                    elOAuthInfoModal.modal();
                },
                failed: function (message) {
                    Application.errorNotification(message);
                }
            });
        })

        $("#select-otp-media", tabProfile).on('change', function(e){
            e.preventDefault();
            var value = $(this).val();
            var field = $(this).attr('name');

            if (value == 'oauth')
            {
                Application.post({
                    container: '.content',
                    url: 'account/oauth',
                    data: {},
                    useAlert: false,
                    success: function (data) {
                        $("#img-oauth",elOAuthModal).attr("src", data.uri);
                        elOAuthModal.modal();
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
                $(this).val(account.otp_media);
                return false;
            }
            else if (value != 'none')
            {
                if (value == 'email' && Application.isEmpty(account.email)){
                    Application.alertDialog({
                        title: 'Perhatian',
                        message: "Alamat email belum diverifikasi<br>Verifikasi OTP tidak bisa dilakukan. "
                    })
                    $(this).val(account.otp_media);
                    return false;
                }
                else if (value == 'telegram' && Application.isEmpty(account.phone)){
                    Application.alertDialog({
                        title: 'Perhatian',
                        message: "Nomor Telpon belum diverifikasi<br>Verifikasi OTP tidak bisa dilakukan."
                    })
                    $(this).val(account.otp_media);
                    return false;
                }
            }

            Application.post({
                container: '.content',
                url: 'account/update',
                data: {id: account.id, value: value, field : field},
                useAlert: false,
                success: function (data) {
                    account['otp_media'] = data.otp_media;
                    account['use_otp'] = data.use_otp;
                    showAccountInfo();
                    Application.successNotification("Setting Verifikasi OTP berhasil disimpan.");
                },
                failed: function (message) {
                    showAccountInfo();
                    Application.errorNotification(message);
                }
            });
        })

        Application.validateForm({
            container: '#form_password',
            rules: {
                oldpassword: { required: true},
                password: { required: true},
                confirmpassword: { required: true, equalTo: "#input_password"},
            },
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'account/password',
                    data: $('form#form_password').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        $("#form_password")[0].reset();
                        Application.successNotification("Password akun berhasil diubah.");
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

                var input_email = $(".contact-value", elEmailModal).val();
                if ( input_email == account.email || input_email == account.email_unverified)
                {
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
                        account.email_unverified = data.value;
                        showAccountInfo();
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

                var input_phone = $(".contact-value", elPhoneModal).val();
                if ( input_phone == account.phone || input_phone == account.phone_unverified)
                {
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
                        account.phone_unverified = data.value;
                        showAccountInfo();
                        Application.successNotification("Nomor Telpon berhasil di simpan. Kode Verifikasi akan dikirimkan melalui Telegram");
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        Application.validateForm({
            container: '#form_name',
            rules: {
                value: { required: true},
            },
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'account/update',
                    data: $('form#form_name').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        elAccountModal.modal('hide');
                        account.name = data.name;
                        showAccountInfo();
                        Application.successNotification("Nama Akun berhasil di simpan.");
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        Application.validateForm({
            container: '#form_verify',
            rules: {
                token: { required: true},
            },
            onSubmit: function (form){
                var type = $("[name=type]", elVerifikasiModal).val();
                var label = type == 'email' ? "Alamat Email" : "Nomor Telpon";

                Application.post({
                    container: '.content',
                    url: 'account/verifikasi',
                    data: $('form#form_verify').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        elVerifikasiModal.modal('hide');

                        if (type == "email") {
                            account.email = data.email;
                            account.email_unverified = '';
                        }
                        else {
                            account.phone = data.phone;
                            account.phone_unverified = '';
                        }

                        showAccountInfo();
                        Application.successNotification(label+" berhasil di verifikasi.");
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })

        Application.validateForm({
            container: '#form_oauth',
            rules: {
                token: { required: true},
            },
            onSubmit: function (form){
                Application.post({
                    container: '.content',
                    url: 'account/oauthVerify',
                    data: $('form#form_oauth').serializeArray(),
                    useAlert: false,
                    success: function (data) {
                        elOAuthModal.modal('hide');
                        account.otp_media = 'oauth';
                        $("#select-otp-media", tabProfile).val(account.otp_media);
                        showAccountInfo();
                        Application.successNotification("OTP Authenticator berhasil di verifikasi.");
                    },
                    failed: function (message) {
                        Application.errorNotification(message);
                    }
                });
            }
        })
        Application.post({
            container: '.content',
            url: 'account/getone',
            useAlert: false,
            success: function (data) {
                account = data;
                showAccountInfo();
            },
            failed: function () {

            }
        });

    }

    var showAccountInfo = function (){
        Application.fillElementData('.content', account);

        $.each (account, function (field, value){
            $("[name="+field+"]", tabProfile).val(value);
        });

        $(".form-group .use-otp", tabProfile).css('display', account.use_otp == 1 ? '' : 'none')
        $(".form-group .otp-media.oauth", tabProfile).css('display', account.use_otp == 1 && account.otp_media == 'oauth' ? '' : 'none')
        $(".form-group .otp-media.non-oauth", tabProfile).css('display', account.use_otp == 1 && account.otp_media != 'oauth' && account.otp_media != 'none' ? '' : 'none')

        var email_required = Application.isEmpty(account.email) && !Application.isEmpty(account.email_unverified);
        var phone_required = Application.isEmpty(account.phone) && !Application.isEmpty(account.phone_unverified);

        $(".email-verified", tabProfile).css('display', email_required ? 'none' : '');
        $(".phone-verified", tabProfile).css('display', phone_required ? 'none' : '');

        $(".email-verified .btn-edit-email", tabProfile).css('display', email_required || !Application.isEmpty(account.email_unverified) ? 'none' : '');
        $(".phone-verified .btn-edit-phone", tabProfile).css('display', phone_required || !Application.isEmpty(account.phone_unverified)? 'none' : '');

        $(".email-unverified", tabProfile).css('display', Application.isEmpty(account.email_unverified) ? 'none' : '');
        $(".phone-unverified", tabProfile).css('display', Application.isEmpty(account.phone_unverified)  ? 'none' : '');

        $(".email-unverified .btn-remove", tabProfile).css('display', email_required ? 'none' : '');
        $(".phone-unverified .btn-remove", tabProfile).css('display', phone_required  ? 'none' : '');

        setImageAvatar();
    }

    var setImageAvatar = function (update){
        var hasImage = !Application.isEmpty(account.avatar);

        if (update){
            var imgsrc = Application.baseUrl() + 'pic/acc/'+account.uid+'?'+Math.random();
            $(".account-avatar").attr("src", imgsrc);
        }
        $(".btn-remove-image.acc-avatar").css('display', hasImage ? '' : 'none');
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();