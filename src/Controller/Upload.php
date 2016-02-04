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
            $this->controller->getResponse()->setBody(stripslashes(json_encode($toDisplay)));
            $this->printOut();
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
            $this->controller->getResponse()->setBody(stripslashes(json_encode($toDisplay)));
            $this->printOut();
        }
    }

    public function galery($params = array())
    {
        // -- Header
        $this->controller->getResponse()->addHeader('Cache-Control', 'no-cache, must-revalidate');
        $this->controller->getResponse()->addHeader('Expires', 'Sat, 29 Oct 2011 13:00:00 GMT+1'); // A date in the past
        $this->controller->getResponse()->addHeader('Content-type', 'application/json; charset=UTF-8');
        // -- Display content
        $data = file_get_contents(GaleryFilePath);
        $this->controller->getResponse()->setBody(false != $data ? $data : '');
        $this->printOut();
    }
}
