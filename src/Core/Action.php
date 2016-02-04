<?php
namespace Core;

use Model\ActualiteDAOImpl;

abstract class Action
{

    protected $controller;

    protected $news;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->news = new ActualiteDAOImpl();
    }

    public function render($toDisplay)
    {
        $this->controller->render($toDisplay);
    }

    public function printOut()
    {
        $this->controller->getResponse()->printOut();
    }

    protected function _forward($module, $action, $params = null)
    {
        $this->controller->forward($module, $action, $params);
    }

    protected function _redirect($url, $permanent = false)
    {
        $this->controller->redirect($url, $permanent);
    }
}
