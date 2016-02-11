<?php
namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ClassProvider implements ServiceProviderInterface
{

    private $classes = array(
        'Model' => array(
            'News'
        ),
        'Core' => array(
            'Request',
            'Response'
        )
    );

    public function register(Container $container)
    {
        foreach ($this->classes as $namespace => $classes) {
            foreach ($classes as $name) {
                $class = $namespace . '\\' . $name;
                $container[lcfirst($name)] = function ($c) use($class) {
                    return new $class($c);
                };
            }
        }
        
        return $container;
    }
}
