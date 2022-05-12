<?php

class ProjectMember extends BaseModel
{
    const ROLE_OWNER        = 'owner';
    const ROLE_ADMIN        = 'admin';
    const ROLE_DEVELOPER    = 'developer';
    const ROLE_GUEST        = 'guest';

    protected $tablename = 't_project_members';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static function findByProject ($id_project)
    {
        return parent::query()
            ->columns(['account.*', 'ProjectMember.role as member_role'])
            ->leftJoin("Account", 'ProjectMember.id_account=account.id', 'account')
            ->where("ProjectMember.id_project = :ws:", ['ws' => $id_project])
            ->execute();
    }

    static function findByProjectAccount ($id_project, $id_account)
    {
        return parent::findFirst(["id_project='$id_project' AND id_account='$id_account'"]);
    }

    static function addMember ($id_project, $id_account, $role = self::ROLE_GUEST)
    {
        if (empty($member = self::findByProjectAccount($id_project, $id_account)))
        {
            $member = new ProjectMember;
            $member->id_project = $id_project;
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

    static function isAdmin ($id_project, $id_account)
    {
        if ($member = self::findByProjectAccount ($id_project, $id_account))
        {
            return in_array($member->role, [self::ROLE_ADMIN, self::ROLE_OWNER]);
        }
        return false;
    }


    static function findAdminByProject ($id_project)
    {
        return parent::query()
            ->columns(['account.*', 'ProjectMember.role as member_role'])
            ->leftJoin("Account", 'ProjectMember.id_account=account.id', 'account')
            ->where("ProjectMember.id_project = :ws:", ['ws' => $id_project])
            ->andWhere("ProjectMember.role IN ('".self::ROLE_OWNER."','".self::ROLE_ADMIN."')")
            ->execute();
    }
}