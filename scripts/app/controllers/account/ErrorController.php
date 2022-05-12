<?php

class ErrorController extends BaseAppController
{
    protected $publicAccess = true;
    protected $pageTitle = '404';

    public function indexAction()
    {
        $this->show404Action();
    }

    public function show404Action()
    {
        $this->application = Application::findByAppId($this->config->application->appId);
        $this->view->domain = $this->config->application->baseUrl;
        $this->view->appTitle = $this->config->application->title;
        $this->view->pageTitle = $this->pageTitle;
        $this->view->pageDescription = $this->pageDescription;
        $this->view->accountUrl = $this->config->application->accountUrl;

        $this->view->pick("error/404");
    }

    public function show503Action()
    {
        if (!$this->isAccountLogged())
        {
            return $this->redirectHome();
        }

        $this->application = Application::findByAppId($this->config->application->appId);
        $this->view->domain = $this->config->application->baseUrl;
        $this->view->appTitle = $this->config->application->title;
        $this->view->pageTitle = $this->pageTitle;
        $this->view->pageDescription = $this->pageDescription;
        $this->view->accountUrl = $this->config->application->accountUrl;

        $this->view->pick("error/503");
    }
}