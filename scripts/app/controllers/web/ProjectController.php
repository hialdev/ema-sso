<?php

class ProjectController extends BaseAppController
{

    public function indexAction()
    {
        $this->view->pick('project/index');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $this->view->slug = $slug;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('project/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}