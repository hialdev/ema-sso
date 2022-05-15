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
                $chat->file = '{"file.jpg":"\/f\/u\/1231232321\/note\/note-slug\/file.jpg","file.png":"\/f\/u\/1231232321\/note\/note-slug\/file.png","file.pdf":"\/f\/u\/1231232321\/note\/note-slug\/file.pdf"}';
                $cs = $chat->save();

                if ($cs) {
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
                $ticket->status = 1;
                if ($ticket->save()) {
                    $this->flashSession->success('Hooray.. Pesan anda terkirim dan Ticket telah terbuka kembali.');
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
}