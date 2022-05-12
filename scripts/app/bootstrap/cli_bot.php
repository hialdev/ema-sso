<?php
/*
 * console bootstrap for Bot
 *
 *
 *
 * @author: me@tes123.id
 */

use Phalcon\Cli\Dispatcher as PhDispatcher;

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
            $file   = $config->log->console->path. 'bot-'.$config->log->console->prefix .'error_'. date('Ymd');
            $msg    = date('Y-m-d H:i:s') .
                    " [" . $aERROR[$e['type']] . "]".
                    "\t Message : " . $e['message'] .
                    "\t Errfile : " . $e['file'] .
                    "\t Errline : " . $e['line'];

            @error_log($msg . "\n", 3, $file);
        }
    });

    require BASE_PATH.'/vendor/autoload.php';

    $di->setShared('telegram', function () {
        $config = $this->getConfig();

        $mysql_credentials = [
            'host'     => $config->database->host,
            'user'     => $config->database->username,
            'password' => $config->database->password,
            'database' => $config->database->db->bot,
        ];

        $telegram = new Longman\TelegramBot\Telegram($config->bot->apiKey, $config->bot->username);
        $telegram->enableMySql($mysql_credentials);
        $telegram->enableAdmins((array) $config->bot->adminUsers);
        $telegram->enableLimiter();

        return $telegram;
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
        return new Phalcon\Logger\Adapter\File($config->log->console->path . 'bot-'.$config->log->console->prefix . date('Ymd'));
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
                        case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
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
            $dispatcher->setTaskSuffix('Bot');
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
        APP_PATH . '/bots/',
        APP_PATH . '/helpers/',

        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir
    ]);
    $loader->register();

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
