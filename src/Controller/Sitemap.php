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
        
        // -- List site pages
        $lastUpdate = $this->news->getLastUpdate();
        $params['UrlEntries'][] = new HumanUrlEntry('Accueil - ' . SiteNom, SITE_PATH, $lastUpdate, ChangeFreqValues::WEEKLY, 0.8);
        $params['UrlEntries'][] = new HumanUrlEntry('Nous contacter', SITE_PATH . 'contact.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
        $params['UrlEntries'][] = new HumanUrlEntry('Nous connaître', SITE_PATH . 'qui-sommes-nous.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
        $params['UrlEntries'][] = new HumanUrlEntry('Nos activités', SITE_PATH . 'activites.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
        $feedGroup = new GroupEntry('Rester informer');
        $feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles', SITE_PATH . 'feed.xml', $lastUpdate, ChangeFreqValues::WEEKLY));
        $feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?excerpt=1', $lastUpdate, ChangeFreqValues::WEEKLY, 0.4));
        $feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles', SITE_PATH . 'feed.xml?feed=atom', $lastUpdate, ChangeFreqValues::WEEKLY));
        $feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?feed=atom&excerpt=1', $lastUpdate, ChangeFreqValues::WEEKLY, 0.4));
        $params['UrlEntries'][] = $feedGroup;
        $params['UrlEntries'][] = new HumanUrlEntry('Politique d\'accessibilité', SITE_PATH . 'politique-accessibilite.html', LastModification, ChangeFreqValues::YEARLY, 0.3);
        $params['UrlEntries'][] = new HumanUrlEntry('Plan du site', SITE_PATH . 'sitemap.html', $lastUpdate, ChangeFreqValues::WEEKLY, 0.3);
        // Actualites
        $nbOfActualites = 50;
        $actualites = $this->news->findAllActualites($nbOfActualites);
        $actualiteGroup = new GroupEntry('Dernières nouvelles', SITE_PATH . 'evenements.html');
        foreach ($actualites as $actualite) {
            $entry = new HumanUrlEntry($actualite->getTitre(), SITE_PATH . 'evenement-' . $actualite->getId() . '.html', $actualite->getDateModif(), ChangeFreqValues::YEARLY);
            $entry->setDescription(dateFr($actualite->getDateDebut()));
            $actualiteGroup->addChild($entry);
        }
        if ($nbOfActualites < $this->news->calculNbActualites()) {
            $actualiteGroup->addChild(new HumanUrlEntry('Et d\'autres encore...', SITE_PATH . 'evenements.html'));
        }
        $params['UrlEntries'][] = $actualiteGroup;
        
        usort($params['UrlEntries'], function ($a, $b) {
            return $a->getPriority() < $b->getPriority();
        });
        
        // -- Fill the body and print the page
        if ('human' == $type) {
            $tpl = 'index-human';
        }
        else {
            $this->response->addHeader("Content-Type", "application/xml");
            $tpl = 'index';
        }
        $this->response->render('sitemap/' . $tpl, $params);
    }
}
