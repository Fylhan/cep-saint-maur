<?php
namespace Controller;

use Core\Action;

class Feed extends Action
{

    public function index($params = array())
    {
        $feedTypes = array(
            RSS1 => 'rss1',
            RSS2 => 'rss2',
            ATOM => 'atom'
        );
        $feedType = array_search($this->request->getParam('feed', 'string', 'rss2'), $feedTypes);
        $excerpt = $this->request->getParam('excerpt', 'int');
        
        // -- Generate the Feed
        $items = $this->news->getAllForFeeds(NbItemPerFeed, $excerpt, $feedType);
        $data = $this->feeder->generateFeed($items, $feedType);
        
        $contentType = "rss+xml";
        if (RSS1 == $feedType) {
            $contentType = "rdf+xml";
        }
        elseif (ATOM == $feedType) {
            $contentType = "atom+xml";
        }
        return $this->response->renderData($data, $contentType);
    }
}
