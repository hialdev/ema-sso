<?php

class SettingController extends BaseAppController
{
    protected $pageTitle = "System Settings";

    public function indexAction()
    {
        $this->view->apiUrl =  $this->config->whatsapp->server->public;
    }
}