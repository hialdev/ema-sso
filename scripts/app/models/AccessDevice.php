<?php

class AccessDevice extends BaseModel
{
	protected $tablename = 'access_devices';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

	public static function findByToken($token)
	{
        $parameters = "access_token= '$token'";
		return parent::findfirst([$parameters]);
	}

	public static function findByDevice($deviceId, $accessId)
	{
        $parameters = "device_id='$deviceId'";
        if ($accessId) $parameters .= " AND access_id='$accessId'";
		return parent::findfirst([$parameters]);
    }

    public static function deleteByToken ($token)
    {
        if ($accessDevice = AccessDevice::findByToken($token))
        {
            return $accessDevice->delete();
        }

        return FALSE;
    }

}