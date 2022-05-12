<?php

class FileController extends BaseAppController
{
    protected $pageTitle = "File";

    public function openAction ($id = 0)
    {
        $this->view->disable();
        if ($file = File::findByID($id))
        {
            if (file_exists($this->config->filePath. $file->filePath))
            {
                $stream = new FileStream($this->config->filePath. $file->filePath, $file->fileMime);
                $stream->start();
            }
        }
    }

    public function uploadAjax ()
    {
        $this->view->disable();

        if (!$this->request->hasFiles())
        {
            return $this->responseError("File tidak ditemukan.");
        }

        $taskId = $this->request->getPost('taskId','int', 0);
        $projectId = $this->request->getPost('projectId', 'int', 0);

        if (empty($task = Task::findById($taskId)))
        {
            return $this->responseError("Task not found.");
        }

        $upFiles = $this->request->getUploadedFiles();

        $file = new File;
        $file->id = File::generateId();
        $file->id_task = $taskId;

        foreach ($upFiles as $upFile)
        {
            $fileKey = $upFile->getKey();
            if ($fileKey == 'file')
            {
                $fileInfo = pathinfo($upFile->getName());
                $fileType = $upFile->getRealType();
                $fileSize = $upFile->getSize();
                $filePath = sprintf("%s/%s/%s_%s_%ss", $task->id_project, date("Y/m/d"), $taskId, $file->id, Utils::slugify($upFile->getName()));

                if (!$this->saveUploadedFile($upFile, $filePath))
                {
                    return $this->responseError("File gagal disimpan.");
                }

                $_filePath = $this->config->filePath . $filePath;

                $file->filePath = $filePath;
                $file->fileName = $fileInfo['basename'];
                $file->fileMime = $fileType;
                $file->fileSize = $fileSize;
            }
        }

        if (empty($file->fileName))
        {
            return $this->responseError("Failed saving file.");
        }

        $file->created_at = Utils::now();
        $file->created_by = $this->account->id;

        if ($file->save())
        {
            return $this->responseSuccess($file->asArrayResult());
        }

        $this->deleteFile ($filePath);

        return $this->responseError("Failed saving file.");
    }

    public function removeAjax ()
    {
        $this->view->disable();

        $fileId = $this->request->getPost('id');

        if ($file = File::findById($fileId))
        {
            $filePath = $file->filePath;
            if ($file->delete())
            {
                $this->deleteFile($filePath);
                return $this->responseSuccess();
            }
        }

        return $this->responseError("Failed deleting file.");
    }
}