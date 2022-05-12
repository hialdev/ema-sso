<?php

class BaseAppController extends BaseController
{
    protected function destroyLoggedAccount()
    {
        ///$domain = parse_url($this->config->application->baseUrl, PHP_URL_HOST);
        $this->cookies->set(
            $this->config->cookie->sso->id,
            null,
            -1,
            "/",
            $this->config->cookie->secure,
            $this->config->cookie->sso->domain,
            $this->config->cookie->httpOnly
        );
        $this->cookies->send();

        $this->clearSession();

        /* $this->cookies->set($this->config->cookie->sso->id, null, -1, "", null, $this->config->cookie->sso->domain);
        $this->cookies->send(); */
    }

    protected function clearSession()
    {
        $this->session->set($this->config->application->appId, false);
    }

    protected function isAccountLogged()
    {
        return $this->getLoggedParams () ? true : false;
    }

    protected function setAccountLogged($accountUid)
    {
        $authParams = [
            'accountUid'    => $accountUid,
            'loggedAt'      => Utils::now()
        ];
        $this->cookies->set(
            $this->config->cookie->sso->id,
            json_encode($authParams),
            //time() + (365 * 86400),         // 1 year
            strtotime('+1 year'),
            "/",
            $this->config->cookie->secure,
            $this->config->cookie->sso->domain,
            $this->config->cookie->httpOnly
        );
        $this->cookies->send();
    }

    protected function redirectLogin ($loginQuery = null)
    {
        $loginUrl = "auth/login";
        if ($loginQuery) $loginUrl .= "?".$loginQuery;

        $this->redirectTo ($loginUrl);
    }

    protected function validateAccess ()
    {
        if ($refUrl = $this->request->getQuery('url'))
            return $this->redirectTo($refUrl);
    }

    protected function menuAccessible ($menu)
    {
        return true;
    }

}
