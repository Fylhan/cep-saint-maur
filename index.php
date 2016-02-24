<?php
if (is_file(__DIR__ . '/config.php')) {
    require (__DIR__ . '/config.php');
}
if (is_file(__DIR__ . '/cache/config-user.php')) {
    require (__DIR__ . '/cache/config-user.php');
}
require (__DIR__ . '/src/config-defaults.php');
require (VENDOR_PATH . '/autoload.php');

$container = new Pimple\Container();
$container->register(new \ServiceProvider\ClassProvider);
$container->register(new \ServiceProvider\DatabaseProvider);
$container['template'] = function ($c) {
    return new \Twig_Environment(new \Twig_Loader_Filesystem(array(
        SRC_PATH . '/Template/',
    )), array(
        'cache' => CACHE_PATH . '/twig',
        'debug' => DEBUG,
        'charset' => Encodage
    ));
};
$container['router'] = function ($c) {
    return new \Core\Router($c);
};

$container['router']->dispatch();
