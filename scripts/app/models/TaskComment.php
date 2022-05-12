<?php

class TaskComment extends BaseModel
{
    protected $tablename = 't_task_comments';
    protected $dbprofile = 'taskman';
    protected $keys = ["id"];

    static function addComment ($id_task, $id_account, $comment)
    {
        $taskComment = new TaskComment;
        $taskComment->id = TaskComment::generateId();
        $taskComment->id_task = $id_task;
        $taskComment->comment = $comment;
        $taskComment->created_by = $id_account;
        $taskComment->created_at = Utils::now();

        if ($taskComment->save())
        {
            return $taskComment;
        }

        return false;
    }

    public function getCreatedAtText()
    {
        return   Utils::time_elapsed($this->created_at);
    }

    static function findComments ($id_task)
    {
        return parent::query()
            ->columns(['TaskComment.*', 'account.*'])
            ->leftJoin('Account', 'TaskComment.created_by=account.id', 'account')
            ->where('TaskComment.id_task=:task:', ['task' => $id_task])
            ->orderBy('TaskComment.created_at')
            ->execute();
    }
}