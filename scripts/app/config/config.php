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
        ],
        'charset'       => 'utf8',
    ],
    'application' => [
        'appId'         => '',
        'defaultRoute'  => 'dashboard',

        'title'         => '',
        'shortTitle'    => '',
        'saltkey'       => '',

        'appDir'        => APP_PATH . '/',
        'controllersDir'=> APP_PATH . '/controllers/',
        'modelsDir'     => APP_PATH . '/models/',
        'viewsDir'      => APP_PATH . '/views/',
        'pluginsDir'    => APP_PATH . '/plugins/',
        'libraryDir'    => APP_PATH . '/libraries/',
        'cacheDir'      => BASE_PATH . '/cache/',

        'baseUrl'       => 'http://ema.test/',
        'accountUrl'    => 'http://account.ema.test/',

        'cliManager'    => '/app/ema/sso/scripts/console/workspace-sso',
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
            'id'        => '',
        ]
    ],
    'filePath'      => '/app/ema/files/sso/',

    'session'   => array(
        'adapter'   => 'redis', //file or redis
        'redisOption'   => array(
            'redisHost' => 'localhost',
            'redisPort' => 6379
        ),
        'fileOption'    => array(
            'path'      => '/app/ema/tmp/sso/'
        )
    ),
    'log' => [
        'web'  => [
            'path'      => '/app/ema/logs/',
            'prefix'    => 'sso_web_',
            'logLevel'  =>  Phalcon\Logger::DEBUG
        ],
        'api'  => [
            'path'      => '/app/ema/logs/',
            'prefix'    => 'sso_api_',
            'logLevel'  =>  Phalcon\Logger::DEBUG
        ],
        'console'  => [
            'path'      => '/app/ema/logs/',
            'prefix'    => 'sso_console_',
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
        'senderName'    => 'Elang Merah SSO',
        'senderEmail'   => 'sender@elangmerah.com',
        'smtp'          => [
            'server'    => 'smtp.yandex.com',
            'port'      => '465',
            'security'  => 'ssl',
            'username'  => 'sender@elangmerah.com',
            'password'  => 'Wk@@XsQ$7e&m'
        ]
    ],

    'queue'         => [
        'beanstalk'     => [
            'host'  => '127.0.0.1',
            'port'  => '11300'
        ],
        'tubes'     => [
            'email'     => 'ws_email',
            'sms'       => 'ws_sms',
            'telegram'  => 'ws_telegram',
            'whatsapp'  => 'ws_whatsapp',
        ]
    ],

    //redis
    'cache' => [
        'host'      => "127.0.0.1",
        'port'      => 6379,
        'lifetime'  => 300
    ],

    'telegram'  => [
        // Telegram CLI client, Using TCP / Socket
        'client'    => 'tcp://localhost:4567'           // unix:///tmp/tg.sck
    ],

    'whatsapp'  => [
        'server'    => [
            'local' => 'http://localhost:8000',
            'public'=> 'http://localhost:8000'
        ]
    ],

    'gdrive'        => [
        'credentials'   => '',          // path ke credentials json google service account
        'folderId'      => ''           // folder id google drive yang di share untuk credentials sa
    ],
]);