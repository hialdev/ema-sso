<?php

class AccountContact extends BaseModel
{
    const CONTACT_EMAIL     = 'email';
    const CONTACT_PHONE     = 'phone';

    protected $tablename = 'account_contacts';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    public static function isEmailAvailable ($email, $accountId = null)
    {
        $parameters = "type = '".AccountContact::CONTACT_EMAIL."' AND value = '$email'";
        if ($accountId) $parameters .= "  AND account_id != '$accountId'";

        return parent::findFirst($parameters)?false:true;
    }

    public static function isPhoneAvailable ($phone, $accountId = null)
    {
        $parameters = "type = '".AccountContact::CONTACT_PHONE."' AND value = '$phone'";
        if ($accountId) $parameters .= "  AND account_id != '$accountId'";

        return parent::findFirst($parameters)?false:true;
    }

    public static function getContact ($accountId, $type)
    {
        $parameters = "type = '".$type."' AND account_id = '$accountId'";
        return parent::findFirst($parameters);
    }

    public static function getPhone ($accountId)
    {
        return AccountContact::getContact($accountId, AccountContact::CONTACT_PHONE);
    }

    public static function getEmail ($accountId)
    {
        return AccountContact::getContact($accountId, AccountContact::CONTACT_EMAIL);
    }

	public static function findByAccount($accountid)
	{
		$parameters = ["account_id='$accountid'"];
		return parent::find($parameters);
	}

    public static function findByEmail ($email)
    {
        $parameters = "type = '".AccountContact::CONTACT_EMAIL."' AND value = '$email'";
        return parent::findFirst($parameters);
    }

}