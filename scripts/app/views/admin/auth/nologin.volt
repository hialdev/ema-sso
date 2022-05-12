{%extends 'layouts/public.volt' %}

{%block page_content%}
<div class="content d-flex justify-content-center align-items-center">

    <!-- Login form -->
    <form class="login-form" method="POST">
        <div class="card mb-0">
            <div class="card-body">

                <div class="text-center mb-2">
                    <div class="mb-1"><img class="logo-on-card" src="assets/images/logo.png" ></div>
                    <h5 class="mb-2">{{appTitle}}</h5>
                </div>

                <div class="text-center mb-2">
                    Anda telah keluar dari aplikasi ini.<br>
                    Silakan <a href="auth/login?ref={{request.get('ref')}}" id="btn-relogin" class="text-center">Klik Disini</a> untuk masuk ke aplikasi ini.
                </div>

            </div>
        </div>

        <div class="text-center mt-2 footer">
            <strong>Copyright &copy; {{date("Y")}}</strong> All rights reserved.
        </div>
    </form>
</div>
<script>let btn = document.getElementById('btn-relogin');btn.href += location.hash;</script>
{%endblock%}