<?php

class NoteController extends BaseAppController
{

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
            $note->file = '{"file.jpg":"\/f\/u\/1231232321\/note\/note-slug\/file.jpg","file.png":"\/f\/u\/1231232321\/note\/note-slug\/file.png","file.pdf":"\/f\/u\/1231232321\/note\/note-slug\/file.pdf"}';
            $note->slug = Utils::slugify($this->request->getPost("title").' '.$id);

            if ($note->save()) {
                $this->flashSession->success('Hooray.. data berhasil disimpan.');
                return $this->dispatcher->forward(["controller" => "Note", "action" => "add" ]);
            }else{
                $this->flashSession->error('Ooops.. Maaf data gagal disimpan.');
                return $this->dispatcher->forward(["controller" => "Note", "action" => "add" ]);
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
            $note->file = '{"file.jpg":"\/f\/u\/1231232321\/note\/note-slug\/file.jpg","file.png":"\/f\/u\/1231232321\/note\/note-slug\/file.png","file.pdf":"\/f\/u\/1231232321\/note\/note-slug\/file.pdf"}';
            $note->slug = Utils::slugify($this->request->getPost("title").' '.$id);

            if ($note->save()) {
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
            $this->view->slug = $slug;
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

        if ($note->delete()) {
            $this->flashSession->success("Berhasil menghapus data.");
            return $this->response->redirect('/note');
        } else {
            $this->flashSession->success("Gagal menghapus data.");
            return $this->response->redirect('/note');
        }
    }
}