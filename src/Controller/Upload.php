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
        
        if (isset($_FILES['file']) && NULL != $_FILES['file'] && '' != $_FILES['file']) {
            $fileInfo = $this->uploader->upload($_FILES['file'], $acceptedTypes);
            if (false !== $fileInfo) {
                $this->upload->update($fileInfo);
            }
            
            if ('human' == $type) {
                $this->response->addFlash('Fichier téléversé avec succès.', OK);
                $this->response->redirect('galery.html');
            }
            $params = array();
            if (false !== $fileInfo) {
                $params['filelink'] = UPLOAD_PATH . '/' . $fileInfo['image'];
                $params['title'] = $fileInfo['title'];
            }
            $this->response->renderData($params);
        }
        
        $this->response->addFlash('Erreur durant le téléversage du fichier.', ERREUR);
        $this->response->redirect('galery.html');
    }

    public function galery($params = array())
    {
        $type = $this->request->getParam('type', 'string');
        
        $images = $this->upload->getAll();
        
        foreach ($images as $k => $image) {
            $images[$k]['image'] = UPLOAD_PATH . '/' . $image['image'];
            $images[$k]['thumb'] = (in_array($image['thumb'], Uploader::TypesThumb) ? IMG_PATH : UPLOAD_PATH) . '/' . $image['thumb'];
            $images[$k]['width'] = ThumbAdminWidth;
            $images[$k]['height'] = ThumbAdminHeight;
        }
        
        if ('human' == $type) {
            $this->response->render('upload/galery', array(
                'images' => $images
            ));
        }
        $this->response->renderData($images);
    }
}
