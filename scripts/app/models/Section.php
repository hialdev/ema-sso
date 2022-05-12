<?php

class Section extends BaseModel
{
    protected $tablename = 't_sections';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static function findByProject ($id_project)
    {
        return parent::find(["id_project='$id_project'"]);
    }

    static function findByProjectAccount ($id_project, $id_account)
    {
        return parent::find(["id_project='$id_project' AND id_account='$id_account'"]);
    }

    public function getTasks ($status = null, $priority = null)
    {
        if ($this->id == 0)
        {
            $tasks = $this->id_account != 0 ?
                Task::findByAssignee($this->id_account, $status, $priority):
                Task::findHomeTask($this->id_project, $status, $priority);
        }
        else
        {
            $tasks = SectionTask::findTaskBySection($this->id, $status, $priority);
        }

        return $tasks;

        /* return $this->id == 0 ?
            Task::findHomeTask($this->id_project, $status, $priority) :
            SectionTask::findTaskBySection($this->id, $status, $priority); */
    }

    static function homeSection ($id_project)
    {
        $section = new Section;
        $section->id = 0;
        $section->id_project = $id_project;
        $section->name = 'Tasks';

        return $section;
    }

    static function assignedSection ($id_account)
    {
        $section = new Section;
        $section->id = 'assigned';
        $section->id_project = 0;
        $section->id_account = $id_account;
        $section->name = 'Assigned Tasks';

        return $section;
    }

    static function addSection ($id_project, $id_account, $name = null)
    {
        $section = new Section;
        $section->id_project = $id_project;
        $section->name = $name?:'New Section';
        $section->created_at = Utils::now();
        $section->id_account = $id_account;

        if ($section->save())
        {
            return $section;
        }

        return false;
    }

    public function addTask ($task)
    {
        if (SectionTask::isExists($this->id, $task->id))
            return false;

        $sectionTask = new SectionTask;
        $sectionTask->id_project = $task->id_project;
        $sectionTask->id_section = $this->id;
        $sectionTask->id_task = $task->id;

        return $sectionTask->save() ? $sectionTask : false;
    }
}