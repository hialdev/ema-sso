<?php

class SectionController extends BaseAppController
{
    protected $pageTitle = "Section";

    /* Ajax Methos Requests  */

    public function createAjax ()
    {
        $this->view->disable();

        $projectId = $this->request->getPost('id_project','int',0);

        if ($projectId)
        {
            if ($project = Project::findById($projectId))
            {
                if ($section = $project->addSection($this->account->id))
                {
                    return $this->responseSuccess($section);
                }
            }
        }
        else
        {
            if ($section = Section::addSection ($projectId, $this->account->id))
            {
                return $this->responseSuccess($section);
            }
        }

        return $this->responseError("Failed to create new section");
    }

    public function updateAjax ()
    {
        $this->view->disable();

        $sectionId = $this->request->getPost('id','int',0);

        if ($section = Section::findById($sectionId))
        {
            $section->name = $this->request->getPost('name');
            if ($section->save())
            {
                return $this->responseSuccess($section);
            }
        }

        return $this->responseError("Failed to update section");
    }

    public function deleteAjax ()
    {
        $this->view->disable();

        $sectionId = $this->request->getPost('id','int',0);

        if ($section = Section::findById($sectionId))
        {
            $tasks = $section->getTasks();

            if ($section->delete())
            {
                if ($tasks->count() > 0)
                {
                    $tasks->delete();
                }

                return $this->responseSuccess();
            }
        }

        return $this->responseError("Failed to delete section");
    }
}