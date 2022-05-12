<?php


Class GDrive extends  Phalcon\Mvc\User\Component
{
    private $client;
    private $service;

    public function __construct ()
    {
        if ($this->config->gdrive->credentials)
            putenv('GOOGLE_APPLICATION_CREDENTIALS='.$this->config->gdrive->credentials);

        $this->client = new Google_Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->setScopes(Google_Service_Drive::DRIVE);
        $this->service = new Google_Service_Drive($this->client);
    }

    private function logInfo ($message)
    {
        $this->log->info($message);
    }

    public function uploadFile ()
    {
        if ($this->service)
        {
            $file = new Google_Service_Drive_DriveFile();
            $file->setName($name);
            $file->setMimeType('application/vnd.google-apps.folder');
            $file->setParents([$parentId ? : $this->config->gdrive->folderId]);
            $file->setDescription('A test document');
            $file->setMimeType('image/jpeg');

            return $this->service->files->create($file);
        }

        return false;
    }

    public function createFolder ($name, $parentId = null)
    {
        if ($this->service)
        {
            $file = new Google_Service_Drive_DriveFile();
            $file->setName($name);
            $file->setMimeType('application/vnd.google-apps.folder');
            $file->setParents([$parentId ? : $this->config->gdrive->folderId]);
            return $this->service->files->create($file);
        }

        return false;
    }
}