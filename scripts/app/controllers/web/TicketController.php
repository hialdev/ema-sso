<?php

class TicketController extends BaseAppController
{
    protected $pageTitle = "Tickets";

    public function indexAction()
    {
        $this->view->pick('ticket/index');
    }

    public function activeAction()
    {
        $this->view->pick('ticket/active');
    }

    public function addAction()
    {
        $this->view->pick('ticket/add');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $this->view->slug = $slug;
            $this->view->backUrl = $this->prevUrl();
            $this->view->pick('ticket/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}