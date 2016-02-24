<?php
namespace Service;

use Core\Base;

class Uploader extends Base
{

    /**
     * Map for file of extension -> mime type
     *
     * @var array
     */
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

    /**
     * Map for picture of extension -> mime type
     *
     * @var array
     */
    const AcceptedTypesPicture = array(
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg'
    );

    /**
     * Map of type -> corresponding thumb file name
     *
     * @var array
     */
    const TypesThumb = array(
        'text/plain' => 'Icon_txt.png',
        'application/zip' => 'Icon_zip.png',
        'audio/mpeg' => 'Icon_aud.png',
        'video/quicktime' => 'Icon_vid.png',
        'application/pdf' => 'Icon_pdf.png',
        'application/msword' => 'Icon_doc.png',
        'application/vnd.ms-excel' => 'Icon_xls.png',
        'application/vnd.ms-powerpoint' => 'Icon_ppt.png',
        'application/vnd.oasis.opendocument.text' => 'Icon_doc.png',
        'application/vnd.oasis.opendocument.spreadsheet' => 'Icon_xls.png',
        'application/vnd.oasis.opendocument.presentation' => 'Icon_ppt.png'
    );

    public function upload($file, $acceptedTypes, $dir = UPLOAD_PATH)
    {
        // Verifications
        if (0 != $file["error"]) {
            throw new \Exception($this->displayError($file["error"]));
        }
        $fileMimeType = strtolower($file['type']);
        $fileExtensionType = strtolower(substr(strrchr($file['name'], '.'), 1));
        $fileExtensionTypePosition = strrpos($file['name'], '.');
        if (! $this->validate($fileMimeType, $fileExtensionType, $acceptedTypes)) {
            throw new \Exception('Bad extension. Accepted: ' . implode(', ', array_keys($acceptedTypes)) . '. Received: ' . $fileExtensionType . ' (' . $fileMimeType . ')');
        }
        list ($fileName, $fileTitle) = $this->rename($file['name'], $fileMimeType, $fileExtensionType, $fileExtensionTypePosition);
        $filePath = $dir . '/' . $fileName;
        
        // Upload
        if (! file_exists($dir)) {
            mkdir($dir);
        }
        $uploaded = move_uploaded_file($file['tmp_name'], $filePath);
        if (! $uploaded) {
            throw new \Exception('Error during upload');
        }
        if ($this->isPicture($filePath)) {
            $thumbName = $this->createThumb($fileName, ThumbWidth, ThumbHeight);
        }
        else {
            $thumbName = @self::TypesThumb[$fileMimeType];
            if (false == $thumbName && 'image/svg+xml' != $fileMimeType) {
                $thumbName = 'Icon_txt.png';
            }
        }
        
        return array(
            'title' => $fileTitle,
            'image' => $fileName,
            'thumb' => $thumbName ? $thumbName : $fileName,
        );
    }

    public function createThumb($fileName, $width, $height, $dir = UPLOAD_PATH)
    {
        $thumbName = 'thumb_' . $fileName;
        $thumbPath = $dir . '/' . $thumbName;
        $filePath = $dir . '/' . $fileName;
        $info = getimagesize($filePath);
        $size = array(
            $info[0],
            $info[1]
        );
        if ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($filePath);
        }
        elseif ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($filePath);
        }
        elseif ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($filePath);
        }
        else {
            return false;
        }
        $thumb = imagecreatetruecolor($width, $height);
        $src_aspect = $size[0] / $size[1];
        $thumb_aspect = $width / $height;
        if ($src_aspect < $thumb_aspect) {
            // narrover
            $scale = $width / $size[0];
            $new_size = array(
                $width,
                $width / $src_aspect
            );
            $src_pos = array(
                0,
                ($size[1] * $scale - $height) / $scale / 2
            );
        }
        elseif ($src_aspect > $thumb_aspect) {
            // wider
            $scale = $height / $size[1];
            $new_size = array(
                $height * $src_aspect,
                $height
            );
            $src_pos = array(
                ($size[0] * $scale - $width) / $scale / 2,
                0
            );
        }
        else {
            // some shape
            $new_size = array(
                $width,
                $height
            );
            $src_pos = array(
                0,
                0
            );
        }
        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);
        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);
        if ($info['mime'] == 'image/jpeg' && imagejpeg($thumb, $thumbPath)) {
            return $thumbName;
        }
        elseif ($info['mime'] == 'image/gif' && imagegif($thumb, $thumbPath)) {
            return $thumbName;
        }
        elseif ($info['mime'] == 'image/png' && imagepng($thumb, $thumbPath)) {
            return $thumbName;
        }
        return false;
    }

    public function isPicture($filePath)
    {
        $info = getimagesize($filePath);
        return (isset($info['mime']) && in_array($info['mime'], array(
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        )));
    }

    private function validate($fileMimeType, $fileExtensionType, $acceptedTypes)
    {
        return (in_array(strtolower($fileMimeType), array_values($acceptedTypes)) || in_array(strtolower($fileExtensionType), array_keys($acceptedTypes)));
    }

    private function rename($currentFileName, $fileMimeType, $fileExtensionType, $fileExtensionTypePosition)
    {
        $cleanFileName = parserUrl(substr($currentFileName, 0, $fileExtensionTypePosition));
        $fileTitle = deparserUrl($cleanFileName);
        $fileName = $cleanFileName . '_' . md5(date('Y-m-d_H-i-s')) . '.' . $fileExtensionType;
        return array(
            $fileName,
            $fileTitle
        );
    }

    private function displayError($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_OK:
                return "Ok";
            case UPLOAD_ERR_INI_SIZE:
                return "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file was only partially uploaded.";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk. Introduced in PHP 5.1.0.";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.";
        }
    }
}
