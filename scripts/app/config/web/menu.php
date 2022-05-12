<?php
return new \Phalcon\Config([
    'Home' => [
        'role'  => '',
        'header'=> false,
        'desc'  => '',
        'icon'  => 'icon-home4',
        'url'   => 'home',
        'menu'  => false,
    ],
    'My Tasks' => [
        'role'  => '',
        'header'=> false,
        'desc'  => '',
        'icon'  => 'far fa-calendar-check',
        'url'   => 'mytask',
        'alias' => 'task/mytask',
        'menu'  => false,
    ],

    'System' => [
        'show'  => false,
        'menu'  => [
            [
                'title' => 'Start',
                'desc'  => '',
                'icon'  => 'far fa-envelope',
                'url'   => 'start',
                'menu'  => false,
            ],
        ]
    ]
]);