<?php

class Role extends BaseModel
{
    const ROLE_GENERIC      = 'generic';
    const ROLE_STAFF        = 'staff';
    const ROLE_CLIENT       = 'client';

    protected $tablename = 'roles';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    public static function getAsOption ()
    {
        $list = parent::find();
        $data = [];

        if ($list)
        {
            foreach ($list as $role)
            {
                $data[] = [
                    'id'    => $role->id,
                    'name'  => $role->name,
                    'icon'  => Utils::getArrayValue(ApplicationHelper::$roleIcons, $role->slug)
                ];
            }
        }

        return $data;
    }

    public static function findBySlug ($slug)
    {
        $parameters = ["slug = '$slug'"];
        return parent::findFirst($parameters);
    }
}
