<?php
namespace Controller;

use Core\Action;
use Core\Message;
use Model\Actualite;
use Service\CacheManager;

class Admin extends Action
{

    public function index($params = array())
    {
        $request = $this->controller->getRequest();
        $params['layout'] = 'index';
        
        // --- Actions
        // -- News
        // - Si on a une demande de suppression
        if (NULL != ($id = $request->getParam('delete', 'int'))) {
            if ($this->news->deleteActualite($id)) {
                // Cache obsolete
                CacheManager::resetCache();
                // Send response
                $message = new Message('La news ' . $id . ' a été supprimée. Bien joué !', OK);
                $this->controller->getResponse()->setFlash($message->toString());
                $this->controller->redirect('administration.html');
            }
            else {
                $message = new Message('Arg, ça ne marche pas. La news ' . $id . ' n\'a pas été supprimée.', ERREUR);
                $this->controller->getResponse()->addVar('Message', $message->toString());
            }
        }
        // - Si on update
        if ($request->isParam('sendNews')) {
            // Update
            $actualite = new Actualite();
            $actualite->fill($request->getParams());
            $actualite = $this->news->updateActualite($actualite);
            $params['Actualite'] = $actualite;
            // Cache obsolete
            CacheManager::resetCache();
            // Update parameter LastModificationActualites
            if (false != ($data = file_get_contents(ParameterFilePath))) {
                $toAdd = 'define(\'LastModificationActualites\', \'' . $actualite->getDateModif()->getTimestamp() . '\');';
                if (preg_match('!define\(\'LastModificationActualites\', \'[0-9]*\'\);!i', $data)) {
                    $data = preg_replace('!define\(\'LastModificationActualites\', \'[0-9]*\'\);!i', $toAdd, $data);
                }
                else {
                    $data .= $toAdd;
                }
                file_put_contents(ParameterFilePath, $data);
            }
            // Send response
            $message = new Message('Voilà une news ajoutée avec succès, bien joué !', OK);
            $this->controller->getResponse()->setFlash($message->toString());
            $this->controller->redirect('administration.html?update=' . $actualite->getId() . '#administrationPage');
        }
        // -- Parameters
        if ($request->isParam('sendData')) {
            // Generate content
            $data = '<?php' . "\n";
            if (null != $_POST['nbParPage'] && 0 != $_POST['nbParPage']) {
                $data .= 'define(\'NbParPage\', ' . parserI($_POST['nbParPage']) . ');' . "\n";
            }
            if (null != $_POST['nbParPageAdmin'] && 0 != $_POST['nbParPageAdmin']) {
                $data .= 'define(\'NbParPageAdmin\', ' . parserI($_POST['nbParPageAdmin']) . ');' . "\n";
            }
            $data .= 'define(\'DisplayHelp\', ' . parserI(@$_POST['displayHelp']) . ');' . "\n";
            if (null != $_POST['emailAdmin'] && NULL != $_POST['emailAdmin']) {
                $data .= 'define(\'EmailAdmin\', \'' . parserS($_POST['emailAdmin']) . '\');' . "\n";
            }
            $data .= 'define(\'LastModificationActualites\', \'' . LastModificationActualites . '\');' . "\n";
            $data .= "\n";
            
            // Open and update the data-user file
            if (false != ($fp = fopen(ParameterFilePath, 'w+')) && fwrite($fp, $data)) {
                fclose($fp);
                $message = new Message('Paramètres bien enregistrés, super !', OK);
                $this->controller->getResponse()->setFlash($message->toString());
                $this->controller->redirect('administration.html');
            }
            // Error
            else {
                $message = new Message('Arg, impossible de mettre à jour les paramètres. Désolé, mais il va falloir en parler avec 
un administrateur.', ERREUR);
                $this->controller->getResponse()->addVar('Message', $message->toString());
            }
        }
        
        // --- Display Actions
        // - Si on a une demande de modification
        if (NULL !== ($id = $request->getParam('update', 'int'))) {
            $params['layout'] = 'update';
            // Creation
            if (0 == $id) {
                $actualite = new Actualite();
            }
            else {
                $actualite = $this->news->findActualiteById($id);
            }
            if (null != $actualite) {
                $params['Actualite'] = $actualite;
            }
            else {
                $message = new Message('La news ' . $id . ' n\'existe pas. C\'est un problème ?', NEUTRE);
                $this->controller->getResponse()->setFlash($message->toString());
                $this->controller->redirect('administration.html');
            }
        }
        // - Sinon liste des actualités
        else {
            // Récupération des éléments
            $page = calculPage();
            $this->controller->getResponse()->addVar('page', $page);
            $nbElement = $this->news->calculNbActualites(true);
            $nbPage = calculNbPage(NbParPageAdmin, $nbElement);
            $appellationElement = 'actualité';
            $actualites = $this->news->findActualites($page, true, NbParPageAdmin);
            $params['page'] = $page;
            $params['nbElement'] = $nbElement;
            $params['nbMaxLienPagination'] = NbMaxLienPagination;
            $params['nbPage'] = $nbPage;
            $params['appellationElement'] = $appellationElement;
            $params['Actualites'] = $actualites;
        }
        
        // -- Prepare Meta Data
        $this->controller->getResponse()->addVar('metaTitle', 'Administration');
        
        // -- Create params
        $tplPparams = array(
            'UrlCourant' => 'administration.html',
            'NbParPage' => NbParPage,
            'NbParPageAdmin' => NbParPageAdmin,
            'DisplayHelp' => DisplayHelp,
            'UrlEmailAdmin' => getUrlImgEmail(EmailAdmin),
            'EmailAdmin' => EmailAdmin
        );
        $tplPparams = array_merge($tplPparams, $params);
        
        // -- Fill the body and print the page
        $this->controller->render('administration/layout-' . $params['layout'] . '.tpl', $tplPparams);
        $this->printOut();
    }

    public function purgeCache()
    {
        CacheManager::resetCache(true);
        $message = new Message('Cache supprimé ! On refait une partie de cache-cache ?', OK);
        $this->controller->getResponse()->setFlash($message->toString());
        $this->_vars = NULL;
        $this->controller->redirect('administration.html');
    }

    public function mailing($params = array())
    {
        $params = array();
        $params['ListesDiffusion'] = array(
            'flambeaux@cepsaintmaur.com',
            'gdj@cepsaintmaur.com',
            'musique@cepsaintmaur.com'
        );
        // -- Prepare Meta Data
        $this->controller->getResponse()->addVar('metaTitle', 'Administration - Gérer les listes de diffusion');
        
        // -- Create params
        $tplPparams = array(
            'UrlCourant' => 'administration-listes-diffusion.html'
        );
        $tplPparams = array_merge($tplPparams, $params);
        
        // -- Fill the body and print the page
        $this->controller->render('administration/layout-diffusion-list.tpl', $tplPparams);
        $this->printOut();
    }
}
