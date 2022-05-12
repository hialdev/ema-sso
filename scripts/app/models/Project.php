<?php

class Project extends BaseModel
{
    protected $tablename = 't_projects';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    public static function findByWorkspace ($id_workspace)
    {
        return parent::find(["id_workspace='$id_workspace'"]);
    }

    public static function findBySlug ($slug)
    {
        return parent::findFirst(["slug='$slug'"]);
    }

    static function slugAvailable ($slug, $excludeId = null)
    {
        $condition = ["slug='$slug'"];
        if ($excludeId) $condition[] = "id!=". $excludeId;

        return parent::findFirst([implode(" AND ", $condition)]) ? false : true;
    }

    public function setSlugByName ()
    {
        if (empty($this->name)) return false;

        $slug = Utils::slugify($this->name);
        $index = 1;
        while(!empty(Project::findBySlug($slug)))
        {
            $slug = Utils::slugify($this->name." ".$index);
            $index++;
        }

        $this->slug = $slug;

        return $slug;
    }

    public function getWorkspace ()
    {
        return Workspace::findById($this->id_workspace);
    }

    public function getSections ()
    {
        return Section::findByProject($this->id, $this->id_account);
    }

    public function getTasks ()
    {
        return Task::findByProject($this->id);
    }

    public function getMembers ()
    {
        return ProjectMember::findByProject ($this->id);
    }

    public function getAdminMembers ()
    {
        return ProjectMember::findAdminByProject ($this->id);
    }

    public function getTags ()
    {
        return Tag::findByProject ($this->id);
    }

    public function addMember ($id_account, $role)
    {
        return ProjectMember::addMember ($this->id, $id_account, $role);
    }

    public function addSection ($id_account, $name = 'New Section')
    {
        return Section::addSection ($this->id, $id_account, $name);
    }

    public function getTaskSummary ()
    {
        return Task::summaryByProject($this->id);
    }

    static function findByMemberAccount ($accountId)
    {
        return ProjectMember::query()
            ->columns(["project.*", "workspace.*"])
            ->leftJoin("Project", "ProjectMember.id_project=project.id", "project")
            ->leftJoin("Workspace", "project.id_workspace=workspace.id", "workspace")
            ->where("ProjectMember.id_account=:account:")
            ->bind(['account' => $accountId])
            ->execute();
    }

    static function homeProject ($id_account)
    {
        $project = new Project;
        $project->id = 0;
        $project->id_account = $id_account;
        $project->id_workspace = 0;
        $project->name = 'My Tasks';

        return $project;
    }

    public function getCreatedAtText ()
    {
        return Utils::formatTanggal($this->created_at, false, true);
    }

    public function isAdminMember ($id_account)
    {
        return ProjectMember::isAdmin($this->id, $id_account);
    }

}