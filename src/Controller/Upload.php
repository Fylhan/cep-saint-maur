<?php
namespace Controller;

use Core\Action;

class Upload extends Action
{

    const AcceptedTypesFile = array(
        'txt' => 'text/plain',
        'zip' => 'application/zip',
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/msword',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.ms-powerpoint',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'odp' => 'application/vnd.oasis.opendocument.presentation'
    );

    const AcceptedTypesPicture = array(
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg'
    );

    public function file()
    {
        return $this->upload(self::AcceptedTypesFile);
    }

    public function picture()
    {
        return $this->upload(self::AcceptedTypesPicture);
    }

    public function upload($acceptedTypes = array())
    {
        if (empty($acceptedTypes)) {
            $acceptedTypes = array_merge(self::AcceptedTypesFile, self::AcceptedTypesPicture);
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
                $params['filelink'] = UploadDir . $fileInfo['image'];
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
        
        if ('human' == $type) {
            $this->response->render('upload/galery', array(
                'images' => $images
            ));
        }
        foreach ($images as $k => $image) {
            $images[$k]['image'] = UploadDir . $image['image'];
            $images[$k]['thumb'] = UploadDir . $image['thumb'];
        }
        $this->response->renderData($images);
    }
}
