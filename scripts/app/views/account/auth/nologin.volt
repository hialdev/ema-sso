{%extends 'layouts/public.volt' %}

{%block page_content%}
<div class="content d-flex justify-content-center align-items-center">
    <div>
        <div class="card mb-0">
            <div class="card-body">

                <div class="text-center mb-2">
                    <div class="m-bottom-10"><img class="logo-on-card" src="assets/images/logo.png" ></div>
                    <h5 class="mb-2">{{appTitle}}</h5>
                </div>

                <div class="text-center mb-2">
                    Anda telah keluar dari aplikasi ini.<br>
                    Silakan <a href="auth/login?ref={{request.get('ref')}}" class="text-center">Klik Disini</a> untuk masuk ke aplikasi ini.
                </div>

            </div>
        </div>

        <div class="text-center mt-3 footer">
            <strong>Copyright &copy; <script>document.write(new Date().getFullYear())</script></strong> All rights reserved.
        </div>
    </div>
</div>
{%endblock%}

{%block page_scripts%}
<script>let btn = document.getElementById('btn-relogin');btn.href += location.hash;</script>
{%endblock%}