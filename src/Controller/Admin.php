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
        $params = $this->news->getList(calculPage(), true, NbParPageAdmin, 'actualité');
        $params['UrlEmailAdmin'] = getUrlImgEmail(EmailAdmin);
        $params['NbParPage'] = NbParPage;
        $params['NbParPageAdmin'] = NbParPageAdmin;
        $params['EmailAdmin'] = EmailAdmin;
        $params['DisplayHelp'] = DisplayHelp;

        $this->response->addVar('metaTitle', 'Administration');
        $this->controller->render('administration/index', $params);
        $this->printOut();
    }

    public function update($params = array())
    {
        $id = $this->request->getParam('id', 'int');
        
        // Update
        if ($this->request->isParam('sendNews')) {
            $actualite = new Actualite();
            $actualite->fill($this->request->getParams());
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
                    if (empty($data)) {
                        $data = '<?php'."\n";
                    }
                    $data .= $toAdd;
                }
                file_put_contents(ParameterFilePath, $data);
            }
            // Send response
            $message = new Message('Voilà une news ajoutée avec succès, bien joué !', OK);
            $this->response->setFlash($message->toString());
            $this->response->redirect('administration-update.html?id=' . $actualite->getId() . '#administrationPage');
        }
        
        // Display
        if (0 == $id) {
            $params['Actualite'] = new Actualite();
        }
        else {
            $params['Actualite'] = $this->news->findActualiteById($id);
        }
        if (null == $params['Actualite']) {
            $message = new Message('La news ' . $id . ' n\'existe pas. C\'est un problème ?', NEUTRE);
            $this->response->setFlash($message->toString());
            $this->response->redirect('administration.html');
        }
        $this->controller->render('administration/update', $params);
        $this->printOut();
    }

    public function delete($params = array())
    {
        $id = $this->request->getParam('id', 'int');
        if ($this->news->deleteActualite($id)) {
            // Cache obsolete
            CacheManager::resetCache();
            // Send response
            $message = new Message('La news ' . $id . ' a été supprimée. Bien joué !', OK);
            $this->response->setFlash($message->toString());
            $this->response->redirect('administration.html');
        }
        $message = new Message('Arg, ça ne marche pas. La news ' . $id . ' n\'a pas été supprimée.', ERREUR);
        $this->response->setFlash($message->toString());
        $this->response->redirect('administration.html');
    }

    public function purgeCache()
    {
        CacheManager::resetCache(true);
        $message = new Message('Cache supprimé ! On refait une partie de cache-cache ?', OK);
        $this->response->setFlash($message->toString());
        $this->response->redirect('administration.html');
    }

    public function updateData($params = array())
    {
        if ($this->request->isParam('sendData')) {
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
            
            if (false != file_put_contents(ParameterFilePath, $data)) {
                $message = new Message('Paramètres bien enregistrés, super !', OK);
                $this->response->setFlash($message->toString());
                $this->response->redirect('administration.html');
            }
        }
        // Error
        $message = new Message('Arg, impossible de mettre à jour les paramètres. Désolé, mais il va falloir en parler avec
un administrateur.', ERREUR);
        $this->response->setFlash($message->toString());
        $this->response->redirect('administration.html');
    }

    public function mailing($params = array())
    {
        // /!\ Ce code fourni par 1&1, notre hébergeur, ne fonctionne pas... Dommage.
        $params = array();
        $params['ListesDiffusion'] = array(
            'flambeaux@cepsaintmaur.com',
            'gdj@cepsaintmaur.com',
            'musique@cepsaintmaur.com'
        );
        // -- Prepare Meta Data
        $this->response->addVar('metaTitle', 'Administration - Gérer les listes de diffusion');
        
        // -- Create params
        $tplPparams = array(
            'UrlCourant' => 'administration-listes-diffusion.html'
        );
        $tplPparams = array_merge($tplPparams, $params);
        
        // -- Fill the body and print the page
        $this->controller->render('administration/mailing', $tplPparams);
        $this->printOut();
    }
}
