<?php

class Chat extends BaseModel
{
    protected $tablename = 'chat';
    protected $dbprofile = 'ticket';
    protected $keys = ["id"];

    public function initialize()
    {
        parent::initialize();

        $this->hasOne(
            'account_id',
            Account::class,
            'id',
            [
                'alias'     => 'Account',
                'reusable'  => true,
            ]
        );

        $this->hasOne(
            'ticket_id',
            Ticket::class,
            'id',
            [
                'alias'     => 'Ticket',
                'reusable'  => true,
            ]
        );

        $this->hasMany(
            'id',
            TicketFile::class,
            'chat_id',
            [
                'alias'     => 'Files',
                'reusable'  => true,
            ]
        );
    }
}
