<?php
require (__DIR__ . '/../vendor/autoload.php');
if (is_file(__DIR__ . '/../config.php')) {
    require (__DIR__ . '/../config.php');
}
if (is_file(__DIR__ . '/../cache/config-user.php')) {
    require (__DIR__ . '/../cache/config-user.php');
}
require (__DIR__ . '/config-defaults.php');

// Config
if (DEBUG) {
    ini_set('display_errors', true);
    ini_set('log_errors', true);
}
error_reporting(E_ALL);
ini_set('error_log', CACHE_PATH . '/error_log.txt');
define('MessageLog', CACHE_PATH . '/message_log.txt');
date_default_timezone_set('Europe/Paris');
ini_set('session.use_cookies', 1); // Use cookies to store session.
ini_set('session.use_only_cookies', 1); // Force cookies for session (phpsessionID forbidden in URL)
ini_set('session.use_trans_sid', false); // Prevent php to use sessionID in URL if cookies are disabled.
// ini_set('session.save_path', CACHE_PATH . '/sessions'); // Prevent php to use sessionID in URL if cookies are disabled.
// if (! is_dir(CACHE_PATH . '/sessions'))
//     mkdir(CACHE_PATH . '/sessions');
session_name('cepsaintmaur');
if (session_id() == '')
    session_start();
if (CompressionEnabled)
    ob_start('ob_gzhandler');
    
// Container
$container = new Pimple\Container();
$container->register(new \ServiceProvider\ClassProvider());
$container->register(new \ServiceProvider\DatabaseProvider());
$container['template'] = function ($c) {
    return new \Twig_Environment(new \Twig_Loader_Filesystem(array(
        SRC_PATH . '/Template/'
    )), array(
        'cache' => CACHE_PATH . '/twig',
        'debug' => DEBUG,
        'charset' => Encodage
    ));
};
$container['router'] = function ($c) {
    return new \Core\Router($c);
};