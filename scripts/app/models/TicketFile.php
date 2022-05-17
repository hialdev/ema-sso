<?php

class TicketFile extends BaseModel
{
    protected $tablename = 'ticket_file';
    protected $dbprofile = 'ticket';
    protected $keys = ["id"];

    public function initialize()
    {
        parent::initialize();

        $this->hasOne(
            'ticket_id',
            Ticket::class,
            'id',
            [
                'alias'     => 'Ticket',
                'reusable'  => true,
            ]
        );

        $this->hasOne(
            'chat_id',
            Chat::class,
            'id',
            [
                'alias'     => 'Chat',
                'reusable'  => true,
            ]
        );

    }
   
}
