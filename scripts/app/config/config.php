<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'version'           => '1.0',
    'environment'       => [
        'production'    => false,
        'offline'       => false,
    ],
    'database' => [
        'adapter'       => 'Mysql',
        'host'          => 'localhost',
        'username'      => 'al',
        'password'      => 'RasulullahSAW12',
        'db'            => [
            'main'      => 'ws_main',
            'sso'       => 'ws_sso',
            'ticket'    => 'ws_ticket'
        ],
        'charset'       => 'utf8',
    ],
    'application' => [
        'appId'         => '2308771280ae4aaa7d3ed1bc3ed7225624cb2ed7',
        'defaultRoute'  => 'home',

        'title'         => 'Ticketing - Client',
        'shortTitle'    => 'Ticketing - Client',
        'saltkey'       => '^%$^TFGYR%$EARTYFHG%^TGHG*(&^&T',

        'appDir'        => APP_PATH . '/',
        'controllersDir'=> APP_PATH . '/controllers/',
        'modelsDir'     => APP_PATH . '/models/',
        'viewsDir'      => APP_PATH . '/views/',
        'pluginsDir'    => APP_PATH . '/plugins/',
        'libraryDir'    => APP_PATH . '/libraries/',
        'cacheDir'      => BASE_PATH . '/cache/',

        'baseUrl'       => 'http://client.ema.test/',
        'accountUrl'    => 'http://account.ema.test/',

        'cliManager'    => '/app/ema/scripts/taskman/console/workspace-taskman',
        'maxWorker'     => 3
    ],
    'cookie'            => [
        'secure'    => false,
        'httpOnly'  => true,
        'sso'           => [
            'id'        => 'EMAID',
            'domain'    => 'ema.test',
        ],
        'browser'       => [
            'id'        => 'EMATICCLID',
            'data'      => 'EMATICCLDATA'
        ]
    ],
    'filePath'      => '/app/ema/client/scripts/www/web/files/',

    'session'   => array(
        'adapter'   => 'redis', //file or redis
        'redisOption'   => array(
            'redisHost' => 'localhost',
            'redisPort' => 6379
        ),
        'fileOption'    => array(
            'path'      => '/app/ema/tmp/'
        )
    ),
    'log' => [
        'web'  => [
            'path'      => '/app/ema/logs/',
            'prefix'    => 'taskman_web_',
            'logLevel'  =>  Phalcon\Logger::DEBUG
        ],
        'api'  => [
            'path'      => '/app/ema/logs/',
            'prefix'    => 'taskman_api_',
            'logLevel'  =>  Phalcon\Logger::DEBUG
        ],
        'console'  => [
            'path'      => '/app/ema/logs/',
            'prefix'    => 'taskman_console_',
            'logLevel'  =>  Phalcon\Logger::DEBUG
        ],
    ],
    'google'    => [
        'client_id'     => '',

        'fcm_auth_key'  => '',
        'fcm_sender_id' => '',
        'fcm_topic'     => ''
    ],

    'email'  => [
        'disabled'      => false,
        'mailer'        => 'phpmailer', //swiftmailer or phpmailer (default)
        'senderName'    => 'Workspace SSO - Demo',
        'senderEmail'   => '',
        'smtp'          => [
            'server'    => 'smtp.xxx.com',
            'port'      => '587',
            'security'  => 'tls',
            'username'  => '',
            'password'  => ''
        ]
    ],

    'queue'         => [
        'beanstalk'     => [
            'host'  => '127.0.0.1',
            'port'  => '11300'
        ],
        'tubes'     => [
            'email'     => 'ws_taskman_email',
            'sms'       => 'ws_taskman_sms',
            'telegram'  => 'ws_taskman_telegram'
        ]
    ],

    //redis
    'cache' => [
        'host'      => "127.0.0.1",
        'port'      => 6379,
        'lifetime'  => 300
    ],

    'gdrive'        => [
        'credentials'   => '',          // path ke credentials json google service account
        'folderId'      => ''           // folder id google drive yang di share untuk credentials sa
    ],
]);