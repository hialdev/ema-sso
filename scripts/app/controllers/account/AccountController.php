<?php

class AccountController extends BaseAppController
{
    protected $pageTitle = "My Account";

    public function indexAction()
    {
        $this->view->list_otp = Utils::getOptionList(Account::$otpText);
        $this->view->pick('pages/account');
    }

    public function getoneAjax ()
    {
        $dataaccount = $this->account->normalizeToArray();
        $dataaccount['email_unverified'] = '';
        $dataaccount['phone_unverified'] = '';

        if ($emailContact = AccountContact::getEmail ($this->account->id))
        {
            if ($emailContact->status == 0)
            {
                $dataaccount['email_unverified'] = $emailContact->value;
            }
        }

        if ($phoneContact = AccountContact::getPhone ($this->account->id))
        {
            if ($phoneContact->status == 0)
            {
                $dataaccount['phone_unverified'] = $phoneContact->value;
            }
        }
        return $this->responseSuccess($dataaccount);
    }

    public function updateAjax ()
    {
        $field = $this->request->getPost('field', null, "");
        $value = $this->request->getPost('value');
        $this->account->$field = $value;

        if ($field == 'otp_media')
        {
            $this->account->use_otp = $value != 'none' ? 1 : 0;
        }

        if ($this->account->save())
        {

            return $this->responseSuccess($this->account->normalizeToArray());
        }

        return $this->responseError("Data gagal disimpan");
    }

    public function verifikasiAjax ()
    {
        $type = $this->request->getPost('type', null, "");
        $token = $this->request->getPost('token', null, "");

        if ($contact = AccountContact::getContact($this->account->id, $type))
        {
            if ($token == $contact->token)
            {
                if ($type == AccountContact::CONTACT_EMAIL)
                {
                    $this->account->email = $contact->value;
                }
                else if ($type == AccountContact::CONTACT_PHONE)
                {
                    if ($telegramData = json_decode($contact->data))
                        $this->account->telegram_id = $telegramData->print_name;

                    $this->account->phone = $contact->value;
                }

                if ($this->account->save())
                {
                    $contact->delete();
                    return $this->responseSuccess($this->account);
                }
            }

            return $this->responseError("Kode Verifikasi tidak benar");
        }

        return $this->responseError("Verifikasi tidak berhasil");
    }

    public function resendverifikasiAjax ()
    {
        $type = $this->request->getPost('type', null, "");

        if ($contact = AccountContact::getContact($this->account->id, $type))
        {
            if ($type == AccountContact::CONTACT_EMAIL)
            {
                $this->notification->emailTemplate(
                    $contact->value,
                    "Kode Verifikasi Alamat Email",
                    "tokenemail",
                    [
                        'account'       => $this->account,
                        'contact'       => $contact,
                        'email'         => $contact->value,
                        'token'         => $contact->token,
                    ]
                );

                return $this->responseSuccess();
            }
            else if ($type == AccountContact::CONTACT_PHONE)
            {
                list($firstName, $lastName) = explode(" ", $this->account->name, 2);

                $message = "Kode Verifikasi Nomor Telepon AKun Elang Merah : ".$contact->token;

                $this->notification->telegramAfterAdd(
                    $contact->value, $firstName, $lastName, $message
                );

                $this->notification->whatsapp(
                    $contact->value, $message
                );

                return $this->responseSuccess();
            }


        }

        return $this->responseError("Pengiriman kode verifikasi tidak berhasil");
    }

    public function updatecontactAjax ()
    {
        $type = $this->request->getPost('type', null, "");
        $value = $this->request->getPost('value', null, "");

        if ($type == AccountContact::CONTACT_EMAIL)
        {
            if (!Account::isEmailAvailable($value, $this->account->id) || !AccountContact::isEmailAvailable($value, $this->account->id))
                return $this->responseError('Email sudah digunakan');
        }
        else if ($type == AccountContact::CONTACT_PHONE)
        {
            $value = SMSHelper::normalizeMsisdn($value);
            if (!Account::isPhoneAvailable($value, $this->account->id) || !AccountContact::isPhoneAvailable($value, $this->account->id))
                return $this->responseError('Nomor HP sudah digunakan');
        }

        if ($contact = AccountContact::getContact($this->account->id, $type))
        {
            $contact->status = 0;
            $contact->data = null;
        }
        else
        {
            $contact = new AccountContact;
            $contact->account_id = $this->account->id;
            $contact->type = $type;
        }

        $contact->value = $value;
        $contact->token = Utils::randomNumber();
        $contact->created = Utils::now();

        if ($contact->save())
        {
            if ($type == AccountContact::CONTACT_EMAIL)
            {
                $this->notification->emailTemplate(
                    $contact->value,
                    "Kode Verifikasi Alamat Email",
                    "tokenemail",
                    [
                        'account'       => $this->account,
                        'contact'       => $contact,
                        'email'         => $contact->value,
                        'token'         => $contact->token,
                    ]
                );
            }
            else if ($type == AccountContact::CONTACT_PHONE)
            {
                list($firstName, $lastName) = explode(" ", $this->account->name, 2);

                $message = "Kode Verifikasi Nomor Telepon AKun Elang Merah : ".$contact->token;

                $this->notification->telegramAfterAdd(
                    $contact->value, $firstName, $lastName, $message
                );

                $this->notification->whatsapp(
                    $contact->value, $message
                );

            }

            return $this->responseSuccess($contact);
        }

        return $this->responseError("Data gagal disimpan");
    }

    public function removecontactAjax ()
    {
        $type = $this->request->getPost('type', null, "");

        if ($contact = AccountContact::getContact($this->account->id, $type))
        {
            if ($contact->delete())
                return $this->responseSuccess();
        }

        return $this->responseError("Data gagal dihapus");
    }

    public function passwordAjax()
    {
        $oldpassword = $this->request->getPost('oldpassword');
        $password = $this->request->getPost('password');

        if ($account = Account::findById($this->account->id))
        {
            /* if (!password_verify($oldpassword, $account->password))
                return $this->responseError("Password lama tidak benar");

            $account->password = password_hash($password, PASSWORD_DEFAULT); */

            if ( strcasecmp($account->password, md5($oldpassword)) != 0 )
                return $this->responseError("Password lama tidak benar");

            $account->password = md5($password);

            if ($account->save())
                return $this->responseSuccess();

            $this->logDebug('DB ERROR: '.$account->getErrorMessage());
            return $this->responseError("Password Akun tidak berhasil diubah");
        }

        return $this->responseError("Akun tidak ditemukan");
    }

    public function oauthAjax()
    {
        $secret = $this->account->getSecret();
        $OAuth = new OAuth($secret);
        $uri = $OAuth->provisioning_uri($this->account->username, $this->config->cookie->sso->domain);

        $data = [
            'uri'       => "http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=".urlencode($uri),
            'secret'    => $secret
        ];
        return $this->responseSuccess($data);
    }

    public function oauthVerifyAjax()
    {
        $code = $this->request->getPost('token', null, "");

        $secret = $this->account->getSecret();
        $OAuth = new OAuth($secret);

        if ($OAuth->verify($code))
        {
            $this->account->secret = $secret;
            $this->account->otp_media = Account::OTP_OAUTH;
            $this->account->use_otp = 1;
            $this->account->save();
            return $this->responseSuccess();
        }

        return $this->responseError("Kode Verifikasi tidak valid.");
    }


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

    public function uploadImageAjax ()
    {
        $this->view->disable();

        if ($this->request->hasFiles() == true)
        {
            $files = $this->request->getUploadedFiles();
            $file = array_shift($files);

            $field = $this->request->getPost('field');
            $name = $file->getName();
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $filename = sprintf("account/%s_%s.%s", $field, $this->account->uid, $ext );

            if ($this->saveUploadedFile($file, $filename))
            {
                $this->account->$field = $filename;

                if ($this->account->save())
                    return $this->responseSuccess($this->account->toArray());

                $this->deleteFile($filename);
            }

            return $this->responseError("Upload tidak berhasil");
        }

        return $this->responseError("File Image tidak ditemukan");
    }

    public function removeImageAjax ()
    {
        $this->view->disable();

        $field = $this->request->getPost('field');

        $filePath = $this->account->$field;
        $this->account->$field = null;

        if ($this->account->save())
        {
            $this->deleteFile($filePath);
            return $this->responseSuccess($this->account->toArray());
        }
        return $this->responseError("Data Image tidak berhasil dihapus");
    }

}