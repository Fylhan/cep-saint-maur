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
        $this->controller->getResponse()->addVar('metaTitle', 'Dernières nouvelles');
        $this->controller->getResponse()->addVar('metaDesc', 'Dernières nouvelles');
        $this->controller->getResponse()->addVar('metaKw', 'news, événements');
        
        $this->controller->render('news/index', $tplPparams);
        $this->printOut();
    }

    public function show($params = NULL)
    {
        $id = parserI($this->controller->getRequest()->getParam('id'));
        $actualite = $this->news->findActualiteById($id, false);
        
        if (NULL != $actualite) {
            // -- Create params
            $actualite->computeExtrait();
            $tplPparams = array(
                'actualite' => $actualite
            );
            
            // -- Prepare Meta Data
            $this->controller->getResponse()->addVar('metaTitle', $actualite->getTitre());
            $this->controller->getResponse()->addVar('metaDesc', 'Dernières nouvelles : ' + $actualite->getTitre());
            $this->controller->getResponse()->addVar('metaKw', 'news');
        }
        // 404
        else {
            $e = new MVCException();
            $e->setPage('d\'actualité ' . $id);
            throw $e;
        }
        // -- Fill the body and print the page
        $this->controller->render('news/show', $tplPparams);
        $this->printOut();
    }
}
