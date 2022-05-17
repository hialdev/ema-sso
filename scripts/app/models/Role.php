<?php

class Role extends BaseModel
{
    const ROLE_GENERIC      = 'generic';
    const ROLE_STAFF        = 'staff';
    const ROLE_CLIENT       = 'client';

    protected $tablename = 'roles';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    public function initialize()
    {
        parent::initialize();

        // To the intermediate table
        $this->hasMany(
            'id',
            AccountRole::class,
            'role_id'
        );
        
        // Many to many -> Roles
        $this->hasManyToMany(
            'id',
            AccountRole::class,
            'role_id',
            'account_id',
            Account::class,
            'id',
            [
                'reusable' => true,
                'alias'    => 'Accounts',
            ]
        );
    }

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
