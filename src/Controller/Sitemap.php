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
        $type = $this->controller->getRequest()->getParam('type');
        
        // -- List site pages
        $params['UrlEntries'][] = new HumanUrlEntry('Accueil - ' . SiteNom, SITE_PATH, LastModificationActualites, ChangeFreqValues::WEEKLY, 0.8);
        $params['UrlEntries'][] = new HumanUrlEntry('Nous contacter', SITE_PATH . 'contact.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
        $params['UrlEntries'][] = new HumanUrlEntry('Nous connaître', SITE_PATH . 'qui-sommes-nous.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
        $params['UrlEntries'][] = new HumanUrlEntry('Nos activités', SITE_PATH . 'activites.html', LastModification, ChangeFreqValues::YEARLY, 0.6);
        $feedGroup = new GroupEntry('Rester informer');
        $feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles', SITE_PATH . 'feed.xml', LastModificationActualites, ChangeFreqValues::WEEKLY));
        $feedGroup->addChild(new HumanUrlEntry('Flux RSS des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?excerpt=1', LastModificationActualites, ChangeFreqValues::WEEKLY, 0.4));
        $feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles', SITE_PATH . 'feed.xml?feed=atom', LastModificationActualites, ChangeFreqValues::WEEKLY));
        $feedGroup->addChild(new HumanUrlEntry('Flux ATOM des dernières nouvelles (résumé seulement)', SITE_PATH . 'feed.xml?feed=atom&excerpt=1', LastModificationActualites, ChangeFreqValues::WEEKLY, 0.4));
        $params['UrlEntries'][] = $feedGroup;
        $params['UrlEntries'][] = new HumanUrlEntry('Politique d\'accessibilité', SITE_PATH . 'politique-accessibilite.html', LastModification, ChangeFreqValues::YEARLY, 0.3);
        $params['UrlEntries'][] = new HumanUrlEntry('Plan du site', SITE_PATH . 'sitemap.html', LastModificationActualites, ChangeFreqValues::WEEKLY, 0.3);
        // Actualites
        $nbOfActualites = 50;
        $actualites = $this->news->findAllActualites($nbOfActualites);
        $actualiteGroup = new GroupEntry('Dernières nouvelles', SITE_PATH . 'evenements.html');
        foreach ($actualites as $actualite) {
            $entry = new HumanUrlEntry($actualite->getTitre(), SITE_PATH . 'evenement-' . $actualite->getId() . '.html', $actualite->getDateModif()->getTimestamp(), ChangeFreqValues::YEARLY);
            $entry->setDescription($actualite->getDateDebutString());
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
            $tpl = 'sitemap-human.twig';
        }
        else {
            $this->controller->getResponse()->addHeader("Content-Type", "application/xml");
            $tpl = 'sitemap.twig';
        }
        $this->controller->render('api/' . $tpl, $params);
        $this->printOut();
    }
}
