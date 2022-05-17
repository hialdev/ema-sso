<?php

class BaseAppController extends BaseController
{
    public $meta = [];
    
     /**
     * Go Back from whence you came
     * @return type
     */
    
    public function prevUrl() {
        return $_SERVER['HTTP_REFERER'];
    }

    public function activeMenu()
    {
        $req = $_SERVER['REQUEST_URI'];
        $r = explode('/',$req);

        switch ($r[1] !== null) {
            case $r[1] === 'projects' || $r[1] === 'project':
                $active = 'project';
                break;
            case $r[1] === 'tickets' || $r[1] === 'ticket':
                $active = 'ticket';
                break;
            case $r[1] === 'notes' || $r[1] === 'note':
                $active = 'note';
                break;
            case $r[1] === 'knowladge':
                $active = 'knowladge';
                break; 
            default:
                $active = '/';
                break;
        }

        return $active;
    }

    public function activeTicket()
    {
        $uid = $this->getLoggedParams()->accountUid;
        $id = Account::findByUID($uid)->id;
        $ticket = Ticket::find([
            'conditions' => 'status != 3 AND account_id = :id:',
            'bind' => [
                'id' => $id,
            ]
        ]);
        $cticket = count($ticket);
        return $cticket;
    }

    public function initialize()
    {
        $this->application = Application::findByAppId($this->config->application->appId);

        $this->setAppVariables();

        if (!$this->publicAccess)
        {
            $this->validate();
            $this->setViewData();
            $r = $this->activeMenu();
            $c = $this->activeTicket();

            if (!$this->request->isAjax())
            {
                $this->validateMenu();
                $this->view->list_menus = $this->userMenu;
                $this->view->uri = $r;
                $this->view->cat = $c;
                $this->view->meta = $this->meta;
                $this->view->profile = $this->setProfile();
                $this->view->accUrl = $this->config->application->accountUrl;               
                $this->view->urlNow = Utils::normalizeUri($this->config->application->baseUrl , $_SERVER['REQUEST_URI']);

                if ($this->activePage)
                {
                    $this->view->page = $this->activePage;
                    $this->view->pageTitle = $this->activePage['title'];
                    $this->view->pageDescription = $this->activePage['desc'];
                }

                $this->initData();
            }
        }
    }

}