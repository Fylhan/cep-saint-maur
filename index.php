<?php
if (is_file(__DIR__ . '/config.php')) {
    require(__DIR__ . '/config.php');
}
if (is_file(__DIR__ . '/cache/config-user.php')) {
    require(__DIR__ . '/cache/config-user.php');
}
require(__DIR__ . '/src/config-defaults.php');
require(VENDOR_PATH . '/autoload.php');

\Core\Router::getInstance()->dispatch();
