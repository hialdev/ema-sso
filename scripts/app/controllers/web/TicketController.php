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
            ]
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
            ]
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
        $this->view->pick('ticket/add');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $ticket = Ticket::findFirst("slug = '$slug'");
            $uid = $this->getLoggedParams()->accountUid;

            $this->view->uid = $uid;
            $this->view->ticket = $ticket;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('ticket/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}