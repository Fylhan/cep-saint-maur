<?php
namespace Controller;

use Core\Action;

class Content extends Action
{

    public function index($params = array())
    {
        $params = $this->news->getList(calculPage());
        $params['nomenu'] = true;
        $this->controller->render('content/index', $params);
        $this->printOut();
    }

    public function presentation($params = array())
    {
        $this->controller->getResponse()->addVar('metaTitle', 'Nous connaître');
        $this->controller->getResponse()->addVar('metaDesc', 'Le CEP Saint-Maur est une communauté évangélique protestante. Quelle est notre histoire ? Qui sommes-nous ? En quoi croyons-nous ?');
        $this->controller->getResponse()->addVar('metaKw', 'jésus, saint-esprit, protestant, évangélique, caef, cnef, confession de foi');
        $this->controller->render('content/presentation', array());
        $this->printOut();
    }

    public function activities($params = array())
    {
        $this->controller->getResponse()->addVar('metaTitle', 'Nos activités');
        $this->controller->getResponse()->addVar('metaDesc', 'Au CEP Saint-Maur, nous proposons régulièrement diverses activités pour les plus jeunes comme pour les plus âgés.');
        $this->controller->getResponse()->addVar('metaKw', 'culte, atelier, flambeaux, scout, scoutisme, gdj, groupe de jeunes');
        $this->controller->render('dcontent/activities', array());
        $this->printOut();
    }

    public function help($params = array())
    {
        $this->controller->getResponse()->addVar('metaTitle', 'Politique d\'accessibilité');
        $this->controller->getResponse()->addVar('metaDesc', 'Politique d\'accessibilité du site du CEP Saint-Maur.');
        $this->controller->getResponse()->addVar('metaKw', 'accesskey, accessibilité, about');
        $this->controller->render('content/help', array());
        $this->printOut();
    }
}
