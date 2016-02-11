<?php
namespace Controller;

use Core\Action;
use Core\MVCException;

class News extends Action
{

    public function index($params = NULL)
    {
        $tplPparams = $this->news->getList(calculPage());
        $tplPparams['UrlTri'] = '';
        $tplPparams['UrlCourant'] = 'evenements.html';
        $this->response->addVar('metaTitle', 'Dernières nouvelles');
        $this->response->addVar('metaDesc', 'Dernières nouvelles');
        $this->response->addVar('metaKw', 'news, événements');
        
        $this->response->render('news/index', $tplPparams);
    }

    public function show($params = array())
    {
        $id = parserI($this->request->getParam('id'));
        $news = $this->news->findActualiteById($id, false);
        
        if (NULL != $news) {
            $params['actualite'] = $news;
            $this->response->addVar('metaTitle', $news->getTitre());
            $this->response->addVar('metaDesc', 'Dernières nouvelles : ' + $news->getTitre());
            $this->response->addVar('metaKw', 'news');
        }
        // 404
        else {
            $e = new MVCException();
            $e->setPage('d\'actualité ' . $id);
            throw $e;
        }
        $this->response->render('news/show', $params);
    }
}
