<?php
namespace Controller;

use Core\Action;
use Service\SitemapGenerator\ChangeFreqValues;
use Service\SitemapGenerator\GroupEntry;
use Service\SitemapGenerator\HumanUrlEntry;

class Sitemap extends Action
{

    public function index($params = array())
    {
        $type = $this->request->getParam('type');
        $lastUpdate = $this->news->getLastUpdate();
        
        // List pages
        $params['UrlEntries'][] = new HumanUrlEntry('Accueil - ' . SiteNom, SITE_PATH, $lastUpdate, ChangeFreqValues::WEEKLY, 0.8);
        $contents = $this->content->getList();
        foreach ($contents as $content) {
            $params['UrlEntries'][] = new HumanUrlEntry($content['title'], SITE_PATH . $content['url'] . '.html', $content['date_update'], ChangeFreqValues::YEARLY, 0.7);
        }
        $nbOfActualites = 50;
        $actualites = $this->news->findAllActualites($nbOfActualites);
        $actualiteGroup = new GroupEntry('Dernières nouvelles', SITE_PATH . 'evenements.html');
        foreach ($actualites as $actualite) {
            $actualiteGroup->addChild(new HumanUrlEntry($actualite->getTitre(), SITE_PATH . 'evenement-' . $actualite->getId() . '.html', $actualite->getDateModif(), ChangeFreqValues::YEARLY, 0.6, dateFr($actualite->getDateDebut())));
        }
        if ($nbOfActualites < $this->news->calculNbActualites()) {
            $actualiteGroup->addChild(new HumanUrlEntry('Et d\'autres encore...', SITE_PATH . 'evenements.html'));
        }
        $params['UrlEntries'][] = $actualiteGroup;
        $feedGroup = new GroupEntry('Rester informer');
        $feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles', SITE_PATH . 'feed.xml', $lastUpdate, ChangeFreqValues::WEEKLY));
        $feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?excerpt=1', $lastUpdate, ChangeFreqValues::WEEKLY, 0.4));
        $feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles', SITE_PATH . 'feed.xml?feed=atom', $lastUpdate, ChangeFreqValues::WEEKLY));
        $feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?feed=atom&excerpt=1', $lastUpdate, ChangeFreqValues::WEEKLY, 0.4));
        $params['UrlEntries'][] = $feedGroup;
        $params['UrlEntries'][] = new HumanUrlEntry('Plan du site', SITE_PATH . 'sitemap.html', $lastUpdate, ChangeFreqValues::WEEKLY, 0.3);
        
        if ('human' == $type) {
            $tpl = 'index-human';
        }
        else {
            $this->response->addHeader("Content-Type", "application/xml");
            $tpl = 'index';
        }
        return $this->response->render('sitemap/' . $tpl, $params);
    }
}
