<?php

class ApplicationAccessLevel extends BaseModel
{
    protected $tablename = 'application_access_levels';
	protected $dbprofile = 'sso';
	protected $keys = ["application_id", 'access_level_id'];

	public static function findByApplication($application_id)
	{
		$parameters = ["application_id='$application_id'"];
		return parent::find($parameters);
    }

    public static function findAccessLevel($application_id, $access_level_id)
	{
		$parameters = ["application_id='$application_id' AND access_level_id='$access_level_id'"];
		return parent::findfirst($parameters);
	}

	public static function findDetailByApplication($application_id)
	{
		return parent::query()
			->columns(["level.*"])
			->leftJoin("AccessLevel", "ApplicationAccessLevel.access_level_id=level.id", "level")
			->where("ApplicationAccessLevel.application_id='$application_id'")
			->execute();
    }
}