<?php

class Access extends BaseModel
{
	const STATUS_ACTIVE        = 1;
    const STATUS_DISABLED      = 0;

	protected $tablename = 'access';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

	static $statusText = [
        self::STATUS_ACTIVE    => 'Aktif',
        self::STATUS_DISABLED  => 'Tidak Aktif',
    ];

	public static function findByApplication($application_id)
	{
		$parameters = ["application_id='$application_id'"];
		return parent::find($parameters);
    }

	public static function findAccess($applicationid, $accountid)
	{
		$parameters = ["application_id= '$applicationid' AND account_id='$accountid'"];
		return parent::findfirst($parameters);
	}

	public static function findByAccount($accountid)
	{
		$parameters = ["account_id='$accountid'"];
		return parent::find($parameters);
	}

    public static function hasAccess($applicationid, $accountid)
	{
		return self::findAccess($applicationid, $accountid) ? true : false;
    }

    public function accountHasAccess()
    {
        return ApplicationAccessLevel::findAccessLevel($this->application_id, $this->access_level_id) ? true : false;
    }

    public static function findByAccountDetail ($accountId)
    {
        return Access::query()
            ->columns([
                "application.*"
            ])
            ->leftJoin("Application", "Access.application_id = application.id", "application")
            ->where("Access.account_id = :accountId: AND Access.status=1")
            ->bind(["accountId" => $accountId])
            ->execute();
    }

    public static function findWebAppByAccountDetail ($accountId)
    {
        return Access::query()
            ->columns([
                "application.*"
            ])
            ->leftJoin("Application", "Access.application_id = application.id", "application")
            ->where("Access.account_id = :accountId: AND Access.status=1")
            ->andWhere("application.type!='app'")
            ->bind(["accountId" => $accountId])
            ->execute();
    }

    public function isActive()
    {
        return $this->status == 1;
    }
}