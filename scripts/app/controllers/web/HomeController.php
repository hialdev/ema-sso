<?php

class HomeController extends BaseAppController
{

    public function indexAction()
    {
        $this->meta = [
            'title' => 'Client Dashboard - PT Elang Merah Api',
            'desc' => 'Selamat datang client yang terhormat di dashboard ticketing PT Elang Merah Api.',
        ];

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
                ],
                'order'      => 'created DESC',
                'limit'      => 5,
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