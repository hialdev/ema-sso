<?php

class Task extends BaseModel
{
    const TASK_TODO         = 0;
    const TASK_INPROGRESS   = 2;
    const TASK_COMPLETED    = 1;

    const TASK_PRIORITY_LOW     = 0;
    const TASK_PRIORITY_NORMAL  = 1;
    const TASK_PRIORITY_HIGH    = 2;
    const TASK_PRIORITY_URGENT  = 3;

    protected $tablename = 't_tasks';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static function findBySection ($id_section)
    {
        return parent::find(["id_section='$id_section'"]);
    }

    static function findByProject ($id_project, $status = null, $priority = null)
    {
        return parent::find(["id_project='$id_project'"]);
    }

    static function findPrioritiesTask ($id_account)
    {
        return parent::query()
            ->columns(["Task.*", "project.*"])
            ->leftJoin("Project", "Task.id_project=project.id", "project")
            ->where("((Task.id_account=:account: AND Task.id_project=0) OR Task.id_assignee=:account:)", ['account' => $id_account])
            ->andWhere("Task.status!=:status:", ['status' => self::TASK_COMPLETED])
            ->inWhere("Task.priority", [self::TASK_PRIORITY_HIGH,self::TASK_PRIORITY_URGENT ])
            ->orderBy("Task.due_date")
            ->execute();
    }

    static function findByIds ($ids = [])
    {
        return parent::query()->inWhere('id', $ids)->execute();
    }

    static function findByAssignee ($id_assignee, $status = null, $priority = null)
    {
        $query = parent::query()
            ->where("Task.id_assignee=:assignee:", ['assignee' => $id_assignee]);

        if (is_numeric($status))
        {
            $query->andWhere("Task.status = :status:", ['status' => $status]);
        }
        elseif (is_array($status))
        {
            $query->inWhere("Task.status", $status);
        }

        if (is_numeric($priority))
        {
            $query->andWhere("Task.priority = :priority:", ['priority' => $priority]);
        }

        return $query->execute();
        //parent::find(["id_assignee='$id_assignee'"]);
    }

    public function savePriority ($priority = 0)
    {
        $this->priority = $priority;
        return $this->save();
    }

    static function findHomeTask ($id_project, $status = null, $priority = null)
    {
        $query = parent::query()
            ->where('Task.id_project=:project:', ['project' => $id_project])
            ->andWhere("Task.id NOT IN (SELECT id_task FROM SectionTask WHERE SectionTask.id_project=:project:)", ['project' => $id_project]);

        if (is_numeric($status))
        {
            $query->andWhere("Task.status = :status:", ['status' => $status]);
        }
        elseif (is_array($status))
        {
            $query->inWhere("Task.status", $status);
        }

        if (is_numeric($priority))
        {
            $query->andWhere("Task.priority = :priority:", ['priority' => $priority]);
        }

        return $query->execute();

        /* return parent::query()
            ->where('Task.id_project=:project:')
            ->andWhere("Task.id NOT IN (SELECT id_task FROM SectionTask WHERE SectionTask.id_project=:project:)")
            ->bind(['project' => $id_project])
            ->execute(); */
    }

    public function getAssignee ()
    {
        return Account::findById($this->id_assignee);
    }

    public function getProject ()
    {
        return Project::findById($this->id_project);
    }

    static function summaryByProject ($id_project)
    {
        return parent::query()
            ->columns([
                "COUNT(1) as task_total",
                "SUM(IF(Task.status = 1, 1, 0)) as task_completed",
                "SUM(IF(Task.status = 1, 0, 1)) as task_incomplete",
            ])
            ->where("Task.id_project = :project:", ['project' => $id_project])
            ->execute()
            ->getFirst();
    }

    public function getStatusIcon ()
    {
        return TaskHelper::statusIcon($this->status);
    }

    public function getPriorityIcon ()
    {
        return TaskHelper::priorityIcon($this->priority);
    }

    public function getDueDateText ()
    {
        return $this->due_date ? Utils::formatTanggal($this->due_date, true, true) : '';
    }

    public function addComment ($id_account, $comment)
    {
        return TaskComment::addComment ($this->id, $id_account, $comment);
    }

    public function getComments ()
    {
        return TaskComment::findComments($this->id);
    }

    public function getFiles ()
    {
        return File::findByTask($this->id);
    }
}