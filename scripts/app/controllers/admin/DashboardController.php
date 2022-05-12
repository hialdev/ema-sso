<?php

class DashboardController extends BaseAppController
{
    protected $pageTitle = "Dashboard";

    public function indexAction()
    {
    }

    public function statAjax ()
    {
        $user_total = Account::count();
        $app_total = Application::count();

        return $this->responseSuccess([
            'user_total'    => Utils::asNumber($user_total,0),
            'app_total'     => Utils::asNumber($app_total,0),
        ]);
    }
}