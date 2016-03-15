<?php
namespace Service;

use Core\Base;

class CacheManager extends Base
{

    public function getCacheDir($controller = null)
    {
        return CACHE_PATH . '/' . (! empty($controller) ? $controller : $this->request->getController());
    }

    public function getCacheFilepath()
    {
        return $this->getCacheDir() . '/' . $this->request->getId() . '.cache';
    }

    public function isEnabled()
    {
        return (! $this->request->isPost() && ! in_array($this->request->getController(), array(
            'admin',
            'upload'
        )));
    }

    /**
     * Put a page in the cache.
     *
     * @param string $data
     *            Data to be stored to cache
     * @return int This function returns the number of bytes that were written to the file, or false on failure
     */
    public function cache($data)
    {
        try {
            if (! is_dir($this->getCacheDir())) {
                mkdir($this->getCacheDir(), 0755, true);
            }
            return file_put_contents($this->getCacheFilepath(), $data);
        } catch (\Exception $e) {
            logThatException($e);
            return false;
        }
    }

    /**
     * Retrieve cache if any
     *
     * @return false if there is no cache or the cached data
     */
    public function retrieve()
    {
        if (is_file($this->getCacheFilepath())) {
            return file_get_contents($this->getCacheFilepath());
        }
        return false;
    }

    /**
     * Purge cache of news and content controller, or all controllers
     *
     * @param string $all            
     */
    public function resetCache($all = false)
    {
        $this->purgeCache('feed');
        $this->purgeCache('sitemap');
        $this->purgeCache('content');
        if ($all) {
            $this->purgeCache('contact');
            $this->purgeCache('twig');
            $this->purgeCache('email');
        }
    }

    /**
     * Purge the whole cache of one controller
     */
    public function purgeCache($controller)
    {
        $this->rrmdir($this->getCacheDir($controller));
    }

    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
