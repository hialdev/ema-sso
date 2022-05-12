<?php

class ProjectController extends BaseAppController
{
    protected $pageTitle = "Project";

    public function indexAction($slug)
    {
        if ($project = Project::findBySlug($slug))
        {
            /* $sections = $project->getSections();
            if ($sections->count() == 0)
            {
                $section = new Section;
                $section->id_project = $project->id;
                $section->name = 'New Section';
                $section->created_at = Utils::now();
                $section->created_by = $this->account->id;
                if ($section->save())
                {
                    $sections = $project->getSections();
                }
            } */

            $this->view->project = $project;
            $this->view->workspace = $project->getWorkspace();
            //$this->view->sections = $sections;
            $this->view->pageTitle = $project->name;
            $this->view->page = [
                'title' => $project->name,
                'url'   => "project/".$slug
            ];

            $this->view->IsAdminProject = $project->isAdminMember($this->account->id);
            $this->view->pick('pages/project');
            return $this->view->finish();
        }

        $this->response->redirect('error/show404')->sendHeaders();
    }

    public function taskAction($slug, $params = null)
    {
        if ($project = Project::findBySlug($slug))
        {
            $filter = (new TaskHelper())->getFilter();

            $sections = [];
            //$sections[] = Section::homeSection($project->id);

            $_sections = $project->getSections();

            if ($_sections->count() == 0)
                $sections[] = $project->addSection($this->account->id, 'Tasks');

            foreach ($_sections as $section)
            {
                $sections[] = $section;
            }

            $_params = explode("/", $params);
            $this->view->params = $_params && isset($_params[1]) ? $_params[1] : '';
            $this->view->taskStatus = $filter->status;
            $this->view->taskPriority = $filter->priority;
            $this->view->taskProject = true;
            $this->view->project = $project;
            $this->view->workspace = $project->getWorkspace();
            $this->view->sections = $sections;
            $this->view->pageTitle = $project->name;
            $this->view->page = [
                'title' => $project->name,
                'icon'  => '',
                'url'   => "project/".$slug
            ];

            $this->view->pick('pages/project.tasks');
            return $this->view->finish();
        }

        $this->response->redirect('error/show404')->sendHeaders();
    }

    /* public function projectAction($slug, $params = null)
    {
        if ($project = Project::findBySlug($slug))
        {
            $sections = $project->getSections();
            if ($sections->count() == 0)
            {
                $section = new Section;
                $section->id_project = $project->id;
                $section->name = 'New Section';
                $section->created_at = Utils::now();
                $section->created_by = $this->account->id;
                if ($section->save())
                {
                    $sections = $project->getSections();
                }
            }

            $this->view->projectProject = true;
            $this->view->project = $project;
            $this->view->workspace = $project->getWorkspace();
            $this->view->sections = $sections;
            $this->view->pageTitle = $project->name;
            $this->view->page = [
                'title' => $project->name,
                'url'   => "project/".$slug
            ];

            $this->view->pick('pages/project.projects');
            return $this->view->finish();
        }

        $this->response->redirect('error/show404')->sendHeaders();
    } */

    /* Ajax Methos Requests  */

    public function createAjax ()
    {
        $this->view->disable();

        $project = new Project;
        $project->id = Project::generateId();
        $project->id_workspace = $this->request->getPost('id_workspace','int',0);
        $project->id_account = $this->account->id;
        $project->name = $this->request->getPost('name');
        $project->description = $this->request->getPost('description');
        $project->setSlugByName ();
        $project->created_at = Utils::now();

        if ($project->save())
        {
            if ($project->addMember($this->account->id, ProjectMember::ROLE_OWNER))
            {
                return $this->responseSuccess($project);
            }
            $project->delete();
        }

        return $this->responseError("Failed to create new project");
    }

    public function updateAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id','int',0);

        if ($project = Project::findById($projectId))
        {
            $project->name = $this->request->getPost('name');
            $project->description = $this->request->getPost('description');

            if ($project->save())
            {
                return $this->responseSuccess($project);
            }
        }

        return $this->responseError("Failed to update project");
    }

    public function slugAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id','int',0);

        if ($project = Project::findById($projectId))
        {
            $slug = $this->request->getPost('slug');
            $slug = Utils::slugify($slug);

            if (!Project::slugAvailable($slug, $projectId))
            {
                return $this->responseError("Project Url already being used.");
            }

            $project->slug = $slug;
            if ($project->save())
            {
                return $this->responseSuccess([
                    'slug' => $slug
                ]);
            }
        }

        return $this->responseError("Failed to update Project Url");
    }

    public function tagsAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id','int',0);
        $tags = [];
        if ($project = Project::findById($projectId))
        {
            if ($records = $project->getTags())
            {
                foreach ($records as $record)
                {
                    $tags[] = [
                        'id'    => $record->id,
                        'name'  => $record->name,
                        'color' => $record->color
                    ];
                }
            }

        }

        return $this->responseSuccess($tags);
    }

    public function membersAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id','int',0);
        $members = [];
        if ($project = Project::findById($projectId))
        {
            if ($records = $project->getMemBers())
            {
                foreach ($records as $record)
                {
                    $members[] = [
                        'id_account'    => $record->account->id,
                        'name'          => $record->account->name,
                        'accountUrl'    => $record->account->getAvatarUrl()
                    ];
                }
            }

        }

        return $this->responseSuccess($members);
    }

    public function addMemberAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id','int',0);

        if ($project = Project::findById($projectId))
        {
            $id_account = $this->request->getPost('id_account','int', 0);

            if ($account = Account::findById($id_account))
            {
                $role = $this->request->getPost('role');

                if (ProjectMember::findByProjectAccount ($projectId, $id_account))
                {
                    return $this->responseError("Member already exists.");
                }

                if ($member = $project->addMember($id_account, $role))
                {
                    return $this->responseSuccess([
                        'id'        => $id_account,
                        'accountUrl'=> $account->getAvatarUrl(),
                        'name'      => $account->name,
                        'role'      => $role
                    ]);
                }
            }
            return $this->responseError("Member Account not found");
        }

        return $this->responseError("Failed to add Project member");
    }

    public function roleMemberAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id','int',0);
        $id_account = $this->request->getPost('id_account','int', 0);
        $role = $this->request->getPost('role');

        if (empty($member = ProjectMember::findByProjectAccount ($projectId, $id_account)))
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

        $projectId = $this->request->getPost('id','int',0);
        $id_account = $this->request->getPost('id_account','int', 0);

        if (empty($member = ProjectMember::findByProjectAccount ($projectId, $id_account)))
        {
            return $this->responseError("Member does not exists.");
        }

        if ($member->delete())
        {
            return $this->responseSuccess();
        }

        return $this->responseError("Failed to delete Project member");
    }

    public function deleteAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id','int',0);

        if ($project = Project::findById($projectId))
        {
            $workspace = $project->getWorkspace();

            if ($project->delete())
            {
                $db = $this->config->database->db->taskman;

                $sqlQuery = sprintf(
                    'DELETE FROM '.$db.'.t_sections WHERE id_project="%1$s";'.
                    'DELETE FROM '.$db.'.t_project_members WHERE id_project="%1$s";'.
                    'DELETE FROM '.$db.'.t_section_tasks WHERE id_project="%1$s";'.
                    'DELETE FROM '.$db.'.t_task_tags WHERE id_task IN (SELECT id FROM '.$db.'.t_tasks WHERE id_project="%1$s");'.
                    //'DELETE FROM '.$db.'.t_task_assignees WHERE id_task IN (SELECT id FROM '.$db.'.t_tasks WHERE id_project="%1$s");'.
                    'DELETE FROM '.$db.'.t_tasks WHERE id_project="%1$s";'
                    ,$projectId);

                $this->executeQuery($sqlQuery);

                return $this->responseSuccess([
                    'workspace' => $workspace
                ]);
            }
        }

        return $this->responseError("Failed to delete project");
    }
}