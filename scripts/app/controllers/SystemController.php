<?php

class SystemController extends BaseController
{
    protected $pageTitle = "Akun";

    public function appsAjax ()
    {
        $apps = [];
        if ($list_applications = Access::findWebAppByAccountDetail($this->account->id))
        {
            foreach ($list_applications as $app)
            {
                if ($app->status == 1 && $app->id != $this->application->id)
                {
                    $url = Utils::cleanUrl($app->url).'/auth/login';

                    $apps[] = [
                        'name'          => $app->name,
                        'url'           => $url,
                        'description'   => $app->description,
                    ];
                }
            }
        }

        return $this->responseSuccess ($apps);
    }
}