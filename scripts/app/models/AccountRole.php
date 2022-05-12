<?php

class AccountRole extends BaseModel
{
    protected $tablename = 'account_roles';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    public static function findRoles ($accountid)
    {
        return AccountRole::query()
            ->columns([
                "AccountRole.role_id", "AccountRole.object_id", "AccountRole.status",
                "role.slug as role_slug", "role.name as role_name", "role.description as role_description"
            ])
            ->join("Role", "AccountRole.role_id = role.id", "role", "LEFT")
            ->where("account_id = :accountId:")
            ->bind(["accountId" => $accountid])
            ->execute();
    }

	public static function findByAccount($accountid)
	{
		$parameters = ["account_id='$accountid'"];
		return parent::find($parameters);
    }

    public static function findByAccountRole($accountid, $roleId)
	{
		$parameters = ["account_id='$accountid' AND role_id='$roleId'"];
		return parent::findfirst($parameters);
	}

    public static function findByAccountRoleSlug($accountid, $roleSlug)
	{
        return AccountRole::query()
            ->columns([
                "AccountRole.*"
            ])
            ->join("Role", "AccountRole.role_id = role.id", "role", "LEFT")
            ->where("account_id = :accountId:")
            ->andWhere("role.slug = :slug:")
            ->bind(["accountId" => $accountid, 'slug' => $roleSlug])
            ->execute()
            ->getfirst();
    }

    public static function getRoleBy($accountid, $roleSlug)
	{
        return self::findByAccountRoleSlug($accountid, $roleSlug);
    }

    public static function seRoleAccount($accountId, $roleSlug, $object_id = null)
    {
        if ($role = Role::findBySlug($roleSlug))
        {
            if (self::findByAccountRole($accountId, $role->id))
            {
                return true;
            }

            $accRole = new AccountRole;
            $accRole->account_id = $accountId;
            $accRole->role_id = $role->id;
            $accRole->object_id = $object_id;
            return $accRole->save();
        }

        return false;
    }

	public static function roleAsArray($accountid)
	{
        $roles = [];
        if ($records = self::findRoles ($accountid))
        {
            foreach ($records as $role)
            {
                $roles[] = $role->role_slug;
            }
        }

        return $roles;
    }

	public static function roleAsArrayKey($accountid)
	{
        $roles = [];
        if ($records = self::findRoles ($accountid))
        {
            foreach ($records as $role)
            {
                $roles[$role->role_slug] = $role->object_id?:null;
            }
        }

        return $roles;
    }

    public static function getAccountRoles ($accountid, $roles = null)
    {
        $query = parent::query()
            ->columns([ "AccountRole.*" ])
            ->join("Role", "AccountRole.role_id = role.id", "role", "LEFT")
            ->where("account_id = :accountId:");

        if ($roles){
            if (is_array($roles)) $query->inWhere("role.slug", $roles);
            else $query->andWhere("role.slug='$roles'");
        }

        return $query
            ->bind(["accountId" => $accountid])
            ->execute();
    }

    public static function accountHasRole ($accountid, $roles)
    {
        $query = parent::query()
            ->columns([ "COUNT(1) as num" ])
            ->join("Role", "AccountRole.role_id = role.id", "role", "LEFT")
            ->where("account_id = :accountId:");

        if (is_array($roles)) $query->andWhere("role.slug IN ('".implode("','", $roles)."')");
        else $query->andWhere("role.slug='$roles'");

        if ($record = $query
            ->bind(["accountId" => $accountid])
            ->execute()
            ->getFirst())
            return $record->num > 0;

        return FALSE;
    }

    public function getRole ()
    {
        return Role::findById($this->role_id);
    }

    public function getDetail ()
    {
        if ($role = self::getRole())
        {
            $data = null;

            if ($role->slug == Role::ROLE_STAFF)
            {
                $data = Pegawai::findById($this->object_id);
            }

            if ($data)
            {
                return (object) [
                    'role'  => $role,
                    'data'  => $data
                ];
            }
        }

        return false;
    }

    public static function getStatistic ()
    {
        return AccountRole::query()
            ->columns([
                "SUM(IF(role.slug='".Role::ROLE_STAFF."',1,0)) as user_staff",
                'COUNT(DISTINCT(AccountRole.account_id)) as user_total',
            ])
            ->leftJoin("Role", "AccountRole.role_id=role.id", "role")
            ->leftJoin("Account", "AccountRole.account_id=account.id", "account")
            ->where("account.id IS NOT NULL")
            ->execute()
            ->getFirst();
    }

}
