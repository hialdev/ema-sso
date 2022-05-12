<?php

class StartController extends BaseAppController
{
    protected $pageTitle = "Start";
    protected $workspaceRequired = false;

    public function indexAction()
    {
        if ($this->workspaces)
        {
            return $this->redirectHome();
        }

        if ($this->request->isPost())
        {
            $workspace = new Workspace;
            $workspace->id = Workspace::generateId();
            $workspace->id_account = $this->account->id;
            $workspace->name = $this->request->getPost('workspace');
            $workspace->setSlugByName();
            $workspace->created_at = Utils::now();

            if ($workspace->save())
            {
                $workspace->addMember($this->account->id, WorkspaceMember::ROLE_ADMIN);

                $project = new Project;
                $project->id = Project::generateId();
                $project->id_workspace = $workspace->id;
                $project->id_account = $this->account->id;
                $project->name = $this->request->getPost('project');
                $project->setSlugByName();
                $project->created_at = Utils::now();

                if ($project->save())
                {
                    $project->addMember($this->account->id, ProjectMember::ROLE_ADMIN);

                    $this->setAppData([
                        'accountId'     => $this->account->id,
                        'workspaceId'   => $workspace->id
                    ]);

                    return $this->redirectHome();
                }

                $workspace->delete();
            }

            $this->flash->error("Workspace tidak berhasil disimpan");
            return $this->redirectTo('start');
        }

        $this->view->pick('pages/start');
    }
}