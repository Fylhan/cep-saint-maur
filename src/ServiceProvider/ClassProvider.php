<?php
namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ClassProvider implements ServiceProviderInterface
{

    private $classes = array(
        'Core' => array(
            'Request',
            'Response'
        ),
        'Model' => array(
            'News',
            'Upload'
        ),
        'Service' => array(
            'Uploader',
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
