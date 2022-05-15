<?php

class Note extends BaseModel
{
    protected $tablename = 'note';
    protected $dbprofile = 'ticket';
    protected $keys = ["id"];

    public static function findBySlug ($slug)
    {
        $parameters = ["slug = '$slug'"];
        return parent::findFirst($parameters);
    }
}
