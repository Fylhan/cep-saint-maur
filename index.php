<?php
if (is_file(__DIR__ . '/config.php')) {
    include_once (__DIR__ . '/config.php');
}
if (is_file(__DIR__ . '/cache/config-user.php')) {
    include_once (__DIR__ . '/cache/config-user.php');
}
include_once (__DIR__ . '/src/config-defaults.php');
include_once (VENDOR_PATH . '/autoload.php');

\Core\Router::getInstance()->dispatch();
