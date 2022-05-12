<?php

class AuthController extends BaseAppController
{
    protected $publicAccess = true;
    protected $appId;
    protected $backUrl;
    protected $userBrowserId;
    protected $userApplication;
    protected $userAccess;
    protected $userAccount;

    public function nologinAction()
    {
        if ($this->isAccountLogged())
            return $this->redirectHome();

        $this->view->pageTitle = 'Sesi Login Berakhir';
    }

    public function exitAction()
    {
        $this->view->disable();
        $this->destroyLoggedAccount();
        return $this->redirectLogin();
    }

    private function authError ($message)
    {
        //$this->flash->clear();
        $this->flash->error($message);
        $this->redirectLogin($this->getQueryString());
    }

    protected function verifyError ($message)
    {
        //$this->flash->clear();
        $this->flash->error($message);
        $this->redirectTo('auth/verify');
    }

    public function loginAction()
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

        if ($this->isAccountLogged())
        {
            if ($this->userAccount = $this->getLoggedAccount())
            {
                if ($this->userApplication->id == $this->application->id)
                {
                    $ref = $this->request->get("ref");
                    return $ref ?
                        $this->redirectTo($ref):
                        $this->redirectHome();
                }

                $this->userAccess = Access::findAccess($this->userApplication->id, $this->userAccount->id);
                return $this->onLogin();
            }
        }

        if ($this->request->isPost())
        {
            if ( !$this->security->checkToken($this->security->getTokenKey(), $this->security->getSessionToken (), false) )
            {
                $this->authError ("Unauthorized Request");
            }

            $username   = $this->request->getPost("username", array('trim', 'string'), false);
            $password   = $this->request->getPost('password', array('trim', 'string'), false);

            if( !$username || !$password )
                $this->authError("Silakan masukkan username dan/atau password");

            if (empty($this->userAccount = Account::findByUsername($username)))
                $this->authError('Akun tidak dikenal');

            $this->userAccess = Access::findAccess($this->userApplication->id, $this->userAccount->id);
            // access should validated on designated application
            /* if (empty($this->userAccess = Access::findAccess($this->userApplication->id, $this->userAccount->id)))
                $this->authError("Akun tidak memiliki akses aplikasi"); */

            /* if (empty($accrole = AccountRole::findByAccountRoleSlug($this->userAccount->id, Role::ROLE_STAFF)))
                $this->authError("Akun tidak memiliki akses aplikasi"); */

            if ($this->userAccount->status != Account::ACCOUNT_ACTIVE)
                $this->authError("Akun tidak aktif");

            if ( strcasecmp($this->userAccount->password, md5($password)) == 0 )
            {
                return $this->onLogin ();
            }
            else
                $this->authError("Username dan/atau password tidak benar");

        }

        $this->session->set('login.params', []);
        $this->session->set('recovery.params', []);

        $this->view->application = $this->userApplication;
        $this->view->appId = $this->appId;
        $this->view->backUrl = $this->backUrl;
        $this->view->browserId = $this->userBrowserId;
        $this->view->pageTitle = 'Log In';
        $this->view->appTitle = $this->userApplication->name;

        $this->view->query_params = $this->getQueryString();
    }

    private function onLogin ()
    {
        // OTP akan di gunakan kalau :
        // - Akun user dan Aplikasi menggunakan otp

        if ($this->userApplication->use_otp == 1)
        {
            if ($this->userAccount->use_otp == 1)
            {
                $this->session->set('login.params', [
                    'accountUID'    => $this->userAccount->uid,
                    'appId'         => $this->appId,
                    'backUrl'       => $this->backUrl,
                    'bId'           => $this->userBrowserId
                ]);

                if ($this->userAccount->isOTPvalid())
                {
                    if ($this->userAccount->otp_media != Account::OTP_OAUTH)
                        $this->sendOTP();

                    return $this->redirectTo('auth/verify');
                }
                else
                {
                    if ($this->userAccount->isOTPUnverified ())
                        return $this->redirectTo('contact/verify');
                }

            }
        }

        return $this->onAfterLogin();
    }

    private function onAfterLogin ()
    {
        if (!$this->isAccountLogged())
            $this->setAccountLogged($this->userAccount->uid);

        $accessToken = Utils::randomString(64);
        if ($this->userAccess)
        {
            $userAgent = UserAgent::parse();
            $this->logDebug(json_encode($userAgent));

            if ($accessDevice = AccessDevice::findByDevice($this->userBrowserId, $this->userAccess->id))
            {
                $accessDevice->login_status = 1;
            }
            else
            {
                $accessDevice = new AccessDevice;
                $accessDevice->access_id = $this->userAccess->id;
                $accessDevice->device_id = $this->userBrowserId;

                $accessDevice->created = Utils::now();
                $accessDevice->last_login = Utils::now();
                $accessDevice->ip_address = $this->request->getClientAddress();
            }

            $accessDevice->device_ua = $this->request->getUserAgent();
            $accessDevice->device_os = Utils::getArrayValue($userAgent, 'platform');
            $accessDevice->device_name = Utils::getArrayValue($userAgent, 'browser').' '.Utils::getArrayValue($userAgent, 'version');
            $accessDevice->access_token = $accessToken;
            $accessDevice->login_status = 1;
            $accessDevice->save();
        }

        $this->session->set('login.params', []);

        if ($this->userApplication->id != $this->application->id)
        {
            $backUrl = $this->userApplication->url;

            if ($this->backUrl)
            {
                if (filter_var($this->backUrl, FILTER_VALIDATE_URL) !== FALSE)
                {
                    $backUrl = $this->backUrl;
                }
            }
            $redirectUrl = $backUrl;
            $redirectUrl .= strpos($backUrl, '?') !== FALSE ? '&' : '?';
            $redirectUrl .= http_build_query([
                'at'    => $accessToken,
                'div'   => base64_encode(uniqid()),
                'id'    => Utils::randomString(32),
                'tm'    => time(),
                'url'   => $backUrl
            ]);

            return $this->redirectTo($redirectUrl);
        }
        else
        {
            $this->setProfile();
        }

        $ref = $this->request->get("ref");
        return $ref ?
            $this->redirectTo($ref):
            $this->redirectHome();
    }

    public function verifyAction()
    {
        if ($loginParams = $this->session->get('login.params'))
        {
            if ($this->userAccount = Account::findByUID($loginParams['accountUID']))
            {
                $this->appId = $loginParams['appId'];
                $this->backUrl = $loginParams['backUrl'];
                $this->userBrowserId = $loginParams['bId'];

                $this->userApplication = $this->appId ? Application::findByAppId($this->appId) : $this->application;;

                if ($this->request->isPost())
                {
                    if ( !$this->security->checkToken($this->security->getTokenKey(), $this->security->getSessionToken (), false) )
                    {
                        $this->verifyError ("Unauthorized Request");
                    }

                    $otpCode = $this->request->getPost('otp_code');

                    $this->userAccess = Access::findAccess($this->userApplication->id, $this->userAccount->id);

                    if ($this->userAccount->otp_media != Account::OTP_OAUTH)
                    {
                        if ($accountOTP = AccountOTP::findOTP($this->userAccess->id, $otpCode))
                        {
                            $accountOTP->closeOTP();
                            return $this->onAfterLogin ();
                        }
                    }
                    else
                    {
                        $OAuth = new OAuth($this->userAccount->getSecret());
                        if ($OAuth->verify($otpCode))
                        {
                            return $this->onAfterLogin ();
                        }
                    }

                    return $this->verifyError('Kode Verifikasi salah');
                }

                $this->view->application = $this->userApplication;
                $this->view->loginParams = $loginParams;
                $this->view->account = $this->userAccount;
                $this->view->is_login = $this->isAccountLogged();
                $this->view->login_url = 'auth/login?'.http_build_query([
                    'appId'     => $this->appId,
                    'backUrl'   => $this->backUrl,
                    'bId'       => $this->userBrowserId,
                    'r'         => Utils::randomString(32)
                ]);
                $this->view->otp_to = $this->userAccount->getOTPInfo();

                return $this->view->finish();
            }
        }

        return $this->redirectLogin();
    }

    protected function sendOTP ($resend = false)
    {
        if (empty($this->userAccess))
            return false;

        if ($accountOTP = AccountOTP::findByAccess($this->userAccess->id))
        {
            if (!$resend)
                $accountOTP->counter = $accountOTP->counter+1;
        }
        else
        {
            $accountOTP = new AccountOTP;
            $accountOTP->account_id = $this->userAccount->id;
            $accountOTP->access_id = $this->userAccess->id;
            $accountOTP->created = Utils::now();
        }

        $otpCode = Utils::randomNumber(4);
        $accountOTP->status = 0;
        $accountOTP->otp_code = $otpCode;
        $accountOTP->expired = date("Y-m-d H:i:s", strtotime('+1 day'));

        $this->logDebug("set OTP");

        if ($this->userAccount->otp_media == Account::OTP_EMAIL)
        {
            if ($this->userAccount->email)
            {
                $this->logDebug("Send OTP via Email");
                $this->notification->emailTemplate(
                    $this->userAccount->email,
                    "Kode Login ".$this->userApplication->name,
                    "otplogin",
                    [
                        'account'       => $this->userAccount,
                        'otpCode'       => $otpCode,
                    ]
                );

                if ($accountOTP->save())
                    return true;

                $this->logDebug($accountOTP->getErrorMessage());
            }
        }
        else if ($this->userAccount->otp_media == Account::OTP_TELEGRAM)
        {
            $telegramAllow = false;
            $this->logDebug("Send OTP via Telegram");

            $message = "Kode Login ".$this->userApplication->name." :\n".$otpCode;

            if ($this->userAccount->telegram_id)
            {
                $this->notification->telegram(
                    $this->userAccount->telegram_id, $message
                );
                $telegramAllow = true;
            }
            else if ($this->userAccount->phone)
            {
                list($firstName, $lastName) = explode(" ", $this->userAccount->name, 2);

                $this->notification->telegramAfterAdd(
                    $this->userAccount->phone, $firstName, $lastName, $message
                );

                $telegramAllow = true;
            }

            if ($telegramAllow)
            {
                $this->notification->whatsapp(
                    $this->userAccount->phone, $message
                );

                if ($accountOTP->save())
                    return true;

                $this->logDebug($accountOTP->getErrorMessage());
            }
        }

        return false;
    }

    public function resendcodeAction()
    {
        $this->view->disable();

        if ($loginParams = $this->session->get('login.params'))
        {
            if ($this->userAccount = Account::findByUID($loginParams['accountUID']))
            {
                if ($this->request->isPost())
                {
                    if ( !$this->security->checkToken($this->security->getTokenKey(), $this->security->getSessionToken (), false) )
                    {
                        $this->verifyError ("Unauthorized Request");
                    }

                    $this->appId = $loginParams['appId'];
                    $this->backUrl = $loginParams['backUrl'];
                    $this->userBrowserId = $loginParams['bId'];

                    $this->userApplication = $this->appId ? Application::findByAppId($this->appId) : $this->application;;
                    $this->userAccess = Access::findAccess($this->userApplication->id, $this->userAccount->id);

                    $this->sendOTP (true);

                    $this->flash->success('Kode Verifikasi sedang dikirim ulang');
                }
            }
        }

        return $this->redirectTo('auth/verify');
    }
}
