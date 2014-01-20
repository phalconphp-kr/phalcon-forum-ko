<?php

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared(
   'url',
       function () use ($config) {
           $url = new \Phalcon\Mvc\Url();
           $url->setBaseUri($config->application->baseUri);
           return $url;
       }
);

/**
 * Setting up volt
 */
$di->setShared(
   'volt',
       function ($view, $di) {

           $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

           $volt->setOptions(
                array(
                    "compiledPath"      => __DIR__ . "/../cache/volt/",
                    "compiledSeparator" => "_",
                )
           );

           return $volt;
       }
);

/**
 * Setting up the view component
 */
$di->setShared(
   'view',
       function () use ($config) {

           $view = new \Phalcon\Mvc\View();

           $view->setViewsDir($config->application->viewsDir);

           $view->registerEngines(
                array(
                    ".volt" => 'volt'
                )
           );

           return $view;
       }
);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared(
   'db',
       function () use ($config) {

           /*$eventsManager = new Phalcon\Events\Manager();

           $logger = new \Phalcon\Logger\Adapter\File("../app/logs/db.log");

           //Listen all the database events
           $eventsManager->attach('db', function($event, $connection) use ($logger) {
               if ($event->getType() == 'beforeQuery') {
                   $logger->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);
               }
           });*/

           $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($config->database->toArray());

           //Assign the eventsManager to the db adapter instance
           //$connection->setEventsManager($eventsManager);

           return $connection;
       }
);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
if ($config->debug->enable != true) {
    $di->setShared(
       'modelsMetadata',
           function () use ($config) {
               return new \Phalcon\Mvc\Model\Metadata\Files(array(
                   'metaDataDir' => __DIR__ . '/../cache/metaData/'
               ));
           }
    );
}

/**
 * Start the session the first time some component request the session service
 */
$di->setShared(
   'session',
       function () {
           $session = new \Phalcon\Session\Adapter\Files();
           $session->start();
           return $session;
       }
);

/**
 * Router
 */
$di->setShared(
   'router',
       function () {
           return include __DIR__ . "/routes.php";
       }
);

/**
 * Register the configuration itself as a service
 */
$di->setShared('config', $config);

/**
 * Register the flash service with the Twitter Bootstrap classes
 */
$di->setShared(
   'flash',
       function () {
           return new Phalcon\Flash\Direct(array(
               'error'   => 'alert alert-error',
               'success' => 'alert alert-success',
               'notice'  => 'alert alert-info',
           ));
       }
);

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->setShared(
   'flashSession',
       function () {
           return new Phalcon\Flash\Session(array(
               'error'   => 'alert alert-error',
               'success' => 'alert alert-success',
               'notice'  => 'alert alert-info',
           ));
       }
);


/**
 * View cache
 */
$di->setShared(
   'viewCache',
       function () use ($config) {

           //Cache data for one day by default
           $frontCache = new \Phalcon\Cache\Frontend\Output(array(
               "lifetime" => ($config->debug->enable ? 0 : 2592000)
           ));

           /*return new \Phalcon\Cache\Backend\Apc($frontCache, array(
               "prefix" => "cache-"
           ));*/

           //Memcached connection settings
           return new \Phalcon\Cache\Backend\File($frontCache, array(
               "cacheDir" => __DIR__ . "/../cache/views/",
               "prefix"   => "cache-"
           ));
       }
);

$di->setShared(
   'timezones',
       function () {
           return require_once __DIR__ . '/timezones.php';
       }
);

$di->setShared('decoda',function(){

        $code = new \Decoda\Decoda();
        $code->addFilter(new \Decoda\Filter\CodeFilter());
        $code->addHook(new \Decoda\Hook\EmoticonHook());
        $code->addHook(new\Decoda\Hook\CensorHook());

        return $code;
    }
);
