<?php

class ApplicationHelper extends  Phalcon\Di\Injectable
{
    static $roleIcons = [
        Role::ROLE_GENERIC      => 'far fa-user',
        Role::ROLE_STAFF        => 'fas fa-user-tie',
    ];

    static $typeIcons = [
        Application::APPLICATION_MOBILE	=> 'icon-iphone',
        Application::APPLICATION_WEB  	=> 'icon-browser',
        Application::APPLICATION_HYBRID => 'icon-windows2',
    ];

}