<?php
/*
 * console bootstrap
 *
 *
 *
 * @author: me@tes123.id
 */

use Phalcon\Cli\Dispatcher as PhDispatcher;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', BASE_PATH . '/app');

try {

    $di = new Phalcon\Di\FactoryDefault\Cli();

    /**
     * -------------------------------------------------------------------------
     * Shared configuration service
     * -------------------------------------------------------------------------
     */
    $di->setShared('config', function () {
        return include APP_PATH . "/config/config.php";
    });

    register_shutdown_function(function() use ($di)
    {
        if(is_null($e = error_get_last()) === FALSE)
        {
            $aERROR = array_flip(array_slice(get_defined_constants(true)['Core'], 1, 15, true));
            $config = $di->getConfig();
            $file   = $config->log->console->path. $config->log->console->prefix .'error_'. date('Ymd');
            $msg    = date('Y-m-d H:i:s') .
                    " [" . $aERROR[$e['type']] . "]".
                    "\t Message : " . $e['message'] .
                    "\t Errfile : " . $e['file'] .
                    "\t Errline : " . $e['line'];

            @error_log($msg . "\n", 3, $file);
        }
    });

    /**
     * Database connection is created based in the parameters defined in the configuration file
     */
    $di->setShared('db', function () {
        $config = $this->getConfig();

        $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;

        $params = [
            'host'     => $config->database->host,
            'username' => $config->database->username,
            'password' => $config->database->password,
            'dbname'   => $config->database->db->main,
            'charset'  => $config->database->charset
        ];

        if ($config->database->adapter == 'Postgresql') {
            unset($params['charset']);
        }

        $connection = new $class($params);

        return $connection;
    });

    $di->set('crypt', function()
    {
        $config = $this->getConfig();
        $crypt = new Phalcon\Crypt();
        $crypt->setKey($config->application->saltkey);
        return $crypt;
    });

    // setup security service
    $di->set('security', function()
    {
        $security = new Phalcon\Security();
        $security->setWorkFactor(12); //Set the password hashing factor to 12 rounds
        return $security;
    }, true);

    $di->set('log', function(){
        $config = $this->getConfig();

        return new Phalcon\Logger (
            'messages',
            [
                new Phalcon\Logger\Adapter\Stream($config->log->console->path . $config->log->console->prefix . date('Ymd'))
            ]
        );
    });

    /**
     * Setting up the view component
     */

    $di->setShared('view', function () {
        $config = $this->getConfig();

        $view = new View();
        $view->setDI($this);
        $view->setViewsDir($config->application->viewsDir);

        $view->registerEngines([
            '.volt' => function ($view) {
                $config = $this->getConfig();

                $volt = new VoltEngine($view, $this);

                $volt->setOptions([
                    'path'      => $config->application->cacheDir,
                    'separator' => '_',
                    'always'    => $config->environment->production ? FALSE : TRUE
                ]);

                return $volt;
            },
            '.phtml' => PhpEngine::class

        ]);

        $view->disableLevel(View::LEVEL_MAIN_LAYOUT);
        $view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        return $view;
    });

    // cache
    $di->set('cache', function()
    {
        $config = $this->getConfig();
        $serializerFactory = new Phalcon\Storage\SerializerFactory();

        $options = [
            'defaultSerializer' => 'Json',
            'lifetime'          => $config->cache->lifetime,
            'host'              => $config->cache->host,
            'port'              => $config->cache->port,
            'index'             => 1,
        ];

        return new Phalcon\Cache\Adapter\Redis($serializerFactory, $options);
        //return $cache = new Phalcon\Cache($adapter);;
    });

    $di->set(
        'dispatcher',
        function() use ($di) {

            $evManager = $di->getShared('eventsManager');

            $evManager->attach(
                "dispatch:beforeException",
                function($event, $dispatcher, $exception)
                {
                   switch ($exception->getCode()) {
                        case Phalcon\Dispatcher\Exception::EXCEPTION_HANDLER_NOT_FOUND:
                        case Phalcon\Dispatcher\Exception::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(
                                array(
                                    'task'      => 'error',
                                    'action'    => 'notfound',
                                )
                            );
                            return false;
                    }
                }
            );
            $dispatcher = new PhDispatcher();
            $dispatcher->setEventsManager($evManager);
            $dispatcher->setDefaultAction('run');
            return $dispatcher;
        },
        true
    );

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    $loader = new \Phalcon\Loader();
    $loader->registerDirs([
        APP_PATH . '/base/',
        APP_PATH . '/tasks',
        APP_PATH . '/helpers/',

        $config->application->modelsDir,
        $config->application->libraryDir
    ]);
    $loader->registerFiles([
        BASE_PATH . '/vendor/autoload.php'
    ]);
    $loader->register();

    $di->set('mailer', function () {
        return new Mailer();
    });

    $di->set('notification', function () {
        return new MessageQueue();
    });

    $di->set('html2pdf', function () {
        return new Knp\Snappy\Pdf('/usr/local/bin/wkhtmltopdf');
    });

    $console = new Phalcon\Cli\Console($di);

    $arguments = [];
    foreach ($argv as $k => $arg) {
        if ($k == 1) {
            $commands = explode (":", $arg, 2);
            $arguments['task'] = $commands[0];
            $arguments['action'] = isset($commands[1]) ? $commands[1] : 'run';
        /* } elseif ($k == 2) {
            $arguments['action'] = $arg; */
        } elseif ($k >= 2) {
            $arguments['params'][] = $arg;
        }
    }

    $console->handle($arguments);

    echo PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
    exit(255);
}
