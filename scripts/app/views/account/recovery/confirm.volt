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
                    <h5 class="mb-0">Verifikasi</h5>
                    <div class="mb-2 text-muted">sebagai <b>{{account.name}}</b></div>
                    <span class="d-block text-muted m-bottom-20">Silakan masukkan Kode verifikasi yang dikirimkan ke alamat email <b>{{account.email}}</b></span>
                </div>

                <?php if ($this->flash->has()) { ?>
                {% for type, messages in flash.getMessages() %}
                    {% for message in messages %}
                        {%if type == 'error' %}
                        <div class="status-message cursor-pointer text-pink-700 mb-1 text-center">{{ message }}</div>
                        {%else%}
                        <div class="status-message cursor-pointer text-green-700 mb-1 text-center">{{ message }}</div>
                        {%endif%}
                    {% endfor %}
                {% endfor %}
                <?php } ?>

                <div class="form-group">
                    <input type="text" class="form-control text-center input-box" required name="kode" placeholder="Kode Verifikasi">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-danger btn-block btn-sm">Verifikasi <i class="icon-circle-right2 ml-2"></i></button>
                </div>

                <div class="">
                    <a href="recovery/forgot?{{query_params}}" class="btn-flat tooltips" title="Kembali ke halaman login">Kembali</a>
                    <a href="javascript:;" class="float-right btn-resend-code btn-flat tooltips" title="Kirim Ulang Kode Verifikasi">Kirim Ulang Kode</a>
                </div>

            </div>
        </div>

        <div class="text-center mt-1 footer">
            <strong>Copyright &copy; {{date('Y')}}</strong> All rights reserved.
        </div>
    </form>
    <!-- /login form -->

    <form id="resend-form" action="recovery/resendcode" method="POST" style="display: none;">
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