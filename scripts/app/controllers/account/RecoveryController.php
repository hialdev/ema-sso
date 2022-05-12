<?php

class RecoveryController extends BaseAppController
{
    protected $publicAccess = true;
    protected $appId;
    protected $backUrl;
    protected $userBrowserId;
    protected $userApplication;
    protected $userAccess;
    protected $userAccount;

    private function authError ($message)
    {
        //$this->flash->clear();
        $this->flash->error($message);

        $querys = $this->getQueryString();

        //$redirectUri = $this->router->getRewriteUri();
        $redirectUri = $this->config->application->baseUrl . APP_URL_REQUEST;
        if ($querys) $redirectUri .= '?'.$querys;

        $this->redirectTo($redirectUri);
    }

    public function resendcodeAction()
    {
        $this->view->disable();

        if ($requestParams = $this->session->get('recovery.params'))
        {
            if ($account = Account::findByUID($requestParams['accountUID']))
            {
                if ($this->request->isPost())
                {
                    $application = Application::findByAppId($requestParams['appId']);

                    if (empty($access = Access::findAccess($application->id, $account->id)))
                        $this->authError("Akun tidak memiliki akses aplikasi");

                    $email = $account->email?:$requestParams['email'];

                    if ($email)
                    {
                        // jika sudah ada, recreate
                        if ($recovery = AccountRecovery::findByAccountId($account->id))
                        {
                            $recovery->status = 0;
                        }
                        // jika belum, buat
                        else
                        {
                            $recovery = new AccountRecovery;
                        }

                        $recovery->token = Utils::randomString();
                        $recovery->account_id = $account->id;
                        $recovery->access_id = $access->id;
                        $recovery->expired = date("Y-m-d H:i:s", strtotime('+1 day'));
                        $recovery->created = Utils::now();

                        if ($recovery->save())
                        {
                            $this->notification->emailTemplate(
                                $account->email,
                                "Kode Verifikasi Pemulihan Password",
                                "akunrecovery",
                                [
                                    'account'       => $account,
                                    'recovery'      => $recovery,
                                ]
                            );
                            $this->flash->success("Kode verifikasi telah dikirim");
                            return $this->redirectTo('recovery/confirm/'.$requestParams['session']);

                        }
                        else  $message = 'Permintaan pemulihan password tidak berhasil';
                    }
                    else  $message = 'Akun tidak memiliki alamat email / belum diverifikasi';

                    $this->flash->error($message);
                    $this->redirectTo('recovery/confirm/'.$requestParams ['session']);
                }
            }
        }

        $this->redirectLogin();
    }

    public function forgotAction ()
    {
        $this->appId = $this->request->get('appId');
        $this->userBrowserId = $this->request->get('bId');
        $this->backUrl = $this->request->get('backUrl');

        if ($this->appId) $this->userApplication = Application::findByAppId($this->appId);

        if (empty($this->userApplication))
        {
            $this->userApplication = $this->application;
            $this->appId = $this->userApplication->appid;
            $this->backUrl = '';
            $this->userBrowserId = $this->browserId;
        }

        if ($this->request->isPost())
        {
            if ( !$this->security->checkToken($this->security->getTokenKey(), $this->security->getSessionToken (), false) )
            {
                $this->authError ("Unauthorized Request");
            }

            $username = $this->request->getPost('username', null, "");

            $emailAccount = "";

            $account = Account::findByUsername($username);

            // account found, but has no verified email
            if ($account)
            {
                if (empty($account->email))
                {
                    if ($contact = AccountContact::getEmail($account->id))
                    {
                        $emailAccount = $contact->value;
                    }
                }
                else $emailAccount = $account->email;
            }

            // account not found, check unverified email if any
            else
            {
                if ($contact = AccountContact::findByEmail($username))
                {
                    if (empty($account))
                        $account = Account::findById($contact->account_id);

                    $emailAccount = $contact->value;
                }
            }

            if ($account)
            {
                if (empty($access = Access::findAccess($this->userApplication->id, $account->id)))
                    $this->authError("Akun tidak memiliki akses aplikasi");

                if ($emailAccount)
                {
                    // jika sudah ada, recreate
                    if ($recovery = AccountRecovery::findByAccountId($account->id))
                    {
                        $recovery->status = 0;
                    }
                    // jika belum, buat
                    else
                    {
                        $recovery = new AccountRecovery;
                    }

                    $recovery->token = Utils::randomString();
                    $recovery->account_id = $account->id;
                    $recovery->access_id = $access->id;
                    $recovery->expired = date("Y-m-d H:i:s", strtotime('+1 day'));
                    $recovery->created = Utils::now();

                    if ($recovery->save())
                    {
                        $session = Utils::randomString(32);
                        $this->session->set('recovery.params', [
                            'accountUID'    => $account->uid,
                            'appId'         => $this->appId,
                            'backUrl'       => $this->backUrl,
                            'bId'           => $this->userBrowserId,
                            'email'         => $emailAccount,
                            'session'       => $session,
                        ]);

                        $this->notification->emailTemplate(
                            $account->email,
                            "Kode Verifikasi Pemulihan Password",
                            "akunrecovery",
                            [
                                'account'       => $account,
                                'recovery'      => $recovery,
                            ]
                        );
                        return $this->redirectTo('recovery/confirm/'.$session);

                    }
                    else  $message = 'Permintaan pemulihan password tidak berhasil';
                }
                else  $message = 'Akun tidak memiliki alamat email';
            }
            else $message = 'Akun tidak dikenal';

            $this->authError ($message);
        }

        $this->session->set('recovery.params', []);

        $this->view->application = $this->userApplication;
        $this->view->appId = $this->appId;
        $this->view->backUrl = $this->backUrl;
        $this->view->browserId = $this->userBrowserId;
        $this->view->pageTitle = 'Lupa Password';

        $this->view->query_params = $this->getQueryString();

    }

    public function confirmAction ($session = null)
    {
        if ($requestParams = $this->session->get('recovery.params'))
        {
            if (!empty($session) && $requestParams['session'] == $session)
            {
                if ($account = Account::findByUID($requestParams['accountUID']))
                {
                    $this->appId = $requestParams['appId'];
                    $this->backUrl = $requestParams['backUrl'];
                    $this->userBrowserId = $requestParams['bId'];

                    $this->userApplication = $this->appId ? Application::findByAppId($this->appId) : $this->application;;

                    if ($this->request->isPost())
                    {
                        if ( !$this->security->checkToken($this->security->getTokenKey(), $this->security->getSessionToken (), false) )
                        {
                            $this->verifyError ("Unauthorized Request");
                        }

                        $kode = $this->request->getPost('kode');

                        if ($recovery = AccountRecovery::findByAccountId($account->id))
                        {
                            if ($recovery->token == $kode && $recovery->status == 0)
                            {
                                $recovery->status = 1;
                                $recovery->save();

                                if (empty($account->email) && ($contact = AccountContact::getEmail($account->id)))
                                {
                                    $account->email = $contact->value;
                                    if ($account->save()) $contact->delete();
                                }

                                $requestParams['confirm'] = Utils::randomString(32);
                                $this->session->set('recovery.params', $requestParams);

                                return $this->redirectTo('recovery/password/'.$requestParams['confirm']);
                            }
                            else $message = 'Kode Verifikasi salah';
                        }
                        else $message = 'Permintaan tidak ditemukan'.$account->id;

                        return $this->authError($message);
                    }

                    $account->email = $account->email?:$requestParams['email'];

                    $this->view->application = $this->userApplication;
                    $this->view->requestParams = $requestParams;
                    $this->view->account = $account;
                    $this->view->pageTitle = 'Verifikasi Kode';

                    $this->view->query_params = http_build_query([
                        'appId'     => $this->appId,
                        'backUrl'   => $this->backUrl,
                        'bId'       => $this->userBrowserId,
                        'r'         => Utils::randomString(32)
                    ]);

                    return $this->view->finish();

                }
            }
        }

        return $this->redirectLogin();
    }

    public function passwordAction ($session = null)
    {
        if ($requestParams = $this->session->get('recovery.params'))
        {
            if (!empty($session) && $requestParams['confirm'] == $session)
            {
                if ($account = Account::findByUID($requestParams['accountUID']))
                {
                    $this->appId = $requestParams['appId'];
                    $this->backUrl = $requestParams['backUrl'];
                    $this->userBrowserId = $requestParams['bId'];

                    $this->userApplication = $this->appId ? Application::findByAppId($this->appId) : $this->application;;

                    if ($this->request->isPost())
                    {
                        if ( !$this->security->checkToken($this->security->getTokenKey(), $this->security->getSessionToken (), false) )
                        {
                            $this->verifyError ("Unauthorized Request");
                        }

                        $password = $this->request->getPost('password');
                        $passwordagain = $this->request->getPost('again');

                        if ($password != $passwordagain)
                            return $this->authError("Password tidak sama.Silakan ulangi.");

                        $account->password = md5($password);
                        if ($account->save())
                        {
                            $requestParams['success'] = Utils::randomString(32);
                            $this->session->set('recovery.params', $requestParams);

                            return $this->redirectTo('recovery/success/'.$requestParams['success']);
                        }

                        return $this->authError("Ubah Password tidak berhasil.");
                    }

                    $this->view->application = $this->userApplication;
                    $this->view->requestParams = $requestParams;
                    $this->view->account = $account;
                    $this->view->pageTitle = 'Pemulihan Password';

                    $this->view->query_params = http_build_query([
                        'appId'     => $this->appId,
                        'backUrl'   => $this->backUrl,
                        'bId'       => $this->userBrowserId,
                        'r'         => Utils::randomString(32)
                    ]);

                    return $this->view->finish();

                }
            }
        }


        return $this->redirectLogin();
    }

    public function successAction ($session = null)
    {
        if ($requestParams = $this->session->get('recovery.params'))
        {
            if (!empty($session) && $requestParams['success'] == $session)
            {
                if ($ccount = Account::findByUID($requestParams['accountUID']))
                {
                    $this->appId = $requestParams['appId'];
                    $this->backUrl = $requestParams['backUrl'];
                    $this->userBrowserId = $requestParams['bId'];

                    $this->userApplication = $this->appId ? Application::findByAppId($this->appId) : $this->application;;

                    $this->view->application = $this->userApplication;
                    $this->view->requestParams = $requestParams;
                    $this->view->account = $ccount;
                    $this->view->pageTitle = 'Pemulihan Berhasil';

                    $this->view->query_params = http_build_query([
                        'appId'     => $this->appId,
                        'backUrl'   => $this->backUrl,
                        'bId'       => $this->userBrowserId,
                        'r'         => Utils::randomString(32)
                    ]);

                    return $this->view->finish();
                }
            }
        }

        return $this->redirectLogin();
    }
}
