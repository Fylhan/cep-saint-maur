<?php
namespace Core;

use RuntimeException;
use Filter\Filterable;
use Filter\SecurityFilter;
use Filter\CacheFilter;

class Router
{

    private $_defaults = array(
        'module' => 'accueil',
        'action' => 'Accueil'
    );

    private $_request;

    private $_response;

    private $_twig;

    private $_filters = array();

    private static $_instance = null;
    
    // --- Constructor
    private function __construct()
    {
        $this->_request = Request::getInstance();
        $this->_response = Response::getInstance();
        
        // Préparation du moteur de template
        $loader = new \Twig_Loader_Filesystem(array(
            INCLUDE_PATH . '/Template/',
            DEFAULT_THEME_PATH . '/js/'
        ));
        $this->_twig = new \Twig_Environment($loader, array(
            'cache' => CACHE_PATH . '/twig',
            'debug' => DEBUG,
            'charset' => Encodage
        ));
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    // --- Coeur
    public function dispatch($defaults = null)
    {
        try {
            // - Action par défaut
            if (null != $defaults && '' != $defaults && count($defaults) > 0) {
                $this->_defaults = $defaults;
            }
            
            // - Analyse la requête reçue pour trouver l'action à effectuer
            $parsed = $this->_request->route($this->_defaults);
            
            // - Add filters
            // Cache
            $cacheFilter = new CacheFilter($this);
            $this->addFilter($cacheFilter);
            // Security
            $securityFilter = new SecurityFilter($this);
            $this->addFilter($securityFilter);
            
            // - Prefilter
            $pass = true;
            foreach ($this->_filters as $filter) {
                $pass *= $filter->preFilter();
            }
            
            if ($pass) {
                // - Action
                $this->forward($parsed['module'], $parsed['action']);
                
                // - Postfilter
                foreach (array_reverse($this->_filters) as $filter) {
                    $filter->postFilter();
                }
            }
        } catch (\Exception $e) {
            $this->_launchException($e)->printOut();
        }
    }

    public function forward($module, $action, $params = null)
    {
        $instance = $this->_getAction($module, ('default' == $module ? 'Static' : $action));
        $instance->{$action}();
    }

    public function redirect($url, $end = true, $permanent = false)
    {
        $this->_response->redirect($url, $end, $permanent);
    }

    public function render($tplPath, $params = array())
    {
        // -- Ajout des params par défaut
        $globalParams = array(
            'SitePath' => SITE_PATH,
            'SiteNom' => SiteNom,
            'SiteDesc' => SiteDesc,
            'ThemePath' => DEFAULT_THEME_PATH,
            'ImgPath' => DEFAULT_THEME_PATH . '/img/',
            'LibPath' => INCLUDE_PATH . '/lib',
            'IllustrationPath' => ILLUSTRATION_PATH . '/',
            'CurrentPath' => getCurrentPage(),
            'FeedPath' => FEED_PATH,
            'StyleEnabled' => StyleEnabled,
            'Locale' => getLocale(),
            'Debug' => DEBUG,
            'Encodage' => Encodage,
            'Author' => Author,
            'metaTitle' => getMetaTitle($this->_response->getVar('metaTitle'), @$this->_response->getVar('page')),
            'metaDesc' => getMetaDesc($this->_response->getVar('metaDesc'), @$this->_response->getVar('page')),
            'metaKw' => getMetaKw($this->_response->getVar('metaKw')),
            'action' => $this->_request->getAction(),
            'FlashMessage' => @$this->_response->getFlash()
        );
        // -- Préparation des paramêtres
        $this->_response->addVars($globalParams);
        $this->_response->addVars($params);
        
        // -- Minify CSS and JS (TODO: move somewhere else)
        if (DEBUG) {
            $cssFiles = array(
                'ie',
                'style',
                'highlight-default',
                'redactor'
            );
            foreach ($cssFiles as $cssFile) {
                if (DEBUG || ! is_file(DEFAULT_THEME_PATH . '/css/' . $cssFile . '.min.css')) {
                    file_put_contents(DEFAULT_THEME_PATH . '/css/' . $cssFile . '.min.css', minifyCss(file_get_contents(DEFAULT_THEME_PATH . '/css/' . $cssFile . '.css')));
                }
            }
            $jsFiles = array(
                'html5',
                'contact'
            );
            foreach ($jsFiles as $jsFile) {
                if (DEBUG || ! is_file(DEFAULT_THEME_PATH . '/js/' . $jsFile . '.min.js')) {
                    file_put_contents(DEFAULT_THEME_PATH . '/js/' . $jsFile . '.min.js', minifyJs(file_get_contents(DEFAULT_THEME_PATH . '/js/' . $jsFile . '.js')));
                }
            }
        }
        
        // -- Création du template
        $tpl = $this->_twig->loadTemplate($tplPath);
        $body = $tpl->render($this->_response->getVars());
        // -- Ajout du body
        $this->_response->setBody($body);
    }

    public function addFilter(Filterable $filter)
    {
        $this->_filters[] = $filter;
    }

    private function _getAction($module, $action)
    {
        $this->_response->addVar('module', $module);
        $this->_response->addVar('action', $action);
        $class = '\\Controller\\' . ucfirst($module);
        if (! class_exists($class)) {
            throw new RuntimeException('Controller not found');
        }
        
        if (! method_exists($class, $action)) {
            throw new RuntimeException('Action not implemented');
        }
        
        $instance = new $class($this);
        return $instance;
    }

    private function _launchException($e)
    {
        $error = $e->__toString();
        $this->_response->addVar('error', $error);
        if ($e instanceof MVCException) {
            $this->_response->addVar('metaTitle', 'Erreur - Page introuvable');
            $this->_response->addVar('page', $e->getPage());
            $this->render('error/404.tpl');
        }
        else {
            $this->_response->addVar('metaTitle', 'Erreur critique');
            $this->render('error/500.tpl');
        }
        logThatException($e);
        return $this->_response;
    }
    
    // --- Set/Get
    public function getResponse()
    {
        return $this->_response;
    }

    public function getRequest()
    {
        return $this->_request;
    }
}
?>