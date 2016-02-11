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
        $this->response->addVar('metaTitle', 'Nous connaître');
        $this->response->addVar('metaDesc', 'Le CEP Saint-Maur est une communauté évangélique protestante. Quelle est notre histoire ? Qui sommes-nous ? En quoi croyons-nous ?');
        $this->response->addVar('metaKw', 'jésus, saint-esprit, protestant, évangélique, caef, cnef, confession de foi');
        $this->response->render('content/presentation', array());
    }

    public function activities($params = array())
    {
        $this->response->addVar('metaTitle', 'Nos activités');
        $this->response->addVar('metaDesc', 'Au CEP Saint-Maur, nous proposons régulièrement diverses activités pour les plus jeunes comme pour les plus âgés.');
        $this->response->addVar('metaKw', 'culte, atelier, flambeaux, scout, scoutisme, gdj, groupe de jeunes');
        $this->response->render('content/activities', array());
    }

    public function help($params = array())
    {
        $this->response->addVar('metaTitle', 'Politique d\'accessibilité');
        $this->response->addVar('metaDesc', 'Politique d\'accessibilité du site du CEP Saint-Maur.');
        $this->response->addVar('metaKw', 'accesskey, accessibilité, about');
        $this->response->render('content/help', array());
    }
}
