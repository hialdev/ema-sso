<?php
return new \Phalcon\Config([
    'application' => [
        'appId'         => '13cc87277aa3fa46c0a023a69a75b1083ce70364',

        'title'         => 'Admin Console',
        'shortTitle'    => 'Admin Console',
        'saltkey'       => '^%$^TFGYR%$EARTYFHG%^TGHG*(&^&T',

        'baseUrl'       => 'http://manage.ema.test/',
    ],
    'cookie'            => [
        'browser'       => [
            'id'        => 'EMAADMID',
        ]
    ],
    'log' => [
        'web'  => [
            'prefix'    => 'sso_admin_',
        ],
    ],
]);