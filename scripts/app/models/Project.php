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

    public function getImageUrl ()
    {
        if (empty($this->config))
            $this->config = $this->getDi()->getConfig();
        
        $filePath = $this->config->filePath.$this->image;
        $basedir = dirname($filePath);
        if (!file_exists($basedir)) {
            return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQMAAADCCAMAAAB6zFdcAAAAQlBMVEX///+hoaGenp6ampr39/fHx8fOzs7j4+P8/Pyvr6/d3d3FxcX29va6urqYmJjs7OzU1NSlpaW1tbWtra3n5+e/v78TS0zBAAACkUlEQVR4nO3b63KCMBCGYUwUUVEO6v3fagWVY4LYZMbZnff51xaZ5jON7CZNEgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQb5tvI8qzX4/nH84XG5Upfj2ir2V2E5fZ/XpIX9saMnhkYLIkiyRJjdgMoiEDMmiQgfwM8rSu77ew2wnPoLTmwdZBs0J2BuXrYckcQm4nOoP+WcmWAbcTnUHZPy9eA24nOoN7n0HI54ToDM5k8PjluwyqgNuJzqDoaugPg8gWZ4noDAYLwuIg75fLeeHHsjNIzrZJwWwW+0DNsmEWPjiEZ5AcD8ZUu8VZ8HyQMifvBdIz+PS33i8adu+7Qn4Gn1Tdupl7rlCfQb9seosK7RkcBy1o30iVZ5CPOtDW3WhQnsF13IV3v0p3BqfJRoSpXVepzmA/24+yqeMyzRm4tqOs44lSUwa3yfgOri25av5CPRnklR33VlPnrqSZV09qMsiqSWV082xOz1uPajJ49pTM/f115k6guWa6JGjJ4N1lt8fXN2rv/vysjFaSQdFXBc/KKF04ptFPliclGVR9Bu27XCyeVOkmy5OODAZN9rYyyip/AIPJ8qIig+PoXbf7YdPdncFoSdCQQT4ZceV+MhiFMBy0hgyu0yGvOLI17KwpyGBaHK5jtt0N5GcwLw7XZdB31sRn8O+ziqYro8Vn4CwOV+k6a9Iz+PwRsKC7h+gMfMXhKu/OmuwM/MXhKq8yWnYG/uJw5Uxoy2jRGZTBZ/jboxuSM1guDtdNhKazJjiDbNMe0AxzKUVnkO+jEJxBxNtJzWCTxlNLzSB8KehJ/H+mJGYAjaDjzj9SnHZRuXZiAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAECXP1XDHv7U4SNFAAAAAElFTkSuQmCC";
        }
        return $this->config->application->baseUrl. 'files/'.$this->image;
    }
}
