<?php

class Note extends BaseModel
{
    protected $tablename = 'note';
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
            'account_id',
            Account::class,
            'id',
            [
                'alias'     => 'Account',
                'reusable'  => true,
            ]
        );
        $this->hasOne(
            'modifer',
            Account::class,
            'id',
            [
                'alias'     => 'Modifer',
                'reusable'  => true,
            ]
        );
        $this->hasMany(
            'id',
            NoteFile::class,
            'note_id',
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
