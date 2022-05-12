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