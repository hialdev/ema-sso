<?php

class TaskController extends BaseAppController
{
    protected $pageTitle = "My Tasks";

    public function indexAction()
    {
        return $this->redirectHome();
    }

    public function MyTaskAction($params = null)
    {
        $filter = (new TaskHelper())->getFilter();
        $project = Project::homeProject($this->account->id);

        $sections = [];
        //$sections[] = Section::homeSection($project->id);
        $sections[] = Section::assignedSection($this->account->id);

        $_sections = $project->getSections();
        foreach ($_sections as $section)
        {
            $sections[] = $section;
        }

        $_params = explode("/", $params);
        $this->view->params = $_params && isset($_params[1]) ? $_params[1] : '';
        $this->view->taskStatus = $filter->status;
        $this->view->taskPriority = $filter->priority;
        $this->view->project = $project;
        $this->view->sections = $sections;
        $this->view->pick('pages/tasks');
    }

    /* Ajax Methos Requests  */

    public function createAjax ()
    {
        $this->view->disable();

        $id_section = $this->request->getPost('id_section','int',0);
        $id_project = $this->request->getPost('id_project','int',0);

        $section = Section::findById($id_section);
        $project = Project::findById($id_project);

        $task = new Task;
        $task->id = Task::generateId();
        $task->pid = $this->request->getPost('pid','int',0);
        //$task->id_section = $id_section;
        $task->id_account = $this->account->id;
        $task->id_project = $project ? $project->id : 0;
        $task->name = $this->request->getPost('name');
        $task->description = $this->request->getPost('description');
        $task->created_at = Utils::now();

        if ($task->save())
        {
            if ($section)
            {
                $section->addTask($task);
            }

            return $this->responseSuccess($task);
        }

        return $this->responseError("Failed to create new task");
    }

    public function detailAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id','int',0);
        if ($task = Task::findById($taskId))
        {
            $files = [];
            if ($records = $task->getFiles())
            {
                foreach ($records as $file)
                {
                    $files[] = $file->asArrayResult();
                }
            }

            return $this->responseSuccess([
                'task'  => $task->toArray(),
                'files' => $files
            ]);
        }

        return $this->responseError("Task not found");
    }

    private function _updateTask ($taskId, $data = [])
    {
        if (is_numeric($taskId))
        {
            if ($task = Task::findById($taskId))
            {
                foreach ($data as $field => $value)
                {
                    if (in_array($field,['name','description','id_section', 'id_assignee','status','priority','due_date']))
                    {
                        $task->$field = $value;
                    }

                }
                if ($task->save())
                    return $task;
            }
        }
        else if (is_array($taskId))
        {
            $updated = [];
            foreach ($taskId as $id)
            {
                if ($task = $this->_updateTask($id, $data))
                {
                    $updated[] = $id;
                }
            }

            return $updated;
        }

        return false;
    }

    public function updateAjax ()
    {
        $this->view->disable();
        $taskId = $this->request->getPost('id','int',0);
        $field = $this->request->getPost('field');

        if ($task = $this->_updateTask($taskId, [
            $field  => $this->request->getPost('value')
        ]))
        {
            return $this->responseSuccess($task);
        }

        return $this->responseError("Failed to update task");
    }

    public function assignmentAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id');
        $id_account = $this->request->getPost('id_account','int',0);

        if (($id_account == 0)  || ($account = Account::findById($id_account)))
        {
            if ($updated = $this->_updateTask($taskId, [
                'id_assignee'  => $id_account
            ]))
            {
                $taskIds = is_numeric($taskId) ? [$taskId] : $taskId;
                foreach ($taskIds as $taskId)
                {
                    if ($task = Task::findById($taskId))
                    {
                        $project = $task->getProject();
                        $assignee = $task->getAssignee();


                        $target = null;
                        if ($id_account == 0)
                        {
                            if ($assignee && $assignee->id != $this->account->id)
                            {
                                $target = $assignee->email;
                            }
                            else
                            {
                                $target = [];
                                if ($members = $project->getAdminMembers())
                                {
                                    foreach ($members as $member)
                                    {
                                        if ($member->account->email)
                                            $target[] = $member->account->email;
                                    }
                                }
                            }

                            $subject = sprintf('Task assigment in project %s has been revoked', $project->name);
                            $template = 'task_unassigned';
                        }
                        else
                        {
                            $target = $account ? $account->email : null;
                            $subject = sprintf('Task in project %s has been assigned', $project->name);
                            $template = 'task_assigned';
                        }

                        if ($target)
                        {
                            $this->notification->emailTemplate(
                                $target, $subject, $template, [
                                    'date'      => Utils::formatTanggal(time(), false, true, true),
                                    'task'      => $task,
                                    'project'   => $project,
                                    'assignee'  => $assignee,
                                    'account'   => $this->account
                                ]);
                        }
                    }
                }

                return $this->responseSuccess([
                    'updated'   => $updated,
                    'assignee'  => $account ? [
                        'assigneeId'    => $id_account,
                        'assigneeName'  => $account->name,
                        'assigneeAvatar'=> $account->getAvatarUrl()
                    ] : ['assigneeId' => 0]
                ]);
            }
        }

        return $this->responseError("Failed to update task assignment");
    }

    public function priorityAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id');
        if ($updated = $this->_updateTask($taskId, [
            'priority'  => $this->request->getPost('priority','int',0)
        ]))
        {
            return $this->responseSuccess(['updated' => $updated ]);
        }

        return $this->responseError("Failed to update task priority");
    }

    public function statusAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id');
        $status = $this->request->getPost('status','int',0);

        if ($updated = $this->_updateTask($taskId, [
            'status'  => $status
        ]))
        {
            $taskIds = is_numeric($taskId) ? [$taskId] : $taskId;
            foreach ($taskIds as $taskId)
            {
                if ($task = Task::findById($taskId))
                {
                    if ($status == Task::TASK_COMPLETED)
                    {
                        $project = $task->getProject();
                        $assignee = $task->getAssignee();
                        $subject = sprintf("Task completed in project %s", $project->name);

                        $target = [];
                        if ($assignee->id != $this->account->id && $assignee->email)
                        {
                            $target[] = $assignee->email;
                        }

                        if ($members = $project->getAdminMembers())
                        {
                            foreach ($members as $member)
                            {
                                if ($member->account->id != $this->account->id && $member->account->email)
                                    $target[] = $member->account->email;
                            }
                        }

                        if ($target)
                        {
                            $this->notification->emailTemplate(
                                $target, $subject, 'task_completed', [
                                    'date'      => Utils::formatTanggal(time(), false, true, true),
                                    'task'      => $task,
                                    'project'   => $project,
                                    'account'   => $this->account,
                                ]);
                        }

                    }
                }

            }

            return $this->responseSuccess(['updated' => $updated ]);
        }

        return $this->responseError("Failed to update task status");
    }

    public function dueDateAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id');
        $due_date = $this->request->getPost('due_date', 'string', null);

        if ($updated = $this->_updateTask($taskId, [
            'due_date'  => $due_date
        ]))
        {
            return $this->responseSuccess(['updated' => $updated ]);
        }

        return $this->responseError("Failed to update task due date");
    }

    public function commentsAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id','int',0);

        if ($task = Task::findById($taskId))
        {
            $comments = [];
            if ($records = $task->getComments())
            {
                foreach ($records as $record)
                {
                    $comments[] = [
                        'id'            => $record->taskComment->id,
                        'accountName'   => $record->account->name,
                        'accountAvatar' => $record->account->getAvatarUrl(),
                        'comment'       => $record->taskComment->comment,
                        'time'          => $record->taskComment->getCreatedAtText()
                    ];
                }
            }

            return $this->responseSuccess($comments);
        }

        return $this->responseError("Failed to add task comment");
    }

    public function addCommentAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id','int',0);
        $comment = $this->request->getPost('comment');

        if ($task = Task::findById($taskId))
        {
            if ($taskComment = $task->addComment($this->account->id, $comment))
            {
                $project = $task->getProject();
                $assignee = $task->getAssignee();

                $target = [];
                if ($assignee->id != $this->account->id && $assignee->email)
                {
                    $target[] = $assignee->email;
                }

                if ($members = $project->getAdminMembers())
                {
                    foreach ($members as $member)
                    {
                        if ($member->account->id != $this->account->id && $member->account->email)
                            $target[] = $member->account->email;
                    }
                }

                if ($target)
                {
                    $this->notification->emailTemplate(
                        $target, "New Comment in Task", 'task_commented', [
                            'date'      => Utils::formatTanggal(time(), false, true, true),
                            'task'      => $task,
                            'project'   => $project,
                            'comment'   => $comment,
                            'commentor' => $this->account
                        ]);
                }

                return $this->responseSuccess([
                    'id'            => $taskComment->id,
                    'accountName'   => $this->account->name,
                    'accountAvatar' => $this->account->getAvatarUrl(),
                    'comment'       => $comment,
                    'time'          => $taskComment->getCreatedAtText()
                ]);
            }
        }

        return $this->responseError("Failed to add task comment");
    }

    public function deleteCommentAjax ()
    {
        $this->view->disable();

        $commentId = $this->request->getPost('id','int',0);

        if ($comment = TaskComment::findById($commentId))
        {
            if ($comment->delete())
            {
                return $this->responseSuccess();
            }
        }

        return $this->responseError("Failed to delete task comment");
    }

    public function deleteAjax ()
    {
        $this->view->disable();

        $taskId = $this->request->getPost('id');
        if (is_numeric($taskId)) $taskId = [$taskId];

        if ($tasks = Task::findByIds($taskId))
        {
            $deleted = [];
            foreach ($tasks as $task)
            {
                $_taskId = $task->id;

                if ($task->delete())
                {
                    $deleted[] = $_taskId;
                }
            }

            if ($deleted)
            {
                return $this->responseSuccess([
                    'deleted' => $deleted
                ]);
            }
        }

        return $this->responseError("Failed to delete task");
    }
}