<?php

use Monolog\Handler\Curl\Util;

class TaskHelper extends  Phalcon\Di\Injectable
{
    static $statusParams = [
        'all'           => null,
        'todo'          => Task::TASK_TODO,
        'inprogress'    => Task::TASK_INPROGRESS,
        'completed'     => Task::TASK_COMPLETED,
        'active'        => [Task::TASK_TODO, Task::TASK_INPROGRESS]
    ];

    static $priorityParams = [
        'low'           => Task::TASK_PRIORITY_LOW,
        'normal'        => Task::TASK_PRIORITY_NORMAL,
        'high'          => Task::TASK_PRIORITY_HIGH,
        'urgent'        => Task::TASK_PRIORITY_URGENT,
    ];

    static $statusIcon  = [
        Task::TASK_TODO         => '<i class="far fa-check-circle text-muted tooltips" title="Todo"></i>',
        Task::TASK_INPROGRESS   => '<i class="icon-circle2 text-info tooltips" title="In Progress"></i>',
        Task::TASK_COMPLETED    => '<i class="icon-checkmark-circle text-success tooltips" title="Completed"></i>',
    ];

    static $priorityIcon  = [
        Task::TASK_PRIORITY_LOW     => '<i class="icon-arrow-down7 text-success tooltips" title="Low"></i>',
        Task::TASK_PRIORITY_NORMAL  => '<i class="icon-arrow-up7 text-muted tooltips" title="Normal"></i>',
        Task::TASK_PRIORITY_HIGH    => '<i class="icon-arrow-up7 text-warning tooltips" title="High"></i>',
        Task::TASK_PRIORITY_URGENT  => '<i class="icon-arrow-up7 text-danger tooltips" title="Urgent"></i>'
    ];

    public function normalizeFilterStatus ($status)
    {
    }

    public function normalizeFilterPriority ($priority)
    {
    }

    public function getFilter()
    {
        $filterStatus = $this->request->get('status');
        $filterPriority = $this->request->get('priority');

        if (!in_array($filterStatus, array_keys(self::$statusParams))) $filterStatus = 'active';
        if (!in_array($filterPriority, array_keys(self::$priorityParams ))) $filterPriority = null;

        return (object)[
            'status'    => self::$statusParams[$filterStatus],
            'priority'  => $filterPriority ? self::$priorityParams[$filterPriority] : null,
        ];
    }

    static function statusIcon ($status)
    {
        return Utils::getArrayValue(TaskHelper::$statusIcon, $status);
    }

    static function priorityIcon ($priority)
    {
        return Utils::getArrayValue(TaskHelper::$priorityIcon, $priority);
    }
}