<?php

class ProjectController extends BaseAppController
{

    public function indexAction()
    {
        $project = Project::find();

        $this->view->projects = $project;
        $this->view->pick('project/index');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $project = Project::findFirst("slug = '$slug'");
            $ps = ProjectStatus::find();

            $this->view->status = $ps;
            $this->view->project = $project;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('project/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}