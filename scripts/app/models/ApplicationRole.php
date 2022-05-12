<?php

class ApplicationRole extends BaseModel
{
    protected $tablename = 'application_roles';
    protected $dbprofile = 'sso';
    protected $keys = ["application_id", 'role_id'];

    public static function findByApplication($application_id)
	{
		$parameters = ["application_id='$application_id'"];
		return parent::find($parameters);
    }

    public static function findRole($application_id, $roleId)
	{
		$parameters = ["application_id='$application_id' AND role_id='$roleId'"];
		return parent::findfirst($parameters);
	}

}