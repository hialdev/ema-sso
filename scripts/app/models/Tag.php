<?php

class Tag extends BaseModel
{
    protected $tablename = 't_tags';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static function findByProject ($id_project)
    {
        return parent::find(["id_project='$id_project'"]);
    }
}