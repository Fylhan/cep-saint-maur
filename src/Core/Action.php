<?php
namespace Core;

use Model\ActualiteDAOImpl;

abstract class Action
{

    /**
     *
     * @var \Core\Router
     */
    protected $controller;

    /**
     *
     * @var \Core\Request
     */
    protected $request;

    /**
     *
     * @var \Core\Response
     */
    protected $response;

    /**
     *
     * @var \Model\ActualiteDAOImpl
     */
    protected $news;

    public function __construct(\Core\Router $controller)
    {
        $this->controller = $controller;
        $this->request = $controller->getRequest();
        $this->response = $controller->getResponse();
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

    protected function forward($module, $action, $params = null)
    {
        $this->controller->forward($module, $action, $params);
    }

    protected function redirect($url, $permanent = false)
    {
        $this->controller->redirect($url, $permanent);
    }
}
