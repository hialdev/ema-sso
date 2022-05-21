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
$router->addGet(
    '/project/([a-zA-Z0-9\_\-]+)/edit',
    [
        'controller'=> 'Project',
        'action'    => 'edit',
        'slug'      => 1,
    ]
);
$router->addPost(
    '/project/([a-zA-Z0-9\_\-]+)/edit',
    [
        'controller'=> 'Project',
        'action'    => 'update',
        'slug'      => 1,
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
$router->add(
    '/project/([a-zA-Z0-9\_\-]+)/delete',
    [
        'controller'=> 'Project',
        'action'    => 'delete',
        'slug'      => 1,
    ]
);
$router->addGet(
    '/project/([a-zA-Z0-9\_\-]+)/task/add',
    [
        'controller'=> 'Project',
        'action'    => 'edit',
        'slug'      => 1,
    ]
);
$router->addGet(
    '/project/([a-zA-Z0-9\_\-]+)/task/([0-9]+)/edit',
    [
        'controller'=> 'Project',
        'action'    => 'edit',
        'slug'      => 1,
        'task_id'   => 2,
    ]
);
$router->add(
    '/project/([a-zA-Z0-9\_\-]+)/task/([0-9]+)/delete',
    [
        'controller'=> 'Project',
        'action'    => 'taskDelete',
        'slug'      => 1,
        'task_id'   => 2,
    ]
);
$router->addGet(
    '/project/add',
    [
        'controller'=> 'Project',
        'action'    => 'add',
    ]
);
$router->addPost(
    '/project/add',
    [
        'controller'=> 'Project',
        'action'    => 'create',
    ]
);


$router->add(
    '/sso/logout',
    [
        'controller'=> 'Project',
        'action'    => 'exit',
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
$router->addGet(
    '/ticket/v/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Ticket',
        'action'    => 'view',
        'slug'      => 1,
    ]
);
$router->addPost(
    '/ticket/v/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Ticket',
        'action'    => 'open',
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
$router->addPost(
    '/ticket/add',
    [
        'controller'=> 'Ticket',
        'action'    => 'create',
    ]
);
$router->add(
    '/ticket/([a-zA-Z0-9\_\-]+)/close',
    [
        'controller'=> 'Ticket',
        'action'    => 'close',
        'slug'      => 1,
    ]
);
$router->add(
    '/ticket/answer',
    [
        'controller'=> 'Ticket',
        'action'    => 'answer',
    ]
);

/* Note */
$router->addGet(
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
$router->addGet(
    '/note/add',
    [
        'controller'=> 'Note',
        'action'    => 'add',
    ]
);
$router->addPost(
    '/note/add',
    [
        'controller'=> 'Note',
        'action'    => 'create',
    ]
);
$router->addGet(
    '/note/([a-zA-Z0-9\_\-]+)/edit',
    [
        'controller'=> 'Note',
        'action'    => 'edit',
        'slug'      => 1,
    ]
);
$router->addPost(
    '/note/([a-zA-Z0-9\_\-]+)/edit',
    [
        'controller'=> 'Note',
        'action'    => 'update',
        'slug'      => 1,
    ]
);
$router->add(
    '/note/([a-zA-Z0-9\_\-]+)/delete',
    [
        'controller'=> 'Note',
        'action'    => 'delete',
        'slug'      => 1,
    ]
);

/* Knowladge */
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
$router->addGet(
    '/knowladge/([a-zA-Z0-9\_\-]+)/edit',
    [
        'controller'=> 'Blog',
        'action'    => 'edit',
        'slug'      => 1,
    ]
);
$router->addPost(
    '/knowladge/([a-zA-Z0-9\_\-]+)/edit',
    [
        'controller'=> 'Blog',
        'action'    => 'update',
        'slug'      => 1,
    ]
);
$router->add(
    '/knowladge/([a-zA-Z0-9\_\-]+)/delete',
    [
        'controller'=> 'Blog',
        'action'    => 'delete',
        'slug'      => 1,
    ]
);
$router->addGet(
    '/knowladge/add',
    [
        'controller'=> 'Blog',
        'action'    => 'add',
    ]
);
$router->addPost(
    '/knowladge/add',
    [
        'controller'=> 'Blog',
        'action'    => 'create',
    ]
);

$router->add(
    '/file/delete/([0-9]+)',
    [
        'controller'=> 'Note',
        'action'    => 'remove',
        'id'      => 1,
    ]
);