<?php
return new \Phalcon\Config([
    'Dashboard' => [
        'role'  => '',
        'header'=> false,
        'desc'  => '',
        'icon'  => 'icon-home4',
        'url'   => 'dashboard',
        'menu'  => false,
    ],
    'SSO Management'=> [
        'role'      => '',
        'header'    => true,
        'desc'      => '',
        'menu'   => [
            [
                'title' => 'Applications',
                'desc'  => '',
                'icon'  => 'icon-grid2',
                'menu'  => false,
                'url'   => 'application'
            ],
            [
                'title' => 'User Accounts',
                'desc'  => '',
                'icon'  => 'fas fa-users',
                'menu'  => false,
                'url'   => 'account'
            ],
        ]
    ],
    'Company Management'   => [
        'role'      => '',
        'header'    => true,
        'desc'      => '',
        'menu'   => [
            [
                'title' => 'Employee',
                'desc'  => '',
                'icon'  => 'fas fa-user-friends',
                'menu'  => false,
                'url'   => 'employee'
            ],
            [
                'show'  => false,
                'title' => 'Clients',
                'desc'  => '',
                'icon'  => 'far fa-address-book',
                'menu'  => false,
                'url'   => 'client'
            ],
        ]
    ],
    'Setting'=> [
        'role'      => '',
        'header'    => true,
        'desc'      => '',
        'menu'   => [
            [
                'title' => 'System Settings',
                'desc'  => '',
                'icon'  => 'icon-cog3',
                'menu'  => false,
                'url'   => 'setting'
            ],
        ]
    ],

]);