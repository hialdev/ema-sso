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
                    <h5 class="mb-3">Pemulihan Password Berhasil</h5>
                    <span class="d-block text-muted m-bottom-20">Selamat, password anda telah berhasil diubah. Silakan login untuk masuk ke aplikasi</span>
                </div>

                <div class="text-center">
                    <a href="auth/login?{{query_params}}" class="font-weight-bold">Login</a>
                </div>

            </div>
        </div>

        <div class="text-center mt-2 footer">
            <strong>Copyright &copy; {{date('Y')}}</strong> All rights reserved.
        </div>
    </form>
    <!-- /login form -->

</div>
{%endblock%}