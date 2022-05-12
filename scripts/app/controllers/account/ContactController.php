<?php

class ContactController extends AuthController
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
        $this->redirectTo('auth/login');
    }

    public function exitAction()
    {
        $this->redirectTo('auth/login');
    }

    public function logoutAction()
    {
        $this->redirectTo('auth/login');
    }

    public function loginAction()
    {
        $this->redirectTo('auth/login');
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
                    $type = $this->request->getPost('type', null, "");

                    if ($contact = AccountContact::getContact($this->userAccount->id, $type))
                    {
                        if ($type == AccountContact::CONTACT_EMAIL)
                        {
                            $this->notification->emailTemplate(
                                $contact->value,
                                "Kode Verifikasi Alamat Email Akun Elang Merah",
                                "tokenemail",
                                [
                                    'account'       => $this->userAccount,
                                    'contact'       => $contact,
                                    'email'         => $contact->value,
                                    'token'         => $contact->token,
                                ]
                            );

                            $this->flash->success('Kode Verifikasi sedang dikirim ulang');
                        }
                        else if ($type == AccountContact::CONTACT_PHONE)
                        {
                            list($firstName, $lastName) = explode(" ", $this->userAccount->name, 2);

                            $message = "Kode Verifikasi Nomor Telepon AKun Elang Merah : ".$contact->token;

                            $this->notification->telegramAfterAdd(
                                $contact->value, $firstName, $lastName, $message
                            );

                            $this->notification->whatsapp(
                                $this->userAccount->phone, $message
                            );


                            $this->flash->success('Kode Verifikasi sedang dikirim ulang');
                        }
                    }

                }
            }
        }
        return $this->redirectTo('contact/verify');
    }

    public function verifyAction()
    {
        if ($loginParams = $this->session->get('login.params'))
        {
            if ($this->userAccount = Account::findByUID($loginParams['accountUID']))
            {
                if ($this->userAccount->isOTPvalid())
                    return $this->redirectLogin();

                $this->appId = $loginParams['appId'];
                $this->backUrl = $loginParams['backUrl'];
                $this->userBrowserId = $loginParams['bId'];

                $this->userApplication = $this->appId ? Application::findByAppId($this->appId) : $this->application;
                $this->userAccess = Access::findAccess($this->userApplication->id, $this->userAccount->id);

                if ($this->request->isPost())
                {
                    if ( !$this->security->checkToken($this->security->getTokenKey(), $this->security->getSessionToken (), false) )
                    {
                        $this->verifyError ("Unauthorized Request");
                    }

                    $token = $this->request->getPost('token');
                    $type = $this->request->getPost('type');

                    if ($contact = AccountContact::getContact($this->userAccount->id, $type))
                    {
                        if ($contact->token == $token)
                        {
                            if ($type == AccountContact::CONTACT_EMAIL)
                            {
                                $this->userAccount->email = $contact->value;
                            }
                            else if ($type == AccountContact::CONTACT_PHONE)
                            {
                                if ($telegramData = json_decode($contact->data))
                                    $this->account->telegram_id = $telegramData->print_name;

                                $this->userAccount->phone = $contact->value;
                            }

                            if ($this->userAccount->save())
                            {
                                $contact->delete();

                                $this->sendOTP(true);

                                return $this->redirectTo('auth/verify');
                            }

                        }
                    }

                    return $this->verifyError('Kode Verifikasi salah');
                }

                if ($this->userAccount->otp_media == Account::OTP_EMAIL)
                {
                    $view = 'contact/verify_email';
                    $contact = AccountContact::getEmail($this->userAccount->id);
                }
                else
                {
                    $view = 'contact/verify_phone';
                    $contact = AccountContact::getPhone($this->userAccount->id);
                }

                $this->view->loginParams = $loginParams;
                $this->view->account = $this->userAccount;
                $this->view->unverified = $contact->value;
                $this->view->is_login = $this->isAccountLogged();
                $this->view->login_url = 'auth/login?'.http_build_query([
                    'appId'     => $this->appId,
                    'backUrl'   => $this->backUrl,
                    'bId'       => $this->userBrowserId,
                    'r'         => Utils::randomString(32)
                ]);

                $this->view->pick($view);

                return $this->view->finish();
            }
        }

        return $this->redirectLogin();
    }

}
