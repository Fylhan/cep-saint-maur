<?php
namespace Core;

use Pimple\Container;

class Request extends Base
{

    /**
     * Pointer to PHP environment variables
     *
     * @access private
     * @var array
     */
    private $server;
    private $get;
    private $post;
    private $files;
    private $cookies;
    private $route;

    /**
     * Constructor
     *
     * @access public
     * @param  \Pimple\Container   $container
     */
    public function __construct(Container $container, array $server = array(), array $get = array(), array $post = array(), array $files = array(), array $cookies = array())
    {
        parent::__construct($container);
        $this->server = empty($server) ? $_SERVER : $server;
        $this->get = empty($get) ? $_GET : $get;
        $this->post = empty($post) ? $_POST : $post;
        $this->files = empty($files) ? $_FILES : $files;
        $this->cookies = empty($cookies) ? $_COOKIE : $cookies;
    }
    
    public function route($defaults = null)
    {
        $module = getModule();
        $action = getAction();
        $this->route = array(
            'module' => (NULL != $module ? $module : $defaults['module']),
            'action' => (NULL != $action ? $action : $defaults['action'])
        );
        return $this->route;
    }

    public function isParam($key, $value = NULL)
    {
        return (NULL != $this->getParam($key) && (NULL == $value || $value == $this->getParam($key)));
    }

    public function getParam($key, $type = 'string')
    {
        $var = null;
        if (! empty($this->get) && isset($this->get[$key])) {
            $var = $this->get[$key];
        }
        else if (! empty($this->post) && isset($this->post[$key])) {
            $var = $this->post[$key];
        }
        if (null != $var) {
            if (startsWith($type, 'int')) {
                return parserI($var);
            }
            if ('float' == $type || 'double' == $type) {
                return parserF($var);
            }
            return parserS($var);
        }
        return null;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getModule()
    {
        return $this->route['module'];
    }

    public function getAction()
    {
        return $this->route['action'];
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function getId()
    {
        return getModule() . '_' . getAction() . '_' . (! empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '');
    }

    public function getValues()
    {
        if (!empty($this->post)) {
            return $this->post;
        }
        return null;
    }
}
