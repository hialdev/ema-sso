{%extends 'layouts/public.volt' %}

{%block page_content%}
<div class="content d-flex justify-content-center align-items-center" style="background: url(assets/images/logo_bg.png); background-size: cover;">

    <!-- Login form -->
    <form class="login-form" method="POST">
        <input type="hidden" name="<?=$this->security->getTokenKey();?>" value="<?=$this->security->getToken();?>"/>
        <input type="hidden" name="appId" value="{{appId}}">
        <input type="hidden" name="backUrl" value="{{backUrl}}">
        <input type="hidden" name="bId" value="{{browserId}}">
        <div class="card rounded-lg">
            <div class="card-body">

                <div class="text-center mt-2">
                    <div class="mb-2"><img class="logo-on-card" src="assets/images/logo.png"></div>
                    <h5 class="mb-3">{{application.name}} <b>Login</b></h5>
                </div>

                <?php if ($this->flash->has()) { ?>
                {% for type, messages in flash.getMessages() %}
                    {% for message in messages %}
                        <div class="status-message cursor-pointer text-pink-700 m-2 text-center">{{ message }}</div>
                    {% endfor %}
                {% endfor %}
                <?php } ?>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="text" class="form-control" required name="username" placeholder="Username / Email">
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" class="form-control" required name="password" placeholder="Password">
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-danger btn-block btn-sm">Login <i class="icon-circle-right2 ml-2"></i></button>
                </div>

            </div>
        </div>

        <div class="text-center">
            <strong>Copyright &copy; {{date('Y')}}</strong> All rights reserved.
        </div>
    </form>
    <!-- /login form -->

</div>




<!-- <div class="content d-flex justify-content-center align-items-center d-none">
    <form class="login-form" method="POST">
        <input type="hidden" name="<?=$this->security->getTokenKey();?>" value="<?=$this->security->getToken();?>"/>
        <input type="hidden" name="appId" value="{{appId}}">
        <input type="hidden" name="backUrl" value="{{backUrl}}">
        <input type="hidden" name="bId" value="{{browserId}}">
        <div class="card mb-0">
            <div class="card-body">

                <div class="text-center m-bottom-10">
                    <div class="m-bottom-10"><img class="logo" src="assets/globals/images/logo.jpeg"></div>
                    <h5 class="mb-3"><b>{{application.name}}</b> Login</h5>
                </div>

                <?php if ($this->flash->has()) { ?>
                {% for type, messages in flash.getMessages() %}
                    {% for message in messages %}
                        <div class="status-message cursor-pointer text-pink-700 m-bottom-10 text-center">{{ message }}</div>
                    {% endfor %}
                {% endfor %}
                <?php } ?>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="text" class="form-control" required name="username" placeholder="Username / Email">
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" class="form-control" required name="password" placeholder="Password">
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-danger btn-block btn-sm">Login <i class="icon-circle-right2 ml-2"></i></button>
                </div>

                <div class="text-center">
                    <a href="recovery/forgot?{{query_params}}">Lupa Password?</a>
                </div>
            </div>
        </div>

        <div class="text-center m-top-20 footer">
            <strong>Copyright &copy; {{date('Y')}}</strong> All rights reserved.
        </div>
    </form>
</div> -->
{%endblock%}

{%block page_scripts%}
<script type="text/javascript">
    $(document).ready(function () {
        $(".status-message").on('click', function(e){
            $(this).remove();
        })
        setTimeout(function(){
            $('.status-message').remove();
        }, 5000);
    });
</script>
{%endblock%}