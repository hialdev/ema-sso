<?php
/*
 * web bootstrap
 *
 *
 *
 * @author: me@tes123.id
 */

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Flash\Session as Flash;
use Phalcon\Mvc\Dispatcher as PhDispatcher;

error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', BASE_PATH . '/app');
define('APP_URL_REQUEST', $_GET['_url'] ?? '');
define('APPDIR', 'web');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new Phalcon\Di\FactoryDefault();

    /**
     * -------------------------------------------------------------------------
     * Shared configuration service
     * -------------------------------------------------------------------------
     */

    $di->setShared('config', function () {
        return  include APP_PATH . "/config/config.php";
    });

    $config = $di->getConfig();

    register_shutdown_function(function() use ($config)
    {
        if(is_null($e = error_get_last()) === FALSE)
        {
            $aERROR = array_flip(array_slice(get_defined_constants(true)['Core'], 1, 15, true));
            //$config = $di->getConfig();
            $file   = $config->log->web->path. $config->log->web->prefix .'error_'. date('Ymd');
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
        $url->setbaseUri($config->application->baseUrl);

        return $url;
    });

    /**
     * Setting up the view component
     */
    $di->setShared('view', function () {
        $config = $this->getConfig();

        $view = new View();
        $view->setDI($this);
        $view->setViewsDir($config->application->viewsDir.'/'.APPDIR.'/');

        $view->registerEngines([
            '.volt' => function ($view) {
                $config = $this->getConfig();

                $volt = new VoltEngine($view, $this);

                $volt->setOptions([
                    'path'      => $config->application->cacheDir.'/'.APPDIR,
                    'separator' => '_',
                    'always'    => $config->environment->production ? FALSE : TRUE
                ]);

                // Extended functions
                $compiler = $volt->getCompiler();
                $compiler->addFunction('in_array', 'in_array');
                $compiler->addFunction('is_array', 'is_array');
                $compiler->addFunction('range', 'range');
                $compiler->addFunction('round', 'round');
                return $volt;
            },
            '.phtml' => PhpEngine::class

        ]);

        $view->disableLevel(View::LEVEL_MAIN_LAYOUT);
        $view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        return $view;
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


    /**
     * If the configuration specify the use of metadata adapter use it or use memory otherwise
     */
    $di->setShared('modelsMetadata', function () {
        return new MetaDataAdapter();
    });

    /**
     * Start the session the first time some component request the session service
     */
    $di->setShared('session', function ()
    {
        $config = $this->getConfig();
        $session    = new Phalcon\Session\Manager();
        if ($config->session->adapter == 'file')
        {
            $sessionAdapter = new Phalcon\Session\Adapter\Stream([
                'savePath'  => $config->session->fileOption->path,
            ]);
        }
        else if($config->session->adapter == 'redis')
        {
            $serializerFactory  = new Phalcon\Storage\SerializerFactory();
            $adapterFactory     = new Phalcon\Storage\AdapterFactory ($serializerFactory);
            $sessionAdapter     = new Phalcon\Session\Adapter\Redis ($adapterFactory, [
                'host'  => $config->session->redisOption->redisHost,
                'port'  => $config->session->redisOption->redisPort,
                'index' => 1
            ]);
        }

        $session->setAdapter($sessionAdapter);
        $session->start();
        return $session;
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
                new Phalcon\Logger\Adapter\Stream($config->log->web->path . $config->log->web->prefix . date('Ymd'))
            ]
        );
    });

    $di->set('mailer', function () {
        return new Mailer();
    });

    $di->set('notification', function () {
        return new MessageQueue();
    });

    $di->setShared('menu', function () {
        return include APP_PATH . "/config/".APPDIR."/menu.php";
    });

    $di->setShared('constant', function () {
        return include APP_PATH . "/config/constant.php";
    });

    /**
     * Register the session flash service with the Twitter Bootstrap classes
     */
    $di->set('flash', function () use ($di) {
        $session = $di->getShared('session');
        $escaper = new Phalcon\Escaper();
        $flash   = new  Phalcon\Flash\Session($escaper, $session);

        $flash->setCssClasses([
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        ]);

        $flash->setAutoescape(false);
        return $flash;
    });

    if (isset($config->environment->offline) && $config->environment->offline == true)
    {
        $view = $di->getShared('view');
        echo $view->getRender('error', 'outofservice', [], function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });
        exit;
    }

    /**
     * -------------------------------------------------------------------------
     * Handle routes
     * -------------------------------------------------------------------------
     */

    //$urlRequest = $_GET['_url'] ?? '/';
    $router = $di->getRouter();
    $router->setDefaultController($config->application->defaultRoute);
    include APP_PATH . "/config/".APPDIR."/routes.php";

    $router->handle(APP_URL_REQUEST?:"/");

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
                                    'controller' => 'error',
                                    'action'     => 'show404',
                                )
                            );
                            return false;
                    }
                }
            );
            $dispatcher = new PhDispatcher();
            $dispatcher->setEventsManager($evManager);
            return $dispatcher;
        },
        true
    );


    /**
     * Include Autoloader
     */
    $loader = new \Phalcon\Loader();
    $loader->registerFiles([ BASE_PATH . "/vendor/autoload.php" ]);

    /**
     * We're a registering a set of directories taken from the configuration file
     */
    $loader->registerDirs(
        [
            APP_PATH . '/base/',
            APP_PATH . '/helpers/',

            $config->application->controllersDir.'/'.APPDIR.'/',
            $config->application->controllersDir,
            $config->application->modelsDir,
            $config->application->libraryDir
        ]
    )->register();

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    if ($application->request->isAjax())
        $application->dispatcher->setActionSuffix('Ajax');

    echo $application->handle(APP_URL_REQUEST?:"/")->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}