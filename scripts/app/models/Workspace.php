<?php

class Workspace extends BaseModel
{
    protected $tablename = 't_workspaces';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    public static function findBySlug ($slug)
    {
        return parent::findFirst(["slug='$slug'"]);
    }

    static function findByAccount ($accountId)
    {
        return parent::findFirst([
            'conditions'    => 'id_account = ?1',
            'bind'          => [
                1 => $accountId
            ]
        ]);
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
        while(!empty(Workspace::findBySlug($slug)))
        {
            $slug = Utils::slugify($this->name." ".$index);
            $index++;
        }

        $this->slug = $slug;

        return $slug;
    }

    public function getProjects ()
    {
        return Project::findByWorkspace($this->id);
    }

    public function addMember ($id_account, $role)
    {
        return WorkspaceMember::addMember ($this->id, $id_account, $role);
    }

    static function findByMemberAccount ($accountId)
    {
        return WorkspaceMember::query()
            ->columns(["workspace.*"])
            ->leftJoin("Workspace", "WorkspaceMember.id_workspace=workspace.id", "workspace")
            ->where("WorkspaceMember.id_account=:account:")
            ->bind(['account' => $accountId])
            ->execute();
    }

    public function getMembers ()
    {
        return WorkspaceMember::findByWorkspace($this->id);
    }

    public function getCreatedAtText ()
    {
        return Utils::formatTanggal($this->created_at, false, true);
    }

    public function isAdminMember ($id_account)
    {
        return WorkspaceMember::isAdmin($this->id, $id_account);
    }

}