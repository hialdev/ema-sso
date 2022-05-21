<?php

class AccountApi extends BaseApi
{
    private function loginData ($application, $account, $roleSlug = null)
    {
        $roles = [];
        $accessInfo = [];

        if (empty($roleSlug))
        {
            $accountroles = AccountRole::findRoles ($account->id);
            foreach ($accountroles as $accountRole)
            {
                $roles[] = [
                    'id'        => $accountRole->role_id,
                    'slug'      => $accountRole->role_slug,
                    'object_id' => $accountRole->object_id,
                    'status'    => $accountRole->status,
                ];
            }
        }
        else
        {
            if ($accountRole = AccountRole::findByAccountRoleSlug($account->id, $roleSlug))
            {
                $roles[] = [
                    'id'        => $accountRole->role_id,
                    'slug'      => $roleSlug,
                    'object_id' => $accountRole->object_id,
                    'status'    => $accountRole->status,
                ];
            }
        }

        if ($access = Access::findAccess($application->id, $account->id))
        {
            $accessInfo = [
                'id'        => $access->id,
                'status'    => $access->status,
            ];
        }

        return [
            'account'   => $account->normalizeInfo(),
            'access'    => $accessInfo,
            'roles'     => $roles
        ];
    }

    public function auth()
    {
        $username = $this->request->getPost('username', null, "");
        $password = $this->request->getPost('password', null, "");
        $roleSlug = $this->request->getPost('role', null, "");
        $appid = $this->request->getPost('appid', null, "");

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if (empty($account = Account::findByUsername($username)))
            return $this->responseError("Akun tidak dikenal");

        if (strcasecmp($account->password, $password) != 0)
            return $this->responseError("Username atau Password salah");

        if ($account->status != Account::ACCOUNT_ACTIVE)
            return $this->responseError("Akun tidak aktif");

        $result = $this->loginData($application, $account, $roleSlug);

        if (empty($result['roles']))
            return $this->responseError("Akun tidak mempunyai akses e");

        /* $roles = [];
        $accessInfo = [];

        if (empty($roleSlug))
        {
            $accountroles = AccountRole::findRoles ($account->id);
            if ($accountroles->count() == 0)
                return $this->responseError("Akun tidak mempunyai akses");

            foreach ($accountroles as $accountRole)
            {
                $roles[] = [
                    'id'        => $accountRole->role_id,
                    'slug'      => $accountRole->role_slug,
                    'object_id' => $accountRole->object_id,
                    'status'    => $accountRole->status,
                ];
            }
        }
        else
        {
            if (empty($accountRole = AccountRole::findByAccountRoleSlug($account->id, $roleSlug)))
                return $this->responseError("Akun tidak mempunyai akses");

            $roles[] = [
                'id'        => $accountRole->role_id,
                'slug'      => $accountRole->role_slug,
                'object_id' => $accountRole->object_id,
                'status'    => $accountRole->status,
            ];
        }

        if ($access = Access::findAccess($application->id, $account->id))
        {
            $accessInfo = [
                'id'        => $access->id,
                'status'    => $access->status,
            ];
        }

        $result = [
            'account'   => $account->normalizeInfo(),
            'access'    => $accessInfo,
            'roles'     => $roles
        ];

        return $this->responseSuccess($result); */
        return $this->responseSuccess($result);
    }

    public function validate()
    {
        $uid = $this->request->getPost('uid', null, "");

        if (empty($account = Account::findByUID($uid)))
            return $this->responseError("Akun tidak dikenal");

        if ($account->status != Account::ACCOUNT_ACTIVE)
            return $this->responseError("Akun tidak aktif");

        $result = [
            'account'       => $account->normalizeInfo()
        ];

        return $this->responseSuccess($result);
    }

    public function register()
    {
        $random = new \Phalcon\Security\Random();

        $username = $this->request->getPost('username', null, "");
        $email = $this->request->getPost('email', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if (!Account::isUsernameAvailable($username))
            return $this->responseError('Username sudah digunakan');

        if (!Account::isEmailAvailable($email) || !AccountContact::isEmailAvailable($email))
            return $this->responseError('Email sudah digunakan');

        $account = new Account;
        $account->id = Account::generateId();
        $account->name = $this->request->getPost('name', null, "");
        $account->username = $username;
        $account->password = $this->request->getPost('password', null, "");
        $account->gender = $this->request->getPost('gender', null, null);
        $account->dob = $this->request->getPost('dob', null, null);
        $account->google_id = $this->request->getPost('google_id', null, null);

        if ($account->google_id){
            $account->avatar = $this->request->getPost('avatar', null, null);
            $account->email = $email;
            $account->google_account = $email;
        }

        $account->date_joined = Utils::now();
        $account->status = Account::ACCOUNT_ACTIVE;
        if ($account->save())
        {
            // create user access to apps
            $access = new Access;
            $access->account_id = $account->id;
            $access->application_id = $application->id;
            $access->status = 1;
            $access->created = Utils::now();
            if ($access->save())
            {
                $role = Role::findBySlug(Role::ROLE_GENERIC);

                // add account role
                $accountRole = new AccountRole;
                $accountRole->account_id = $account->id;
                $accountRole->role_id = $role->id;
                $accountRole->object_id = null;
                $accountRole->status =1;
                if ($accountRole->save())
                {
                    $contactsaved = true;
                    if (empty($account->google_id))
                    {
                        // add user contact email, to be verified;
                        $contact = new AccountContact;
                        $contact->type = AccountContact::CONTACT_EMAIL;
                        $contact->account_id = $account->id;
                        $contact->value = $email;
                        $contact->token = Utils::randomString();
                        $emailToken = $contact->token;
                        $contact->created = Utils::now();

                        if (!$contact->save())
                            $contactsaved = false;
                    }

                    if ($contactsaved)
                    {
                        $accountroles = AccountRole::findRoles ($account->id);
                        $result = [
                            'account'       => $account->toArray(),
                            //'application'   => $application->toArray(),
                            'access'        => $access->toArray(),
                            'roles'         => $accountroles->toArray(),
                            'children'      => [],
                            'quran_bookmark'=> [],
                            'family'        => [],
                            'member'        => []
                        ];

                        Notification::addNotification (
                            $account->id,
                            "Selamat bergabung, Registrasi akun berhasil",

                            "Selamat datang di aplikasi Sahara.\n".
                            ($account->google_id ? '' : "Silakan cek email anda, untuk verifikasi alamat email anda.")
                        );

                        $this->notification->emailTemplate(
                            $email,
                            "Selamat datang di Sahara",
                            "akunbaru",
                            [
                                'account'       => $account,
                                'email'         => $email,
                            ]
                        );

                        if (empty($account->google_id))
                        {
                            $this->notification->emailTemplate(
                                $email,
                                "Kode Verifikasi Alamat Email",
                                "tokenemail",
                                [
                                    'account'       => $account,
                                    'contact'       => $contact,
                                    'email'         => $email,
                                    'token'         => $emailToken,
                                ]
                            );
                        }

                        return $this->responseSuccess($result);
                    }

                    //rollback
                    $accountRole->deleteRecordBy(["account_id='".$account->id."'"]);

                }
                //rollback
                $access->delete();
            }

            //rollback
            $account->delete();
        }

        return $this->responseError('Registrasi akun tidak berhasil');
    }

    public function password()
    {
        $old_password = $this->request->getPost('old_password', null, "");
        $password = $this->request->getPost('password', null, "");
        $mode = $this->request->getPost('mode', null, 1);
        $uid = $this->request->getPost('uid', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            if ($mode == 1 && strcasecmp($account->password, $old_password) != 0)
                return $this->responseError("Password lama salah");

            $account->password = $password;
            if ($account->save())
                return $this->responseSuccess();

            else {
                $this->logError($account->getErrorMessage());
                $message = 'Ubah password tidak berhasil ';
            }
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function recovery()
    {
        $username = $this->request->getPost('username', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");
        if ($account = Account::findByUsername($username))
        {

            if (empty($access = Access::findAccess($application->id, $account->id)))
                return $this->responseError("Akun tidak memiliki akses aplikasi");

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
                if ($account->email)
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
                }

                return $this->responseSuccess([
                    'account' => $account
                ]);
            }
            else  $message = 'Permintaan pemulihan password tidak berhasil'. implode('', $recovery->getMessages());
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function confirmrecovery()
    {
        $uid = $this->request->getPost('uid', null, "");
        $token = $this->request->getPost('token', null, "");

        if ($account = Account::findById($uid))
        {
            if ($recovery = AccountRecovery::findByAccountId($account->id))
            {
                if ($recovery->token == $token && $recovery->status == 0)
                {
                    $recovery->status = 1;
                    $recovery->save();

                    return $this->responseSuccess([
                        'account' => $account
                    ]);
                }
                else $message = 'Kode Verifikasi tidak ditemukan';
            }
            else $message = 'Permintaan tidak ditemukan';
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function update()
    {
        $uid = $this->request->getPost('uid', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            $account->name = $this->request->getPost('name', null, "");
            $account->gender = $this->request->getPost('gender', null, "");
            $account->address = $this->request->getPost('address', null, "");
            $account->dob = $this->request->getPost('dob', null, "");

            if ($account->save())
                return $this->responseSuccess($account->normalizeToArray());

            else $message = 'Update akun tidak berhasil '. implode('', $account->getMessages());
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function badges ()
    {
        $uid = $this->request->getPost('uid', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            $inboxCount = Notification::count("account_id='".$account->id."' AND type='user' AND read=0");
            $messageCount = Notification::count("account_id='".$account->id."' AND type='system' AND read=0");

            return $this->responseSuccess([
                'inbox'     => $inboxCount,
                'message'   => $messageCount
            ]);
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function info()
    {
        $uid = $this->request->getPost('uid', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            $email = AccountContact::getEmail($account->id);

            return $this->responseSuccess([
                'account'       => $account->normalizeToArray(),
                'unverified'    => [
                    'email' => $email ? $email->value : "",
                ]
            ]);
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function updatecontact()
    {
        $type = $this->request->getPost('type', null, "");
        $value = $this->request->getPost('value', null, "");
        $uid = $this->request->getPost('uid', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            if ($type == AccountContact::CONTACT_EMAIL)
            {
                if (!Account::isEmailAvailable($value, $account->id) || !AccountContact::isEmailAvailable($value, $account->id))
                  return $this->responseError('Email sudah digunakan');
            }
            else
            {
                if (!Account::isPhoneAvailable($value, $account->id) || !AccountContact::isPhoneAvailable($value, $account->id))
                    return $this->responseError('Nomor HP sudah digunakan');
            }

            if ($contact = AccountContact::getContact($account->id, $type))
            {
                $contact->status = 0;
            }
            else
            {
                $contact = new AccountContact;
                $contact->account_id = $account->id;
                $contact->type = $type;
            }

            $contact->value = $value;
            $contact->token = Utils::randomString();
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
                            'account'       => $account,
                            'contact'       => $contact,
                            'email'         => $contact->value,
                            'token'         => $contact->token,
                ]
                    );
                }
                return $this->responseSuccess();
            }
            else  $message = 'Update kontak tidak berhasil'. implode('', $contact->getMessages());
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function removecontact()
    {
        $uid = $this->request->getPost('uid', null, "");
        $type = $this->request->getPost('type', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            if ($contact = AccountContact::getContact($account->id, $type))
            {
                if ($contact->delete())
                {
                    $email = AccountContact::getEmail($account->id);
                    $phone = AccountContact::getPhone($account->id);

                    return $this->responseSuccess([
                        'account'       => $account->normalizeToArray(),
                        'unverified'    => [
                            'email' => $email ? $email->value : "",
                            'phone' => $phone ? $phone->value : ""
                        ]
                    ]);
                }
                else $message = 'Hapus kontak '.$type.' tidak berhasil';
            }
            else $message = 'Permintaan tidak ditemukan';
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function verifycontact()
    {
        $type = $this->request->getPost('type', null, "");
        $token = $this->request->getPost('token', null, "");
        $uid = $this->request->getPost('uid', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            if ($contact = AccountContact::getContact($account->id, $type))
            {
                if ($contact->token == $token)
                {
                    if ($type == AccountContact::CONTACT_EMAIL)
                        $account->email = $contact->value;
                    else if ($type == AccountContact::CONTACT_PHONE)
                        $account->phone = $contact->value;

                    if ($account->save())
                    {
                        if ($type == AccountContact::CONTACT_EMAIL)
                        {
                            $this->notification->emailTemplate(
                                $account->email,
                                "Verifikasi Email Berhasil",
                                "verifikasiemail",
                                [
                                    'account'       => $account,
                                ]
                            );
                        }

                        $contact->delete();
                        return $this->responseSuccess($account);
                    }
                    else  $message = 'Verifikasi tidak berhasil';
                }
                else $message = 'Kode Verifikasi tidak ditemukan';
            }
            else $message = 'Permintaan tidak ditemukan';
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function socialmediaAuth ()
    {
        $socialmedia = $this->request->getPost('socialmedia');
        $appid = $this->request->getPost('appid', null, "");
        $roleSlug = $this->request->getPost('role', null, "");
        $id = $this->request->getPost('id');
        $email = $this->request->getPost('email');
        $isGoogle = $this->request->getPost('type') == 'google';
        $googleId = $isGoogle ? $id : null;
        $facebookId = !$isGoogle ? $id : null;

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        $user = Account::findByGoogleId($googleId);
        $email_exists = false;
        $user_exists = false;
        $login_data = [];

        if ($account = Account::findByGoogleId($googleId))
        {
            if ($account->status != Account::ACCOUNT_ACTIVE)
                return $this->responseError("Akun tidak aktif");

            $login_data = $this->loginData($application, $account, $roleSlug);

            if (empty($login_data['roles']))
                return $this->responseError("Akun tidak mempunyai akses");

            $user_exists = true;
            $email_exists = true;
        }
        else
        {
            if ($account = Account::findByUsername($email))
            {
                $email_exists = true;
            }
        }

        return $this->responseSuccess([
            'account_exists'=> $user_exists,
            'login_data'    => $login_data,
            'email_exists'  => $email_exists
        ]);
    }

    public function googleAuth ()
    {
        $appid = $this->request->getPost('appid');
        $id_token = $this->request->getPost('token');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        $client = new Google_Client(['client_id' => $this->config->google->client_id]);  // Specify the CLIENT_ID of the app that accesses the backend
        try {
            $payload = $client->verifyIdToken($id_token);
        } catch (LogicException $e){
            return $this->responseError($e->getMessage());
        }

        if (empty($payload))
            return $this->responseError("Request tidak dikenali");

        $userid = $payload['sub'];
        $email_exists = false;
        $user_exists = false;

        if ($account = Account::findByGoogleId($userid))
        {
            if ($account->status != Account::ACCOUNT_ACTIVE)
                return $this->responseError("Akun tidak aktif");

            if (empty($application = Application::findByAppId($appid)))
                return $this->responseError("Akses aplikasi tidak dikenal");

            if (empty($access = Access::findAccess($application->id, $account->id)))
                return $this->responseError("Akun tidak memiliki akses aplikasi");

            $accountroles = AccountRole::findRoles ($account->id);
            if ($accountroles->count() == 0)
                return $this->responseError("Akun tidak mempunyai permission");

            $roleOrtu = [];
            foreach ($accountroles as $row)
            {
                if ($row->role_slug == Role::ROLE_ORTU)
                    $roleOrtu = $row;
            }

            $children = [];

            if ($roleOrtu)
            {
                $result = Siswa::findByOrtu($roleOrtu->object_id);
                $children = $result->toArray();
            }

            $quranbookmark = IbadahTilawah::findByAccount($account->id);

            $user_exists = true;
            $email_exists = true;

            $familymembers = [];
            $family = [];

            if ($myfamily = Family::findByAccountMember ($account->id))
            {
                $family = $myfamily;
                if ($aMember = FamilyMember::findByFamilyId($family->id))
                {
                    foreach ($aMember as $member)
                    {
                        $familymembers[] = $member;
                    }
                }

            }

            $data = [
                'account'       => $account->toArray(),
                'access'        => $access->toArray(),
                'roles'         => $accountroles->toArray(),
                'children'      => $children,
                'quran_bookmark'=> $quranbookmark->toArray(),
                'family'        => $family,
                'member'        => $familymembers
            ];
        }
        else
        {
            $email  = $payload['email'];
            $data   = [];
            if ($account = Account::findByEmail($email)){
                $email_exists = true;
            }

        }

            /* {
                "iss":"https:\/\/accounts.google.com",
                "azp":"182207412050-giibqv42gmh5a6f086qu6rmejse9c8ct.apps.googleusercontent.com",
                "aud":"182207412050-l7987ej4nl5cbbii9bsmlh7u734mu8d1.apps.googleusercontent.com",
                "sub":"108676060469534199206",

                "email":"app.tes123.id@gmail.com",
                "email_verified":true,
                "name":"Aplikasi tes123",
                "picture":"https:\/\/lh6.googleusercontent.com\/-mxfMe1EdZeY\/AAAAAAAAAAI\/AAAAAAAAAAA\/ACHi3relLzbmYEpIri52T91PTv1w2BC14w\/s96-c\/photo.jpg",
                "given_name":"Aplikasi",
                "family_name":"tes123",
                "locale":"en",
                "iat":1557888060,
                "exp":1557891660} */


            // If request specified a G Suite domain:
            //$domain = $payload['hd'];

        //return $this->responseSuccess($payload);
        return $this->responseSuccess([
            'user_exists'   => $user_exists,
            'user_data'     => $data,
            'email_exists'  => $email_exists
        ]);
    }

    public function removegoogle()
    {
        $uid = $this->request->getPost('uid', null, "");
        $appid = $this->request->getPost('appid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($account = Account::findById($uid))
        {
            if (!empty($account->google_id))
            {
                $google_email = $account->google_account;
                $account->google_id = null;
                $account->google_account = null;
                if ($account->save())
                {
                    Notification::addNotification (
                        $account->id,
                        "Akun Sahara Anda berhenti menggunakan akun google",

                        "Akun Sahara Anda telah berhenti menggunakan akun google ".$google_email.".\n".
                        "Selanjutnya anda tidak bisa login menggunakan akun google anda"
                    );

                    $this->notification->emailTemplate(
                        $google_email,
                        "Akun Sahara anda sudah tidak terhubung dengan akun google",
                        "akungoogle",
                        [
                            'account'       => $account,
                            'email'         => $google_email,
                            'connected'     => false
                        ]
                    );

                    return $this->responseSuccess();
                }
                else $message = 'Akun google tidak berhasil di hapus dari akun';
            }
            else $message = 'Akun google tidak ditemukan';
        }
        else $message = 'Akun tidak dikenal';

        return $this->responseError($message);
    }

    public function setgoogle ()
    {
        $appid = $this->request->getPost('appid');
        $id_token = $this->request->getPost('token');
        $uid = $this->request->getPost('uid');

        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        $client = new Google_Client(['client_id' => $this->config->google->client_id]);  // Specify the CLIENT_ID of the app that accesses the backend
        try {
            $payload = $client->verifyIdToken($id_token);
        } catch (LogicException $e){
            return $this->responseError($e->getMessage());
        }

        if (empty($payload))
            return $this->responseError("Request tidak dikenali");

        $userid = $payload['sub'];

        if (Account::findByGoogleId($userid))
        {
            return $this->responseError("Akun google sudah digunakan");
        }
        else
        {
            if ($account = Account::findById($uid))
            {
                $account->google_id = $userid;
                $account->avatar = $payload['picture'];
                $account->google_account = $payload['email'];
                if ($account->save()){

                    Notification::addNotification (
                        $account->id,
                        "Akun google berhasil dihubungkan",

                        "Akun Sahara Anda telah terhubung dengan akun google ".$payload['email'].".\n".
                        "Selanjutnya anda bisa login menggunakan akun google anda"
                    );

                    $this->notification->emailTemplate(
                        $payload['email'],
                        "Akun Sahara telah terhubung dengan akun google",
                        "akungoogle",
                        [
                            'account'       => $account,
                            'email'         => $payload['email'],
                            'connected'     => true
                        ]
                    );

                    return $this->responseSuccess();

                }
            }
        }

        return $this->responseError("Akun google tidak berhasil dihubungkan");
    }
}