<?php
namespace Filter;

use Core\Base;

class SecurityFilter extends Base implements Filterable
{

    public function preFilter()
    {
        if (! in_array($this->request->getController(), array(
            'admin',
            'upload'
        ))) {
            return true;
        }
        
        /*
         * Algorithm
         * * If est déjà loggé (session uid existe, pad de changement d'IP et pas la fin) : return true
         * * If se logue (not banished, security key, bcrypt, enregistrement des sessions, avec des cookies pour conserver la session malgrè la fermeture du navigateur) : manage banishement and return true
         * * Sinon (error ou arrivée sur la page sans être logué, affichage formulaire de login): return false
         */
        // -- Is already logged in
        if ($this->auth->isLoggedIn()) {
            return true;
        }
        
        // -- Login request: check authentication
        if ($this->auth->login()) {
            return true;
        }
        
        // -- Error, or no security key: display only the form
        $this->response->addVar('metaTitle', 'Administration - Authentification');
        $this->response->render('admin/login');
        return false;
    }

    public function postFilter()
    {}
}
