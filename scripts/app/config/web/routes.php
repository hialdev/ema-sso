<?php
/*
 * custom routes
 */
$router->add(
    '/project',
    [
        'controller'=> 'Project',
        'action'    => 'index',
    ]
);
$router->add(
    '/project/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Project',
        'action'    => 'view',
        'slug'      => 1,
    ]
);

/* Ticket */
$router->add(
    '/ticket',
    [
        'controller'=> 'Ticket',
        'action'    => 'index',
    ]
);
$router->add(
    '/ticket/v/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Ticket',
        'action'    => 'view',
        'slug'      => 1,
    ]
);
$router->add(
    '/ticket/active',
    [
        'controller'=> 'Ticket',
        'action'    => 'active',
    ]
);
$router->add(
    '/ticket/add',
    [
        'controller'=> 'Ticket',
        'action'    => 'add',
    ]
);
 
/* Note */
$router->add(
    '/note',
    [
        'controller'=> 'Note',
        'action'    => 'index',
    ]
);
$router->add(
    '/note/p/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Note',
        'action'    => 'byProject',
        'slug' => 1,
    ]
);
$router->add(
    '/note/v/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Note',
        'action'    => 'view',
        'slug'      => 1,
    ]
);
$router->add(
    '/note/add',
    [
        'controller'=> 'Note',
        'action'    => 'add',
    ]
);

/* */
$router->add(
    '/knowladge',
    [
        'controller'=> 'Blog',
        'action'    => 'index',
    ]
);
$router->add(
    '/knowladge/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Blog',
        'action'    => 'view',
        'slug'      => 1,
    ]
);

$router->add(
    '/mytask/:params',
    [
        'controller'=> 'Task',
        'action'    => 'MyTask',
        'param'      => 1,
    ]
);
$router->add(
    '/task/([a-zA-Z0-9\_\-]+)/:params',
    [
        'controller'=> 'Project',
        'action'    => 'task',
        'slug'      => 1,
        'id'        => 2
    ]
);
$router->add(
    '/p/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Project',
        'action'    => 'index',
        'slug'      => 1,
    ]
);
$router->add(
    '/w/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Workspace',
        'action'    => 'index',
        'slug'      => 1,
    ]
);

$router->add(
    '/img/(view|thumb)/([0-9]+)*/:int/:int',
    [
        'controller'    => 'Image',
        'action'        => 1,
        'id'            => 2,
        'width'         => 3,
        'height'        => 4
    ]
);

$router->add(
    '/img/(view|thumb)/([0-9]+)*/:int',
    [
        'controller'    => 'Image',
        'action'        => 1,
        'id'            => 2,
        'width'         => 3,
    ]
);

$router->add(
    '/img/(view|thumb|full)/([0-9]+)*',
    [
        'controller'    => 'Image',
        'action'        => 1,
        'id'            => 2,
    ]
);

$router->add(
    '/f/([0-9]+)*',
    [
        'controller'    => 'File',
        'action'        => 'open',
        'id'            => 1,
    ]
);