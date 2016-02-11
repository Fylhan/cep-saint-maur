<?php
namespace Controller;

use Core\Action;
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
        $this->response->render('administration/index', $params);
    }

    public function update($params = array())
    {
        // Update
        if ($this->request->isParam('sendNews')) {
            $values = $this->news->prepareUpdate($this->request->getValues());
            $id = $this->news->update($values);
            // Cache obsolete
            CacheManager::resetCache();
            $this->response->addFlash('Voilà une news ajoutée avec succès, bien joué !', OK);
            $this->response->redirect('administration-update.html?id=' . $id . '#administrationPage');
        }
        
        // Display
        $id = $this->request->getParam('id', 'int');
        if (0 == $id) {
            $params['Actualite'] = new Actualite();
        }
        else {
            $params['Actualite'] = $this->news->findActualiteById($id, true);
            if (null == $params['Actualite']) {
                $this->response->addFlash('La news ' . $id . ' n\'existe pas. Si c\'est un problème, il va falloir contacter un administrateur.', NEUTRE);
                $this->response->redirect('administration.html');
            }
        }
        $this->response->render('administration/update', $params);
    }

    public function delete($params = array())
    {
        $id = $this->request->getParam('id', 'int');
        if ($this->news->remove($id)) {
            // Cache obsolete
            CacheManager::resetCache();
            $this->response->addFlash('La news ' . $id . ' a été supprimée. Bien joué !', OK);
            $this->response->redirect('administration.html');
        }
        $this->response->addFlash('Arg, ça ne marche pas. La news ' . $id . ' n\'a pas été supprimée.', ERREUR);
        $this->response->redirect('administration.html');
    }

    public function purgeCache()
    {
        CacheManager::resetCache(true);
        $this->response->addFlash('Cache supprimé ! On refait une partie de cache-cache ?', OK);
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
            $data .= "\n";
            
            if (false != file_put_contents(ParameterFilePath, $data)) {
                $this->response->addFlash('Paramètres bien enregistrés, super !', OK);
                $this->response->redirect('administration.html');
            }
        }
        // Error
        $this->response->addFlash('Arg, impossible de mettre à jour les paramètres. Désolé, mais il va falloir en parler avec
un administrateur.', ERREUR);
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
        $this->response->addVar('metaTitle', 'Administration - Gérer les listes de diffusion');
        
        $tplPparams = array(
            'UrlCourant' => 'administration-listes-diffusion.html'
        );
        $tplPparams = array_merge($tplPparams, $params);
        
        $this->response->render('administration/mailing', $tplPparams);
    }
}
