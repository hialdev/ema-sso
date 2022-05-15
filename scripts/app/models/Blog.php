<?php

class Blog extends BaseModel
{
    protected $tablename = 'blog';
    protected $dbprofile = 'ticket';
    protected $keys = ["id"];

    public static function findBySlug ($slug)
    {
        $parameters = ["slug = '$slug'"];
        return parent::findFirst($parameters);
    }
}
