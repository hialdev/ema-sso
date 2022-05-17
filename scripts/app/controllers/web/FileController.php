<?php

class FileController extends BaseAppController
{
    protected $pageTitle = "File";

    public function openAction ($path = "")
    {
        if ($file = File::findFirst($path))
        {
            $path = $this->config->filePath. $file->path;
            if (file_exists($path))
            {
                return $this->redirect($path);
            }
        }
    }

}