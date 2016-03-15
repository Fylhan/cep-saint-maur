<?php
namespace ServiceProvider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Core\Router;
use Filter\CacheFilter;
use Filter\SecurityFilter;

/**
 * Route Provider
 *
 * @package serviceProvider
 * @author Frederic Guillot
 */
class RouteProvider implements ServiceProviderInterface
{

    /**
     * Register providers
     *
     * @access public
     * @param \Pimple\Container $container            
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['router'] = new Router($container);
        $container['router']->addFilter(new SecurityFilter($container));
        if (CacheEnabled)
            $container['router']->addFilter(new CacheFilter($container));
        return $container;
    }
}
