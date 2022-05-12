<?php
return new \Phalcon\Config([
    'application' => [
        'appId'         => '9f111f5d2eeca8cd4a72612d3eeb8a141ca15121',

        'title'         => 'Account',
        'shortTitle'    => 'Account',
        'saltkey'       => '^%$^TFGYR%$EARTYFHG%^TGHG*(&^&T',

        'baseUrl'       => 'http://account.ema.test/',
    ],
    'cookie'            => [
        'browser'       => [
            'id'        => 'EMAACCID',
        ]
    ],
    'log' => [
        'web'  => [
            'prefix'    => 'sso_account_',
        ],
    ],
]);