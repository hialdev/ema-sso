<?php
/*
 * custom routes
 */
$router->addGet(
    '/mytask/:params',
    [
        'controller'=> 'Task',
        'action'    => 'MyTask',
        'param'      => 1,
    ]
);
$router->addGet(
    '/task/([a-zA-Z0-9\_\-]+)/:params',
    [
        'controller'=> 'Project',
        'action'    => 'task',
        'slug'      => 1,
        'id'        => 2
    ]
);
$router->addGet(
    '/p/([a-zA-Z0-9\_\-]+)',
    [
        'controller'=> 'Project',
        'action'    => 'index',
        'slug'      => 1,
    ]
);
$router->addGet(
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