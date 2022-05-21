<?php

class Task extends BaseModel
{
    protected $tablename = 'task';
    protected $dbprofile = 'ticket';
    protected $keys = ["id"];

    public function initialize()
    {
        parent::initialize();

        $this->hasOne(
            'status',
            ProjectStatus::class,
            'id',
            [
                'alias'     => 'Status',
                'reusable'  => true,
            ]
        );
    }
}
