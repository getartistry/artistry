<?php
namespace UserMeta;

/**
 * Provided classes for qqUploader.
 * Handle file uploads via XMLHttpRequest.
 * Three classes in this file: qqUploadedFileXhr, qqUploadedFileForm and FileUploader.
 */
if (! class_exists('qqUploadedFileXhr')) {

    class qqUploadedFileXhr
    {

        /**
         * Save the file to the specified path
         *
         * @return boolean TRUE on success
         */
        function save($path)
        {
            $input = fopen("php://input", "r");
            $temp = tmpfile();
            $realSize = stream_copy_to_stream($input, $temp);
            fclose($input);
            
            if ($realSize != $this->getSize()) {
                return false;
            }
            
            $target = fopen($path, "w");
            fseek($temp, 0, SEEK_SET);
            stream_copy_to_stream($temp, $target);
            fclose($target);
            
            return true;
        }

        function getName()
        {
            return $_GET['qqfile'];
        }

        function getSize()
        {
            if (isset($_SERVER["CONTENT_LENGTH"])) {
                return (int) $_SERVER["CONTENT_LENGTH"];
            } else {
                throw new Exception('Getting content length is not supported.');
            }
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
if (! class_exists('qqUploadedFileForm')) {

    class qqUploadedFileForm
    {

        /**
         * Save the file to the specified path
         *
         * @return boolean TRUE on success
         */
        function save($path)
        {
            if (! move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
                return false;
            }
            return true;
        }

        function getName()
        {
            return $_FILES['qqfile']['name'];
        }

        function getSize()
        {
            return $_FILES['qqfile']['size'];
        }
    }
}

if (! class_exists('FileUploader')) {

    class FileUploader
    {

        private $allowedExtensions = array();

        private $sizeLimit = 10485760;

        private $file;

        function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760)
        {
            $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
            $this->allowedExtensions = $allowedExtensions;
            $this->sizeLimit = $sizeLimit;
            
            $this->checkServerSettings();
            
            if (isset($_GET['qqfile'])) {
                $this->file = new qqUploadedFileXhr();
            } elseif (isset($_FILES['qqfile'])) {
                $this->file = new qqUploadedFileForm();
            } else {
                $this->file = false;
            }
        }

        private function checkServerSettings()
        {
            $postSize = $this->toBytes(ini_get('post_max_size'));
            $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
            
            if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
                $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
                // die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
            }
        }

        private function toBytes($str)
        {
            $val = trim($str);
            $last = strtolower($str[strlen($str) - 1]);
            switch ($last) {
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
            return $val;
        }

        /**
         * Returns array('success'=>true) or array('error'=>'error message')
         */
        function handleUpload($replaceOldFile = FALSE)
        {
            global $userMeta;
            
            // $uploads = wp_upload_dir();
            $uploads = $userMeta->uploadDir();
            
            $uploadPath = $uploads['path'];
            $uploadUrl = $uploads['url'];
            
            if (! is_writable($uploadPath)) {
                return array(
                    'error' => __('Server error. Upload directory is not writable.', $userMeta->name)
                );
            }
            
            if (! $this->file) {
                return array(
                    'error' => __('No files were uploaded.', $userMeta->name)
                );
            }
            
            $size = $this->file->getSize();
            
            if ($size == 0) {
                return array(
                    'error' => __('File is empty', $userMeta->name)
                );
            }
            
            if ($size > $this->sizeLimit) {
                return array(
                    'error' => __('File is too large', $userMeta->name)
                );
            }
            
            $pathinfo = pathinfo($this->file->getName());
            // $filename = time();
            $filename = $pathinfo['filename'];
            $filename = strtolower(sanitize_file_name($filename));
            // $filename = str_replace( " ", "-", $filename );
            // $filename = md5(uniqid());
            $ext = strtolower($pathinfo['extension']);
            
            if ($this->allowedExtensions && ! in_array(strtolower($ext), $this->allowedExtensions)) {
                $these = implode(', ', $this->allowedExtensions);
                return array(
                    'error' => sprintf(__('File %1$s has an invalid extension, it should be one of %2$s.', $userMeta->name), $pathinfo['filename'], $these)
                );
            }
            
            if (! $replaceOldFile) {
                // / don't overwrite previous files that were uploaded
                while (file_exists($uploadPath . $filename . '.' . $ext)) {
                    $filename .= '-' . time();
                }
            }
            
            $field_name = isset($_REQUEST['field_name']) ? $_REQUEST['field_name'] : null;
            
            // $filepath = $uploads['subdir'] . "/$filename.$ext";
            $filepath = $uploads['subdir'] . "$filename.$ext";
            
            if ($this->file->save($uploadPath . $filename . '.' . $ext)) {
                do_action('pf_file_upload_after_uploaded', $field_name, $filepath);
                return array(
                    'success' => true,
                    'field_name' => $field_name,
                    'filepath' => $filepath
                );
            } else {
                return array(
                    'error' => __('Uploaded file could not be saved.', $userMeta->name) . __('The upload was cancelled due to server error', $userMeta->name)
                );
            }
        }
    }
}
    
