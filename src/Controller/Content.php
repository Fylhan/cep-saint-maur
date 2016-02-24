<?php
namespace Controller;

use Core\Action;

class Content extends Action
{

    public function index($params = array())
    {
        $params = $this->news->getList(calculPage());
        $params['nomenu'] = true;
        $this->response->render('content/index', $params);
    }

    public function presentation($params = array())
    {
        return $this->show('presentation');
    }

    public function activities($params = array())
    {
        return $this->show('activities');
    }

    public function help($params = array())
    {
        return $this->show('help');
    }

    private function show($url)
    {
        $content = $this->content->getByUrl($url);
        $this->response->addVar('metaTitle', $content['title']);
        $this->response->addVar('metaDesc', $content['abstract']);
        $this->response->addVar('metaKw', $content['keywords']);
        $this->response->render('content/show', array(
            'page' => $content
        ));
    }
}
