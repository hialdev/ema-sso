<?php

class AccountRecovery extends BaseModel
{
    const CONTACT_EMAIL     = 'email';
    const CONTACT_PHONE     = 'phone';

    protected $tablename = 'account_recoveries';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    public static function findByToken ($token)
    {
        $parameters = "token = '$token'";
        return parent::findFirst($parameters);
    }

    public static function findByAccountId ($accountId)
    {
        $parameters = "account_id = '$accountId'";
        return parent::findFirst($parameters);
    }

}