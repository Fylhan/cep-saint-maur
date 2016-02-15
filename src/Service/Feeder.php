<?php
namespace Service;

use Core\Base;

class Feeder extends Base
{

    public function generateFeed($items, $feedType = RSS2)
    {
        // - Configure Feed
        $feed = \FeedWriterFactory::create($feedType);
        $feed->setTitle(SiteNom);
        $feed->setLink(SITE_PATH);
        $feed->setDescription(SiteDesc);
        $feedImgUrl = ILLUSTRATION_PATH . '/cepsaintmaur.png';
        $feed->setImage(SiteNom, SITE_PATH, $feedImgUrl); // Image title and link must match with the 'title' and 'link' channel elements for RSS 2.0
        $feed->setChannelElement('language', DefaultLocale);
        $feed->setChannelElement('copyright', SITE_PATH);
        $feed->setChannelElement('pubDate', date(DATE_RSS, time()));
        
        
        // - Generate feed
        foreach ($items as $item) {
            $feed->addItem($item);
        }
        $feedData = $feed->generateFeedAndRetrieve();
        return $feedData;
    }

    /**
     * Update RSS1, 2 and ATOM Feeds for actualites
     */
    public function updateAllNewsFeed()
    {
        CacheManager::purgeCache('api');
        $this->updateNewsFeed(false, RSS1);
        $this->updateNewsFeed(false, RSS2);
        $this->updateNewsFeed(false, ATOM);
    }
}
