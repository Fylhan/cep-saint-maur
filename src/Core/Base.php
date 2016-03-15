<?php

namespace Core;

use Pimple\Container;

/**
 * Base Class
 *
 * @property \Core\Request                                $request
 * @property \Core\Response                               $response
 * @property \Core\Router                                 $router
 * @property \Model\Content                               $content
 * @property \Model\News                                  $news
 * @property \Model\Upload                                $upload
 * @property \PicoDb\Database                             $db
 * @property \Service\Auth                                $auth
 * @property \Service\CacheManager                        $cacheManager
 * @property \Service\Feeder                              $feeder
 * @property \Service\Mailer                              $mailer
 * @property \Service\Uploader                            $uploader
 * @property \Twig_Environment                            $template
 */
abstract class Base
{
    /**
     * Container instance
     *
     * @access protected
     * @var \PimpleModel\Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @access public
     * @param  \Pimple\Container   $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Load automatically models
     *
     * @access public
     * @param  string $name Model name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container[$name];
    }
}
