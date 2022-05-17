<?php

class TicketController extends BaseAppController
{
    protected $pageTitle = "Tickets";

    public function indexAction()
    {
        
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        $ts = TicketStatus::find();
        $tp = TicketPriority::find();

        $tsa = [];
        foreach ($ts as $tsi) {
            $tsa[] = $tsi->id;
        };
        $tpa = [];
        foreach ($tp as $tpi) {
            $tpa[] = $tpi->id;
        };

        $qq = $this->request->getQuery('q', 'string');
        $qp = $this->request->getQuery('priority', 'int');
        $qs = $this->request->getQuery('status', 'int');

        if($qq === null) $qq === "";
        if ($qp === 0 || $qp === null) {
            $p = $tpa;
        }else{
            $p = [$qp];
        }
        if ($qs === 0 || $qs === null) {
            $s = $tsa;
        }else{
            $s = [$qs];
        }

        $ticket = Ticket::find([
            'conditions' => 'account_id = :id: AND '.
                            'status in ({s:array}) AND '.
                            'priority in ({p:array}) AND '.
                            'no LIKE :q:',
            'bind' =>
            [
                'id' => $id,
                'q' => '%'.$qq.'%',
                'p' => $p,
                's' => $s,
            ],
            'order' => 'created DESC',
        ]);

        $query = ['q'=>$qq,'p'=>(string) $qp,'s'=>(string) $qs];
        $opt = ['p'=>$tp,'s'=>$ts];
        //var_dump($opt['p'][0]->id);dd();
        $c = count($ticket);

        $this->view->opt = $opt;
        $this->view->query = $query;
        $this->view->count = $c;
        $this->view->tickets = $ticket;
        $this->view->pick('ticket/index');

    }

    public function activeAction()
    {
        //variable
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        $ts = TicketStatus::find([
            'conditions' => 'name != :s:',
            'bind' => ['s' => 'closed']
        ]);
        $tp = TicketPriority::find();

        //to array id
        $tsa = [];
        foreach ($ts as $tsi) {
            $tsa[] = $tsi->id;
        };
        
        $tpa = [];
        foreach ($tp as $tpi) {
            $tpa[] = $tpi->id;
        };

        //get query
        $qq = $this->request->getQuery('q', 'string');
        $qp = $this->request->getQuery('priority', 'int');
        $qs = $this->request->getQuery('status', 'int');

        //sanitize query
        if($qq === null) $qq === "";
        if ($qp === 0 || $qp === null) {
            $p = $tpa;
        }else{
            $p = [$qp];
        }
        if ($qs === 0 || $qs === null) {
            $s = $tsa;
        }else{
            $s = [$qs];
        }

        //var_dump($s);dd();
        //get ticket
        $ticket = Ticket::find([
            'conditions' => 'account_id = :id: AND '.
                            'priority in ({p:array}) AND '.
                            'status in ({s:array}) AND '.
                            'no LIKE :q:',
            'bind' =>
            [
                'id' => $id,
                'q' => '%'.$qq.'%',
                'p' => $p,
                's' => $s,
            ],
            'order' => 'created DESC',
        ]);

        //array buat logic di view
        $query = ['q'=>$qq,'p'=>(string) $qp,'s'=>(string) $qs];
        $opt = ['p'=>$tp,'s'=>$ts];

        $this->view->opt = $opt;
        $this->view->query = $query;
        $this->view->uid = $uid;
        $this->view->tickets = $ticket;
        $this->view->pick('ticket/active');
    }

    public function addAction()
    {
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        $project = Project::find("account_id = $id");
        $priorities = TicketPriority::find();

        $q = $this->request->getQuery('p', 'string');
        if ($q !== null || $q !== "") {
            $sid = $q;
        }

        $this->view->prioritys = $priorities;
        $this->view->projects = $project;
        $this->view->sid = $sid;
        $this->view->pick('ticket/add');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $ticket = Ticket::findFirst("slug = '$slug'");
            $uid = $this->getLoggedParams()->accountUid;
            $id = Account::findByUID($uid)->id;

            $this->view->uid = $uid;
            $this->view->ticket = $ticket;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('ticket/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function createAction()
    {
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        try {
            $qpid = $this->request->getPost("project_id");
            $lastTicketId = Ticket::findFirst([
                'order' => 'id DESC',
            ])->id;
            $ticket = new Ticket();
            $ticket->no = "#EMATIC".((int)$lastTicketId+1);
            $ticket->project_id = $qpid;
            $ticket->account_id = $id;
            $ticket->subject = $this->request->getPost("subject");
            $ticket->status = 1;
            $ticket->priority = $this->request->getPost("priority");
            $ticket->slug = Utils::slugify($this->request->getPost("subject").' '.$id);
            $ts = $ticket->save();
            
            if ($ts) {
                $chat = new Chat();
                $chat->ticket_id = $ticket->id;
                $chat->content = $this->request->getPost("content");
                $chat->account_id = $id;
                
                $cs = $chat->save();

                if ($cs) {
                    if ($this->request->hasFiles())
                    {
                        $this->uploadFile($ticket->id,$chat->id);
                    }
                    $this->flashSession->success('Hooray.. data berhasil disimpan.');
                    return $this->dispatcher->forward(["controller" => "Ticket", "action" => "add" ]);
                }else{
                    $messages = $chat->getMessages();
                    foreach ($messages as $message) {
                        $this->flashSession->error($message);
                    }
                    return $this->dispatcher->forward(["controller" => "Ticket", "action" => "add" ]);
                }
            }else{
                echo "gagal di ts";
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . '<br>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    public function closeAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $uid = $this->getLoggedParams()->accountUid;
            $id = Account::findByUID($uid)->id;
            $ticket = Ticket::findFirst("slug = '$slug'");
            $ticket->status = 3;
            
            if ($ticket->save()) {
                $this->flashSession->success('Hooray.. Ticket telah ditutup.');
                return $this->response->redirect("/ticket/v/$slug");
            }else{
                $this->flashSession->error('Ooops.. Ticket gagal ditutup.');
                return $this->response->redirect($this->prevUrl());
            }   
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function openAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $ticket = Ticket::findFirst("slug = '$slug'");
            $uid = $this->getLoggedParams()->accountUid;
            $id = Account::findByUID($uid)->id;

            $chat = new Chat();
            $chat->ticket_id = $ticket->id;
            $chat->content = $this->request->getPost('content');
            $chat->account_id = $id;
    
            if ($chat->save()) {
                if ($this->request->hasFiles())
                {
                    $this->uploadFile($ticket->id,$chat->id);
                }
                $ticket->status = 1;
                if ($ticket->save()) {
                    $this->flashSession->success('Pesan berhasil terkirim');
                    return $this->response->redirect("/ticket/v/$slug");
                }else{
                    $messages = $chat->getMessages();
                    foreach ($messages as $message) {
                        $this->flashSession->error($message);
                    }
                    return $this->response->redirect($this->prevUrl());
                }  
            }
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function uploadFile ($ticket_id, $chat_id)
    {
        $this->view->disable();

        if (empty($ticket = Ticket::findFirst("id = $ticket_id")))
        {
            $this->flashSession->error("Ticket tidak ditemukan.");
            return $this->response->redirect('/ticket/v/'.$ticket->slug);
        }

        if (empty($chat = Chat::findFirst("id = $chat_id")))
        {
            $this->flashSession->error("Chat tidak ditemukan.");
            return $this->response->redirect('/ticket');
        }

        $upFiles = $this->request->getUploadedFiles();

        foreach ($upFiles as $upFile)
        {
            $fileKey = $upFile->getKey();
            $fileKey = explode('.',$fileKey)[0];

            if ($fileKey === 'file')
            {
                $file = new TicketFile;
                $file->id = TicketFile::generateId();
                $file->ticket_id = $ticket_id;
                $file->chat_id = $chat_id;
                $fileInfo = pathinfo($upFile->getName());
                $fileType = $upFile->getRealType();
                $fileSize = $upFile->getSize();
                $filePath = "ticket/".sprintf("%s/%s/%s_%s_%ss", $ticket_id, date("Y/m/d"), $chat_id, $file->id, Utils::slugify($upFile->getName()));

                if (!$this->saveUploadedFile($upFile, $filePath))
                {
                    $this->responseError("File gagal disimpan.");
                    return $this->response->redirect('/ticket/v/'.$ticket->slug);
                }

                $_filePath = $this->config->filePath . $filePath;

                $file->path = $filePath;
                $file->name = $fileInfo['basename'];
                
                if (empty($file->name))
                {
                    $this->flashSession->error("Gagal menyimpan file.");
                    return $this->response->redirect('/ticket/v/'.$ticket->slug);
                }

                $save = $file->save();
            }
        }

        if ($save)
        {
            $this->flashSession->success("Berhasil menyimpan file.");
            return $this->response->redirect('/ticket/v/'.$ticket->slug);
        }

        $this->deleteFile ($filePath);

        $this->flashSession->error("Gagal mengirimkan pesan dan menambahkan file.");
        return $this->response->redirect('/ticket/v/'.$ticket->slug);
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