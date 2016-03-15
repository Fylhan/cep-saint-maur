<?php
namespace Filter;

use Core\Base;

class CacheFilter extends Base implements Filterable
{

    public function preFilter()
    {
        if ($this->cacheManager->isEnabled()) {
            // Cache available
            if (false !== ($pageData = $this->cacheManager->retrieve())) {
                $this->response->setBody($pageData);
                $this->response->printOut();
                return false;
            }
            // Start create a cached version
            ob_start();
        }
        return true;
    }

    public function postFilter()
    {
        if ($this->cacheManager->isEnabled()) {
            // Finish create a cached version
            $this->cacheManager->cache(ob_get_contents());
            ob_end_flush();
        }
    }
}
