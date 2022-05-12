<?php

class HomeController extends BaseAppController
{
    protected $pageTitle = "Home";

    public function indexAction()
    {
        $this->view->tanggal = Utils::formatTanggal(time(), false, false);
        $this->view->greeting = Utils::greeting();

        $projectList = [];
        foreach ($this->workspaces as $workspace)
        {
            foreach ($workspace['projects'] as $project)
            {
                $project['workspace'] = $workspace['name'];
                $projectList[] = $project;
            }
        }

        $records = Task::findPrioritiesTask($this->account->id);
        $myPriorities = [];
        foreach ($records as $record)
        {
            $myPriorities[] = $record;
        }

        $this->view->projectList = $projectList;
        $this->view->myPriorities = $myPriorities;

        $this->view->pick('pages/home');
    }

    public function statAjax ()
    {
        $user_total = Account::count();
        $app_total = Application::count();

        return $this->responseSuccess([
            'user_total'    => Utils::asNumber($user_total,0),
            'app_total'     => Utils::asNumber($app_total,0),
        ]);
    }
}