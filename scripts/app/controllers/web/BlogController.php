<?php

class BlogController extends BaseAppController
{

    public function indexAction()
    {
        $this->view->pick('blog/index');
    }

    public function viewAction()
    {
        if ($slug = $this->dispatcher->getParam('slug'))
        {
            $this->view->slug = $slug;
            $this->view->pick('blog/view');
        }else{
            $this->view->pick('erorr/404');
        }
    }
}