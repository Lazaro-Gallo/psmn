<?php
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'staging'));

defined('APPLICATION_PATH_CACHE')
    || define('APPLICATION_PATH_CACHE', (getenv('APPLICATION_PATH_CACHE') ?
        getenv('APPLICATION_PATH_CACHE') : APPLICATION_PATH . '/../data'));

defined('APPLICATION_PATH_LIBS')
    || define('APPLICATION_PATH_LIBS', (getenv('APPLICATION_PATH_LIBS') ?
        getenv('APPLICATION_PATH_LIBS') : APPLICATION_PATH . '/../premium-libs'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH_LIBS), get_include_path(),
)));

define('CLI_APP', false);

require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
    ->run();
