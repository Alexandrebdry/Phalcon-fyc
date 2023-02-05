<?php

declare(strict_types=1);

use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Url as UrlResolver;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Session\Adapter\Stream as SessionAdapter;

$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->setShared('url', function () {
    $config = $this->getConfig();
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

$di->setShared('db', function () {
    $config = $this->getConfig();
    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});

$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->setShared('session', function() {
    $session = new SessionManager() ;
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir()
    ]);
    $session->setAdapter($files);
    $session->start();
    return $session;
}) ;
