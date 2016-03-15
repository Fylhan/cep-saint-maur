<?php
namespace Controller;

use Core\Action;
use Service\Uploader;

class Upload extends Action
{

    public function file()
    {
        return $this->upload(Uploader::AcceptedTypesFile);
    }

    public function picture()
    {
        return $this->upload(Uploader::AcceptedTypesPicture);
    }

    public function upload($acceptedTypes = array())
    {
        if (empty($acceptedTypes)) {
            $acceptedTypes = array_merge(Uploader::AcceptedTypesFile, Uploader::AcceptedTypesPicture);
        }
        $type = $this->request->getParam('type', 'string');
        
        if (isset($_FILES['file']) && null != $_FILES['file'] && '' != $_FILES['file']) {
            $fileInfo = $this->uploader->upload($_FILES['file'], $acceptedTypes);
            if (false !== $fileInfo) {
                $this->upload->update($fileInfo);
            }
            
            if ('human' == $type) {
                $this->response->addFlash('Fichier téléversé avec succès.', OK);
                return $this->response->redirect('galery.html');
            }
            $params = array();
            if (false !== $fileInfo) {
                $params['filelink'] = UPLOAD_PATH . '/' . $fileInfo['filename'];
                $params['title'] = $fileInfo['title'];
            }
            return $this->response->renderData($params);
        }
        
        $this->response->addFlash('Erreur durant le téléversage du fichier.', ERREUR);
        return $this->response->redirect('galery.html');
    }

    public function delete()
    {
        $id = $this->request->getParam('id', 'int');
        $fileInfo = $this->upload->getById($id);
        if (empty($fileInfo)) {
            $this->response->addFlash('Le fichier "' . $id . '" n\'existe pas.', ERREUR);
            return $this->response->redirect('galery.html');
        }
        if ($this->upload->remove($id) && $this->uploader->delete($fileInfo['filename'])) {
            if (\Model\Upload::TYPE_IMG == $fileInfo['type']) {
                $this->uploader->delete($fileInfo['thumb']);
            }
            $this->response->addFlash('Fichier supprimé avec succès.', OK);
            return $this->response->redirect('galery.html');
        }
        $this->response->addFlash('Errur lors de la suppression du fichier "' . $fileInfo['filename'] . '".', ERREUR);
        return $this->response->redirect('galery.html');
    }

    public function galery($params = array())
    {
        $type = $this->request->getParam('type', 'string');
        
        $files = $this->upload->getAll('human' == $type ? null : \Model\Upload::TYPE_IMG);
        
        foreach ($files as $k => $image) {
            $files[$k]['image'] = UPLOAD_PATH . '/' . $image['filename'];
            $files[$k]['thumb'] = (in_array($image['thumb'], Uploader::TypesThumb) ? IMG_PATH : UPLOAD_PATH) . '/' . $image['thumb'];
            $files[$k]['width'] = ThumbAdminWidth;
            $files[$k]['height'] = ThumbAdminHeight;
        }
        
        if ('human' == $type) {
            return $this->response->render('upload/galery', array(
                'images' => $files
            ));
        }
        return $this->response->renderData($files);
    }
}
