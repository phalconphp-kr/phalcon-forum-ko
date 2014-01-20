<?php

// composer loaders
require_once (dirname(dirname(__DIR__))."/vendor/autoload.php");

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    array(
       'Phosphorum\Models'      => $config->application->modelsDir,
       'Phosphorum\Controllers' => $config->application->controllersDir,
       'Phosphorum\Github'      => $config->application->libraryDir . '/Github',
    )
);

$loader->register();
