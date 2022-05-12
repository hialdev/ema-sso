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
	var handleStat = function (){
		Application.post({
			container: '.statistic',
			url: 'dashboard/stat',
			data: {},
			useAlert: false,
			success: function (data) {
				Application.fillDataValue($(".statistic"), data);
			},
			failed: function () {
			}
		});
	}

	var handleEvents = function (){
		handleStat();
	}

	return {
		init: function () {
			handleEvents();
		},
	}
}();
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
var Setting = function () {
    var socket, containerQR, containerWA, containerLog, imgQR;
	var handleEvents = function (){
        containerQR = $(".wa-qrcode");
        containerWA = $(".wa-info");
        containerLog = $(".wa-logs");

        imgQR = containerQR.find('img');

        socket = io.connect(HOST);
        socket.on('message', function(msg) {
            containerLog.text(msg);
        });

        socket.on('qr', function(src) {
            imgQR.attr('src', src);
            containerQR.show();
            containerLog.hide();
            containerWA.hide();
        });

        socket.on('ready', function(data) {
            containerQR.hide();
            containerWA.show();
            containerLog.hide();
        });

        socket.on('authenticated', function(data) {
            containerQR.hide();
            containerWA.show();
            containerLog.hide();
        });

        socket.on('disconnected', function(data) {
            containerQR.hide();
            containerWA.hide();
            containerLog.show();
        });
	}

	return {
		init: function () {
			handleEvents();
		},
	}
}();