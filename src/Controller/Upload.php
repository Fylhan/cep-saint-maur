<?php
namespace Controller;

use Core\Action;
use Service\FileUploader;

class Upload extends Action
{

    public function file($params = array())
    {
        if (isset($_FILES['file']) && NULL != $_FILES['file'] && '' != $_FILES['file']) {
            // -- Load fileUploader
            $acceptedTypes = array(
                'txt' => 'text/plain',
                'zip' => 'application/zip',
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
            );
            $fileUpload = new FileUploader(UploadDir, $acceptedTypes);
            
            // -- Upload
            $fileInfo = $fileUpload->upload($_FILES['file']);
            // -- Fill the body and print the page
            $toDisplay = $fileInfo;
            if (NULL != $fileInfo && $fileInfo['ack']) {
                $toDisplay = array(
                    'filelink' => $fileInfo['dir'] . $fileInfo['name'],
                    'title' => $fileInfo['title']
                );
            }
            $this->response->setBody(stripslashes(json_encode($toDisplay)));
            $this->response->printOut();
        }
    }

    public function picture($params = array())
    {
        if (isset($_FILES['file']) && NULL != $_FILES['file'] && '' != $_FILES['file']) {
            // -- Load fileUploader
            $acceptedMimeTypes = array(
                'image/png',
                'image/jpg',
                'image/gif',
                'image/jpeg',
                'image/pjpeg'
            );
            $acceptedExtensionTypes = array(
                'png',
                'jpg',
                'gif',
                'jpeg',
                'pjpeg'
            );
            
            $fileUpload = new FileUploader(UploadDir, $acceptedMimeTypes, $acceptedExtensionTypes);
            
            // -- Upload
            $fileInfo = $fileUpload->upload($_FILES['file']);
            if (NULL != $fileInfo && $fileInfo['ack']) {
                // Manage gallery
                $fileUpload->addToGallery($fileInfo);
            }
            
            $toDisplay = $fileUpload;
            if (NULL != $fileUpload && $fileUpload['ack']) {
                $toDisplay = array(
                    'filelink' => $fileUpload['dir'] . $fileUpload['name'],
                    'title' => $fileUpload['title']
                );
            }
            $this->response->setBody(stripslashes(json_encode($toDisplay)));
            $this->response->printOut();
        }
    }

    public function galery($params = array())
    {
        $type = $this->request->getParam('type', 'string');

        $data = file_get_contents(GaleryFilePath);
        if ('human' == $type) {
            $this->response->render('upload/galery', json_decode($data));
        }
        else {
            $this->response->addHeader('Cache-Control', 'no-cache, must-revalidate');
            $this->response->addHeader('Expires', 'Sat, 29 Oct 2011 13:00:00 GMT+1'); // A date in the past
            $this->response->addHeader('Content-type', 'application/json; charset=UTF-8');
            $this->response->setBody(false != $data ? $data : '');
            $this->response->printOut();
        }
    }
}
