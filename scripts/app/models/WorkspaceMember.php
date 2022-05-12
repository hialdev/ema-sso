<?php

class WorkspaceMember extends BaseModel
{
    const ROLE_OWNER        = 'owner';
    const ROLE_ADMIN        = 'admin';
    const ROLE_DEVELOPER    = 'developer';
    const ROLE_GUEST        = 'guest';

    protected $tablename = 't_workspace_members';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static function findByWorkspace ($id_workspace)
    {
        return parent::query()
            ->columns(['account.*', 'WorkspaceMember.role as member_role'])
            ->leftJoin("Account", 'WorkspaceMember.id_account=account.id', 'account')
            ->where("WorkspaceMember.id_workspace = :ws:", ['ws' => $id_workspace])
            ->execute();
    }

    static function findByWorkspaceAccount ($id_workspace, $id_account)
    {
        return parent::findFirst(["id_workspace='$id_workspace' AND id_account='$id_account'"]);
    }

    static function addMember ($id_workspace, $id_account, $role = self::ROLE_GUEST)
    {
        if (empty($member = self::findByWorkspaceAccount($id_workspace, $id_account)))
        {
            $member = new WorkspaceMember;
            $member->id_workspace = $id_workspace;
            $member->id_account = $id_account;
            $member->role = $role;
            $member->created_at = Utils::now();

            if (!$member->save())
            {
                return false;
            }
        }

        return $member;
    }

    static function isAdmin ($id_workspace, $id_account)
    {
        if ($member = self::findByWorkspaceAccount ($id_workspace, $id_account))
        {
            return in_array($member->role, [self::ROLE_ADMIN, self::ROLE_OWNER]);
        }

        return false;
    }
}