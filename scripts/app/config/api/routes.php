<?php

/* Root */
$app->get('/', function () {
    echo 'Sahara API';
});

/* Account related API */
$app->post('/account/{method}', function($method){
    $ctrl = new AccountApi;
    echo (method_exists($ctrl, $method)) ?
        $ctrl->$method() : $ctrl->notfound();
});

/* Access TOken API */
$app->post('/access/{method}', function($method){
    $ctrl = new AccessApi;
    echo (method_exists($ctrl, $method)) ?
        $ctrl->$method() : $ctrl->notfound();
});

/* Dashboard/home API */
$app->post('/dashboard/{method}', function($method){
    $ctrl = new DashboardApi;
    echo (method_exists($ctrl, $method)) ?
        $ctrl->$method() : $ctrl->notfound();
});

/* Notification related API */
$app->post('/notification/list', function(){
    $ctrl = new NotificationApi;
    echo $ctrl->list();
});

$app->post('/notification/add', function(){
    $ctrl = new NotificationApi;
    echo $ctrl->add();
});

$app->post('/notification/remove', function(){
    $ctrl = new NotificationApi;
    echo $ctrl->remove();
});

$app->post('/notification/setread', function(){
    $ctrl = new NotificationApi;
    echo $ctrl->setAsRead();
});

$app->get('/help/{topic}', function($topic){
    echo (new HelpApi)->view($topic);
});

$app->get("/account/test", function(){
    $ctrl = new AccountApi;
    echo $ctrl->test();
});

/* System related API */
$app->post('/system/checkupdate', function(){
    $ctrl = new SystemApi;
    echo $ctrl->checkupdate();
});
