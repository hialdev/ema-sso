<?php

class Project extends BaseModel
{
    protected $tablename = 'project';
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
            Task::class,
            'project_id',
            [
                'alias'     => 'Tasks',
                'reusable'  => true,
            ]
        );

        $this->hasMany(
            'id',
            Ticket::class,
            'project_id',
            [
                'alias'     => 'Tickets',
                'reusable'  => true,
            ]
        );

        $this->hasMany(
            'id',
            Note::class,
            'project_id',
            [
                'alias'     => 'Notes',
                'reusable'  => true,
            ]
        );
    }

    public static function findBySlug ($slug)
    {
        $parameters = ["slug = '$slug'"];
        return parent::findFirst($parameters);
    }

    public function bar($project)
    {
        $task = $project->getRelated('Tasks');
        $c = count($task);
        $tc = [];
        foreach ($task as $t) {
            if ($t->status === '3') {
                $tc[] = $t;
            }
        }
        $ctc = count($tc);
        $bar = $ctc / $c * 100;
        $bar = number_format($bar, 0);
        return $bar;
    }

    public function countStatus($project,$status_id)
    {
        $task = $project->getRelated('Tasks');
        $tc = [];
        foreach ($task as $t) {
            if ($t->status === (string) $status_id) {
                $tc[] = $t;
            }
        }
        $ctc = count($tc);
        return $ctc;
    }
}
