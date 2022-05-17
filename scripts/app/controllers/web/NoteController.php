<?php

class NoteController extends BaseAppController
{
    protected $metaPage = [
        'title' => "Notes - Elang Merah Api",
        'desc'  => "Dokumentasi / catatan dari project - PT Elang Merah Api",
    ];

    public function indexAction()
    {
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        $project = Project::find("account_id = $id");
        
        $q = $this->request->getQuery('q', 'string');
        if ($q === null) $q = "";

        $note = Note::find([
            'conditions' => 'account_id = :id: AND '.
                            'title LIKE :q:',
            'bind' => [
                'id' => $id,
                'q' => '%'.$q.'%',
            ],
            'order'      => 'modified DESC',
            'limit'      => 6,
        ]);

        $this->view->q = $q;
        $this->view->projects = $project;
        $this->view->notes = $note;
        $this->view->pick('note/index');
    }

    public function addAction()
    {
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        $project = Project::find("account_id = $id");

        $q = $this->request->getQuery('p', 'string');
        if ($q !== null || $q !== "") {
            $sid = $q;
        }

        $this->view->projects = $project;
        $this->view->sid = $sid;
        $this->view->pick('note/add');
    }

    public function createAction()
    {
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        try {
            $qpid = $this->request->getPost("project_id");
            $note = new Note();
            $note->project_id = $qpid;
            $note->account_id = Project::findFirst("id = $qpid")->getAccount()->id;
            $note->modifer = $id;
            $note->title = $this->request->getPost("title");
            $note->note = $this->request->getPost("note");
            $note->slug = Utils::slugify($this->request->getPost("title").' '.$id);

            if ($note->save()) {
                if ($this->request->hasFiles())
                {
                    $this->uploadFile($note->id);
                }
                $this->flashSession->success('Hooray.. data berhasil disimpan.');
                return $this->response->redirect('/note/v/'.$note->slug);
            }else{
                $this->flashSession->error('Ooops.. Maaf data gagal disimpan.');
                return $this->response->redirect('/note/v/'.$note->slug);
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . '<br>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    public function editAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $note = Note::findFirst("slug = '$slug'");
            $uid = $this->getLoggedParams()->accountUid;
            $id = Account::findByUID($uid)->id;
            $project = Project::find("account_id = $id");   

            $this->view->projects = $project;
            $this->view->note = $note;
            $this->view->pick('note/edit');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function updateAction()
    {
        if ($slug = $this->dispatcher->getParam('slug')){
            $uid = $this->getLoggedParams()->accountUid;
            $id = Account::findByUID($uid)->id;
            $qpid = $this->request->getPost("project_id");
            $note = Note::findFirst("slug = '$slug'");
            $note->project_id = $qpid;
            $note->account_id = Project::findFirst("id = $qpid")->getAccount()->id;
            $note->modifer = $id;
            $note->title = $this->request->getPost("title");
            $note->note = $this->request->getPost("note");
            $note->slug = Utils::slugify($this->request->getPost("title").' '.$id);

            if ($note->save()) {
                if ($this->request->hasFiles())
                {
                    $this->uploadFile($note->id);
                }
                $this->flashSession->success('Hooray.. data berhasil diupdate.');
                return $this->response->redirect('/note');
            }else{
                $this->flashSession->error('Ooops.. Maaf data gagal diupdate.');
                return $this->response->redirect($this->prevUrl());
            }
        }
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $note = Note::findFirst("slug = '$slug'");

            $this->view->note = $note;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('note/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function byProjectAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $project = Project::findFirst("slug = '$slug'");
            $q = $this->request->getQuery('q', 'string');
            if ($q === null) $q = "";
            $note = Note::find([
                'conditions' => 'project_id = :pid: AND title LIKE :q:',
                'bind'       => [
                    'pid'    => $project->id,
                    'q'      => '%'.$q.'%',
                ]
            ]);

            $this->view->q = $q;
            $this->view->notes = $note;
            $this->view->project = $project;
            $this->view->pick('note/byproject');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function deleteAction($slug)
    {
        $note = Note::findFirst("slug = '$slug'");
        $files = $note->getFiles();

        if (count($files) !== 0) {
            foreach ($files as $file) {
                $filePath = $file->path;
                if ($file->delete())
                {
                    $del = $this->deleteFile($filePath);
                    $this->flashSession->success("Berhasil menghapus file $file->name.");
                }else{
                    $this->flashSession->error("Gagal menghapus file $file->name.");
                }
            }
            if ($del) {
                if ($note->delete()) {
                    $this->flashSession->success("Berhasil menghapus $note->title.");
                } else {
                    $this->flashSession->error("Gagal menghapus $note->title.");
                }
            }

        }else{
            
            if ($note->delete()) {
                $this->flashSession->success("Berhasil menghapus $note->title.");
            } else {
                $this->flashSession->error("Gagal menghapus $note->title.");
            }
        }
        
        return $this->response->redirect('/note');
        
    }

    public function uploadFile ($note_id)
    {
        $this->view->disable();

        if (empty($note = Note::findFirst("id = $note_id")))
        {
            $this->flashSession->error("Note tidak ditemukan.");
            return $this->response->redirect('/note/v/'.$note->slug);
        }

        $upFiles = $this->request->getUploadedFiles();

        foreach ($upFiles as $upFile)
        {
            $fileKey = $upFile->getKey();
            $fileKey = explode('.',$fileKey)[0];

            if ($fileKey === 'file')
            {
                $file = new NoteFile;
                $file->id = NoteFile::generateId();
                $file->note_id = $note_id;
                $fileInfo = pathinfo($upFile->getName());
                $fileType = $upFile->getRealType();
                $fileSize = $upFile->getSize();
                $filePath = "note/".sprintf("%s/%s/%s_%s_%ss", $note_id, date("Y/m/d"), $note_id, $file->id, Utils::slugify($upFile->getName()));

                if (!$this->saveUploadedFile($upFile, $filePath))
                {
                    return $this->responseError("File gagal disimpan.");
                    return $this->response->redirect('/note/v/'.$note->slug);
                }

                $_filePath = $this->config->filePath . $filePath;

                $file->path = $filePath;
                $file->name = $fileInfo['basename'];
                
                if (empty($file->name))
                {
                    return $this->flashSession->error("Gagal menyimpan file.");
                    return $this->response->redirect('/note/v/'.$note->slug);
                }

                $save = $file->save();
            }
        }

        if ($save)
        {
            return $this->flashSession->success("Berhasil menyimpan file.");
            return $this->response->redirect('/note/v/'.$note->slug);
        }

        $this->deleteFile ($filePath);

        return $this->flashSession->error("Gagal mengupload dan menambahkan file.");
        return $this->response->redirect('/note/v/'.$note->slug);
    }

    public function removeAction ()
    {
        
        if ($id = $this->dispatcher->getParam('id'))
        {
            $this->view->disable();
    
            if ($file = NoteFile::findById($id))
            {
                $filePath = $file->path;
                if ($file->delete())
                {
                    $this->deleteFile($filePath);

                    $this->flashSession->success("Berhasil menghapus file $file->name.");
                }else{
                    $this->flashSession->error("Gagal menghapus file $file->name.");
                }
            }
            return $this->response->redirect($this->prevUrl());
            
        }else{
            $this->view->pick('erorr/404');
        }
    }
}