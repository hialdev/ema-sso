<?php

class BaseController extends Phalcon\Mvc\Controller
{
    protected $publicAccess = false;
    protected $account;

    protected $profile;
    protected $pageTitle = "";
    protected $pageDescription = "";
    protected $userMenu = [];
    protected $activePage;
    protected $application;
    protected $browserId;

    public function onConstruct()
    {
        $this->setBrowserId ();
    }

    public function loginAction()
    {
        $ref = $this->request->get("ref");
        if ($this->isAuth())
        {
            return $ref ?
                $this->redirectTo($ref) :
                $this->redirectHome();
        }

        $loginQuery = Utils::normalizeUri($this->config->application->baseUrl, $ref);
        return $this->redirectLogin($loginQuery);
    }

    /* public function nologinAction()
    {
        $ref = $this->request->get("ref");
        if ($this->isAuth())
        {
            return $ref ?
                $this->redirectTo($ref) :
                $this->redirectHome();
        }

        if (!$this->hasAccess ())
        {
            return $this->response->redirect('error/show503')->sendHeaders();
        }

        $this->view->pageTitle = 'Sesi Login Berakhir';
    } */

    public function exitAction()
    {
        $this->view->disable();

        if (!$this->hasAccess ())
        {
            return $this->response->redirect('error/show503')->sendHeaders();
        }

        $this->destroyLoggedAccount();
        return $this->redirectLogin($this->config->application->baseUrl);
    }

    protected function getAppCookie ($cookieId)
    {
        if ($cookie = $this->cookies->get($cookieId))
        {
            return $cookie->getValue();
        }

        return null;
    }

    protected function setAppCookie ($cookieId, $cookieData, $lifetime = null, $domain = null)
    {
        if (is_null($lifetime)) $lifetime = strtotime('+1 year');

        if (empty($domain))
            $domain = parse_url($this->config->application->baseUrl, PHP_URL_HOST);

        $this->cookies->set(
            $cookieId,
            $cookieData,
            $lifetime,
            "/",
            $this->config->cookie->secure,
            $domain,
            $this->config->cookie->httpOnly
        );
        $this->cookies->send();
    }

    private function setBrowserId ()
    {
        $cookieID = $this->config->cookie->browser->id;

        $this->browserId = $this->getAppCookie($cookieID);

        if (empty($this->browserId))
        {
            $this->browserId = Utils::randomString(32);
        }

        $this->setAppCookie($cookieID, $this->browserId);
        /* $domain = parse_url($this->config->application->baseUrl, PHP_URL_HOST);

        $this->cookies->set(
            $cookieID,
            $this->browserId,
            strtotime('+1 year'),
            "/",
            $this->config->cookie->secure,
            $domain,
            $this->config->cookie->httpOnly
        );
        $this->cookies->send(); */
    }

    protected function clearSession()
    {
        $this->session->set($this->config->application->appId, false);
        $this->session->set($this->config->application->appId.'_profile', false);
        //$this->session->destroy();
    }

    protected function destroyLoggedAccount()
    {/*
        $this->cookies->set(
            $this->config->cookie->sso->id,
            null,
            -1,
            "/",
            $this->config->cookie->secure,
            $this->config->cookie->sso->domain,
            $this->config->cookie->httpOnly
        );
        $this->cookies->send(); */

        $this->setAppCookie($this->config->cookie->sso->id, null, -1, $this->config->cookie->sso->domain);
        $this->clearSession();
    }

    protected function getLoggedParams ()
    {
        if ($authParams = $this->getAppCookie($this->config->cookie->sso->id))
        {
            if ($auth = json_decode($authParams))
            {
                if (isset($auth->accountUid) && !empty($auth->accountUid))
                    return $auth;
            }
        }

        return FALSE;
    }

    protected function isAccountLogged()
    {
        if ($this->getLoggedAccount())
            return true;

        return false;
    }

    protected function getLoggedAccount()
    {
        if ($authParams = $this->getLoggedParams())
        {
            return Account::findByUID($authParams->accountUid);
        }

        return false;
    }

    protected function validateAccount()
    {
        if ($this->publicAccess == false)
        {
            if (!$this->isAccountLogged())
            {
                $this->clearSession();
                $this->redirectLogin();
            }

            $this->account = $this->getLoggedAccount();
            $this->setProfile();
        }
    }

    protected function setProfile ()
    {
        if (empty($data = $this->session->get($this->config->application->appId.'_profile')))
        {
            $acc        = $this->getLoggedAccount();
            $roles      = AccountRole::roleAsArray($this->account->id);
            $staffRole  = AccountRole::findByAccountRoleSlug($this->account->id, Role::ROLE_STAFF);
            $staff      = $staffRole ? Employee::findById($staffRole->object_id) : null;

            $data = [
                'user' => $staff ? [
                    'id'    => $staffRole->id,
                    'name'  => $staff->getFullName(),
                ] : [
                    'id'    => $acc->id,
                    'name'  => $acc->name,
                ],
                'roles' => $roles,
            ];

            $this->session->set($this->config->application->appId.'_profile', $data);
        }

        $this->profile = $data;
        return $data;
    }

    protected function redirectTo ($uri)
    {
        $this->response->redirect($uri)->sendHeaders();
        exit;
    }

    protected function redirectLogin ($loginQuery = null)
    {
        $params = [
            'appId'     => $this->config->application->appId,
            'backUrl'   => $loginQuery?:$this->config->application->baseUrl . APP_URL_REQUEST,
            'bId'       => $this->browserId,
            'r'         => Utils::randomString(32)
        ];
        $url = $this->config->application->accountUrl .'auth/login?' .  http_build_query($params);
        $this->redirectTo ($url);
    }

    protected function redirectHome ()
    {
        $this->redirectTo ($this->config->application->defaultRoute);
    }

    protected function hasAccess ()
    {
        if ($this->isAccountLogged())
        {
            if (empty($this->account))
                $this->account = $this->getLoggedAccount();

            if ($access = Access::findAccess($this->application->id, $this->account->id))
            {
                if ($access->accountHasAccess()) return true;
            }
        }
        return false;
    }

    protected function validateAccess ()
    {
        $access = Access::findAccess($this->application->id, $this->account->id);

        if (empty($access) || !$access->accountHasAccess())
        {
            $this->clearSession();
            return $this->response->redirect('error/show503')->sendHeaders();
        }

        if ($accessToken = $this->request->getQuery('at'))
        {
            if ($accessDevice = AccessDevice::findByToken($accessToken))
            {
                if ($accessDevice->access_id == $access->id && $accessDevice->device_id == $this->browserId)
                {
                    if ($refUrl = $this->request->getQuery('url'))
                        return $this->redirectTo($refUrl);

                    return $this->redirectHome();
                }
            }
        }
    }

    protected function validateMenu ()
    {
        $curPage = $this->dispatcher->getControllerName();
        if ($this->dispatcher->getActionName() !== 'index')
            $curPage .= '/'.$this->dispatcher->getActionName();

        $curPage = strtolower($curPage);

        foreach ($this->menu as $title => $menu)
        {
            if (!$this->menuAccessible($menu)) continue;

            $_menu = [
                'title'     => $title,
                'header'    => $menu->header?:false,
                'desc'      => $menu->desc,
                'icon'      => $menu->header ? '':(isset($menu->icon) ? $menu->icon : ''),
                'show'      => isset($menu->show) ? $menu->show : true,
                'active'    => $menu->header ? false :  ($this->menuHasUrl ($menu, $curPage) ? 'active' : ''),
                'url'       => isset($menu->url) ? $menu->url : '#',
                'write'     => isset($menu->write) ? $this->accountHasRole($menu->write) : true,
                'menu'      => []
            ];

            if ($_menu['active']) $this->activePage = $_menu;

            $this->userMenu[] = $_menu;
            if ($menu->menu)
            {
                foreach ($menu->menu as $submenu)
                {
                    if (!$this->menuAccessible($submenu)) continue;

                    $child = [
                        'title'     => $submenu->title,
                        'desc'      => $submenu->desc,
                        'header'    => false,
                        'show'      => isset($submenu->show) ? $submenu->show : $_menu['show'],
                        'icon'      => isset($submenu->icon) ? $submenu->icon : '',
                        'active'    => $this->menuHasUrl ($submenu, $curPage) ? 'active' : '',
                        'url'       => isset($submenu->url) ? $submenu->url : '#',
                        'write'     => isset($submenu->write) ? $this->accountHasRole($submenu->write) : true,
                        'menu'      => []
                    ];
                    if ($child['active']) $this->activePage = $child;

                    if ($submenu->menu)
                    {
                        foreach ($submenu->menu as $menuchild)
                        {
                            if (!$this->menuAccessible($menuchild)) continue;

                            $subchild = [
                                'title'     => $menuchild->title,
                                'desc'      => $menuchild->desc,
                                'header'    => false,
                                'show'      => isset($menuchild->show) ? $menuchild->show : $child['show'],
                                'icon'      => isset($menuchild->icon) ? $menuchild->icon : '',
                                'active'    => $this->menuHasUrl ($menuchild, $curPage) ? 'active' : '',
                                'url'       => isset($menuchild->url) ? $menuchild->url : '#',
                                'write'     => isset($menuchild->write) ? $this->accountHasRole($menuchild->write) : true,
                                'menu'      => []
                            ];

                            if ($subchild['active'])
                            {
                                $this->activePage = $subchild;
                                $child['active'] = 'active';
                            }


                            if (isset($menuchild->menu) && $menuchild->menu)
                            {

                                foreach ($menuchild->menu as $menugrandchild)
                                {
                                    if (!$this->menuAccessible($menugrandchild)) continue;

                                    $grandchild = [
                                        'title'     => $menugrandchild->title,
                                        'desc'      => $menugrandchild->desc,
                                        'header'    => false,
                                        'show'      => isset($menugrandchild->show) ? $menugrandchild->show : $menuchild['show'],
                                        'icon'      => isset($menugrandchild->icon) ? $menugrandchild->icon : '',
                                        'active'    => $this->menuHasUrl ($menugrandchild, $curPage) ? 'active' : '',
                                        'url'       => isset($menugrandchild->url) ? $menugrandchild->url : '#',
                                        'write'     => isset($menugrandchild->write) ? $this->accountHasRole($menugrandchild->write) : true,
                                        'menu'      => []
                                    ];
                                    $subchild['menu'][] = $grandchild;

                                    if ($grandchild['active'])
                                    {
                                        $this->activePage = $grandchild;
                                        $subchild['active'] = 'active';
                                        $child['active'] = 'active';
                                    }

                                    //saat ini cukup dua level menu aja, tidak ada pengecekan submenu
                                }
                            }
                            $child['menu'][] = $subchild;
                        }
                    }

                    $this->userMenu[] = $child;
                }
            }
        }

        $this->view->curPage = $curPage;
    }

    protected function validate()
    {
        $this->validateAccount();
        $this->validateAccess();
    }

    protected function setViewData()
    {
        $this->view->account = (object) $this->account->normalizeToArray();
        $this->view->profile = $this->profile;
    }

    protected function setAssetVariable ()
    {
        $this->view->assets = Utils::asset_path();
    }

    protected function setAppVariables()
    {
        if (!$this->request->isAjax())
        {
            $this->view->domain = $this->config->application->baseUrl;
            $this->view->appTitle = $this->config->application->title;
            $this->view->appShortTitle = $this->config->application->shortTitle;
            $this->view->pageTitle = $this->pageTitle;
            $this->view->pageDescription = $this->pageDescription;
            $this->view->accountUrl = $this->config->application->accountUrl;

            $this->setAssetVariable ();
        }
    }

    public function initialize()
    {
        $this->application = Application::findByAppId($this->config->application->appId);

        $this->setAppVariables();

        if (!$this->publicAccess)
        {
            $this->validate();
            $this->setViewData();

            if (!$this->request->isAjax())
            {
                $this->validateMenu();
                $this->view->list_menus = $this->userMenu;

                if (empty($this->activePage))
                {
                    $this->response->redirect('error/show404')->sendHeaders();
                }

                $this->view->page = $this->activePage;
                $this->view->pageTitle = $this->activePage['title'];
                $this->view->pageDescription = $this->activePage['desc'];

                $this->initData();
            }
        }
    }

    protected function initData ()
    {
        $this->view->appData = base64_encode(base64_encode(json_encode([
            'account'   => $this->account->normalizeInfo (),
            'profile'   => $this->profile,
            'accountUrl'=> $this->config->application->accountUrl,
            'appTitle'  => $this->config->application->title,
        ])));
    }

    protected function ajaxResponse($status, $message, $data = null, $code = null)
    {
        $resp = [
            'status'    => $status,
            'message'   => $message
        ];

        if (!is_null($code))
            $resp['code'] = $code;

        if (!is_null($data))
            $resp['data'] = $data;

        return $this->jsonResponse($resp);
    }

    protected function jsonResponse ($resp)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        return json_encode($resp);
    }

    protected function responseSuccess($data = null, $message='success')
    {
        return $this->ajaxResponse(true, $message, $data);
    }

    protected function responseError($message, $code = null, $data = null)
    {
        return $this->ajaxResponse(false, $message, $data, $code);
    }

    private function _generateLog ($message)
    {
        return '['.$this->router->getControllerName().':'.$this->router->getActionName().'] '.$message;
    }

    protected function logDebug ($message)
    {
        $this->log->debug($this->_generateLog($message));
    }

    protected function logError ($message)
    {
        $this->log->error($this->_generateLog($message));
    }

    protected function logInfo ($message)
    {
        $this->log->info($this->_generateLog($message));
    }

    protected function logWarning ($message)
    {
        $this->log->warning($this->_generateLog($message));
    }

    protected function saveUploadedFile ($fileObject, $filename)
    {
        if ($fileObject instanceof Phalcon\Http\Request\File)
        {
            $filePath = $this->config->filePath.$filename;
            $basedir = dirname($filePath);
            
            if (!file_exists($basedir)) mkdir ($basedir,0777,true);
            
            $allowed = array('gif', 'png', 'jpg', 'jpeg', 'zip', 'pdf');

            if (in_array($fileObject->getExtension(),$allowed)) {
                if ($fileObject->moveTo($filePath))
                {
                    return true;
                }else{
                    $this->flashSession->error("File $filePath tidak dapat diupload.");
                }
            }else{
                $this->flashSession->error("File extension tidak didukung.");
                return false;
            }
        }

        return false;
    }

    protected function getFileUrl ($filename)
    {
        return $this->config->fileUrl.$filename;
    }

    protected function deleteFile ($filename)
    {
        $filePath = $this->config->filePath.$filename;

        if (file_exists($filePath))
            return unlink($filePath);

        return false;
    }

    protected function accountHasRole ($roles)
    {
        if ($roles instanceof Phalcon\Config)
            $roles = $roles->toArray();
        else if (!is_array($roles))
            $roles = [$roles];

        return is_array($roles) && is_array($this->user['roles']) ?
            count(array_intersect($this->user['roles'], $roles)) > 0 : false;
    }

    protected function menuHasUrl ($menu, $url)
    {
        if (isset($menu->alias) && $menu->alias == $url) return true;
        return $menu->url == $url;
    }

    protected function menuAccessible ($menu)
    {
        $accountRoles = true;
        if (isset($menu->roles))
        {
            if ($accountRoles = $this->accountHasRole ($menu->roles))
                return true;
            else $accountRoles = false;
        }

        return $accountRoles;
    }

    protected function getQueryString ()
    {
        $querys = $this->request->getQuery();
        unset($querys["_url"]);

        return http_build_query($querys);
    }

    protected function executeQuery ($sqlQuery)
    {
        $db = $this->getDi()->getShared('db');
        return $db->execute($sqlQuery);
    }
}