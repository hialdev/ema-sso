<?php

class NoteController extends BaseAppController
{

    public function indexAction()
    {
        $this->view->pick('note/index');
    }

    public function addAction()
    {
        $this->view->pick('note/add');
    }

    public function editAction()
    {
        $this->view->pick('note/edit');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $this->view->slug = $slug;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('note/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }

    public function byProjectAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $this->view->slug = $slug;
            $this->view->pick('note/byproject');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}