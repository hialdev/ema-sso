<?php

class HomeController extends BaseAppController
{

    public function indexAction()
    {

        $uid = $this->getLoggedParams()->accountUid;
        $acc = Account::findByUID($uid);
        $id = $acc->id;

        $project = Project::find(
            [
                'conditions' => 'account_id = :id:',
                'bind'       => [
                    'id' => $id,
                ]
            ]
        );
        $ticket = Ticket::find(
            [
                'conditions' => 'account_id = :id:',
                'bind'       => [
                    'id' => $id,
                ]
            ]
        );

        $cproject = count($project);
        $cticket = count($ticket);

        $count = [
            'project'=>$cproject,
            'ticket'=>$cticket,
        ];
        
        // $d = $acc->id;
        // var_dump($d);dd();

        $this->view->count = $count;
        $this->view->tickets = $ticket;
        $this->view->pick('home/index');
    }
}