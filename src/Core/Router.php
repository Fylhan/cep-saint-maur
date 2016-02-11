<?php
namespace Core;

use RuntimeException;
use Filter\Filterable;
use Filter\SecurityFilter;
use Filter\CacheFilter;

class Router extends Base
{

    private $_defaults = array(
        'module' => 'accueil',
        'action' => 'Accueil'
    );

    private $_filters = array();
    // $cacheFilter = new CacheFilter($this);
    // $this->addFilter($cacheFilter);
    // $securityFilter = new SecurityFilter($this);
    // $this->addFilter($securityFilter);
    public function dispatch($defaults = null)
    {
        try {
            if (null != $defaults && '' != $defaults && count($defaults) > 0) {
                $this->_defaults = $defaults;
            }
            
            $parsed = $this->request->route($this->_defaults);
            
            $pass = true;
            foreach ($this->_filters as $filter) {
                $pass *= $filter->preFilter();
            }
            
            if ($pass) {
                $this->forward($parsed['module'], $parsed['action']);
                
                foreach (array_reverse($this->_filters) as $filter) {
                    $filter->postFilter();
                }
            }
        } catch (\Exception $e) {
            $this->_launchException($e)->printOut();
        }
    }

    public function forward($module, $action, $params = null)
    {
        $instance = $this->_getAction($module, $action);
        $instance->{$action}();
    }

    public function addFilter(Filterable $filter)
    {
        $this->_filters[] = $filter;
    }

    private function _getAction($module, $action)
    {
        $this->response->addVar('module', $module);
        $this->response->addVar('action', $action);
        $class = '\\Controller\\' . ucfirst($module);
        if (! class_exists($class)) {
            throw new RuntimeException('Controller not found');
        }
        
        if (! method_exists($class, $action)) {
            throw new RuntimeException('Action not implemented');
        }
        
        $instance = new $class($this->container);
        return $instance;
    }

    private function _launchException($e)
    {
        $error = $e->__toString();
        $this->response->addVar('error', $error);
        if ($e instanceof MVCException) {
            $this->response->addVar('metaTitle', 'Erreur - Page introuvable');
            $this->response->addVar('page', $e->getPage());
            $this->response->render('error/404');
        }
        else {
            $this->response->addVar('metaTitle', 'Erreur critique');
            $this->response->render('error/500');
        }
        logThatException($e);
        return $this->response;
    }
}
