<?php

class AccessLevel extends BaseModel
{
    const ACCESS_LEVEL_ADMIN    = 'admin';
    const ACCESS_LEVEL_USER     = 'user';

    protected $tablename = 'access_levels';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    public static function findBySlug ($slug)
    {
        $parameters = ["slug = '$slug'"];
        return parent::findFirst($parameters);
    }
}