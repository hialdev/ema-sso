<?php

class SectionTask extends BaseModel
{
    protected $tablename = 't_section_tasks';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static function isExists ($id_section, $id_task)
    {
        return parent::findFirst(["id_section='$id_section' AND id_task='$id_task'"]) ? true : false;
    }

    static function findTaskBySection ($id_section, $status = null, $priority = null)
    {
        $bind = ['section' => $id_section];

        $query = parent::query()
            ->columns(["task.*"])
            ->leftJoin('Task','SectionTask.id_task=task.id', "task")
            ->where("SectionTask.id_section=:section:", ['section' => $id_section]);

        if (is_numeric($status))
        {
            $query->andWhere("task.status = :status:", ['status' => $status]);
        }
        elseif (is_array($status))
        {
            $query->inWhere("task.status", $status);
        }

        if (is_numeric($priority))
        {
            $query->andWhere("task.priority = :priority:", ['priority' => $priority]);
        }

        return $query->execute();

        /* return parent::query()
            ->columns(["task.*"])
            ->leftJoin('Task','SectionTask.id_task=task.id', "task")
            ->where("SectionTask.id_section=:section:")
            ->bind(['section' => $id_section])
            ->execute(); */
    }
}