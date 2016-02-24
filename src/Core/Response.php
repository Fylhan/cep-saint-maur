<?php
namespace Core;

class Response extends Base
{

    private $_blocks = array();

    private $_vars = array();

    private $_headers = array();

    private $_body;

    public function redirect($url, $end = true, $permanent = false)
    {
        if ($permanent) {
            $this->_headers['Status'] = '301 Moved Permanently';
        }
        else {
            $this->_headers['Status'] = '302 Found';
        }
        $this->_headers['location'] = $url;
        // if (NULL != $this->_vars && count($this->_vars) > 0) {
        // $this->_headers['location'] .= '?' . http_build_query($this->_vars);
        // }
        
        if ($end) {
            $this->printOut();
            exit();
        }
    }

    public function printOut()
    {
        foreach ($this->_headers as $key => $value) {
            header($key . ':' . $value);
        }
        echo $this->_body;
    }

    public function render($tplPath, $params = array())
    {
        // -- Ajout des params par défaut
        $globalParams = array(
            'SitePath' => SITE_PATH,
            'SiteNom' => SiteNom,
            'SiteDesc' => SiteDesc,
            'CssPath' => CSS_PATH.'/',
            'JsPath' => JS_PATH.'/',
            'ImgPath' => IMG_PATH.'/',
            'CurrentPath' => getCurrentPage(),
            'Locale' => getLocale(),
            'Debug' => DEBUG,
            'Encodage' => Encodage,
            'Author' => Author,
            'metaTitle' => getMetaTitle($this->response->getVar('metaTitle'), @$this->response->getVar('page')),
            'metaDesc' => getMetaDesc($this->response->getVar('metaDesc'), @$this->response->getVar('page')),
            'metaKw' => getMetaKw($this->response->getVar('metaKw')),
            'action' => $this->request->getAction(),
            'FlashMessage' => @$this->response->getFlash()
        );
        // -- Préparation des paramêtres
        $this->response->addVars($globalParams);
        $this->response->addVars($params);
        
        // -- Minify CSS and JS (TODO: move somewhere else)
        if (DEBUG) {
            $cssFiles = array(
                'ie',
                'style',
                'highlight-default',
                'redactor'
            );
            foreach ($cssFiles as $cssFile) {
                if (DEBUG || ! is_file(CSS_PATH . '/' . $cssFile . '.min.css')) {
                    file_put_contents(CSS_PATH . '/' . $cssFile . '.min.css', minifyCss(file_get_contents(ASSETS_PATH . '/css/' . $cssFile . '.css')));
                }
            }
            $jsFiles = array(
                'html5',
                'contact'
            );
            foreach ($jsFiles as $jsFile) {
                if (DEBUG || ! is_file(JS_PATH . '/' . $jsFile . '.min.js')) {
                    file_put_contents(JS_PATH . '/' . $jsFile . '.min.js', minifyJs(file_get_contents(ASSETS_PATH . '/js/' . $jsFile . '.js')));
                }
            }
        }
        
        // Create body
        $tpl = $this->template->loadTemplate($tplPath . '.html.twig');
        $body = $tpl->render($this->response->getVars());
        $this->setBody($body);
        
        $this->printOut();
        exit();
    }
    
    /**
     * 
     * @param $data Array for JSON or string or anything else
     * @param string $type JSON by default
     */
    public function renderData($data, $type='json')
    {
        $this->addHeader('Cache-Control', 'no-cache, must-revalidate');
        $this->addHeader('Expires', 'Sat, 29 Oct 2011 13:00:00 GMT+1'); // A date in the past
        $this->addHeader('Content-type', 'application/'.$type.'; charset=UTF-8');
        $this->setBody('json' === $type ? json_encode($data) : $data);
        $this->printOut();
        exit();
    }

    public function addBlock($key, $block)
    {
        $this->_blocks[$key] = $block;
    }

    public function getBlock($key)
    {
        return $this->_blocks[$key];
    }

    public function getBlocks()
    {
        return $this->_blocks;
    }

    public function addVar($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    public function addVars($array)
    {
        $this->_vars = array_merge($this->_vars, $array);
    }

    public function getVar($key)
    {
        return array_key_exists($key, $this->_vars) ? $this->_vars[$key] : '';
    }

    public function getVars()
    {
        return $this->_vars;
    }

    public function addFlash($msg, $type)
    {
        $_SESSION['flashNb'] = 0;
        $msg = new Message($msg, $type);
        $_SESSION['flash'] = $msg->toString();
    }

    /**
     * @deprecated
     */
    public function setFlash(Message $value)
    {
        $_SESSION['flashNb'] = 0;
        $_SESSION['flash'] = $value;
    }

    public function getFlash()
    {
        if (isset($_SESSION['flashNb']) && $_SESSION['flashNb'] >= 1) {
            unset($_SESSION['flash']);
            return '';
        }
        $_SESSION['flashNb'] = @$_SESSION['flashNb'] + 1;
        return @$_SESSION['flash'];
    }

    public function addHeader($key, $value)
    {
        $this->_headers[$key] = $value;
    }

    public function setBody($value)
    {
        $this->_body = $value;
    }
}
