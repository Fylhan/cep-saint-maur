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

    private $attributes;

    /**
     * Constructor
     *
     * @access public
     * @param \Pimple\Container $container            
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

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isParam($key, $value = NULL)
    {
        return (NULL != $this->getParam($key) && (NULL == $value || $value == $this->getParam($key)));
    }

    public function getParam($key, $type = 'string', $defaultValue = '')
    {
        $var = null;
        if (! empty($this->get) && isset($this->get[$key])) {
            $var = $this->get[$key];
        }
        else 
            if (! empty($this->post) && isset($this->post[$key])) {
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
        return $defaultValue;
    }

    public function matchRoute()
    {
        $this->attributes = array(
            '_controller' => parserUrl($this->get['controller'], false, false),
            '_action' => parserUrl($this->get['action'], false, false)
        );
        $this->attributes['_route'] = $this->getController().'/'.$this->getAction();
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getRoute()
    {
        return $this->attributes['_route'];
    }

    public function getController()
    {
        return $this->attributes['_controller'];
    }

    public function getAction()
    {
        return $this->attributes['_action'];
    }

    public function getId()
    {
        return $this->getController() . '_' . $this->getAction() . '_' . (! empty($this->server["QUERY_STRING"]) ? '?' . $this->server["QUERY_STRING"] : '');
    }

    public function getValues()
    {
        if (! empty($this->post)) {
            return $this->post;
        }
        return null;
    }
}
