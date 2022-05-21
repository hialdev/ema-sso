<?php

class Ticket extends BaseModel
{
    protected $tablename = 'ticket';
    protected $dbprofile = 'ticket';
    protected $keys = ["id"];

    public function initialize()
    {
        parent::initialize();

        $this->hasOne(
            'project_id',
            Project::class,
            'id',
            [
                'alias'     => 'Project',
                'reusable'  => true,
            ]
        );

        $this->hasOne(
            'status',
            TicketStatus::class,
            'id',
            [
                'alias'     => 'Status',
                'reusable'  => true,
            ]
        );

        $this->hasOne(
            'priority',
            TicketPriority::class,
            'id',
            [
                'alias'     => 'Priority',
                'reusable'  => true,
            ]
        );

        $this->hasMany(
            'id',
            Chat::class,
            'ticket_id',
            [
                'alias'     => 'Chat',
                'reusable'  => true,
            ]
        );

        $this->hasOne(
            'account_id',
            Account::class,
            'id',
            [
                'alias'     => 'Account',
                'reusable'  => true,
            ]
        );

        $this->hasMany(
            'id',
            TicketFile::class,
            'ticket_id',
            [
                'alias'     => 'Files',
                'reusable'  => true,
            ]
        );
    }
    public static function findBySlug ($slug)
    {
        $parameters = ["slug = '$slug'"];
        return parent::findFirst($parameters);
    }
}
