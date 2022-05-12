<?php

class BaseAppController extends BaseController
{
     /**
     * Go Back from whence you came
     * @return type
     */
    protected function prevUrl() {
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

    public function initialize()
    {
        $this->application = Application::findByAppId($this->config->application->appId);

        $this->setAppVariables();

        if (!$this->publicAccess)
        {
            $this->validate();
            $this->setViewData();
            $r = $this->activeMenu();

            if (!$this->request->isAjax())
            {
                $this->validateMenu();
                $this->view->list_menus = $this->userMenu;
                $this->view->uri = $r;

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