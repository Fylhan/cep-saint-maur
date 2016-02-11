<?php
namespace Controller;

use Core\Action;
use Service\FeedUpdater;

class Feed extends Action
{

    public function index($params = NULL)
    {
        // -- Retrieve feed parameters
        $params['feedType'] = RSS2;
        $feedTypes = array(
            RSS1 => 'rss1',
            RSS2 => 'rss2',
            ATOM => 'atom'
        );
        if (isset($_GET['feed']) && NULL != $_GET['feed'] && "" != $_GET['feed'] && in_array($_GET['feed'], $feedTypes)) {
            $params['feedType'] = array_search($_GET['feed'], $feedTypes);
        }
        $excerpt = parserI(@$_GET['excerpt']);
        
        // -- Generate the Feed
        $feedUpdater = new FeedUpdater($this->news);
        $params['feedData'] = $feedUpdater->updateActualitesFeed($excerpt, $params['feedType']);
        
        $contentType = "application/rss+xml";
        if (RSS1 == $params['feedType']) {
            $contentType = "application/rdf+xml";
        }
        else 
            if (ATOM == $params['feedType']) {
                $contentType = "application/atom+xml";
            }
        header("Content-Type: " . $contentType);
        // Feed
        echo $params['feedData'];
        $this->response->printOut();
    }
}
