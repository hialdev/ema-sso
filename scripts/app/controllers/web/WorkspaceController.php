<?php

class WorkspaceController extends BaseAppController
{
    protected $pageTitle = "Workspace";

    public function indexAction($slug)
    {
        if ($workspace = Workspace::findBySlug($slug))
        {
            $this->view->workspace = $workspace;
            $this->view->pageTitle = $workspace->name;
            $this->view->page = [
                'title' => $workspace->name,
                'url'   => "workspace/".$slug
            ];
            $this->view->IsAdminWorkspace = $workspace->isAdminMember($this->account->id);
            $this->view->pick('pages/workspace');
            return $this->view->finish();
        }

        $this->response->redirect('error/show404')->sendHeaders();
    }

    /* Ajax Methos Requests  */

    public function createAjax ()
    {
        $this->view->disable();

        $workspace = new Workspace;
        $workspace->id = Workspace::generateId();
        $workspace->id_account = $this->account->id;
        $workspace->name = $this->request->getPost('name');
        $workspace->description = $this->request->getPost('description');
        $workspace->setSlugByName ();
        $workspace->created_at = Utils::now();

        if ($workspace->save())
        {
            if ($workspace->addMember($this->account->id, WorkspaceMember::ROLE_OWNER))
            {
                return $this->responseSuccess($workspace);
            }
            $workspace->delete();
        }

        return $this->responseError("Failed to create new workspace");
    }

    public function listAjax ()
    {
        $this->view->disable();

        $data = Workspace::getAsOptionList ('id', 'name', ["id_account='".$this->account->id."'"]);
        return $this->jsonResponse($data);
    }

    public function updateAjax ()
    {
        $this->view->disable();

        $workspaceId = $this->request->getPost('id','int',0);

        if ($workspace = Workspace::findById($workspaceId))
        {
            $workspace->name = $this->request->getPost('name');
            $workspace->description = $this->request->getPost('description');

            if ($workspace->save())
            {
                return $this->responseSuccess($workspace);
            }
        }

        return $this->responseError("Failed to update workspace");
    }

    public function slugAjax ()
    {
        $this->view->disable();

        $workspaceId = $this->request->getPost('id','int',0);

        if ($workspace = Workspace::findById($workspaceId))
        {
            $slug = $this->request->getPost('slug');
            $slug = Utils::slugify($slug);

            if (!Workspace::slugAvailable($slug, $workspaceId))
            {
                return $this->responseError("workspace Url already being used.");
            }

            $workspace->slug = $slug;
            if ($workspace->save())
            {
                return $this->responseSuccess([
                    'slug' => $slug
                ]);
            }
        }

        return $this->responseError("Failed to update workspace Url");
    }

    public function addMemberAjax ()
    {
        $this->view->disable();

        $workspaceId = $this->request->getPost('id','int',0);

        if ($workspace = Workspace::findById($workspaceId))
        {
            $id_account = $this->request->getPost('id_account','int', 0);

            if ($account = Account::findById($id_account))
            {
                $role = $this->request->getPost('role');

                if (WorkspaceMember::findByWorkspaceAccount ($workspaceId, $id_account))
                {
                    return $this->responseError("Member already exists.");
                }

                if ($member = $workspace->addMember($id_account, $role))
                {
                    return $this->responseSuccess([
                        'id'    => $id_account,
                        'accountUrl'=> $account->getAvatarUrl(),
                        'name'  => $account->name,
                        'role'  => $role
                    ]);
                }
            }
            return $this->responseError("Member Account not found");
        }

        return $this->responseError("Failed to add workspace member");
    }

    public function roleMemberAjax ()
    {
        $this->view->disable();

        $workspaceId = $this->request->getPost('id','int',0);
        $id_account = $this->request->getPost('id_account','int', 0);
        $role = $this->request->getPost('role');

        if (empty($member = WorkspaceMember::findByWorkspaceAccount ($workspaceId, $id_account)))
        {
            return $this->responseError("Member does not exists.");
        }

        $member->role = $role;
        if ($member->save())
        {
            return $this->responseSuccess();
        }

        return $this->responseError("Failed to update member role");
    }

    public function deleteMemberAjax ()
    {
        $this->view->disable();

        $workspaceId = $this->request->getPost('id','int',0);
        $id_account = $this->request->getPost('id_account','int', 0);

        if (empty($member = WorkspaceMember::findByWorkspaceAccount ($workspaceId, $id_account)))
        {
            return $this->responseError("Member does not exists.");
        }

        if ($member->delete())
        {
            return $this->responseSuccess();
        }

        return $this->responseError("Failed to delete workspace member");
    }

    public function deleteAjax ()
    {
        $this->view->disable();

        $workspaceId = $this->request->getPost('id','int',0);

        if ($workspace = Workspace::findById($workspaceId))
        {
            $projects = $workspace->getProjects ();
            if ($projects->count() > 0)
            {
                return $this->responseError("Plese remove all projects inside workspace first.");
            }

            /* if ($workspace->delete())
            {
                return $this->responseSuccess();
            } */
        }

        return $this->responseError("Failed to delete workspace");
    }
}