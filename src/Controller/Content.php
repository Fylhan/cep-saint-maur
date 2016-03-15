<?php
namespace Controller;

use Core\Action;

class Content extends Action
{

    public function index($params = array())
    {
        $params = $this->news->getList(calculPage());
        $params['nomenu'] = true;
        return $this->response->render('content/index', $params);
    }

    public function presentation($params = array())
    {
        return $this->show('qui-sommes-nous');
    }

    public function activities($params = array())
    {
        return $this->show('activites');
    }

    public function help($params = array())
    {
        return $this->show('politique-accessibilite');
    }

    private function show($url)
    {
        $content = $this->content->getByUrl($url);
        $this->response->addVar('metaTitle', $content['title']);
        $this->response->addVar('metaDesc', $content['abstract']);
        $this->response->addVar('metaKw', $content['keywords']);
        return $this->response->render('content/show', array(
            'page' => $content
        ));
    }
}
