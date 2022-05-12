<?php

class File extends BaseModel
{
    protected $tablename = 't_files';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static $imageMime = [
        'image/gif', 'image/png', 'image/jpg', 'image/jpeg', 'image/webp'
    ];

    static function findByTask ($id_task)
    {
        return parent::find(["id_task='$id_task'"]);
    }

    public function isImageFile()
    {
        return in_array($this->fileMime, self::$imageMime);
    }

    public function getFileSizeText()
    {
        return Utils::readableBytes($this->fileSize);
    }

    public function getCreatedAtText()
    {
        return Utils::formatTanggal($this->created_at, true, true, true);
    }

    public function getUrl ()
    {
        if (empty($this->config))
            $this->config = $this->getDi()->getConfig();

        return $this->config->application->baseUri. 'f/'.$this->id;
    }

    public function getImageUrl ($width = null, $height = null)
    {
        if (!$this->isImageFile()) return '';

        if (empty($this->config))
            $this->config = $this->getDi()->getConfig();

        $imageSsize = "";
        if (is_numeric($width) || is_numeric($height))
        {
            $imageSsize = "/";
            $imageSsize .= $width ? $width : "";
            if ($height) $imageSsize .= "/".$height;
        }

        return $this->config->application->imageUrl. 'view/'.$this->id.$imageSsize;
    }

    public function asArrayResult ()
    {
        return [
            'taskId'        => $this->id_task,
            'fileId'        => $this->id,
            'fileName'      => $this->fileName,
            'fileDateTime'  => $this->getCreatedAtText(),
            'fileSize'      => $this->getFileSizeText(),
            'isImage'       => $this->isImageFile(),
            'fileUrl'       => $this->getUrl(),
            'thumbnail'     => $this->getImageUrl(50,50)
        ];
    }
}