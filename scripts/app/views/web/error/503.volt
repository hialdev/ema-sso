{%extends 'layouts/public.volt' %}

{%block page_content%}
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
    <h2 class="mb-2 mx-2">503 Permission Error</h2>
    <p class="mb-4 mx-2">Maaf anda tidak dapat mengakses halaman ini.</p>
    <a href="{{accountUrl}}" class="btn btn-primary">Go to My Account</a>
    <div class="mt-4">
        <img
        src="/assets/img/illustrations/girl-doing-yoga-light.png"
        alt="girl-doing-yoga-light"
        width="500"
        class="img-fluid"
        data-app-dark-img="illustrations/girl-doing-yoga-dark.png"
        data-app-light-img="illustrations/girl-doing-yoga-light.png"
        />
    </div>
    </div>
</div>
{%endblock%}