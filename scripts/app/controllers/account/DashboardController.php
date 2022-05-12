<?php

class DashboardController extends BaseAppController
{
    protected $pageTitle = "Dashboard";

    public function indexAction()
    {
        $this->view->today = Utils::now();
        $this->view->nama_hari = Utils::FormatTanggal(time(), false, true);
        $this->view->nama_bulan = Utils::namaBulan();

        $this->view->list_applications = Access::findWebAppByAccountDetail($this->account->id);

        $this->view->pick('pages/dashboard');
    }
}