<?php

class HomeController extends BaseAppController
{
    protected $pageTitle = "Home";

    public function indexAction()
    {
        
        $this->view->pick('home/index');
    }
}