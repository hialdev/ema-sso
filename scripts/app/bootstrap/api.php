<?php
/*
 * API bootstrap
 *
 *
 *
 * @author: me@tes123.id
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Cache\Backend\Redis;
use Phalcon\Url as UrlResolver;

error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include Services
     */

    /**
     * Shared configuration service
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
            $file   = $config->log->api->path. $config->log->api->prefix .'error_'. date('Ymd');
            $msg    = date('Y-m-d H:i:s') .
                    " [" . $aERROR[$e['type']] . "]".
                    "\t Message : " . $e['message'] .
                    "\t Errfile : " . $e['file'] .
                    "\t Errline : " . $e['line'];

            @error_log($msg . "\n", 3, $file);
        }
    });

    /**
     * The URL component is used to generate all kind of urls in the application
     */
    $di->setShared('url', function () {
        $config = $this->getConfig();

        $url = new UrlResolver();
        $url->setBaseUri($config->application->baseUri);
        return $url;
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

    $di->set('log', function(){
        $config = $this->getConfig();

        return new Phalcon\Logger (
            'messages',
            [
                new Phalcon\Logger\Adapter\Stream($config->log->api->path . $config->log->api->prefix . date('Ymd'))
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
                    'always'    => $config->environment->production ? FALSE : TRUE,
                    'prefix'    => 'api_',
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
    });

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(
        [
            APP_PATH . '/base/',
            APP_PATH . '/helpers/',
            $config->application->controllersDir. 'api/',

            $config->application->modelsDir,
            $config->application->libraryDir,
        ]
    );

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

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);
    /**
     * Include Application
     */
    include APP_PATH . '/config/api/routes.php';

    /**
     * Not found handler
     */
    $app->notFound(function () use($app) {
        $api = new BaseApi;
        echo $api->apiNotFound();
    });

    /**
     * Handle the request
     */
    $app->handle($_SERVER["REQUEST_URI"]);


} catch (\Exception $e) {
      echo $e->getMessage() . '<br>';
      echo '<pre>' . $e->getTraceAsString() . '</pre>';
}