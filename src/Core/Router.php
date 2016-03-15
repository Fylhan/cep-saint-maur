<?php
namespace Core;

use RuntimeException;
use Filter\Filterable;

class Router extends Base
{

    private $filters = array();

    public function addFilter(Filterable $filter)
    {
        $this->filters[] = $filter;
    }

    public function dispatch()
    {
        try {
            $this->request->matchRoute();
            
            $pass = true;
            foreach ($this->filters as $filter) {
                $pass *= $filter->preFilter();
            }
            
            if ($pass) {
                $this->forward($this->request);
                
                foreach (array_reverse($this->filters) as $filter) {
                    $filter->postFilter();
                }
            }
        } catch (\Exception $e) {
            $this->launchException($e);
        }
    }

    public function forward(Request $request)
    {
        $this->response->addVar('controller', $request->getController());
        $this->response->addVar('action', $request->getAction());
        $instance = $this->getController($request->getController(), $request->getAction());
        $instance->{$request->getAction()}();
    }

    private function getController($controller, $action)
    {
        $class = '\\Controller\\' . ucfirst($controller);
        if (! class_exists($class)) {
            throw new RuntimeException('Controller "' . $controller . '" not found');
        }
        if (! method_exists($class, $action)) {
            throw new RuntimeException('Action "' . $controller . '::' . $action . '" not implemented');
        }
        $instance = new $class($this->container);
        return $instance;
    }

    private function launchException($e)
    {
        logThatException($e);
        $this->response->addVar('error', $e->__toString());
        if ($e instanceof RuntimeException) {
            $this->response->addVar('metaTitle', 'Erreur - Page introuvable');
            $this->response->addVar('page', $this->request->getRoute());
            return $this->response->render('error/404');
        }
        $this->response->addVar('metaTitle', 'Erreur critique');
        return $this->response->render('error/500');
    }
}
