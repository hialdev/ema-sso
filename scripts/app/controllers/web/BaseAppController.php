<?php

class BaseAppController extends BaseController
{
    protected $workspaces = [];
    protected $workspaceRequired = true;

    protected function getAppData ()
    {
        if ($_appData = $this->getAppCookie($this->config->cookie->browser->data))
        {
            if ($appData = json_decode($_appData))
            {
                if (isset($appData->accountId) &&
                    isset($appData->workspaceId) &&
                    $appData->accountId == $this->account->id)

                    return $appData;
            }
        }

        return FALSE;
    }

    protected function setAppData ($data)
    {
        $this->setAppCookie($this->config->cookie->browser->data, json_encode($data));
    }

    protected function setViewData()
    {
        parent::setViewData();

        $this->view->workspaces = $this->workspaces;
    }

    protected function validate()
    {
        parent::validate();

        $workspaces = Workspace::findByMemberAccount($this->account->id);
        $_projects = Project::findByMemberAccount($this->account->id);

        if ($workspaces->count() > 0)
        {
            foreach ($workspaces as $workspace)
            {
                $this->workspaces[$workspace->slug] = [
                    'id'        => $workspace->id,
                    'name'      => $workspace->name,
                    'slug'      => $workspace->slug,
                    'projects'  => []
                ];
            }
        }
        else
        {
            if ($this->workspaceRequired && $this->dispatcher->getControllerName() != 'start')
            {
                return $this->redirectTo('start');
            }
        }

        if ($_projects->count() > 0)
        {
            foreach ($_projects as $record)
            {
                if (!isset($this->workspaces[$record->workspace->slug]))
                {
                    $this->workspaces[$record->workspace->slug] = [
                        'id'        => $record->workspace->id,
                        'name'      => $record->workspace->name,
                        'slug'      => $record->workspace->slug,
                        'projects'  => []
                    ];
                }

                $this->workspaces[$record->workspace->slug]['projects'][] = [
                    'id'        => $record->project->id,
                    'name'      => $record->project->name,
                    'slug'      => $record->project->slug,
                ];
            }
        }


       /*  print_r($this->workspaces);die; */

        /* $appData = $this->getAppData ();

        $this->workspace = $appData ?
            Workspace::findById ($appData->workspaceId) :
            Workspace::findByAccount ($this->account->id);

        if ($this->workspaceRequired)
        {
            if (empty($this->workspace) && $this->dispatcher->getControllerName() != 'start')
            {
                return $this->redirectTo('start');
            }
        } */
    }

    public function initialize()
    {
        $this->application = Application::findByAppId($this->config->application->appId);

        $this->setAppVariables();

        if (!$this->publicAccess)
        {
            $this->validate();
            $this->setViewData();

            if (!$this->request->isAjax())
            {
                $this->validateMenu();
                $this->view->list_menus = $this->userMenu;

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