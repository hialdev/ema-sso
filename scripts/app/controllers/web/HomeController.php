<?php

class HomeController extends BaseAppController
{
    protected $metaPage = [
        'title' => "Dashboard Client - Elang Merah Api",
        'desc'  => "Halaman Dashboard Client - PT Elang Merah Api",
    ];

    public function indexAction()
    {

        $ticket = Ticket::find(
            [
                'order'      => 'created DESC',
                'limit'      => 15,
            ]
        );

        $cclient = count(Account::findByRoleSlug("client"));
        $cblog = count(Blog::find());
        $cproject = count(Project::find());
        $cticket = count(Ticket::find());

        $count = [
            'project'=>$cproject,
            'ticket'=>$cticket,
            'blog' => $cblog,
            'client' => $cclient,
        ];
        
        // $d = $acc->id;
        // var_dump($d);dd();

        $this->view->count = $count;
        $this->view->tickets = $ticket;
        $this->view->pick('home/index');
    }
}