<?php

class BaseAppController extends BaseController
{
    protected function setAssetVariable ()
    {
        $this->view->assets = Utils::asset_path(null, 'rev-manifest-admin.json');
    }
}