<?php

class NoteFile extends BaseModel
{
    protected $tablename = 'note_file';
    protected $dbprofile = 'ticket';
    protected $keys = ["id"];

    public function initialize()
    {
        parent::initialize();

        $this->hasOne(
            'note_id',
            Note::class,
            'id',
            [
                'alias'     => 'Note',
                'reusable'  => true,
            ]
        );
    }

    public function asArrayResult ()
    {
        return [
            'noteId'       => $this->note_id,
            'fileId'        => $this->id,
            'fileName'      => $this->name,
            'filePath'      => $this->path,
        ];
    }

    public function getUrl ()
    {
        if (empty($this->config))
            $this->config = $this->getDi()->getConfig();

        return $this->config->application->baseUrl. 'files/'.$this->path;
    }
   
}
