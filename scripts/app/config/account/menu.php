<?php
return new \Phalcon\Config([
    'Main Menu'=> [
        'role'      => '',
        'header'    => true,
        'desc'      => '',
        'menu'   => [
            [
                'title' => 'Dashboard',
                'desc'  => 'Aplikasi layanan yang tersedia',
                'menu'  => false,
                'icon'  => 'icon-home4',
                'url'   => 'dashboard',
            ],
            [
                'title' => 'My Account',
                'desc'  => 'Informasi dan Setting Akun ',
                'icon'  => 'icon-profile',
                'menu'  => false,
                'url'   => 'account'
            ],
        ]
    ],
]);