<?php

class ProjectController extends BaseAppController
{
    protected $metaPage = [
        'title' => "Projects - Elang Merah Api",
        'desc'  => "Halaman project - PT Elang Merah Api",
    ];

    public function indexAction()
    {
        $project = Project::find();

        $this->view->projects = $project;
        $this->view->pick('project/index');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $project = Project::findFirst("slug = '$slug'");
            $ps = ProjectStatus::find();
            $ts = TicketStatus::find([
                'conditions' => 'name != :s:',
                'bind' => ['s' => 'closed']
            ]);
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
                'conditions' => 'project_id = :id: AND '.
                                'status in ({s:array}) AND '.
                                'priority in ({p:array}) AND '.
                                'no LIKE :q:',
                'bind' =>
                [
                    'id' => $project->id,
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
            $this->view->status = $ps;
            $this->view->tickets = $ticket;
            $this->view->project = $project;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('project/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}