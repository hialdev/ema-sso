{%extends 'layouts/public.volt' %}

{%block page_content%}
<div class="content d-flex justify-content-center align-items-center">

    <!-- Login form -->
    <form class="login-form" method="POST">
        <input type="hidden" name="<?=$this->security->getTokenKey();?>" value="<?=$this->security->getToken();?>"/>
        <div class="card mb-0">
            <div class="card-body">

                <div class="text-center mb-1">
                    <div class="mb-1"><img class="logo-on-card" src="assets/images/logo.png" ></div>
                    <h5 class="mb-3 font-weight-bold">{{application.name}} </h5>
                    <h5 class="mb-0">Verifikasi Login</h5>
                    <div class="mb-2 text-muted">sebagai <b>{{account.name}}</b></div>
                    {%if account.otp_media != 'oauth'%}
                    <span class="d-block text-muted m-bottom-20">Silakan masukkan Kode verifikasi yang dikirimkan ke {{otp_to}}</span>
                    {%else%}
                    <span class="d-block text-muted m-bottom-20">Silakan masukkan Kode verifikasi dari <br><b>Aplikasi Authenticator</b> anda</span>
                    {%endif%}
                </div>

                <?php if ($this->flash->has()) { ?>
                {% for type, messages in flash.getMessages() %}
                    {% for message in messages %}
                        {%if type == 'error' %}
                        <div class="status-message cursor-pointer text-pink-700 mb-1 text-center">{{ message }}</div>
                        <!-- <div class="alert alert-danger border-0 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ message }}
                        </div> -->
                        {%else%}
                        <div class="status-message cursor-pointer text-green-700 mb-1 text-center">{{ message }}</div>
                        {%endif%}
                    {% endfor %}
                {% endfor %}
                <?php } ?>

                <!-- <div class="form-group form-group-feedback form-group-feedback-left"></div> -->
                <div class="form-group">
                    <input type="text" class="form-control text-center input-box" required name="otp_code" placeholder="Kode Verifikasi">
                    <!-- <div class="form-control-feedback">
                        <i class="icon-price-tag3 text-muted"></i>
                    </div> -->
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-danger btn-block btn-sm">Verifikasi dan Login <i class="icon-circle-right2 ml-2"></i></button>
                </div>

                <div class="">
                    {%if is_login%}
                    <a href="auth/logout" class="btn-flat tooltips" title="Logout Account">Logout</a>
                    {%else%}
                    <a href="{{login_url}}" class="btn-flat tooltips" title="Kembali ke halaman login">Kembali</a>
                    {%endif%}
                    {%if account.otp_media != 'oauth'%}
                    <a href="javascript:;" class="float-right btn-resend-code btn-flat tooltips" title="Kirim Ulang Kode Verifikasi">Kirim Ulang Kode</a>
                    {%endif%}
                </div>

            </div>
        </div>

        <div class="text-center mt-2 footer">
            <strong>Copyright &copy; 2020</strong> All rights reserved.
        </div>
    </form>
    <!-- /login form -->

    <form id="resend-form" action="auth/resendcode" method="POST" style="display: none;">
        <input type="hidden" name="<?=$this->security->getTokenKey();?>" value="<?=$this->security->getToken();?>"/>
    </form>

</div>
{%endblock%}

{%block page_scripts%}
<script type="text/javascript">
    $(document).ready(function () {
        $(".btn-resend-code").on('click', function(e){
            $("#resend-form").submit();
        })
        $(".status-message").on('click', function(e){
            $(this).remove();
        })
        setTimeout(function(){
            $('.status-message').remove();
        }, 5000);
    });
</script>
{%endblock%}