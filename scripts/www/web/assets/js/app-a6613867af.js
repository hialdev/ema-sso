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

        modalDialog: function(dOptions) {
            var options = $.extend(true, {
                    class: "",
                    iconTitle: "icon-question3",
                    backdropStatic: false,
                    title: "Dialog Title",
                    closeOnEscape: true,
                    body: {
                        class: '',
                        style: ''
                    },
                    content: "Dialog Content",
                    cancelButton: {
                        class: "btn-light",
                        label: 'Cancel',
                        callback: function (){}
                    },
                    okButton: {
                        class: "btn-success",
                        label: '<i class="icon-checkmark2"></i> OK',
                        callback: function (){}
                    },
                    onShow:function(modal){}
                },dOptions);

            var backdropStatic = options.backdropStatic ? 'data-backdrop="static"' : '';
            var closeOnEscape = options.closeOnEscape ? 'tabindex="-1"' : '';
            var tmpl = [
                '<div class="z-dialog modal" ',backdropStatic,' ',closeOnEscape,'>',
                    '<div class="modal-dialog modal-dialog-centered ',options.class,'">',
                        '<div class="modal-content">',
                            '<div class="modal-header ">',
                                '<h5 class="modal-title font-weight-bold"><i class="',options.iconTitle,' mr-1"></i> ',options.title,'</h5>',
                                '<button type="button" class="close" data-dismiss="modal">&times;</button>',
                            '</div>',

                            '<div class="modal-body ',options.body.class,'" style="',options.body.style,'">',
                                options.content,
                            '</div>',

                            '<div class="modal-footer">',
                                '<button type="button" data-dismiss="modal" class="btn canccel btn-xs ',options.cancelButton.class,'">',options.cancelButton.label,'</button>',
                                '<button type="button" class="btn ok btn-xs ',options.okButton.class,'">',options.okButton.label,'</button>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>'

              ].join('');

            var modalDialog = $(tmpl);

            modalDialog.find("button.ok").on('click', function (){
                options.okButton.callback(modalDialog);
            });

            modalDialog.one('shown.bs.modal', function (event) {
                options.onShow(modalDialog);
            })

            modalDialog.one('hide.bs.modal', function (){
                options.cancelButton.callback();
                modalDialog.remove();
            });

            var instModal = modalDialog.modal();

            return instModal;
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
					'<div class="modal-dialog modal-dialog-centered ',options.class,'">',
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
                xhr: undefined,
            }, options);

            return $.ajax({
                type: setting.type,
                data : setting.data,
                cache: false,
                async: setting.async,
                processData: setting.processData,
                contentType: setting.contentType,
                dataType: 'json',
                url: baseUrl+setting.url,
                xhr: setting.xhr,
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
            return Application.ajaxCall(setting);
        },

        post: function (options){
            var setting = $.extend(true, {
                type: "POST",
            }, options);
            return Application.ajaxCall(setting);
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

            return Application.post(setting);
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

            return Application.get(setting);
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

        errorNotification: function (message, donotclear, layout){
            if (donotclear != true) Noty.closeAll();
            if (layout == undefined) layout = 'topCenter';
            new Noty({
                ////theme: ' alert alert-danger alert-styled-left p-0',
                theme: 'limitless',
                text: message,
                timeout:3000,
                type: 'error',
                layout: layout
                //progressBar: false,
            }).show();
        },

        warningNotification: function (message, donotclear, layout){
            if (donotclear != true) Noty.closeAll();
            if (layout == undefined) layout = 'topCenter';
            new Noty({
                ////theme: ' alert alert-danger alert-styled-left p-0',
                theme: 'limitless',
                text: message,
                timeout:3000,
                type: 'warning',
                layout: layout
                //progressBar: false,
            }).show();
        },

        successNotification: function (message, donotclear, layout){
            if (donotclear != true) Noty.closeAll();
            if (layout == undefined) layout = 'topCenter';
            new Noty({
                //theme: ' alert alert-success alert-styled-left p-0',
                theme: 'limitless',
                text: message,
                timeout:3000,
                type: 'success',
                progressBar: false,
                layout: layout
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


        select2Lookup: function (options){
            var setting = $.extend(true,{
                element: undefined,
                data: [],
                select: {
                    language: "id",
                    placeholder: "Pilih ...",
                    allowClear: true,
                    dropdownAutoWidth : false,
                    width: '100%'
                },
                defaultValue: undefined
            },options);

            if (setting.element === undefined)
                return;

            var optselect = $.extend(true,{
                data: setting.data
            },setting.select);

            $(setting.element).empty();
            if (setting.select.allowClear) $(setting.element).append("<option/>");
            $(setting.element).select2(optselect);

            if (setting.defaultValue) {
                $(setting.element).val(setting.defaultValue).trigger('change.select2');
            }
        },

        select2Remote: function (options){
            var options = $.extend(true,{
                        url: "",
                        type: "POST",
                        cache: true,
                        dataType: 'json',
                        element: undefined,
                        readonly: false,
                        extdata: [],
                        query: {},
                        callback: function(){},
                        select: {
                            language: "id",
                            placeholder: "Pilih ...",
                            allowClear: true,
                            dropdownAutoWidth : false,
                            multiple: false,
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
                    if (options.select.allowClear && !options.select.multiple) $(options.element).append("<option/>");
                    $(options.element).select2(optselect);

                    if (options.defaultValue) {
                        $(options.element).val(options.defaultValue).trigger('change.select2');
                    }

                    options.callback.call(undefined);
                },
                error: function(o,t,e){
                }
            });
        },

        select2RemoteSearch: function (option){

            var setting = $.extend(true,{
                element: '',
                url: '',
                placeholder: 'Pilih ...',
                minimumInputLength: 3,
                multiple: false,
                query:{},
                /* language:{
                    inputTooShort: function(args) {
                        // args.minimum is the minimum required length
                        // args.input is the user-typed text
                        return "Masukkan minimal "+args.minimum+' karakter';
                    },
                    inputTooLong: function(args) {
                        // args.maximum is the maximum allowed length
                        // args.input is the user-typed text
                        return "You typed too much";
                    },
                    errorLoading: function() {
                        return "Error loading results";
                    },
                    loadingMore: function() {
                        return "Tampilkan lebih banyak";
                    },
                    noResults: function() {
                        return "Data tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    maximumSelected: function(args) {
                        // args.maximum is the maximum number of items the user may select
                        return "Error loading results";
                    }
                }, */
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

            if (Application.isEmpty(option.element)) return;

            var url = setting.url.indexOf('http') == -1 ? baseUrl+setting.url : setting.url;
            $(option.element).select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query = {};
                        $.each(option.query, function(key, value) {
                            query[key] = value;
                        });
                        query['q'] = params.term;
                        query['page'] = params.page;
                        return query;/* {
                            q: params.term, // search term
                            page: params.page
                        }; */
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
                templateSelection: setting.templateSelection,
                language: setting.language,
                multiple: setting.multiple
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
        randomNumber: function(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
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
        },

        generateId: function(){
            return String(Date.now())+String(Application.randomNumber(10000, 99999));
        }

    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    Application.init();
});
var UploadManagement = function (){
    const clsUpload = 'input.fileupload';

    var queues = [];
    var uploadContainer;
    var inputFile;
    var uploadParams = {
        data: {},
        onSuccess: function (data){}
    };

    var itemUpload = function (id){
        var _me = this;
        _me.itemId = id;

        var uploadInfo = $("#upl-"+id);
        var progressBar, progressIndikator, uploadStatus;

        var validItem = uploadInfo.length > 0;

        if (validItem){
            progressBar = uploadInfo.find(".progressBar");
            progressIndikator = progressBar.find(".progress");
            uploadStatus = uploadInfo.find(".uploadStatus");
        }

        _me.progress = function (value){
            if (validItem){
                progressIndikator.css('width', value+"%");
            }
        }
        _me.success = function (data){
            _updateQueue(_me.itemId, 1);

            if (validItem){
                progressBar.remove();
                uploadStatus.text('Success');
                uploadStatus.removeClass('d-none').addClass('text-success');

                var _queue = _findQueue(_me.itemId);
                if (_queue) _queue.onSuccess(data);

            }
        }
        _me.failed = function (){
            _updateQueue(_me.itemId, -1);

            if (validItem){
                progressBar.remove();
                uploadStatus.text('Failed');
                uploadStatus.removeClass('d-none').addClass('text-danger');
            }
        }
        _me.remove = function (){
            _deleteQueue(_me.itemId);

            if (validItem){
                uploadInfo.remove();

                if (uploadContainer.find('.card-upload-info').length == 0){
                    _hideUploader();
                }
            }
        }

        return _me;
    };

    var _updateQueue = function (id, status){
        return queues.find((o, i) => {
            if (o.id === id) {
                queues[i].status = status;
                return true;
            }
        });
    }

    var _deleteQueue = function (id){
        queues = queues.filter(function( obj ) {
            return obj.id !== id;
        });

        return queues;
    }

    var _findQueue = function (id){
        return queues.find(o => o.id === id);
    }

    var _addToQueue = function (data){

        if (uploadContainer.find('.upl-header').length == 0){
            uploadContainer.append(
                '<div class="upl-header">'+
                    '<div class="flex-grow-1 upl-caption"></div>'+
                    '<div>'+
                        '<a class="upl-btn mr-1 upl-btn-toggle"><i class="icon-arrow-down12"></i></a>'+
                        '<a class="upl-btn upl-btn-close"><i class="icon-cross2"></i></a>'+
                    '</div>'+
                '</div>'
            );
        }
        if (uploadContainer.find('.upl-content').length == 0){
            uploadContainer.append(
                '<div class="upl-content"></div>'
            );
        }

        uploadContainer.find('.upl-content').append(
            '<div data-id="'+data.id+'" id="upl-'+data.id+'" class="card-upload-info justify-content-between">'+
                '<div class="upl-info">'+
                    '<div class="small text-truncate mb-1">'+data.file.name+'</div>'+
                    '<div class="progressBar"><div class="progress"></div></div>'+
                    '<div class="small uploadStatus d-none"></div>'+
                '</div>'+
                '<div class="upl-btn"><span class="b-close-upl-info icon-cross2 cursor-p"></span></div>'+
            '</div>'
        );

        data['status'] = 0;
        queues.push(data);

        /* queues.push({
            id: data.id,
            status: 0,
            file: data.file,
            data: data.data,
        }); */

        _uploadStatusUpdate();
    }

    var _uploadStatusUpdate = function (){
        var _qitems = queues.filter(function( obj ) {
            return obj.status === 0;
        });

        var inProcess = _qitems ? _qitems.length : 0;
        var message = "Upload Finished";

        if (inProcess > 0) message = inProcess+' uploading ...';
        else {
            if (uploadContainer.find('.upl-info .uploadStatus.text-danger').length == 0)
                _uploaderToggle(true);
        }
        uploadContainer.find('.upl-caption').text(message);
    }

    var _uploaderToggle = function (minimize){
        if (minimize == undefined) minimize = !uploadContainer.hasClass('minimize');
        var btn = uploadContainer.find('.upl-btn-toggle');
        if (minimize) {
            btn.html('<i class="icon-arrow-up12"></i>');
            uploadContainer.addClass('minimize');
        } else {
            btn.html('<i class="icon-arrow-down12"></i>');
            uploadContainer.removeClass('minimize');
        }
    }

    var _hideUploader = function (){
        uploadContainer.empty();
        queues = [];
    }

    var _handleEvents = function (){
        uploadContainer.on('click', '.upl-btn-toggle, .upl-caption', function (){
            _uploaderToggle();
        })

        uploadContainer.on('click', '.b-close-upl-info', function (){
            let item = $(this).closest('.card-upload-info');
            let itemId = item.data('id');

            var uploadItem = new itemUpload(itemId);
            uploadItem.remove();
        })

        uploadContainer.on('click', '.upl-btn-close', function (){
            if (uploadContainer.find('.progressBar').length > 0){
                _uploaderToggle(true);
            }else{
                _hideUploader();
            }
        })

        uploadContainer.on('change', clsUpload, function (e){
            var input = this;
            if (input.files && input.files.length > 0) {
                $.each(input.files, function (i, file){
                    let fileId = 'id-'+Application.generateId();

                    _addToQueue({
                        id: fileId,
                        file: file,
                        data: uploadParams.data,
                        onSuccess: uploadParams.onSuccess
                    });
                })
            }
            _uploaderToggle(false);
            _startUpload();
        })
    }

    var _startUpload = function (){
        if (queues.length > 0){
            let item  = queues.find(o => o.status === 0);

            if (item){

                var uploadItem = new itemUpload(item.id);
                var formUploadData = new FormData();
                formUploadData.append('file', item.file);

                $.each (item.data, function (key, value){
                    formUploadData.append(key, value);
                })

                var axUpload = Application.post({
                    container: '.upl-container',
                    url: 'file/upload',
                    data: formUploadData,
                    contentType: false,
                    processData: false,
                    useBlockUI: false,
                    useAlert: false,
                    success: function (result) {
                        console.log("success");
                        uploadItem.success(result);
                    },
                    failed: function (message) {
                        console.log("failed");
                        uploadItem.failed();
                    },
                    xhr: function(){
                        var xhr = $.ajaxSettings.xhr();
                        xhr.upload.onprogress = function (e) {
                            if (e.lengthComputable) {
                                let curItem =  _findQueue(item.id);

                                if (curItem == undefined || (curItem && curItem.status != 0)){
                                    if (axUpload){
                                        console.log("aborting ....");
                                        axUpload.abort();
                                    }
                                }
                                var p = e.loaded / e.total * 100;
                                uploadItem.progress(p);
                            }
                        };

                        return xhr;
                    },
                    done: function(){
                        console.log("done");
                        _uploadStatusUpdate();

                        setTimeout(function(){
                            _startUpload();
                        }, 500);
                    }
                });
            }
        }
    }

    return {
        init: function (){
            uploadContainer = $(".upl-container");
            if (uploadContainer.length == 0){
                $('body').append('<div class="upl-container"/>');
                uploadContainer = $(".upl-container");
            }
            _handleEvents();
        },
        addToQueue: function (data){
            _addToQueue(data);
        },
        start: function(){
            _startUpload();
        },
        selectFile: function(params, onSuccess){
            inputFile = uploadContainer.find(clsUpload);
            if (inputFile.length == 0){
                uploadContainer.prepend('<input type="file" name="file" multiple class="fileupload" style="display:none;">');
                inputFile = uploadContainer.find(clsUpload);
            }
            uploadParams.data = params;
            uploadParams.onSuccess = onSuccess?onSuccess : function(data){};
            inputFile.click ();
        }
    }
}();

document.addEventListener('DOMContentLoaded', function() {
    UploadManagement.init();
});
// Initialize module
// ------------------------------
var AppStart = function() {
    var handleExit = function () {
        var uploadContainer = $(".upl-container");

        window.onbeforeunload = function() {
            if (uploadContainer.find('.progressBar').length > 0){
                return "If you leave this page, uploading files will be aborted";
            }else return;
        }

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

    var handleNavbarAction = function (){
        $(".btn-new-workspace").click(function(){
            Workspace.add({
                onSuccess: function(data){
                    location.href = 'w/'+data.slug;
                }
            });
        })

        $(".btn-new-project").click(function(){
            Project.add({
                onSuccess: function(data){
                    location.href = 'task/'+data.slug;
                }
            });
        })

        $(".btn-workspace-menu").click(function(e){
            e.preventDefault();
            e.stopPropagation();

            let data = $(this).data();
            location.href = 'w/'+data.slug;
        })

        $(".btn-project-menu").click(function(e){
            e.preventDefault();
            e.stopPropagation();

            let data = $(this).data();
            location.href = 'p/'+data.slug;
        })
    }

    return {
        initialize: function (){
            handleExit();

            handleNavbarAction();
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


    }

}();

document.addEventListener('DOMContentLoaded', function() {
    AppStart.initialize();
});
(function( $) {
    "use strict";

    let dataDefaults = {
        project: {
            id: 0,
            slug: '',
            workspaceId: 0,
            name: ''
        },
        section: {
            id: 0,
            name: 'New Section',
            order_no: 1,
        },
        task: {
            id: 0,
            status: 0,
            priority: 1,
            dueDate: null,
            name: '',
            order_no: 1,
            assigneeId:0,
            assigneeName:'',
            assigneeAvatar:''
        }
    }

    let taskFilters = {
        status: ['all','todo','inprogress', 'completed'],
        priority: ['low','normal','high', 'urgent'],
    }

    let taskStatusIcon = [
        '<i class="far fa-check-circle text-muted"></i>',
        '<i class="icon-checkmark-circle text-success"></i>',
        '<i class="icon-circle2 text-info"></i>'];
    let taskStatusText = [
        'Todo', 'In Progress', 'Completed'];

    let taskPriorityIcon = [
        '<i class="icon-arrow-down7 text-success"></i>',
        '<i class="icon-arrow-up7 text-muted"></i>',
        '<i class="icon-arrow-up7 text-warning"></i>',
        '<i class="icon-arrow-up7 text-danger"></i>'
    ];
    let taskPriorityText = [
        'Low', 'Normal', 'High', 'Urgent'];


    function getTextWidth(element) {
        var canvas = document.createElement("canvas");
        var context = canvas.getContext("2d");
        context.font = getFont(element);
        let width = context.measureText(element.value+"------").width;
        let formattedWidth = Math.round(width) + "px";

        //console.log (element.value, width, formattedWidth);
        return formattedWidth;
    }

    function getFont(element) {
        var prop = ["font-style", "font-variant", "font-weight", "font-size", "font-family"];
        var font = "";
        for (var x in prop)
            font += window.getComputedStyle(element, null).getPropertyValue(prop[x]) + " ";

        return font;
    }

    function getPosition(e, $menu) {
        var mouseX = e.clientX
            , mouseY = e.clientY
            , boundsX = $(window).width()
            , boundsY = $(window).height()
            , menuWidth = $menu.outerWidth()
            , menuHeight = $menu.outerHeight()
            , tp = {"position":"absolute","z-index":9999}
            , Y, X, parentOffset;

        if (mouseY + menuHeight > boundsY) {
            Y = {"top": mouseY - menuHeight + $(window).scrollTop()};
        } else {
            Y = {"top": mouseY + $(window).scrollTop()};
        }

        if ((mouseX + menuWidth > boundsX) && ((mouseX - menuWidth) > 0)) {
            X = {"left": mouseX - menuWidth + $(window).scrollLeft()};
        } else {
            X = {"left": mouseX + $(window).scrollLeft()};
        }

        parentOffset = $menu.offsetParent().offset();

        return $.extend(tp, Y, X);
    }

    function stringToDate (strDate){
        let m = moment(strDate, 'YYYY-MM-DD');
        let date = m.toDate();

         return date == 'Invalid Date' ? null : date;
    }

    $.fn.TaskManager = function( options ) {
        var me = this;

        var defaults = {
            prefix: 'tm',
            taskDetailElement: '.task-detail-wrapper',

            onAddTask: function(e, taskSection, taskItem){},
            onTaskOpened: function (e, taskItem){},
            onTaskClosed: function (e, taskItem){},
            onTaskSelected: function (e, taskItem){},

            data: dataDefaults.project
        }

        me.settings = $.extend({}, defaults, options);
        me.sectionIds = [];
        me.sections = {};
        me._data = {};

        me.selectedTask = undefined;
        me.lastTask = undefined;

        me.sectionList = undefined;
        me.sectionAddButton = undefined;

        me.header = undefined;
        me.taskDetail = undefined;
        me.body = undefined;

        me.filterStatus = undefined;
        me.filterPriority = undefined;
        me.taskdatePicker = undefined;

        me.initialize = function (){

            if (!me.hasClass('taskman')) me.addClass('taskman taskman-wrapper');

            me._data = $.extend({}, me.settings.data, me.data());
            me.find('.taskman-header').remove();
            me.prepend(TaskManager_Templates.taskmanHeader);
            me.header = me.find('.taskman-header');

            me.removeAttr("data-id data-name data-workspace-id data-slug");

            const urlParams = new URLSearchParams(window.location.search);
            var _status = urlParams.get('status');
            var _priority = urlParams.get('priority');

            if (!taskFilters.status.includes(_status)) _status = '';
            if (!taskFilters.priority.includes(_priority)) _priority = '';

            me.filterStatus = _status;
            me.filterPriority = _priority;

            me.header.find('.filter-task-status').val(_status);
            me.header.find('.filter-task-priority').val(_priority);

            if (me._data.id != 0) me.header.find('.task-header-project').removeClass('d-md-block');

            me.body = me.find('.taskman-content');
            if (me.body.length == 0){
                $(TaskManager_Templates.taskmanContent).after(me.header);
                me.body = me.find('.taskman-content');
            }

            me.sectionList = me.find('.task-groups');
            if (me.sectionList.length == 0)
            {
                me.body.prepend($('<div>').addClass('task-groups'));
                me.sectionList = me.find('.task-groups');
            }

            me.find('.'+me.settings.prefix+'-sec.task-group').each(function(i, element){
                let _selectionElement = $(element);

                let _sectionId = _selectionElement.data('id');
                me.sectionIds.push(_sectionId);

                let section = _selectionElement.TaskSection(me);
                me.sections[_sectionId] = section;
            })

            let _taskDetail = me.find('.task-detail-wrapper');
            if (_taskDetail.length == 0){
                me.append(TaskManager_Templates.taskDetail);
                _taskDetail = me.find('.task-detail-wrapper');
            }

            me.taskDetail = _taskDetail.TaskDetail(me);

            me.taskContextMenu = $('#TaskContextMenu');

            if (me.taskContextMenu.length == 0){
                $('body').append(TaskManager_Templates.taskContextmenu);
                me.taskContextMenu = $('#TaskContextMenu');
            }

            me.taskdatePicker = me.taskContextMenu.find('.Task_datePickerMenu')

            me.sectionAddButton = me.find('.tm-sec-action .btn-new-section');
            if (me.sectionAddButton.length == 0){
                me.body.append(TaskManager_Templates.sectionAddButton);
                me.sectionAddButton = me.find('.tm-sec-action .btn-new-section');
            }

            _loadProjectMembers();
            _loadProjectTags();

            _bindEvents();
            return this;
        }

        me.getFirstSection = function (excludeAssigned){
            let sectionId = me.sectionIds[0];
            if (excludeAssigned && sectionId == 'assigned') sectionId = me.sectionIds[1];
            return me.sections[sectionId];
        }

        me.getFirstTask = function (){
            let _task = undefined;

            let _section = me.getFirstSection();
            if (_section) _item = _section.tasks[_section.taskIds[0]];

            return _task;
        }

        me.taskById = function (taskId){
            var task = undefined;
            $.each(me.sectionIds, function (i, sid){
                let _section = me.sections[sid];
                if (_section){
                    let _task = _section.tasks[taskId];
                    if (_task){
                        task = _task;
                        return false;
                    }
                }
            })

            return task;
        }

        me.selectTask = function (taskId){
            let _task = me.taskById(taskId);

            /* for (var _sid in me.sections){
                let _tid = (me.sections[_sid].taskIds.find((task)=> {
                    return taskId == task;
                }));
                if (_tid){
                    _task = me.sections[_sid].tasks[_tid];
                    break;
                }
            } */
            if (_task) _task.selectItem();

            return _task;
        }

        me.addTask = function (){
            let availableTask = me.selectedTask && !me.selectedTask.section.isAssignedSection();
            let section = availableTask ? me.selectedTask.section : me.getFirstSection(true);

            if (section){
                section.addTaskItem({}, availableTask?me.selectedTask:0);
            }
            else
            {
                Section.add({
                    id_project: me._data.id
                },function (_section){
                    section = me.addSection(_section, true);
                    section.addTaskItem({}, 0);
                },function(error){
                })
            }
        }

        me.addSection = function (params, openAdded){
            let data = $.extend({}, dataDefaults.section, params);

            let _sectionTemplate = TaskManager_Templates.sectionItem
                .replaceAll('@SECTION_ID@', data.id)
                .replaceAll('@SECTION_ACTION@', TaskManager_Templates.sectionAction);

            me.sectionList.append(_sectionTemplate);

            this.sectionIds.push(data.id);
            var _section = $('#g-'+data.id).TaskSection(me, {data:data});
            this.sections[data.id] = _section;

            if (openAdded){
                _section.collapse(false);
            }else{
                _section.renameTitle();
                //_section.find('.group-title-frame').click();
            }

            return this.sections[data.id];
        }

        me.removeSection = function (section){
            me.sectionIds = me.sectionIds.filter(function(value, index, arr){
                return value != section._data.id;
            });

            delete me.sections[section._data.id];
            section.remove();
        }

         me.scrollTask = function (element){
            let _top = element.offset().top;
            let _height = me.body.height();
            let _offset = _top - me.body.offset().top;
            let _scrollTop = me.body.scrollTop();
            let _position = _scrollTop + _offset + element[0].scrollHeight;

            let _scrollPos = _scrollTop;
            if (_offset > _height){
                _scrollPos   = _position-_height;
            }else if (_offset < 0){
                _scrollPos   = _scrollTop-element[0].scrollHeight;
            }

            /* console.log(
                "el height:"+element[0].scrollHeight,
                "offset:"+_offset,
                'CUR scrollTop:'+me.body.scrollTop(),
                'scroll Height:'+scroll_height,
                "height:"+_height,
                "position:"+_position,
                "NEXT scrollTop:"+_scrollPos
            ); */
            me.body.scrollTop(_scrollPos);
            /* me.body.animate({
                scrollTop: _scrollPos
            }, 'fast'); */
        }

        var _loadProjectMembers = function (){
            let menuAssignee = me.find('#assigneeMenu');
            let contextMenuAssignee = me.taskContextMenu.find('.Task_assigneeMenu');
            let menuItem;
            contextMenuAssignee.empty();
            menuAssignee.empty();

            Project.memberList(me._data.id, function (members){
                $.each(members, function (i, member){

                    menuItem = TaskManager_Templates.taskAssigneeMenuItem
                        .replace('@ID@', member.id_account)
                        .replace('@NAME@', member.name)
                        .replace('@AVATAR@', '<img src="'+member.accountUrl+'/30/30" class="rounded-round mr-1">');

                    menuAssignee.append(menuItem);
                    contextMenuAssignee.append(menuItem)
                })

                if (members.length > 0){
                    menuAssignee.append(TaskManager_Templates.menuItemDivider);
                    contextMenuAssignee.append(TaskManager_Templates.menuItemDivider)
                }

                menuItem = TaskManager_Templates.taskAssigneeMenuItem
                    .replace('@ID@', 0)
                    .replace('@NAME@', 'No Assignee')
                    .replace('@AVATAR@', '<i class="fas fa-user-slash text-muted mr-1"></i>');

                menuAssignee.append(menuItem);
                contextMenuAssignee.append(menuItem)
            })
        }

        var _loadProjectTags = function (){
            let menuTag = me.find('#tagMenu');
            let contextMenuTag = me.taskContextMenu.find('.Task_tagMenu');
            let menuItem;
            contextMenuTag.empty();
            menuTag.empty();

            Project.tagList(me._data.id, function (tags){
                $.each(tags, function (i, tag){

                    menuItem = TaskManager_Templates.taskTagMenuItem
                        .replace('@ID@', tag.id_)
                        .replace('@NAME@', tag.name)
                        .replace('@COLOR@', tag.color);

                    menuTag.append(menuItem);
                    contextMenuTag.append(menuItem)
                })

                if (tags.length > 0){
                    menuTag.append(TaskManager_Templates.menuItemDivider);
                    contextMenuTag.append(TaskManager_Templates.menuItemDivider)
                }

                menuItem = TaskManager_Templates.taskTagMenuItem
                    .replace('@ID@', 0)
                    .replace('@NAME@', 'No Tag')
                    .replace('@COLOR@', 'transparent');

                menuTag.append(menuItem);
                contextMenuTag.append(menuItem)
            })
        }

        var _bindEvents = function (){
            me.taskdatePicker.datepicker();

            me.sectionAddButton.on('click', function(e){
                Section.add({
                    id_project: me._data.id
                },function (section){
                    me.addSection(section);
                },function(error){
                    Application.warningNotification(error,false,'topCenter');
                })
            })

            $('body').click(function (e) {
                if (e.target, $(e.target).closest('#TaskContextMenu').length == 0)
                    me.taskContextMenu.hide();
            });

            $('.sidebar,.navbar').click(function () {
                me.taskDetail.close();
            });

            me.on('click', '.taskman-header',function (e) {
                me.taskDetail.close();
            });

            me.on('click', '.taskman-content',function (e) {
                if (e.target !== e.currentTarget) return;
                me.taskDetail.close();
            });

            me.header.on('click', '.btn-add-task', function(e){
                me.addTask();
            })

            me.header.on('click', '.btn-add-section', function(e){
                Section.add({
                    id_project: me._data.id
                },function (section){
                    me.addSection(section);
                },function(error){
                })
            })


            $('body').on('keydown', function(e){
                let _tag = e.target.tagName.toUpperCase();
                //console.log(e.target.tagName.toUpperCase());

                if (e.keyCode == 13){
                    if (['INPUT','TEXTAREA'].includes(_tag)) return;

                    //console.log("enter",);
                    e.stopPropagation();
                    if (me.selectedTask){
                        //me.selectedTask.find('.task-name-frame').click();
                        //onAddTask(e, me.selectedTask.section, me.selectedTask);
                        me.selectedTask.section.addTaskItem({}, me.selectedTask);
                    }

                }else if (e.keyCode == 113){ // F12
                    if (['INPUT','TEXTAREA'].includes(_tag)) return;

                    e.preventDefault();
                    e.stopPropagation();

                    if (me.selectedTask){
                        /* me.selectedTask.find('.task-name-frame').click(); */
                        me.selectedTask.renameTask();
                    }
                }else if (e.keyCode == 40){ // arrow down
                    //console.log("down");
                    if (_tag == 'TEXTAREA') return;

                    e.preventDefault();
                    e.stopPropagation();
                    var _item;

                    if (me.selectedTask){
                        //console.log(me.selectedTask.nextItem(true));
                        _item = me.selectedTask.nextItem(true);
                    }else if (me.lastTask){
                        _item = me.lastTask;
                    }else{
                        let _section = me.sections[me.sectionIds[0]];
                        if (_section) _item = _section.tasks[_section.taskIds[0]];
                    }

                    if (_item && _item.length > 0){
                        _item.selectItem();
                        me.scrollTask(_item);
                    }
                    return false;

                }else if (e.keyCode == 38){ // arrow up
                    //console.log("up");
                    if (_tag == 'TEXTAREA') return;

                    e.preventDefault();
                    e.stopPropagation();
                    var _item;

                    if (me.selectedTask){
                        _item = me.selectedTask.prevItem(true);

                    }else if (me.lastTask){
                        _item = me.lastTask;
                    }else{
                        let _section = me.sections[me.sectionIds[0]];
                        if (_section) _item = _section.tasks[_section.taskIds[0]];
                    }
                    if (_item && _item.length > 0){
                        _item.selectItem();
                        me.scrollTask(_item);
                    }
                    return false;
                }
            })

            me.header.on('change', '.select-filter', function(e){
                const url = new URL(location.href);
                var _goto = url.origin + url.pathname;

                let _status = me.find('.filter-task-status').val();
                let _priority = me.find('.filter-task-priority').val();

                var _params = {};
                if (_status != "") _params['status'] = _status;
                if (_priority != "") _params['priority'] = _priority;

                const params = new URLSearchParams(_params);
                let _query = params.toString();

                if (!Application.isEmpty(_query)) _goto += '?' + _query;
                location.href = _goto;
            })
        }

        return me.initialize();
    };

    $.fn.TaskDetail = function(taskman) {
        var me = this;
        const classDetail = '--open-task-detail';

        me.taskman = taskman;
        me.task = undefined;

        me.inputTitle = undefined;
        me.inputDescription = undefined;
        me.inputComment = undefined;
        me.datePicker = undefined;
        me.commentList = undefined;
        me.content = undefined;
        me.fileList = undefined;

        var initData = false;

        me.initialize = function (){

            if ($(".page-header").css('display') == 'block'){
                me.css('width', '100%');
            }

            me.content = me.find('.section-content');
            me.inputTitle = me.find('textarea.text-title');
            me.inputComment = me.find('textarea.text-comment');
            me.inputDescription = me.find('textarea.text-description');
            me.datePicker = me.find('.TaskDetail_datePickerMenu');
            me.commentList = me.find('.task-comments-wrapper');
            me.fileList = me.find('.task-files-wrapper');

            _bindEvents();
            return this;
        }

        me.isOpened = function (){
            return me.hasClass(classDetail);
        }

        me.open = function (task){
            me.task = task;

            if (!me.isOpened())
            {
                me.addClass(classDetail);
            }

            let taskDueDate = stringToDate(me.task._data.dueDate);

            me.datePicker.datepicker('update', taskDueDate);
            me.commentList.empty();
            me.fileList.find('.task-file-images').empty();
            me.fileList.find('.task-file-other').empty();

            task.setOpenedTask ();
            autosize.update(me.find('textarea.text-autosize'));
            _resizeView();

            me.taskman.settings.onTaskOpened(undefined, me.task);
        }

        me.close = function (){
            me.removeClass(classDetail);
            me.taskman.settings.onTaskClosed(undefined, me.task);
            me.task = undefined;
        }

        me.cancelComment = function (){
            me.removeClass('--new-comment');
            me.inputComment.val('');
            me.find('.task-comment-box').removeClass('editing');
            _resizeView();
        }

        var _resizeView = function (){
            let h = me.find('.section-footer').height();
            me.find('.section-content').css('margin-bottom', h);
        }

        var _newComment = function (){
            me.find('.task-comment-box').addClass('editing');
            me.addClass('--new-comment');
            me.inputComment.val('');
            autosize.update(me.inputComment);
            _resizeView();
        }

        var _bindEvents = function (){

            me.datePicker.datepicker()
            .on('changeDate', function(e) {
                me.find('#dropdownMenuDate').dropdown('hide');
                //console.log(e.date, moment(e.date).format('YYYY-MM-DD'));
                let date = e.date ?  moment(e.date).format('YYYY-MM-DD') : null;

                Task.dueDate(me.task._data.id, date, function(data){
                    me.task.setTaskDueDate(date);
                }, function(error){
                    Application.warningNotification(error,false,'topCenter');
                })
            });

            //me.datePicker.datepicker('setDate', me.task._data.dueDate);

            /* me.find('#dropdownMenuDate').on('click', function () {
                me.find('#dropdownMenuDate').dropdown('show');
                console.log(datepicker);
                // do something...
            }) */


            autosize(me.find('textarea.text-autosize'));

            me.on('click', '.btn-close-detail', function(e){
                me.close();
            })

            me.on('click', '.task-title-frame', function(e){
                me.find('.task-title-cont').addClass('editing');
                me.inputTitle.focus();
            })

            me.inputTitle.on('blur',  function(e){
                me.find('.task-title-cont').removeClass('editing');
            })

            me.inputTitle.on('change',  function(e){
                var input = this;

                if (this.value == this.defaultValue) return;

                Task.update(me.task._data.id, input.value
                    ,function(task){
                        me.task.setTaskName(input.value);
                        //me._data = $.extend({},me._data, task);
                        //me.taskRow.find('.task-name').text(input.value);
                    },function(error){
                        Application.warningNotification(error,false,'topCenter');
                    })

                //me.find('.task-title-frame').text(this.value);
            })

            me.on('click', '.btn-action-task', function(e){
                let menuItem = $(this)
                let action = menuItem.data('action');

                if (action == 'status'){
                    let status = menuItem.data('status');

                    Task.status(
                        me.task._data.id, status,
                        function(data){
                            me.task.setTaskStatus(status);
                        },
                        function(error){
                            Application.warningNotification(error,false,'topCenter');
                        });


                }else if (action == 'priority'){
                    let priority = menuItem.data('priority');
                    Task.priority(
                        me.task._data.id, priority,
                        function(data){
                            me.task.setTaskPriority(priority);
                        },
                        function(error){
                            Application.warningNotification(error,false,'topCenter');
                        });
                }else if (action == 'assignment'){
                    let id_account = menuItem.data('accountId');
                    Task.assignee(me.task._data.id, id_account
                        ,function (result){
                            me.task.setTaskAssignee(result.assignee);
                        },function (error){
                            Application.warningNotification(error,false,'topCenter');
                        });
                }
            })

            me.on('click', '.btn-action-task-comment', function(e){
                let menuItem = $(this)
                let commentItem = menuItem.closest('.task-comment-item');
                let action = menuItem.data('action');
                let commentId = commentItem.data('id');

                if (action == 'delete'){
                    let status = menuItem.data('status');

                    Task.deleteComment(
                        commentId,
                        function(data){
                            commentItem.remove();
                        });
                }
            })

            me.inputTitle.on('keydown',  function(e){
                if (e.keyCode == 13){
                    e.preventDefault();
                    e.stopPropagation();
                    this.blur();
                    return false;
                }else if(e.keyCode == 27){
                    this.value = me.task._data.name;
                    this.blur();
                }
            })

            me.on('click', '.task-description-frame', function(e){
                me.find('.task-description-wrapper').addClass('editing');
                me.inputDescription.focus();
                autosize.update(me.inputDescription);
            })

            /* me.on('blur', 'textarea.text-description', function(e){
                me.find('.task-description-wrapper').removeClass('editing');
            }) */

            /* me.on('change', 'textarea.text-description', function(e){
                var btnSave = me.find('.btn-save-description');
                btnSave.prop('disabled', Application.isEmpty(this.value));
            }) */

            me.inputDescription.on('keydown', function(e){
                if (e.ctrlKey && (e.keyCode == 13)){
                    me.task.saveTaskDescription();
                }
            })
            me.inputDescription.on('keyup', function(e){
                var btnSave = me.find('.btn-save-description');
                btnSave.prop('disabled', Application.isEmpty(this.value));
            })

            me.on('click', '.btn-save-description', function(e){
                me.task.saveTaskDescription();
            })

            me.on('click', '.btn-cancel-description', function(e){
                //me.task.setTaskDescription();
                me.find('.task-description-wrapper').removeClass('editing');
                me.inputDescription.val(me.task._data.description);
            })

            me.inputComment.on('focus', function(e){
                _newComment();
            })

            me.inputComment.on('blur', function(e){
                let _btnInComment = e.relatedTarget && e.relatedTarget.classList.contains('btn-attach-comment');

                if (Application.isEmpty(this.value) && !_btnInComment){
                    me.cancelComment();
                }
            })

            me.inputComment.on('keyup', function(e){
                var btnSave = me.find('.btn-send-comment');
                btnSave.prop('disabled', Application.isEmpty(this.value));
            })

            me.inputComment.on('keydown', function(e){
                if (e.ctrlKey && (e.keyCode == 13)){
                    me.task.saveTaskComment();
                }
            })

            me.on('click', '.btn-cancel-comment', function(e){
                me.cancelComment();
            })

            me.on('click', '.btn-send-comment', function(e){
                me.task.saveTaskComment();
            })

            me.inputComment.on('keyup', function(e){
                _resizeView();
            })

            me.on('click', '.btn-due-date', function (e){
                let action = $(this).data('action');
                var date = null;
                if (action == 'today' ) date = moment().toDate();//.format('YYYY-MM-DD');
                else if (action == 'tomorrow' ) date = moment().add(1, 'days').toDate(); //.format('YYYY-MM-DD');

                me.datePicker.datepicker('setDate', date);
            })

            me.on('click', '.btn-attachment', function(e){
                UploadManagement.selectFile({
                    name: me.task._data.name,
                    taskId: me.task._data.id,
                    projectId:  me.taskman._data.id,
                }, function (result){
                    console.log(result);
                    var taskItem = me.taskman.taskById (result.taskId);
                    if (taskItem){
                        taskItem.addTaskImage(result);
                    }
                });
            })

            me.fileList.on('click', '.btn-remove-file', function(e){
                var fileItem = $(this).closest('.task-file-item ');
                var fileId = fileItem.data('id');
                Task.removeFile(fileId, function(){
                    fileItem.remove();
                },function(error){
                    Application.warningNotification(error,false,'topCenter');
                })
            })
        }

        return me.initialize();
    }

    $.fn.TaskSection = function(taskman, options ) {
        var me = this;
        var defaults = {
            data: dataDefaults.section
        }

        me.taskman = taskman;
        me.settings = $.extend({}, defaults, options);
        me.taskIds = [];
        me.tasks = {};
        me._data = {};

        me.taskList = undefined;
        me.taskAddButton = undefined;
        me.taskInsertButton = undefined;

        me.sectionInput = undefined;
        me.sectionActionWrapper = undefined;

        me.initialize = function (){

            me._data = $.extend({}, me.settings.data, me.data());
            me.taskList = me.find('> .task-list');
            me.removeAttr("data-id");

            if (Application.isEmpty(me._data.name))
                me._data.name = me.find('.group-title-text').text();

            if (me._data.id == 0) me.addClass('task-group--home');
            if (me.isAssignedSection()) me.addClass('task-group--assigned');
            else me.addClass('task-group--sec');

            me.taskList.find('> .'+me.taskman.settings.prefix+'-sec-ti.task-item').each(function(i, element){
                let _taskElement = $(element);

                let _taskId = _taskElement.data('id');
                me.taskIds.push(_taskId);

                let task = _taskElement.TaskItem(me);
                me.tasks[_taskId] = task;
            })

            me.sectionInput = me.find('.task-group-input');
            //me.sectionInput.css('width', getTextWidth(me.sectionInput[0]));

            me.sectionActionWrapper = me.find('.group-action-wrapper');

            var _templateSectionAction = '';
            if (!me.isAssignedSection()){
                _templateSectionAction += TaskManager_Templates.sectionActionAdd;
                if (me._data.id != 0 ) _templateSectionAction += TaskManager_Templates.sectionActionMore;
            }

            /* if (me._data.id != 'assigned') _templateSectionAction += TaskManager_Templates.sectionActionAdd;
            else if (me._data.id != 0 ) _templateSectionAction += TaskManager_Templates.sectionActionMore; */

            me.sectionActionWrapper.html(_templateSectionAction);

            me.taskAddButton = me.taskList.find('.tm-sec-act.task-item--action');
            me.taskInsertButton = me.find('>.task-group-title .btn-insert-task');

            if (me.isAssignedSection()) me.taskAddButton.remove();
            else
            {
                if (me.taskAddButton.length == 0)
                {
                    me.taskList.append( TaskManager_Templates.taskAddButton );

                    me.taskAddButton = me.taskList.find('.tm-sec-act.task-item--action');
                }
            }


            _bindEvents();
            return this;
        }

        me.isAssignedSection = function (){
            return me._data.id == 'assigned';
        }

        me.isCollapsed = function (){
            return me.find('.btn-group-toggle').hasClass('collapsed');
        }

        me.collapse = function (collapse){
            if (collapse == true || collapse == undefined)
            {
                if (!me.isCollapsed()){
                    me.find('.btn-group-toggle').addClass('collapsed').attr('aria-expanded',true);
                    me.find('.task-list').removeClass('show');
                }
            }
            else
            {
                if (me.isCollapsed()){
                    me.find('.btn-group-toggle').removeClass('collapsed').attr('aria-expanded',false);
                    me.find('.task-list').addClass('show');
                }
            }
        }

        me.addTaskItem = function (params, taskItem){
            let data = $.extend({}, dataDefaults.task, params);
            //let _tid = me.taskman.settings.prefix + '-q-choice-item-'+ data.id;
            me.taskman.find('.task-item').removeClass('selected editing');

            me.collapse(false);

            let _taskItemTemplate = TaskManager_Templates.taskItem.replaceAll('@TASK_ID@', data.id);

            if (taskItem == undefined){
                me.taskAddButton.before(_taskItemTemplate);
                //me.taskList.append(_taskItem);
            }else if (taskItem === 0){
                me.taskList.prepend(_taskItemTemplate);
            }else {
                taskItem.after(_taskItemTemplate);
            }

            var newItem = me.taskList.find('.task-item--new').TaskItem(me);
            newItem.renameTask();
            //newItem.find('.task-name-frame').click();

            me.taskman.taskDetail.close();
        }

        me.removeTaskItem = function (taskItem){
            me.taskIds = me.taskIds.filter(function(value, index, arr){
                return value != taskItem._data.id;
            });

            delete me.tasks[taskItem._data.id];
            taskItem.find('.task-status').tooltip('dispose');
            taskItem.remove();
        }

        me.renameTitle = function (){
            me.addClass('editing');
            me.sectionInput.focus();
            me.sectionInput.css('width', getTextWidth(me.sectionInput[0]));
            me.sectionInput[0].selectionStart = me.sectionInput[0].selectionEnd = me.sectionInput.val().length;
        }

        var _ajaxDeleteSection = function (){
            Section.delete(me._data.id,
            function (){
                me.taskman.removeSection(me);
            },
            function(error){
                Application.warningNotification(error,false,'topCenter');
            })
        }

        var _bindEvents = function (){

            me.taskAddButton.on('click', function(e){
                me.addTaskItem();
            })

            me.taskInsertButton.on('click', function(e){
                if (me.find('.btn-group-toggle').hasClass('collapsed')) me.find('.btn-group-toggle').click();
                me.addTaskItem({},0);
            })

            me.on('click', '.group-title-frame', function(e){
                e.preventDefault();
                if (me._data.id == 0 || me.isAssignedSection()) return;
                me.renameTitle();
                /* me.addClass('editing');
                me.sectionInput.focus();
                me.sectionInput[0].selectionStart = me.sectionInput[0].selectionEnd = me.sectionInput.val().length; */
            })

            me.sectionInput.on('change', function(e){
                var input = this;

                if (this.value == this.defaultValue) return;

                Section.update(me._data.id, input.value
                ,function(){
                    me.find('> .task-group-title .group-title-text').text(input.value);
                },function(error){
                    input.value = input.defaultValue;
                    Application.warningNotification(error,false,'topCenter');
                })
            })

            me.sectionInput.on('keydown', function(e){
                this.style.width = getTextWidth(this);

                if (e.keyCode == 13 && !Application.isEmpty(this.value)){
                    this.blur();
                }else if (e.keyCode == 27){ //escape
                    this.value = this.defaultValue;
                    this.blur();
                }
            })

            /* me.sectionInput.on('keypress', function(e){
                this.style.width = getTextWidth(this);
                //this.style.width = ((this.value.length + 2) * 10)+ 'px';
            }) */

            me.sectionInput.on('blur', function(e){
                me.removeClass('editing');
            })

            me.sectionActionWrapper.on('click', '.btn-rename-section', function(e){
                me.renameTitle();
            })

            me.sectionActionWrapper.on('click', '.btn-delete-section', function(e){
                if (me.taskman.sectionIds.length > 1)
                {
                    if (me.taskIds.length > 0){
                        Application.confirmDialog({
                            title: "Delete Section",
                            message: 'All tasks in section will be deleted',
                            callback: function(){
                                _ajaxDeleteSection();
                            }
                        })
                    }else{
                        _ajaxDeleteSection();
                    }
                }else{
                    Application.warningNotification("At least one Section in Project",false, 'topCenter');
                }
            })
        }

        return me.initialize();
    }

    $.fn.TaskItem = function(object, options ) {
        var me = this;
        var defaults = {
            data: dataDefaults.task
        }

        me.taskman = object.taskman;
        me.section = object;

        me.settings = $.extend({}, defaults, options);
        me.subtaskIds = [];
        me.subtasks = {};

        me.taskList = undefined;
        me.taskRow = undefined;
        me.taskInput = undefined;

        me.newItem = undefined;

        me.initialize = function (){

            me._data = $.extend({}, me.settings.data, me.data());

            me.taskList = me.find('> .task-list');
            me.taskRow = me.find('> .task-row');
            me.taskInput = me.taskRow.find('.task-input');
            me.newItem = me.hasClass('task-item--new');

            me.removeAttr("data-id data-status data-priority data-due-date data-assignee-id data-assignee-name data-assignee-avatar");

            if (Application.isEmpty(me._data.name))
                me._data.name = me.find('.task-name').text();

            me.taskList.find('> .'+me.taskman.settings.prefix+'-sec-ti.task-item').each(function(i, element){
                let _subtaskElement = $(element);

                let _subtaskId = _subtaskElement.data('id');
                me.subtaskIds.push(_subtaskId);

                let subtask = _subtaskElement.TaskItem(me);
                me.subtasks[_taskId] = subtask;
            })

            if (me.subtaskIds.length > 0){
                me.addClass('has-subtask');
            }

            me.setTaskStatus();
            me.setTaskDueDate();
            me.setTaskPriority();
            me.setTaskAssignee();
            me.setTaskProject();

            if (me._data.status == 1)
                me.addClass('ts--complete');

            _bindEvents();
            return this;
        }

        me.nextItem = function (global){
            let _item = me.next('.task-item');

            if (_item.length == 0 && global){
                let _grp = me.section.next();

                if (_grp){
                    let _section = me.taskman.sections[_grp.data('id')];

                    if (_section && _section.taskIds.length > 0){
                        _item = _section.tasks[_section.taskIds[0]];
                    }
                }
            }else{
                let _id = _item.data('id');
                _item = me.section.tasks[_id];
            }

            return _item;
        }

        me.prevItem = function (global){
            let _item = me.prev('.task-item');

            if (_item.length == 0 && global){
                let _grp = me.section.prev();
                if (_grp){
                    let _section = me.taskman.sections[_grp.data('id')];
                    if (_section && _section.taskIds.length > 0){
                        _item = _section.tasks[_section.taskIds[_section.taskIds.length-1]];
                    }
                }
            }else{
                let _id = _item.data('id');
                _item = me.section.tasks[_id];
            }

            return _item;
        }

        me.isTaskOpened = function (){
            return me.taskman.taskDetail && me.taskman.taskDetail.isOpened() && (me.taskman.taskDetail.task == me);
        }

        me.openTaskDetail = function (){
            if (me.taskman.taskDetail.task != me)
                me.taskman.taskDetail.open(me);
        }

        me.closeTaskDetail = function (){
            me.taskman.taskDetail.close();
        }

        me.getTaskName = function (){
            return me._data.name ? me._data.name : me.find('.task-name').text();
        }

        me.selectItem = function (e, single, selected){
            if (single === undefined || single === true){
                me.taskman.find('.task-item').removeClass('selected editing');
            }

            if (selected === undefined || selected === true)
            {
                me.addClass('selected');
                me.taskman.lastTask = me.taskman.selectedTask;
                if (!me.newItem) me.taskman.selectedTask = me;

                me.taskman.scrollTask(me);

                if (me.taskman.taskDetail.isOpened()){
                    me.openTaskDetail();
                }
            }
            else me.removeClass('selected');
        }

        me.renameTask = function (){
            if (me.hasClass('selected')){
                me.addClass('editing');
                me.taskInput.focus();
                me.taskInput[0].selectionStart = me.taskInput[0].selectionEnd = me.taskInput.val().length;
            }
        }

        me.setTaskStatus = function (status){
            if (status === undefined) status = me._data.status;

            let _statusBtn = me.find('.task-status');
            _statusBtn.html(taskStatusIcon[status]);
            if (status == 1){
                _statusBtn.attr('data-original-title', 'Set Todo');
                me.addClass('ts--complete');
            }else{
                _statusBtn.attr('data-original-title', 'Set Completed');
                me.removeClass('ts--complete');
            }
            me._data.status = status;
            _statusBtn.tooltip('update');

            if (me.isTaskOpened()){

                me.taskman.taskDetail.find('.menu-item-status').removeClass('--selected');
                me.taskman.taskDetail.find('.menu-item-status[data-status='+status+']').addClass('--selected');

                me.taskman.taskDetail.find('.btn-task-status').html(taskStatusIcon[me._data.status]);
            }
        }

        me.setTaskProject = function (){
            if (me.taskman._data.id != 0) me.find('.task-project-wrapper').removeClass('d-md-block');
        }

        me.setTaskAssignee = function (assignee){
            if (assignee === undefined) assignee = me._data;
            else {
                $.each(assignee, function (field, value){
                    me._data[field] = value;
                })
            }
            let icon = assignee.assigneeId == 0 ? '' : '<img src="'+assignee.assigneeAvatar+'/20/20" class="rounded-round"></img>';

            me.find('.task-assignee').html(icon);

            if (me.isTaskOpened()){
                me.taskman.taskDetail.find('.menu-item-assignee').removeClass('--selected');
                me.taskman.taskDetail.find('.menu-item-assignee[data-account-id='+assignee.assigneeId+']').addClass('--selected');

                if (Application.isEmpty(icon)) icon = '<i class="far fa-user text-muted"></i>';
                me.taskman.taskDetail.find('.task-assigne-cont .task-icon-label').html(icon);
                me.taskman.taskDetail.find('.task-assigne-cont .task-assignee').text(assignee.assigneeName ? assignee.assigneeName : 'Add Assignee');
            }
        }

        me.setTaskName = function (name){
            if (name === undefined) name = me._data.name;
            else me._data.name = name;

            me.find('.task-name').text(name);
            me.find('.task-input').val(name);

            if (me.isTaskOpened()){
                me.taskman.taskDetail.find('.t-name').text(name);
                me.taskman.taskDetail.find('textarea.text-title').val(name);
            }
        }

        var _taskDueDate = function (strdate){
            var strdate = Application.isEmpty(strdate) || strdate == '0000-00-00' ? '' : strdate;

            if (strdate){
                var date = moment(strdate, 'YYYY-MM-DD');
                var _date = moment().startOf('day');
                var today = _date.toDate().getTime();
                var tomorrow = _date.add(1, 'days').toDate().getTime();
                var dueDate = date.toDate().getTime();

                if (dueDate == today) strdate = '<span class="text-warning">Today</span>';
                else if (dueDate == tomorrow) strdate = '<span class="text-info">Tomorrow</span>';
                else if (dueDate > today) strdate = '<span class="">'+date.format("DD MMM")+'</span>';
                else if (dueDate < today) strdate = '<span class="text-danger">'+date.format("DD MMM")+'</span>';
            }

            return strdate;
        }

        me.setTaskDueDate = function (date){
            if (date === undefined) date = me._data.dueDate;
            else me._data.dueDate = date;

            let duedate = _taskDueDate(date);

            me.find('.task-due-date').html(duedate);
            //console.log(_taskDueDate(date));

            if (me.isTaskOpened()){
                if (Application.isEmpty(duedate)) duedate = 'Due Date';
                me.taskman.taskDetail.find('.task-due-date').html(duedate);
            }
        }

        me.setTaskPriority = function (priority){
            if (priority === undefined) priority = me._data.priority;
            else me._data.priority = priority;

            me.find('.task-priority').html(priority != 1 ? taskPriorityIcon[priority] : '');

            if (me.isTaskOpened()){

                me.taskman.taskDetail.find('.menu-item-priority').removeClass('--selected');
                me.taskman.taskDetail.find('.menu-item-priority[data-priority='+priority+']').addClass('--selected');

                me.taskman.taskDetail.find('.btn-task-priority').html(taskPriorityIcon[priority] + '<span class="ml-1 text-dark">'+taskPriorityText[priority]+'</span>');
            }
        }

        me.setTaskDescription = function (description){
            if (description == undefined) description = me._data.description;
            else me._data.description = description;

            if (me.isTaskOpened()){
                me.taskman.taskDetail.find('.t-description').text(Application.isEmpty(description) ? 'Add description': description);
                me.taskman.taskDetail.inputDescription.val(description);
            }
        }

        me.saveTaskDescription = function (){
            if (me.isTaskOpened())
            {
                var input = me.taskman.taskDetail.inputDescription;
                var description = input.val();
                if (description == me._data.description || Application.isEmpty(description)) {
                    me.taskman.taskDetail.find('.task-description-wrapper').removeClass('editing');
                    return;
                }

                Task.description(me._data.id, description
                    ,function(task){
                        me.setTaskDescription(description);
                        me.taskman.taskDetail.find('.task-description-wrapper').removeClass('editing');
                    },function(error){
                        Application.warningNotification(error,false,'topCenter');
                    })
            }
        }

        me.addComment = function (comment){
            let _commentItem = TaskManager_Templates.taskCommentItem
                .replace('@ID@', comment.id)
                .replace('@NAME@', comment.accountName)
                .replace('@AVATAR@', comment.accountAvatar)
                .replace('@TIME@', comment.time)
                .replace('@COMMENT@', comment.comment);

            me.taskman.taskDetail.commentList.append(_commentItem);
        }

        me.saveTaskComment = function (){
            if (me.isTaskOpened())
            {
                var input = me.taskman.taskDetail.inputComment;
                var comment = input.val();
                if (Application.isEmpty(comment)) {
                    me.taskman.taskDetail.cancelComment();
                    return;
                }

                Task.addComment(me._data.id, comment
                    ,function(data){
                        me.addComment(data);
                        me.taskman.taskDetail.content.animate({ scrollTop: me.taskman.taskDetail.content.prop("scrollHeight")}, 100);
                        me.taskman.taskDetail.cancelComment();
                    },function(error){
                        Application.warningNotification(error,false,'topCenter');
                    })
            }
        }

        me.addTaskImage = function (file){
            if (me.isTaskOpened())
            {
                let template = file.isImage ? TaskManager_Templates.taskFileImageItem : TaskManager_Templates.taskFileItem;
                template = template
                    .replace('@FILEID@', file.fileId)
                    .replace('@FILEURL@', file.fileUrl)
                    .replace('@IMAGEURL@', file.thumbnail)
                    .replace('@FILENAME@', file.fileName);

                if (file.isImage) me.taskman.taskDetail.fileList.find('.task-file-images').append(template);
                else me.taskman.taskDetail.fileList.find('.task-file-other').append(template);

            }
        }

        me.setOpenedTask = function (){
            /* let t_name = task._data.name ? task._data.name : task.find('.task-name').text();

            me.taskDetail.find('.t-name').text(t_name);
            me.taskDetail.find('textarea.text-title').val(t_name); */

            me.setTaskName();
            me.setTaskDueDate();
            me.setTaskStatus();
            me.setTaskPriority();
            me.setTaskAssignee();

            Task.detail(me._data.id, function(result){
                me._data = $.extend({}, me._data, result.task);
                me.setTaskDescription();

                $.each (result.files, function (i, file){
                    me.addTaskImage(file);
                })

            },function(error){
                Application.warningNotification(error,false,'topCenter');
            })

            Task.comments(me._data.id, function(comments){
                $.each(comments, function(i, comment){
                    me.addComment(comment);
                })
            })

            me.taskman.taskDetail.find('.t-description').text('Add description');
            me.taskman.taskDetail.find('.task-description-wrapper').removeClass('editing');
            me.taskman.taskDetail.find('.task-comment-box').removeClass('editing');
            me.taskman.taskDetail.find('.btn-save-description').prop('disabled', Application.isEmpty(me._data.description));

            me.taskman.taskDetail.find('.text-comment').val('');
            me.taskman.taskDetail.find('.btn-send-comment').prop('disabled', true);
        }

        me.saveTaskItem = function(task){
            let taskId = parseInt(task.id);
            me.removeClass('task-item--new');
            me.newItem = false;

            me._data = $.extend({},me._data, task);
            me.section.taskIds.push(taskId);
            me.attr('id', taskId);
            me.attr('data-id', taskId);

            me.section.tasks[taskId] = me;
        }

        me.removeTaskItem = function(){
            me.section.removeTaskItem(me);
        }

        var _bindEvents = function (){

            me.on('click', function(e){
                if (e.which == 1 || e.which == undefined){
                    if (e.ctrlKey) {
                        me.selectItem(e,false,!me.hasClass('selected'));
                    }else{
                        let _target = $(e.target);
                        if (me.hasClass('selected') && _target.is('.task-name-frame,.task-name, .task-input')){
                            //if (_target.is('.task-name-frame,.task-name')) me.renameTask();
                            //&& _target.is('.task-name-frame,.task-name, .task-input')
                            return;
                        }
                        me.selectItem(e);
                    }
                }
            })

            me.on('dblclick',function(e){
                if (!e.ctrlKey){
                    let _target = $(e.target);
                    if (me.hasClass('selected') && _target.is('.task-name-frame,.task-name, .task-input')){
                        return;
                    }

                    me.openTaskDetail();
                }
            })

            /* me.on('click', function(e){
                e.preventDefault();
                let _target = $(e.target);
                if (me.hasClass('selected') && _target.is('.task-name-frame,.task-name, .task-input')){
                    return;
                }
                _selectItem(e);
            })
 */
            me.on('click', '.task-name-frame', function(e){
                e.preventDefault();
                if (!e.ctrlKey){
                    if (me.taskman.find('.task-item.selected').length == 1){
                        me.renameTask();
                    }else{
                        me.selectItem(e);
                    }
                }
            })

            me.on('click', '.task-status', function(e){
                e.preventDefault();
                if (me.hasClass('selected')) me.selectItem(e);
                let _status = me._data.status == 1 ? 0 :1;

                me.find('.task-status').tooltip('hide');

                Task.status(
                me._data.id, _status,
                function(data){

                    me.setTaskStatus(_status);
                    let isActiveFilter = me.taskman.filterStatus != 'all' && ((Application.isEmpty(me.taskman.filterStatus) && _status == 1) || (me.taskman.filterStatus && me.taskman.filterStatus != _status));

                    if (isActiveFilter){
                        me.taskman.selectedTask = undefined;

                        if (me.isTaskOpened()){
                            me.closeTaskDetail();
                        }

                        setTimeout(() => {
                            me.removeTaskItem();
                        }, 300);
                    }
                },
                function(error){
                    Application.warningNotification(error,false,'topCenter');
                });
            })

            me.taskInput.on('change', function(e){
                var input = this;
                if (this.value == this.defaultValue) return;

                if (!Application.isEmpty(this.value))
                {
                    if (me.newItem){
                        Task.add({
                            id_section: me.section._data.id,
                            id_project: me.taskman._data.id,
                            name: input.value
                        },function(task){
                            me.saveTaskItem(task);
                            me.selectItem(e);
                            me.taskRow.find('.task-name').text(input.value);
                        },function(error){
                            Application.warningNotification(error,false,'topCenter');
                        })
                    }else{
                        Task.update(me._data.id, input.value
                        ,function(task){
                            me._data = $.extend({},me._data, task);
                            me.taskRow.find('.task-name').text(input.value);
                        },function(error){
                            Application.warningNotification(error,false,'topCenter');
                        })
                    }
                }
            })

            me.taskInput.on('keydown', function(e){
                if (e.keyCode == 13 && !Application.isEmpty(this.value)){
                    this.blur();
                    if (!me.newItem) me.taskman.selectTask = me;
                    //me.removeClass('editing');
                }else if (e.keyCode == 27){ //escape
                    this.value = this.defaultValue;
                    this.blur();
                }
            })

            me.taskInput.on('blur', function(e){
                me.removeClass('editing');

                if (Application.isEmpty(this.value) && me.newItem){
                    me.taskman.selectedTask = me.taskman.lastTask;
                    me.taskman.lastTask = undefined;
                    me.remove();

                    if (me.taskman.selectedTask)
                        me.taskman.selectedTask.click();
                }
            })

            me.on('contextmenu','.task-name-wrapper', function(e){
                if (!me.hasClass('selected')){
                    me.selectItem(e);
                }

                let css = getPosition(e, me.taskman.taskContextMenu);
                let selectedItems = me.taskman.find('.task-item.selected');
                let singleItem = selectedItems.length == 1;

                me.taskman.taskContextMenu.find('.single-item').css('display', singleItem ? '' : 'none');

                me.taskman.taskContextMenu.find('.menu-item-priority').removeClass('--selected');
                me.taskman.taskContextMenu.find('.menu-item-status').removeClass('--selected');
                me.taskman.taskContextMenu.find('.menu-item-assignee').removeClass('--selected');

                var _date = null;
                if (singleItem){
                    _date = stringToDate(me._data.dueDate);
                    me.taskman.taskContextMenu.find('.menu-item-priority[data-priority='+me._data.priority+']').addClass('--selected');
                    me.taskman.taskContextMenu.find('.menu-item-status[data-status='+me._data.status+']').addClass('--selected');
                    me.taskman.taskContextMenu.find('.menu-item-assignee[data-account-id='+me._data.assigneeId+']').addClass('--selected');
                }

                var ids = [];
                selectedItems.each(function(i,element){
                    ids.push($(element).data('id'));
                })

                me.taskman.taskdatePicker.datepicker('update', _date);
                me.taskman.taskdatePicker.off('changeDate')
                .on('changeDate', function(e) {
                    me.taskman.taskContextMenu.hide();

                    let date = e.date ?  moment(e.date).format('YYYY-MM-DD') : null;

                    Task.dueDate(ids, date, function(result){
                        $.each(result.updated, function(i, taskID){
                            let taskItem = me.taskman.taskById(taskID);
                            if (taskItem){
                                taskItem.setTaskDueDate(date);
                            }
                        })
                    }, function(error){
                        Application.warningNotification(error,false,'topCenter');
                    })
                });

                //open menu
                let $menu = me.taskman.taskContextMenu
                    .data("invokedOn", $(e.target))
                    .show()
                    .css(css)
                    .off('click')
                    .on('click', 'a.btn-action-task', function (e) {
                        let menuItem = $(this)
                        let action = menuItem.data('action');

                        switch (action){
                            case 'delete':
                                Task.delete(ids
                                ,function (result){
                                    $.each(result.deleted, function(i, taskID){
                                        let taskItem = me.taskman.taskById(taskID);
                                        if (taskItem){
                                            taskItem.removeTaskItem();
                                        }
                                    })
                                    //selectedItems.remove();
                                },function (error){
                                    Application.warningNotification(error,false,'topCenter');
                                })

                                break;
                            case 'rename':
                                me.renameTask();
                                break;
                            case 'priority':
                                let priority = menuItem.data('priority');
                                Task.priority(ids, priority
                                    ,function (result){
                                        $.each(result.updated, function(i, taskID){
                                            let taskItem = me.taskman.taskById(taskID);
                                            if (taskItem){
                                                taskItem.setTaskPriority(priority);
                                            }
                                        })
                                    },function (error){
                                        Application.warningNotification(error,false,'topCenter');
                                    })

                                break;
                            case 'status':
                                let status = menuItem.data('status');
                                let isActiveFilter = me.taskman.filterStatus != 'all' && ((Application.isEmpty(me.taskman.filterStatus) && status == 1) || (me.taskman.filterStatus && me.taskman.filterStatus != status));

                                Task.status(ids, status
                                    ,function (result){

                                        $.each(result.updated, function(i, taskID){
                                            let taskItem = me.taskman.taskById(taskID);
                                            if (taskItem){
                                                taskItem.setTaskStatus(status);
                                            }

                                            if (isActiveFilter){
                                                if (taskItem.isTaskOpened()){
                                                    taskItem.closeTaskDetail();
                                                }

                                                setTimeout(() => {
                                                    taskItem.removeTaskItem();
                                                }, 300);
                                            }

                                        })

                                        if (isActiveFilter)
                                            me.taskman.selectedTask = undefined;

                                    },function (error){
                                        Application.warningNotification(error,false,'topCenter');
                                    })

                                break;
                            case 'open':
                                me.openTaskDetail();
                                break;
                            case 'assignment':
                                let id_account = menuItem.data('accountId');
                                Task.assignee(ids, id_account
                                    ,function (result){
                                        $.each(result.updated, function(i, taskID){
                                            let taskItem = me.taskman.taskById(taskID);
                                            if (taskItem){
                                                taskItem.setTaskAssignee(result.assignee);
                                            }
                                        })
                                    },function (error){
                                        Application.warningNotification(error,false,'topCenter');
                                    })
                                break;
                            case 'duedate':
                                Task.dueDate(ids);
                                break;
                        }


                        $menu.hide();
                    })
                    .on('click', '.btn-due-date', function(e){
                        let action = $(this).data('action');
                        var date = null;
                        if (action == 'today' ) date = moment().toDate();//.format('YYYY-MM-DD');
                        else if (action == 'tomorrow' ) date = moment().add(1, 'days').toDate(); //.format('YYYY-MM-DD');

                        me.taskman.taskdatePicker.datepicker('setDate', date);
                    });

                return false;
            })

            /* me.on('dragstart', function(e){
                this.style.opacity = '0.4';
            })
            me.on('dragend', function(e){
                this.style.opacity = '1';
            })
            me.on('dragenter', function(e){
                e.preventDefault();
                this.classList.add('--dragover');
            })
            me.on('dragover', function(e){
                e.preventDefault();
                this.classList.add('--dragover');
            })
            me.on('dragleave', function(e){
                this.classList.remove('--dragover');
            }) */
        }

        return me.initialize();
    }

})( jQuery );
const TaskManager_Templates = {
    taskmanHeader:
    [   '<div class="taskman-header">',
            '<div class="d-flex justify-content-between align-items-center w-100  border-bottom">',
                '<div class="mb-0 text-muted font-weight-bold d-flex align-items-center justify-content-between">',
                    '<div class="p-2">',
                        '<div class="btn-group">',
                            '<button type="button" class="btn btn-xs btn-add-task bg-transparent border-slate"><b><i class="icon-plus22"></i></b> <span class="d-none d-md-inline-block">Add</span> Task</button>',
                            '<button type="button" class="btn btn-xs bg-transparent border-slate dropdown-toggle" data-toggle="dropdown"></button>',
                            '<div class="dropdown-menu dropdown-menu-condensed">',
                                '<a class="dropdown-item btn-add-section"><i class="icon-menu7"></i> Add Section</a>',
                            '</div>',
                        '</div>',
                    '</div>',
                    '<div class="d-none d-md-block">',
                    '</div>',
                '</div>',
                '<div class="pr-1 pr-md-3 d-flex justify-content-end align-items-center">',
                    '<span class="d-none d-md-inline-block"><i class="far fa-check-circle text-muted tooltips  mr-1" title="Status"></i></span>',
                    '<select class="select-filter filter-task-status mr-2">',
                        '<option value="">Active Tasks</option>',
                        '<option value="all">All Taks</option>',
                        '<option value="todo">Todo</option>',
                        '<option value="inprogress">In Progress</option>',
                        '<option value="completed">Completed</option>',
                    '</select>',
                    '<span class="d-none d-md-inline-block"><i class="icon-arrow-up7 text-muted tooltips  mr-1" title="Priority"></i></span>',
                    '<select class="select-filter filter-task-priority">',
                        '<option value="">All Priorities</option>',
                        '<option value="urgent">Urgent</option>',
                        '<option value="high">High</option>',
                        '<option value="normal">Normal</option>',
                        '<option value="low">Low</option>',
                    '</select>',
                '</div>',
            '</div>',
            '<div class="task-header">',
                '<div class="flex-grow-1">Task Name</div>',
                '<div class="task-header-project d-none d-md-block">Project</div>',
                '<div class="task-header-tag d-none d-md-block"><i class="icon-price-tag3 text-muted small"></i></div>',
                '<div class="task-header-assignee d-none d-md-block"><i class="far fa-user text-muted small"></i></div>',
                '<div class="task-header-date d-none d-md-block">Due Date</div>',
                '<div class="task-header-action d-none d-md-block"><i class="icon-arrow-up7 text-muted small"></i></div>',
            '</div>',
        '</div>'].join(''),
    taskmanContent:
    [   '<div class="taskman-content">',
            '<div class="task-groups"></div>',
        '</div>'].join(''),

    sectionAddButton:
    [   '<div class="tm-sec-action task-group">',
            '<div class="task-group-title">',
                '<a class="btn-new-section"><i class="icon-plus22"></i> Add Section</a>',
            '</div>',
        '</div>'].join(''),

    sectionActionAdd:
       '<a class="action-button btn-insert-task tooltips" title="Add Task"><i class="icon-plus22"></i></a>',

    sectionActionMore:
    [   '<a class="action-button dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu"></i></a>',
        '<div class="dropdown-menu dropdown-menu-condensed">',
            '<a class="dropdown-item btn-rename-section"><i class="icon-pencil3 text-muted"></i> Rename Section</a>',
            '<a class="dropdown-item btn-delete-section text-danger"><i class="icon-trash-alt"></i> Delete Section</a>',
        '</div>'].join(''),

    sectionItem:
    [   '<div class="tm-sec task-group" id="g-@SECTION_ID@" data-id="@SECTION_ID@">',
            '<div class="task-group-title">',
                '<div class="group-toggle">',
                    '<a data-toggle="collapse" class="btn-group-toggle collapsed" href="#gt-@SECTION_ID@"></a>',
                '</div>',
                '<div class="group-title-wrapper">',
                    '<div class="group-title-frame">',
                        '<div class="group-title-frame-border" style=""></div>',
                        '<span class="group-title-text" style="">New Section</span>',
                    '</div>',
                    '<input class="task-group-input" value="New Section" placeholder="">',
                '</div>',
                '<div class="group-action-wrapper">@SECTION_ACTION@</div>',
            '</div>',
            '<ul class="task-list collapse" id="gt-@SECTION_ID@"></ul>',
        '</div>'].join(''),

    taskAddButton:
    ['  <li class="tm-sec-act task-item--action">',
            '<div class="task-row--action btn-new-task">',
                '<a class="task-row--action-text">Add Task ...</a>',
            '</div>',
        '</li>'].join(''),

    taskItem:
    [   '<li class="tm-sec-ti task-item selected task-item--new">',
            '<ul class="task-list collapse" id="st-@TASK_ID@"></ul>',
            '<div class="task-row">',
                '<div class="task-row-front">',
                    '<span class="task-status">',
                        '<i class="far fa-check-circle text-muted"></i>',
                    '</span>',
                    '<span class="subtask-toggle">',
                        '<a data-toggle="collapse" class="btn-task-toggle collapsed" href="#st-{{itemId}}"></a>',
                    '</span>',
                '</div>',
                '<div class="task-row-content">',
                    '<div class="task-name-wrapper">',
                        '<div class="task-name-frame">',
                            '<div class="task-name-frame-border"></div>',
                            '<span class="task-name"></span>',
                        '</div>',
                        '<input class="task-input" maxlength="1024" value="" placeholder="">',
                    '</div>',
                    '<div class="task-project-wrapper text-truncate d-none d-md-block">',
                        '<span class="task-project"></span>',
                    '</div>',
                    '<div class="task-tag-wrapper d-none d-md-block">',
                        '<span class="task-tag"></span>',
                    '</div>',
                    '<div class="task-assignee-wrapper d-none d-md-block">',
                        '<span class="task-assignee"></span>',
                    '</div>',
                    '<div class="task-date-wrapper d-none d-md-block">',
                        '<span class="task-due-date"></span>',
                    '</div>',
                    '<div class="task-action-wrapper d-none d-md-block">',
                        '<span class="task-priority"></span>',
                    '</div>',
                '</div>',
            '</div>',
        '</li>'].join(''),

    taskContextmenu:
    [   '<div class="dropdown-menu dropdown-menu-condensed" id="TaskContextMenu">',
            '<a class="dropdown-item btn-action-task single-item font-weight-bold" data-action="open"><i class="icon-file-text"></i> Detail</a>',
            '<a class="dropdown-item btn-action-task single-item" data-action="rename"><i class="icon-pencil3"></i> Rename</a>',
            '<div class="dropdown-divider single-item"></div>',
            '<div class="dropdown-submenu">',
                '<a class="dropdown-item"><i class="far fa-check-circle text-muted"></i> Set Status</a>',
                '<ul class="dropdown-menu">',
                    '<li><a class="dropdown-item btn-action-task menu-item-status" data-status="0" data-action="status"><i class="far fa-check-circle text-muted"></i> Todo</a></li>',
                    '<li><a class="dropdown-item btn-action-task menu-item-status" data-status="2" data-action="status"><i class="icon-circle2 text-info"></i> In Progress</a></li>',
                    '<li><a class="dropdown-item btn-action-task menu-item-status" data-status="1" data-action="status"><i class="icon-checkmark-circle text-success"></i> Complete</a></li>',
                '</ul>',
            '</div>',
            '<div class="dropdown-submenu">',
                '<a class="dropdown-item"><i class="icon-arrow-up7"></i> Set Priority</a>',
                '<ul class="dropdown-menu">',
                    '<li><a class="dropdown-item btn-action-task menu-item-priority" data-priority="3" data-action="priority"><i class="icon-arrow-up7 text-danger"></i> Urgent</a></li>',
                    '<li><a class="dropdown-item btn-action-task menu-item-priority" data-priority="2" data-action="priority"><i class="icon-arrow-up7 text-warning"></i> High</a></li>',
                    '<li><a class="dropdown-item btn-action-task menu-item-priority" data-priority="1" data-action="priority"><i class="icon-arrow-up7 text-muted"></i> Normal</a></li>',
                    '<li><a class="dropdown-item btn-action-task menu-item-priority" data-priority="0" data-action="priority"><i class="icon-arrow-down7 text-success"></i> Low</a></li>',
                '</ul>',
            '</div>',
            //'<a class="dropdown-item btn-action-task" data-action="assignee"><i class="far fa-user"></i> Set Assignee</a>',
            //'<a class="dropdown-item btn-action-task" data-action="duedate"><i class="far fa-calendar-check"></i> Set Due Date</a>',
            '<div class="dropdown-submenu">',
                '<a class="dropdown-item"><i class="far fa-user"></i> Set Assignee</a>',
                '<div class="dropdown-menu Task_assigneeMenu">',
                '</div>',
            '</div>',
            '<div class="dropdown-submenu dropup">',
                '<a class="dropdown-item"><i class="far fa-calendar-check"></i> Set Due Date</a>',
                '<ul class="dropdown-menu">',
                    '<div class="Task_datePickerMenu mb-2"></div>',
                    '<div class="d-flex justify-content-between p-2">',
                        '<button data-action="today" class="border btn btn-xs btn-due-date">Today</button>',
                        '<button data-action="tomorrow" class="border btn btn-xs btn-due-date">Tomorrow</button>',
                        '<button data-action="none" class="border btn btn-xs btn-due-date">None</button>',
                    '</div>',
                '</ul>',
            '</div>',
            '<div class="dropdown-submenu d-none">',
                '<a class="dropdown-item"><i class="icon-price-tag3"></i> Set Tag</a>',
                '<div class="dropdown-menu Task_tagMenu">',
                '</div>',
            '</div>',
            //'<a class="dropdown-item btn-action-task" data-action="tag"><i class="icon-price-tag3"></i> Set Tag</a>',
            '<div class="dropdown-divider"></div>',
            '<a class="dropdown-item btn-action-task" data-action="delete"><i class="icon-trash"></i> Delete</a>',
        '</div>'].join(''),

    menuItemDivider:
        '<div class="dropdown-divider"></div>',

    taskAssigneeMenuItem:
    [
        '<a class="dropdown-item btn-action-task menu-item-assignee" data-account-id="@ID@" data-action="assignment">',
            '@AVATAR@ @NAME@',
        '</a>'].join(''),

    taskTagMenuItem:
       '<a class="dropdown-item btn-action-task menu-item-tag" data-account-id="@ID@" data-action="tag"><span class="tag-block mr-2" style="background-color:@COLOR@"></span> @NAME@</a>',

    taskDetail:
    [   '<div class="task-detail task-detail-wrapper">',
            '<div class="section-wrapper">',
                '<div class="section-header border-bottom">',
                    '<div class="d-flex align-items-center">',
                        '<div class="flex-grow-1">',
                        '</div>',
                        '<div class="border-left">',
                            '<a class="btn cursor-pointer btn-close-detail text-muted"><i class="icon-move-right"></i></a>',
                        '</div>',
                    '</div>',
                '</div>',
                '<div class="section-content">',
                    '<div class="d-flex task-title-wrapper">',
                        '<div>',
                            '<a class="dropdown-toggle cursor-pointer" data-toggle="dropdown"><i class="far fa-check-circle"></i></a>',
                            '<div class="dropdown-menu dropdown-menu-condensed">',
                                '<a class="dropdown-item btn-action-task" data-status="0" data-action="status"><i class="far fa-check-circle"></i> Todo</a>',
                                '<a class="dropdown-item btn-action-task" data-status="2" data-action="status"><i class="icon-circle2 text-info"></i> In Progress</a>',
                                '<a class="dropdown-item btn-action-task" data-status="1" data-action="status"><i class="icon-checkmark-circle text-success"></i> Complete</a>',
                            '</div>',
                        '</div>',
                        '<div class="flex-grow-1">',
                            '<div class="task-title-frame">',
                                '<div class="task-title-frame-border"></div>',
                                '<textarea class="text-input"></textarea>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>',
                '<div class="section-footer border-top">',
                    'footer',
                '</div>',
            '</div>',
        '</div>'].join(''),

    taskCommentItem:
    [   '<div class="task-comment-item" data-id="@ID@">',
            '<div class="mr-1"><img src="@AVATAR@/40/40" class="rounded-circle"></div>',
            '<div class="task-comment-cont">',
                '<div class="task-comment-header">',
                    '<div>',
                        '<span class="commentor">@NAME@</span>',
                        '<span class="comment-time">@TIME@</span>',
                    '</div>',
                    '<div class="task-comment-action">',
                        '<a class="dropdown-toggle cursor-pointer caret-0" data-toggle="dropdown"><i class="icon-more2"></i></a>',
                        '<div class="dropdown-menu dropdown-menu-condensed dropdown-menu-right"> ',
                            '<a class="dropdown-item btn-action-task-comment" data-action="delete"><i class="icon-cross2"></i> Delete</a>',
                        '</div>',
                    '</div>',
                '</div>',
                '<div class="task-comment-content">@COMMENT@</div>',
            '</div>',
        '</div>'].join(''),

    memberItem:
    [   '<div class="border-bottom pb-1 pt-1 item-member" data-id="@ACCOUNT_ID@">',
            '<div class="row">',
                '<div class="col-md-9 col-8 d-flex align-items-center">',
                    '<img src="@ACCOUNT_AVATAR@/30/30" class="rounded-circle mr-2">',
                    '<span class="member-name">@ACCOUNT_NAME@</span>',
                '</div>',
                '<div class="col-2 text-right d-flex align-items-center justify-content-end">',
                    '<a href="#" class="member-role text-dark dropdown-toggle" data-toggle="dropdown" >@ROLE@</a>',
                    '<div class="dropdown-menu">',
                        '<a data-role="contributor" class="dropdown-item btn-role-member">Contributor</a>',
                        '<a data-role="guest" class="dropdown-item btn-role-member">Guest</a>',
                        '<a data-role="admin" class="dropdown-item btn-role-member">Admin</a>',
                    '</div>',
                '</div>',
                '<div class="col-md-1 col-2  d-flex align-items-center justify-content-end">',
                    '<button class="btn border btn-xs btn-icon btn-delete-member tooltips" title="Remove Member"  type="button"><i class="icon-cross3"></i></button>',
                '</div>',
            '</div>',
        '</div>'].join(''),

    taskFileImageItem:
    [   '<div class="task-file-item col-md-2 col-4" data-id="@FILEID@">',
            '<div><a href="@FILEURL@" target="_blank"><img src="@IMAGEURL@" class="w-100"></a></div>',
            '<button class="btn btn-xs btn-remove-file btn-icon mt-1"><i class="icon-cross2"></i> remove</button>',
        '</div>'].join(''),

    taskFileItem:
    [   '<div class="task-file-item d-flex mb-1" data-id="@FILEID@">',
            '<a href="@FILEURL@" target="_blank" class="flex-grow-1 text-truncate">',
                '<i class="icon-file-empty mr-1"></i> @FILENAME@',
            '</a>',
            '<button class="btn btn-xs btn-remove-file btn-icon"><i class="icon-cross2"></i></button>',
        '</div>'].join(''),
}
var Workspace = function () {

    var runAction = function (url, data, onSuccess, onFailed){
		Application.post({
			container: '.content',
			url: url,
			data: data,
			useAlert: false,
			useBlockUI: false,
			success: onSuccess,
			failed: onFailed
		});
	}

    var addDialog = function(option){
		var setting = $.extend(true,{
            onShow: function(){},
            onSuccess:function(data){},
            onError:function(message){Application.errorNotification(message, false, 'topCenter');}
		},option);

        var content = [
            '<div class="w-100">',
                '<div class="form-group">',
                    '<label class="h3 mb-1">Create New Workspace</label>',
                    '<input required autocomplete="off" class="form-control w-name" type="text" required placeholder="Workspace Name"></input>',
                '</div>',
                '<div class="form-group">',
                    '<input autocomplete="off" class="form-control w-desc" type="text" placeholder="Description"></input>',
                '</div>',
            '</div>',
        ].join('');

        Application.modalDialog({
            title: 'New Workspace',
            iconTitle: 'icon-puzzle2 mr-1',
            backdropStatic: true,
            class: 'modal-md rounded-round',
            body: {
                style: 'min-height:300px',
                class: 'd-flex align-items-center p-4',
            },
            content: content,
            onShow: function(modal){
                modal.find("input.w-name").focus();
                modal.find("input").on('keydown',function(e){
                    if (e.keyCode == 13) modal.find('button.ok').click();
                })
            },
            okButton: {
                class: "btn-success",
                label: '<i class="icon-checkmark2"></i> Save',
                callback: function (modal){
                    let inputName = modal.find('input.w-name');
                    let inputDesc = modal.find('input.w-desc');
                    let name = inputName.val();

                    if (Application.isEmpty(name)){
                        inputName.focus();
                        return;
                    }

                    Application.post({
                        container: modal,
                        url: 'workspace/create',
                        data: {name: name, description: inputDesc.val()},
                        success: function (data) {
                            setting.onSuccess(data)
                            modal.modal('hide');
                        },
                        failed: function (message) {
                            setting.onError(message);
                        }
                    });
                }
            },
        })
	}

    var updateDialog = function(option){
		var setting = $.extend(true,{
            id: 0,
            name: '',
            description: '',
            onShow: function(){},
            onSuccess:function(data){},
            onError:function(message){Application.errorNotification(message, false, 'topCenter');}
		},option);

        var content = [
            '<div class="w-100">',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Workspace Name</label>',
                    '<input required autocomplete="off" class="form-control w-name" type="text" required placeholder="Workspace Name" value="',setting.name,'"></input>',
                '</div>',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Description</label>',
                    '<input autocomplete="off" class="form-control w-desc" type="text" placeholder="Description" value="',setting.description,'"></input>',
                '</div>',
            '</div>',
        ].join('');

        Application.modalDialog({
            title: 'Workspace Profile',
            iconTitle: 'icon-puzzle2 mr-1',
            backdropStatic: true,
            class: 'modal-md rounded-round',
            body: {
                style: 'min-height:300px',
                class: 'd-flex align-items-center p-4',
            },
            content: content,
            onShow: function(modal){
                modal.find("input.w-name").focus();
                modal.find("input").on('keydown',function(e){
                    if (e.keyCode == 13) modal.find('button.ok').click();
                })
            },
            okButton: {
                class: "btn-success",
                label: '<i class="icon-checkmark2"></i> Save',
                callback: function (modal){
                    let inputName = modal.find('input.w-name');
                    let inputDesc = modal.find('input.w-desc');
                    let name = inputName.val();

                    if (Application.isEmpty(name)){
                        inputName.focus();
                        return;
                    }

                    Application.post({
                        container: modal,
                        url: 'workspace/update',
                        data: {id: setting.id, name: name, description: inputDesc.val()},
                        success: function (data) {
                            setting.onSuccess(data)
                            modal.modal('hide');
                        },
                        failed: function (message) {
                            setting.onError(message);
                        }
                    });
                }
            },
        })
	}

    var addMemberDialog = function(option){
		var setting = $.extend(true,{
            id: 0,
            onShow: function(){},
            onSuccess:function(data){},
            onError:function(message){Application.errorNotification(message, false, 'topCenter');}
		},option);

        var content = [
            '<div class="w-100">',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Member Account</label>',
                    '<select class="form-control select-account" data-placeholder="Select Member Account ...">',
                    '</select>',
                '</div>',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Member Role</label>',
                    '<select class="form-control select-role" data-placeholder="Member Role ...">',
                        '<option></option>',
                        '<option value="contributor">Contributor</option>',
                        '<option value="guest">Guest</option>',
                        '<option value="admin">Admin</option>',
                    '</select>',
                '</div>',
            '</div>',
        ].join('');

        Application.modalDialog({
            title: 'Add Workspace Member',
            iconTitle: 'far fa-user mr-1',
            backdropStatic: true,
            closeOnEscape: false,
            class: 'modal-md rounded-round',
            body: {
                style: 'min-height:300px',
                class: 'd-flex align-items-center p-4',
            },
            content: content,
            onShow: function(modal){

                modal.find('.select-role').select2();

                Application.select2RemoteSearch({
                    element: modal.find('.select-account'),
                    url: 'data/accountSearch',
                    placeholder: 'Select Member Account ...',
                    minimumInputLength: 2,
                    templateResult: function (result){
                        if (result.loading) return "Searching ...";

                        var markup = '<div class="select2-result-repository d-flex align-itemx-center">' +
                                '<div class="mr-2"><img class="rounded-circle" src="'+result.data.accountUrl+'/50/50"></div>'+
                                '<div>'+
                                    '<div class="select2-result-repository__title font-weight-bold">' + result.data.name + '</div>' +
                                    '<div class="select2-result-repository__description">' + result.data.email + '</div>' +
                                '</div>'+
                            '</div>';

                        return markup;
                    }
                });

                modal.find(".select-account").focus();
            },
            okButton: {
                class: "btn-success",
                label: '<i class="icon-checkmark2"></i> Save',
                callback: function (modal){
                    let selectAccount = modal.find('.select-account');
                    let selectRole = modal.find('.select-role');
                    let accountId = selectAccount.val();
                    let role = selectRole.val();

                    if (Application.isEmpty(accountId)){
                        selectAccount.focus();
                        return;
                    }

                    if (Application.isEmpty(role)){
                        selectRole.focus();
                        return;
                    }

                    Application.post({
                        container: modal,
                        url: 'workspace/addMember',
                        data: {id: setting.id, id_account: accountId, role: role},
                        success: function (data) {
                            setting.onSuccess(data)
                            modal.modal('hide');
                        },
                        failed: function (message) {
                            setting.onError(message);
                        }
                    });
                }
            },
        })
	}

    return {
        add: function (options){
            addDialog(options);
        },
        update: function (id, name, description, onSuccess, onError){
            updateDialog({
                id: id,
                name: name,
                description: description,
                onSuccess: onSuccess,
                onFailed: onError
            });
        },
        delete: function (id, onSuccess, onError){
            runAction('workspace/delete', {
                id:id
            }, onSuccess, onError);
        },
        addMember: function (id, onSuccess, onError){
            addMemberDialog({
                id: id,
                onSuccess: onSuccess,
                onFailed: onError
            });
        },
        removeMember: function (id, accountId, onSuccess, onError){
            runAction('workspace/deleteMember', {
                id:id, id_account: accountId
            }, onSuccess, onError);
        },
        roleMember: function (id, accountId, role, onSuccess, onError){
            runAction('workspace/roleMember', {
                id:id, id_account: accountId, role: role
            }, onSuccess, onError);
        },
    }
}();
var Project = function () {

    var runAction = function (url, data, onSuccess, onFailed){
		Application.post({
			container: '.content',
			url: url,
			data: data,
			useAlert: false,
			useBlockUI: false,
			success: onSuccess,
			failed: onFailed
		});
	}

    var addDialog = function(option){
		var setting = $.extend(true,{
            onShow: function(){},
            onSuccess:function(data){},
            onError:function(message){Application.errorNotification(message, false, 'topCenter');}
		},option);

        var content = [
            '<div class="w-100">',
                '<div class="form-group">',
                    '<label class="h3 mb-1">Create New Project</label>',
                    '<select required class="form-control select-workspace" data-placeholder="Select Workspace ...">',
                    '</select>',
                '</div>',
                '<div class="form-group">',
                    '<input required autocomplete="off" class="form-control p-name" type="text" required placeholder="Project Name"></input>',
                '</div>',
                '<div class="form-group">',
                    '<input autocomplete="off" class="form-control p-desc" type="text" placeholder="Description"></input>',
                '</div>',
            '</div>',
        ].join('');

        Application.modalDialog({
            title: 'New Project',
            iconTitle: 'icon-cube2 mr-1',
            backdropStatic: true,
            class: 'modal-md rounded-round',
            body: {
                style: 'min-height:300px',
                class: 'd-flex align-items-center p-4',
            },
            content: content,
            onShow: function(modal){

                Application.select2Remote({
                    element: modal.find('.select-workspace'),
                    url: 'workspace/list',
                    callback: function (){}
                });

                modal.find(".select-workspace").focus();
                modal.find("input").on('keydown',function(e){
                    if (e.keyCode == 13) modal.find('button.ok').click();
                })
            },
            okButton: {
                class: "btn-success",
                label: '<i class="icon-checkmark2"></i> Save',
                callback: function (modal){
                    let inputWorkspace = modal.find('.select-workspace');
                    let inputName = modal.find('input.p-name');
                    let inputDesc = modal.find('input.p-desc');
                    let name = inputName.val();
                    let workspace = inputWorkspace.val();

                    if (Application.isEmpty(workspace)){
                        inputWorkspace.focus();
                        return;
                    }

                    if (Application.isEmpty(name)){
                        inputName.focus();
                        return;
                    }

                    Application.post({
                        container: modal,
                        url: 'project/create',
                        data: {id_workspace: workspace, name: name, description: inputDesc.val()},
                        success: function (data) {
                            setting.onSuccess(data)
                            modal.modal('hide');
                        },
                        failed: function (message) {
                            setting.onError(message);
                        }
                    });
                }
            },
        })
	}

    var updateDialog = function(option){
		var setting = $.extend(true,{
            id: 0,
            name: '',
            description: '',
            onShow: function(){},
            onSuccess:function(data){},
            onError:function(message){Application.errorNotification(message, false, 'topCenter');}
		},option);

        var content = [
            '<div class="w-100">',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Project Name</label>',
                    '<input required autocomplete="off" class="form-control f-name" type="text" required placeholder="Project Name" value="',setting.name,'"></input>',
                '</div>',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Description</label>',
                    '<input autocomplete="off" class="form-control f-desc" type="text" placeholder="Description" value="',setting.description,'"></input>',
                '</div>',
            '</div>',
        ].join('');

        Application.modalDialog({
            title: 'Update Project Profile',
            iconTitle: 'icon-cube2 mr-1',
            backdropStatic: true,
            class: 'modal-md rounded-round',
            body: {
                style: 'min-height:300px',
                class: 'd-flex align-items-center p-4',
            },
            content: content,
            onShow: function(modal){
                modal.find("input.f-name").focus();
                modal.find("input").on('keydown',function(e){
                    if (e.keyCode == 13) modal.find('button.ok').click();
                })
            },
            okButton: {
                class: "btn-success",
                label: '<i class="icon-checkmark2"></i> Save',
                callback: function (modal){
                    let inputName = modal.find('input.f-name');
                    let inputDesc = modal.find('input.f-desc');
                    let name = inputName.val();

                    if (Application.isEmpty(name)){
                        inputName.focus();
                        return;
                    }

                    Application.post({
                        container: modal,
                        url: 'project/update',
                        data: {id: setting.id, name: name, description: inputDesc.val()},
                        success: function (data) {
                            setting.onSuccess(data)
                            modal.modal('hide');
                        },
                        failed: function (message) {
                            setting.onError(message);
                        }
                    });
                }
            },
        })
	}

    var updateSlugDialog = function(option){
		var setting = $.extend(true,{
            id: 0,
            slug: '',
            onShow: function(){},
            onSuccess:function(data){},
            onError:function(message){Application.errorNotification(message, false, 'topCenter');}
		},option);

        var content = [
            '<div class="w-100">',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Url Slug</label>',
                    '<input required autocomplete="off" class="form-control f-name" type="text" required placeholder="Project Url" value="',setting.slug,'"></input>',
                    '<div class="mt-1">',Application.baseUrl(),'task/<span class="f-preview font-weight-bold">',setting.slug,'</span></div>',
                '</div>',
            '</div>',
        ].join('');

        Application.modalDialog({
            title: 'Update Project Url',
            iconTitle: 'icon-cube2 mr-1',
            backdropStatic: true,
            class: 'modal-md rounded-round',
            body: {
                style: 'min-height:300px',
                class: 'd-flex align-items-center p-4',
            },
            content: content,
            onShow: function(modal){
                var input = modal.find("input.f-name");
                input.focus();
                input.on('keydown',function(e){
                    if (e.keyCode == 13) modal.find('button.ok').click();
                })
                input.on('keyup',function(e){
                    modal.find(".f-preview").text(Application.slugify(this.value));
                })
                input.on('change',function(e){
                    this.value = Application.slugify(this.value);
                })
            },
            okButton: {
                class: "btn-success",
                label: '<i class="icon-checkmark2"></i> Save',
                callback: function (modal){
                    let inputName = modal.find('input.f-name');
                    let name = inputName.val();

                    if (Application.isEmpty(name)){
                        inputName.focus();
                        return;
                    }

                    Application.post({
                        container: modal,
                        url: 'project/slug',
                        data: {id: setting.id, slug: name},
                        success: function (data) {
                            setting.onSuccess(data)
                            modal.modal('hide');
                        },
                        failed: function (message) {
                            setting.onError(message);
                        }
                    });
                }
            },
        })
	}

    var addMemberDialog = function(option){
		var setting = $.extend(true,{
            id: 0,
            onShow: function(){},
            onSuccess:function(data){},
            onError:function(message){Application.errorNotification(message, false, 'topCenter');}
		},option);

        var content = [
            '<div class="w-100">',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Member Account</label>',
                    '<select class="form-control select-account" data-placeholder="Select Member Account ...">',
                    '</select>',
                '</div>',
                '<div class="form-group">',
                    '<label class="col-form-label font-weight-bold">Member Role</label>',
                    '<select class="form-control select-role" data-placeholder="Member Role ...">',
                        '<option></option>',
                        '<option value="contributor">Contributor</option>',
                        '<option value="guest">Guest</option>',
                        '<option value="admin">Admin</option>',
                    '</select>',
                '</div>',
            '</div>',
        ].join('');

        Application.modalDialog({
            title: 'Add Project Member',
            iconTitle: 'far fa-user mr-1',
            backdropStatic: true,
            closeOnEscape: false,
            class: 'modal-md rounded-round',
            body: {
                style: 'min-height:300px',
                class: 'd-flex align-items-center p-4',
            },
            content: content,
            onShow: function(modal){

                modal.find('.select-role').select2();

                Application.select2RemoteSearch({
                    element: modal.find('.select-account'),
                    url: 'data/accountSearch',
                    placeholder: 'Select Member Account ...',
                    minimumInputLength: 2,
                    templateResult: function (result){
                        if (result.loading) return "Searching ...";

                        var markup = '<div class="select2-result-repository d-flex align-itemx-center">' +
                                '<div class="mr-2"><img class="rounded-circle" src="'+result.data.accountUrl+'/50/50"></div>'+
                                '<div>'+
                                    '<div class="select2-result-repository__title font-weight-bold">' + result.data.name + '</div>' +
                                    '<div class="select2-result-repository__description">' + result.data.email + '</div>' +
                                '</div>'+
                            '</div>';

                        return markup;
                    }
                });

                /* Application.select2Remote({
                    element: modal.find('.select-account'),
                    url: 'data/account',
                    callback: function (){}
                }); */

                modal.find(".select-account").focus();
            },
            okButton: {
                class: "btn-success",
                label: '<i class="icon-checkmark2"></i> Save',
                callback: function (modal){
                    let selectAccount = modal.find('.select-account');
                    let selectRole = modal.find('.select-role');
                    let accountId = selectAccount.val();
                    let role = selectRole.val();

                    if (Application.isEmpty(accountId)){
                        selectAccount.focus();
                        return;
                    }

                    if (Application.isEmpty(role)){
                        selectRole.focus();
                        return;
                    }

                    Application.post({
                        container: modal,
                        url: 'project/addMember',
                        data: {id: setting.id, id_account: accountId, role: role},
                        success: function (data) {
                            setting.onSuccess(data)
                            modal.modal('hide');
                        },
                        failed: function (message) {
                            setting.onError(message);
                        }
                    });
                }
            },
        })
	}

    return {
        add: function (options){
            addDialog(options);
        },
        update: function (id, name, description, onSuccess, onError){
            updateDialog({
                id: id,
                name: name,
                description: description,
                onSuccess: onSuccess,
                onFailed: onError
            });
        },
        tagList: function (id, onSuccess, onError){
            runAction('project/tagList', {
                id:id
            }, onSuccess, onError);
        },
        slug: function (id, slug, onSuccess, onError){
            updateSlugDialog({
                id: id,
                slug: slug,
                onSuccess: onSuccess,
                onFailed: onError
            });
        },
        tagList: function (id, onSuccess, onError){
            runAction('project/tags', {
                id:id
            }, onSuccess, onError);
        },
        delete: function (id, onSuccess, onError){
            runAction('project/delete', {
                id:id
            }, onSuccess, onError);
        },
        memberList: function (id, onSuccess, onError){
            runAction('project/members', {
                id:id
            }, onSuccess, onError);
        },
        addMember: function (id, onSuccess, onError){
            addMemberDialog({
                id: id,
                onSuccess: onSuccess,
                onFailed: onError
            });
        },
        removeMember: function (id, accountId, onSuccess, onError){
            runAction('project/deleteMember', {
                id:id, id_account: accountId
            }, onSuccess, onError);
        },
        roleMember: function (id, accountId, role, onSuccess, onError){
            runAction('project/roleMember', {
                id:id, id_account: accountId, role: role
            }, onSuccess, onError);
        },
    }
}();
var Section = function () {

	var runAction = function (url, data, onSuccess, onFailed){
		Application.post({
			container: '.content',
			url: url,
			data: data,
			useAlert: false,
			useBlockUI: false,
			success: onSuccess,
			failed: onFailed
		});
	}

    return {
        add: function (data, onSucccess, onError){
            runAction('section/create', data, onSucccess, onError);
        },
        update: function (id, name, onSucccess, onError){
            runAction('section/update', {
                id: id,
                name: name
            }, onSucccess, onError);
        },
        delete: function (id, onSucccess, onError){
            runAction('section/delete', {
                id:id
            }, onSucccess, onError);
        },
    }
}();
var Task = function () {

    var selectAssignee = function (options){
        var me = this;
        var setting = $.extend(true, {
            type: 'file',
            items: [],
            onSuccess: function(result){},
            onError: function(error){}
        },options);

        var selectedTag;

        var _jenjang = {
            'SD' : "Sekolah Dasar",
            'SMP': "Sekolah Menengah Pertama",
            'SMA': "Sekolah Menengah Atas",
        }

        var _template = [
            '<div class="z-dialog modal">',
                '<div class="modal-dialog modal-sm modal-dialog-centered">',
                    '<div class="modal-content">',
                        '<div class="modal-header ">',
                            '<h5 class="modal-title font-weight-bold">Tag</h5>',
                            '<button type="button" class="close" data-dismiss="modal">&times;</button>',
                        '</div>',

                        '<div class="modal-body p-0" style="min-height:200px;">',
                            '<div class="tag-list"></div>',
                            '<div class="tag-empty d-none h-100 justify-content-center align-items-center"><div class="text-muted">Tag tidak ditemukan</div></div>',
                            '<div class="tag-select d-none p-3">',
                                '<div class="form-group mb-1 id-yayasan id-sekolah id-kelas id-unit id-siswa id-staff">',
                                    '<select class="form-control id-yayasan select-tag select2 select-yayasan" data-placeholder="Yayasan ..." data-allow-clear="true">',
                                        '<option></option>',
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-sekolah id-kelas id-siswa">',
                                    '<select class="form-control id-sekolah select-tag select2 select-sekolah" data-placeholder="Sekolah ..." data-allow-clear="true">',
                                        /* '<option></option>', */
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-kelas">',
                                    '<select class="form-control select2 select-ta" data-placeholder="Tahun Ajaran ..." data-allow-clear="true">',
                                        '<option></option>',
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-kelas">',
                                    '<select multiple="multiple" class="form-control id-kelas select-tag select2 select-kelas" data-placeholder="Kelas ..." data-allow-clear="true">',
                                        /* '<option></option>', */
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-jenjang">',
                                    '<select multiple="multiple" class="form-control id-jenjang select-tag select2 select-jenjang" data-placeholder="Jenjang Sekolah ..." data-allow-clear="true">',
                                        /* '<option></option>', */
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-tingkat">',
                                    '<select multiple="multiple" class="form-control id-tingkat select-tag select2 select-tingkat" data-placeholder="Tingkat Kelas ..." data-allow-clear="true">',
                                        /* '<option></option>', */
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-unit">',
                                    '<select class="form-control id-unit select-tag select2 select-unit" data-placeholder="Nama Unit ..." data-allow-clear="true">',
                                        /* '<option></option>', */
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-siswa">',
                                    '<select class="form-control id-siswa select-tag select2 select-siswa" data-placeholder="Nama Siswa ..." data-allow-clear="true">',
                                        /* '<option></option>', */
                                    '</select>',
                                '</div>',
                                '<div class="form-group mb-1 id-staff">',
                                    '<select class="form-control id-staff select-tag select2 select-staff" data-placeholder="Nama Pegawai ..." data-allow-clear="true">',
                                        /* '<option></option>', */
                                    '</select>',
                                '</div>',

                                '<div class="form-group mt-1  id-yayasan  id-sekolah id-kelas id-jenjang id-tingkat id-unit id-siswa id-staff ">',
                                    '<div class="small text-muted"><span class="nama-tag font-weight-bold"></span> bisa dipilih lebih dari satu</div>',
                                '</div>',
                            '</div>',
                        '</div>',

                        '<div class="modal-footer">',
                            '<button type="button" disabled class="btn btn-action btn-save btn-sm btn-success">Set Tag</button>',
                        '</div>',
                    '</div>',
                '</div>',
            '</div>'
        ].join('');

        var elTemplate = $(_template);
        elTemplate.find('.tooltips').tooltip({trigger: 'hover'});

        var viewTagList = elTemplate.find('.tag-list');
        var viewTagSelect = elTemplate.find('.tag-select');

        var viewMode = 'list';

        function _showViewContent (viewName){
            elTemplate.find('.modal-body > div').addClass('d-none').removeClass('d-flex');
            var view = elTemplate.find('.modal-body > div.tag-'+viewName);
            view.removeClass('d-none');
            if (viewName == 'empty') view.addClass('d-flex');

            viewMode = viewName;
        }

        function _showSelectContent (slug){
            viewTagSelect.find('.select2').val(null).trigger('change.select2');
            viewTagSelect.find('.form-group').hide();
            viewTagSelect.find('.form-group.'+slug).show();
            _saveButton(false);
        }

        function _drawTagItem (tag){
            var html = [
                '<div class="d-flex pl-2 pr-2 align-items-center cursor-pointer dialog-list-item border-bottom" data-name="'+tag.name+'" data-slug="'+tag.slug+'" data-id="'+tag.id+'">',
                    '<div class="flex-grow-1 p-2">',
                        '<i class="icon-price-tag3 mr-1"></i>',tag.name,
                    '</div>',
                    '<div class="flex-shrink-1">',
                        '<i class="icon-arrow-right13"></i>',
                    '</div>',
                '</div>'
            ].join('');

            return html;
        }

        function _initList(){
            var selJenjang = viewTagSelect.find('.select-jenjang');
            $.each(_jenjang, function(id, jenjang){
                selJenjang.append(new Option(jenjang, id));
            })
            var selTingkat = viewTagSelect.find('.select-tingkat');
            for (let index = 1; index <= 12; index++) {
                selTingkat.append(new Option("Kelas "+index, index));
            }
            var selTA = viewTagSelect.find('.select-ta');
            const d = new Date();
            let year = parseInt(d.getFullYear());

            for (let index = year; index >= 2000; index--) {
                let ta = String(index)+"/"+String(index+1);
                let id = "TA_"+String(index)+"-"+String(index+1);
                selTA.append(new Option("Tahun Ajaran "+ta, id));
            }

            viewTagSelect.find('.select2').select2({language: "id"});
        }

        function _selectYayasan (multiple){
            Application.select2Remote({
                url: 'data/yayasan',
                element: viewTagSelect.find('.select-yayasan'),
                select: {
                    language: "id",
                    placeholder: "Pilih Yayasan ...",
                    allowClear: false,
                    multiple: multiple
                },
                callback:function(){
                    viewTagSelect.find('.select-yayasan').val(null).change();
                }
            })
        }

        function _getTagList (){
            Application.post({
                container: elTemplate,
                url: 'tag/list',
                useAlert: false,
                success: function (data) {
                    if (data.list.length > 0){
                        $.each(data.list, function(i, tag){
                            viewTagList.append(
                                _drawTagItem(tag)
                            );
                        })
                    }else _showViewContent('empty');
                },
                failed: function (message) {
                    _showViewContent('empty');
                }
            });
        }

        function _resetLayout(){
            _showViewContent('list');

            elTemplate.find('.modal-footer button.btn-action').prop('disabled', false);
            elTemplate.find('.modal-title').html('Tag');
            _saveButton();
        }

        function _saveButton(enabled){
            let viewSelectModa = enabled == undefined ? (viewMode == 'select') : enabled;
            elTemplate.find('button.btn-save').prop('disabled', !viewSelectModa);
        }



        elTemplate.find('.tooltips').on('click', function(e){
            $(this).tooltip('hide');
        })

        viewTagSelect.on('change', '.select-yayasan', function(e){
            var me = $(this);
            var id_yayasan = me.val();

            if (['id-sekolah','id-kelas','id-siswa'].includes(selectedTag.slug)){
                Application.select2Remote({
                    url: 'data/sekolah',
                    query:{id_yayasan : id_yayasan},
                    element: viewTagSelect.find('.select-sekolah'),
                    select: {
                        language: "id",
                        placeholder: "Pilih Sekolah ...",
                        multiple: selectedTag.slug == 'id-sekolah'
                    },
                })
            }else if (['id-unit'].includes(selectedTag.slug)){
                Application.select2Remote({
                    url: 'data/unit',
                    query:{id_yayasan : id_yayasan},
                    element: viewTagSelect.find('.select-unit'),
                    select: {
                        language: "id",
                        placeholder: "Pilih Unit ...",
                        multiple: true
                    },
                })
            }else if (['id-staff'].includes(selectedTag.slug)){
                Application.select2RemoteSearch({
                    url: 'data/staffSearch',
                    query:{id_yayasan : id_yayasan},
                    element: viewTagSelect.find('.select-staff'),
                    placeholder: "Pilih Staff Pegawai ...",
                    language: "id",
                    multiple: true,
                    templateResult: function (result){
                        if (result.loading) return "Mencari ...";

                        var markup = '<div class="select2-result-repository">' +
                                '<div class="select2-result-repository__title">' + result.data.nama + '</div>' +
                                '<div class="select2-result-repository__description">' + result.data.jabatan + " - "+result.data.nama_unit + '</div>' +
                            '</div>';

                        return markup;
                    },
                })
            }
        })

        viewTagSelect.on('change', '.select-ta', function(e){
            var me = $(this);
            var id_sekolah =viewTagSelect.find('.select-sekolah').val();
            var id_ta = me.val();

            Application.select2Remote({
                url: 'data/kelas',
                query:{id_sekolah:id_sekolah, id_ta : id_ta},
                element: viewTagSelect.find('.select-kelas'),
                select: {
                    language: "id",
                    placeholder: "Pilih Kelas ...",
                    multiple: true
                },
            })
        })

        viewTagSelect.on('change', '.select-sekolah', function(e){
            var me = $(this);
            var id_sekolah = me.val();

            if (['id-kelas'].includes(selectedTag.slug)){
                var id_ta = viewTagSelect.find('.select-ta').val();
                Application.select2Remote({
                    url: 'data/kelas',
                    query:{id_sekolah:id_sekolah, id_ta : id_ta},
                    element: viewTagSelect.find('.select-kelas'),
                    select: {
                        language: "id",
                        placeholder: "Pilih Kelas ...",
                        multiple: true
                    },
                })
            }else if (['id-siswa'].includes(selectedTag.slug)){
                Application.select2RemoteSearch({
                    url: 'data/siswaSearch',
                    query:{id_sekolah:id_sekolah},
                    element: viewTagSelect.find('.select-siswa'),
                    placeholder: "Pilih Siswa ...",
                    language: "id",
                    multiple: true,
                    templateResult: function (result){
                        if (result.loading) return "Mencari ...";

                        var markup = '<div class="select2-result-repository">' +
                                '<div class="select2-result-repository__title">' + result.data.nama + '</div>' +
                                '<div class="select2-result-repository__description">' + result.data.nama_kelas + '</div>' +
                            '</div>';

                        return markup;
                    },
                })
            }
        })

        viewTagSelect.on('change', '.select-tag', function(e){
            var me = $(this);
            var hasClass = me.hasClass(selectedTag.slug);
            var state = false;

            if (!hasClass) return;

            var hasValue = !Application.isEmpty(me.val());

            switch (selectedTag.slug){
                case 'id-yayasan':
                    state = hasValue && hasClass;
                    break;
                case 'id-sekolah':
                    state = hasValue && hasClass;
                    break;
                case 'id-kelas':
                    state = hasValue && hasClass;
                    break;
                case 'id-jenjang':
                    state = hasValue && hasClass;
                    break;
                case 'id-tingkat':
                    state = hasValue && hasClass;
                    break;
                case 'id-siswa':
                    state = hasValue && hasClass;
                    break;
                case 'id-unit':
                    state = hasValue && hasClass;
                    break;
                case 'id-staff':
                    state = hasValue && hasClass;
                    break;
            }
            _saveButton(state);
        })

        elTemplate.on('click', '.btn-back', function(e){
            selectedTag = undefined;
            $.each(['select-sekolah','select-unit','select-kelas'], function(i, id){
                let sel = viewTagSelect.find('select.'+id);
                Application.clearSelect(sel);
                sel.select2({language: "id"});
            })
            _resetLayout();
        })

        elTemplate.on('click', '.dialog-list-item', function(e){
            var data = $(this).data();
            selectedTag = data;
            _saveButton(true);
            elTemplate.find('.modal-title').html('<button class="btn-icon btn btn-back btn-sm rounded"><i class="icon-arrow-left12"></i></button> Tag '+data.name+'');
            elTemplate.find('.nama-tag').text(data.name);
            _selectYayasan(selectedTag.slug == 'id-yayasan');

            _showViewContent('select');
            _showSelectContent(data.slug);
        })

        elTemplate.find('.btn-save').on('click', function(e){
            var selectValue = viewTagSelect.find('select.'+selectedTag.slug);
            var value = selectValue.val();

            if (!Array.isArray(value)){
                if (Application.isEmpty(value)){
                    selectValue.focus();
                    return;
                }
                value = [value];
            }

            if (value.length == 0) {
                selectValue.focus();
                return;
            }

            Application.post({
                container: elTemplate,
                url: 'tag/set',
                data: {type: setting.type, tag: selectedTag.id, values: value, items:setting.items},
                useAlert: false,
                success: function (result) {
                    setting.onSuccess(result);
                    elTemplate.modal('hide');
                },
                failed: function (message) {
                    setting.onError(message);
                    Application.errorNotification(message);
                }
            });

        })

        elTemplate.one('hide.bs.modal', function (){
            elTemplate.remove();
        });

        _getTagList();
        _initList();

        elTemplate.modal();
    };


	var runAction = function (url, data, onSuccess, onFailed){
		Application.post({
			container: '.content',
			url: url,
			data: data,
			useAlert: false,
			useBlockUI: false,
			success: onSuccess,
			failed: onFailed
		});
	}

    return {
        add: function (data, onSucccess, onError){
            runAction('task/create', data, onSucccess, onError);
        },
        update: function (id, name, onSucccess, onError){
            runAction('task/update', {
                id: id, field: 'name', value: name
            }, onSucccess, onError);
        },
        delete: function (id, onSucccess, onError){
            runAction('task/delete', {
                id:id
            }, onSucccess, onError);
        },
        priority: function (id, priority, onSucccess, onError){
            runAction('task/priority', {
                id: id, priority: priority
            }, onSucccess, onError);
        },
        status: function (id,status, onSucccess, onError){
            runAction('task/status', {
                id: id, status: status
            }, onSucccess, onError);
        },
        dueDate: function (id, duedate, onSucccess, onError){
            runAction('task/dueDate', {
                id: id, due_date: duedate
            }, onSucccess, onError);
        },
        description: function (id, description, onSucccess, onError){
            runAction('task/update', {
                id: id, field :'description', value: description
            }, onSucccess, onError);
        },
        detail: function (id, onSucccess, onError){
            runAction('task/detail', {
                id: id
            }, onSucccess, onError);
        },
        assignee: function (id, id_account, onSucccess, onError){
            runAction('task/assignment', {
                id: id, id_account: id_account
            }, onSucccess, onError);
        },
        comments: function (id, onSucccess, onError){
            runAction('task/comments', {
                id:id,
            }, onSucccess, onError);
        },
        addComment: function (id, comment, onSucccess, onError){
            runAction('task/addComment', {
                id:id, comment: comment
            }, onSucccess, onError);
        },
        deleteComment: function (id, onSucccess, onError){
            runAction('task/deleteComment', {
                id:id
            }, onSucccess, onError);
        },
        removeFile: function (id, onSucccess, onError){
            runAction('file/remove', {
                id:id
            }, onSucccess, onError);
        },
    }
}();
var TaskHome = function () {
    var handleEvents = function (){
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();
var MyTask = function () {
    var TaskManager;
    var TaskHeader = $(".page-content-header");

    var onHashChange = function (){

        /* $(window).on('hashchange', function(){
            var _id = location.hash.substring(1);

            if (_id){
                TaskManager.openTaskDetail();
            }else{
                TaskManager.closeTaskDetail();
            }

        });

        $(window).hashchange(); */

        window.addEventListener('popstate', (event) => {
            let params = event.state;
            ///console.log("location: " + document.location + ", state: " + JSON.stringify(event.state));
            //console.log(params);
            if (params.taskId){
                let task = TaskManager.selectTask(params.taskId);
                task.openTaskDetail();
            }
        });
    }

    var handleEvents = function (){
        var params = $('params').data();
        TaskManager = $("#task-manager").TaskManager ({
            onTaskOpened: function (e, taskItem){
                document.title = taskItem.getTaskName() + ' | ' + Application.title()
                const urlParams = new URLSearchParams(window.location.search);
                let _query = urlParams.toString();

                window.history.replaceState({
                    taskId: taskItem._data.id
                }, null, 'mytask/'+taskItem._data.id+ (_query ? '?'+_query : ''));
            },
            onTaskClosed: function(e, taskItem){
                document.title = 'My Tasks | ' + Application.title()
                const urlParams = new URLSearchParams(window.location.search);
                let _query = urlParams.toString();

                window.history.replaceState({
                    taskId: undefined
                }, null, 'mytask/'+ (_query ? '?'+_query : ''));

            }

        });

        if (params.taskId){
            let task = TaskManager.selectTask(params.taskId);
            task.openTaskDetail();
        }

        onHashChange();
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();
var TaskProject = function () {
    var TaskManager;
    var TaskHeader = $(".page-content-header");

    var onHashChange = function (){

        /* $(window).on('hashchange', function(){
            var _id = location.hash.substring(1);

            if (_id){
                TaskManager.openTaskDetail();
            }else{
                TaskManager.closeTaskDetail();
            }

        });

        $(window).hashchange(); */

        window.addEventListener('popstate', (event) => {
            let params = event.state;
            ///console.log("location: " + document.location + ", state: " + JSON.stringify(event.state));
            //console.log(params);
            if (params.taskId){
                let task = TaskManager.selectTask(params.taskId);
                task.openTaskDetail();
            }
        });
    }

    var handleEvents = function (){
        var params = $('params').data();

        TaskManager = $("#task-manager").TaskManager ({
            onTaskOpened: function (e, taskItem){
                document.title = taskItem.getTaskName() + ' | ' + Application.title()
                const urlParams = new URLSearchParams(window.location.search);
                let _query = urlParams.toString();

                window.history.replaceState({
                    taskId: taskItem._data.id
                }, null, 'task/'+TaskManager._data.slug+'/'+taskItem._data.id+ (_query ? '?'+_query : ''));
            },
            onTaskClosed: function(e, taskItem){
                document.title = TaskManager._data.name + ' | ' + Application.title()
                const urlParams = new URLSearchParams(window.location.search);
                let _query = urlParams.toString();

                window.history.replaceState({
                    taskId: undefined
                }, null, 'task/'+TaskManager._data.slug+ (_query ? '?'+_query : ''));

            }

        });

        if (params.taskId){
            let task = TaskManager.selectTask(params.taskId);
            task.openTaskDetail();
        }

        onHashChange();
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();
var WorkspaceSetting = function () {
    var workspaceId = $('params').data('id');
    var elSetting = $('#workspace-setting');

    var drawItemMember = function (data){
        var _itemMember = TaskManager_Templates.memberItem
            .replace('@ACCOUNT_ID@', data.id)
            .replace('@ACCOUNT_NAME@', data.name)
            .replace('@ACCOUNT_AVATAR@', data.accountUrl)
            .replace('@ROLE@', data.role);

        elSetting.find('.member-list').append(_itemMember);
    }

    var handleEvents = function (){

        $('.btn-edit-profile').on('click', function(e){
            e.preventDefault();

            Workspace.update(
                workspaceId,
                elSetting.find('.f-name').text(),
                elSetting.find('.f-description').text(),
                function (data){
                    Application.successNotification("Workspace profile has been saved.");
                    Application.fillDataValue(elSetting, data);
                },
                function (error){
                    Application.errorNotification(error,false, 'topCenter');
                }
            )
        })

        elSetting.on('click', '.btn-delete-member', function(e){
            e.preventDefault();
            let memberItem = $(this).closest('.item-member');
            let accountId = memberItem.data('id');

            Application.confirmDialog({
                title: 'Remove Confirmation',
                message: 'Remove Member from Workspace ?',
                label: {
                    yes: 'Remove',
                    no: 'Cancel'
                },
                callback: function (){
                    Workspace.removeMember(
                        workspaceId,
                        accountId,
                        function (data){
                            Application.successNotification("Workspace member has been removed.");
                            memberItem.remove();
                        },
                        function(error){
                            Application.errorNotification(error,false, 'topCenter');
                        }
                    );
                }
            })
        })

        $('.btn-add-member').on('click', function(e){
            e.preventDefault();

            Workspace.addMember(
                workspaceId,
                function (data){
                    Application.successNotification("Workspace member has been added.");
                    drawItemMember(data);
                },
                function (error){
                    Application.errorNotification(error,false, 'topCenter');
                }
            )
        })

        elSetting.on('click', '.btn-delete-workspace', function(e){
            e.preventDefault();

            Application.confirmDialog({
                title: 'Remove Workspace',
                message: 'By deleting Workspace, all projects, tasks and related datas will also be delete<br><br>Continue ?',
                label: {
                    yes: 'Delete Workspace',
                    no: 'Cancel'
                },
                buttonclass: {
                    yes: 'btn-danger',
                },
                callback: function (){
                    Workspace.delete(
                        workspaceId,
                        function (data){
                            Application.successNotification("Workspace has been deleted.");
                            location.href='';
                        },
                        function(error){
                            Application.errorNotification(error,false, 'topCenter');
                        }
                    );
                }
            })
        })

        elSetting.on('click', '.btn-role-member', function(e){
            e.preventDefault();
            let me = $(this);
            let memberItem = me.closest('.item-member');
            let accountId = memberItem.data('id');
            let role = me.data('role');

            Workspace.roleMember(
                workspaceId,
                accountId,
                role,
                function (data){
                    Application.successNotification("Member role has been saved.");
                    memberItem.find('.member-role').text(role);
                },
                function(error){
                    Application.errorNotification(error,false, 'topCenter');
                }
            );
        })

    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();
var ProjectSetting = function () {
    var projectId = $('params').data('id');
    var elSetting = $('#project-setting');

    var drawItemMember = function (data){
        var _itemMember = TaskManager_Templates.memberItem
            .replace('@ACCOUNT_ID@', data.id)
            .replace('@ACCOUNT_NAME@', data.name)
            .replace('@ACCOUNT_AVATAR@', data.accountUrl)
            .replace('@ROLE@', data.role);

        elSetting.find('.member-list').append(_itemMember);
    }

    var handleEvents = function (){
        $('.btn-edit-profile').on('click', function(e){
            e.preventDefault();

            Project.update(
                projectId,
                elSetting.find('.f-name').text(),
                elSetting.find('.f-description').text(),
                function (data){
                    Application.successNotification("Project profile has been saved.");
                    Application.fillDataValue(elSetting, data);
                },
                function (error){
                    Application.errorNotification(error,false, 'topCenter');
                }
            )
        })

        $('.btn-edit-url').on('click', function(e){
            e.preventDefault();

            Project.slug(
                projectId,
                elSetting.find('.f-slug').text(),
                function (data){
                    Application.successNotification("Project Url has been saved.");
                    elSetting.find('.f-slug').text(data.slug);
                },
                function (error){
                    Application.errorNotification(error,false, 'topCenter');
                }
            )
        })

        elSetting.on('click', '.btn-delete-member', function(e){
            e.preventDefault();
            let memberItem = $(this).closest('.item-member');
            let accountId = memberItem.data('id');

            Application.confirmDialog({
                title: 'Remove Member',
                message: 'Remove Member from Project ?',
                label: {
                    yes: 'Remove',
                    no: 'Cancel'
                },
                buttonclass: {
                    yes: 'btn-danger',
                },
                callback: function (){
                    Project.removeMember(
                        projectId,
                        accountId,
                        function (data){
                            Application.successNotification("Project member has been removed.");
                            memberItem.remove();
                        },
                        function(error){
                            Application.errorNotification(error,false, 'topCenter');
                        }
                    );
                }
            })
        })

        $('.btn-add-member').on('click', function(e){
            e.preventDefault();

            Project.addMember(
                projectId,
                function (data){
                    Application.successNotification("Project member has been added.");
                    drawItemMember(data);
                },
                function (error){
                    Application.errorNotification(error,false, 'topCenter');
                }
            )
        })

        elSetting.on('click', '.btn-delete-project', function(e){
            e.preventDefault();

            Application.confirmDialog({
                title: 'Remove Project',
                message: 'By deleting project, all tasks and related datas will also be delete<br><br>Continue ?',
                label: {
                    yes: 'Delete Project',
                    no: 'Cancel'
                },
                buttonclass: {
                    yes: 'btn-danger',
                },
                callback: function (){
                    Project.delete(
                        projectId,
                        function (data){
                            Application.successNotification("Project has been deleted.");
                            if (data.workspace) location.href='w/'+data.workspace.slug;
                            else location.href = '';
                        },
                        function(error){
                            Application.errorNotification(error,false, 'topCenter');
                        }
                    );
                }
            })
        })

        elSetting.on('click', '.btn-role-member', function(e){
            e.preventDefault();
            let me = $(this);
            let memberItem = me.closest('.item-member');
            let accountId = memberItem.data('id');
            let role = me.data('role');

            Project.roleMember(
                projectId,
                accountId,
                role,
                function (data){
                    Application.successNotification("Member role has been saved.");
                    memberItem.find('.member-role').text(role);
                },
                function(error){
                    Application.errorNotification(error,false, 'topCenter');
                }
            );
        })
    }

    return {
        init: function (){
            handleEvents();
        },
    }
}();