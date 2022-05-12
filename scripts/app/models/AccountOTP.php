<?php

class AccountOTP extends BaseModel
{
    protected $tablename = 'account_otps';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    public static function findOTP ($accessId, $otp)
    {
        $parameters = "access_id='$accessId' AND otp_code = '$otp' AND status = 0 AND expired >= NOW()";
        return parent::findFirst($parameters);
    }

    public static function findByAccess ($accessId)
    {
        $parameters = "access_id='$accessId'";
        return parent::findFirst($parameters);
    }

    public function closeOTP ()
    {
        $this->status = 1;
        return $this->save();

    }
}