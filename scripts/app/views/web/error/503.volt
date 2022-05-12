{%extends 'layouts/public.volt' %}

{%block page_content%}
<div class="content d-flex justify-content-center align-items-center">

    <!-- Login form -->
    <form class="login-form" method="POST">
        <div class="card mb-0">
            <div class="card-body">

                <div class="text-center mb-2">
                    <div class="m-bottom-10"><img class="logo-on-card" src="assets/images/logo.png" ></div>
                    <h5 class="mb-2">{{appTitle}}</h5>
                </div>

                <div class="text-center mb-2">
                    <h4 class="font-weight-bold m-0 text-info">[ 503 ]</h4>
                    <div class=" text-error">Anda tidak memiliki akses di aplikasi ini</div>
                </div>

                <div class="text-center mb-2">
                    Goto <a href="{{accountUrl}}">My Account</a>
                </div>
            </div>
        </div>

        <div class="text-center mt-2 footer">
            <strong>Copyright &copy; {{date("Y")}}</strong> All rights reserved.
        </div>
    </form>
</div>
{%endblock%}