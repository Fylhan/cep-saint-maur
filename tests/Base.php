<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/config-defaults.php';

abstract class Base extends PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        date_default_timezone_set('UTC');

        if (DB_DRIVER === 'mysql') {
            $pdo = new PDO('mysql:host='.DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
            $pdo->exec('DROP DATABASE '.DB_NAME);
            $pdo->exec('CREATE DATABASE '.DB_NAME);
            $pdo = null;
        }

        $this->container = new Pimple\Container;
        $this->container->register(new ServiceProvider\DatabaseProvider);
        $this->container->register(new ServiceProvider\ClassProvider);

        $this->container['db']->logQueries = true;
    }

    public function tearDown()
    {
        $this->container['db']->closeConnection();
    }

    public function isWindows()
    {
        return substr(PHP_OS, 0, 3) === 'WIN';
    }
}
