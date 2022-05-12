<?php

class AccountController extends BaseAppController
{
    protected $pageTitle = "User Accounts";

    public function indexAction()
    {
        $this->view->list_role = Role::getAsOption();
        $this->view->list_aplikasi = Application::getAsOption();
        $this->view->list_status = Utils::getOptionList(Account::$statusText);
        $this->view->list_department = Department::getAsOptionList();
    }

    public function datatableAjax ()
    {
        $this->view->disable();

        $roleId = $this->request->getPost('role_id');
        $appId = $this->request->getPost('application_id');
        $sso = $this->config->database->db->sso;
        $query = $this->request->getPost('query');

        $aParams = [
            'from'          => "$sso.accounts a",
            'column'        => [
                'a.id', 'a.uid', 'a.name', 'a.username', 'a.email', 'a.date_joined', 'a.status',
                "(SELECT GROUP_CONCAT(ro.name) FROM $sso.account_roles ar LEFT JOIN $sso.roles ro ON ar.role_id=ro.id WHERE ar.account_id=a.id) as roles",
                "(SELECT GROUP_CONCAT(ro.slug) FROM $sso.account_roles ar LEFT JOIN $sso.roles ro ON ar.role_id=ro.id WHERE ar.account_id=a.id) as roles_slug",
                "(SELECT COUNT(1) From $sso.access_devices ad LEFT JOIN $sso.access ac ON ac.id=ad.access_id WHERE ac.account_id=a.id) as user_device"
            ],
            'filter'        => ["child_account=0"],
            'searchColumn'  => [
                'status'    => ['exact' => true, 'field' => 'a.status'],
            ]
        ];

        if ($query)
        {
            $aParams['condition'][] = "(a.username LIKE '%$query%' OR a.email LIKE '%$query%')";
        }

        if ($appId)
        {
            $aParams['join'][] = "LEFt JOIN $sso.access ac ON ac.account_id=a.id AND ac.application_id='$appId'";
            $aParams['filter'][] = "ac.application_id IS NOT NULL";
        }

        if ($roleId)
        {
            $aParams['join'][] = "LEFt JOIN $sso.account_roles r ON r.account_id=a.id AND r.role_id='$roleId'";
            $aParams['filter'][] = "r.role_id IS NOT NULL";
        }

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];
        foreach ($result['data'] as $row)
        {
            $row['date_joined_txt'] = Utils::formatTanggal($row['date_joined'], true, true, true);
            $row['status_txt'] = Utils::getArrayValue(Account::$statusText, $row['status']);
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function getAjax ()
    {
        if ($account = Account::findByUID($this->request->getPost("uid")))
        {
            unset($account->password);
            $dataaccount = $account->normalizeToArray();
            $dataaccount['email_unverified'] = '';
            $dataaccount['phone_unverified'] = '';

            if ($emailContact = AccountContact::getEmail ($account->id))
            {
                if ($emailContact->status == 0)
                {
                    $dataaccount['email_unverified'] = $emailContact->value;
                }
            }

            if ($phoneContact = AccountContact::getPhone ($account->id))
            {
                if ($phoneContact->status == 0)
                {
                    $dataaccount['phone_unverified'] = $phoneContact->value;
                }
            }

            return $this->responseSuccess($dataaccount);
        }

        return $this->responseError("Akun tidak ditemukan");
    }

    public function createAjax ()
    {
        $account = new Account;

        $account->id = Account::generateId();
        $account->uid = Utils::uuid();
        $account->username = $this->request->getPost('username');
        $password = Utils::randomString(8);
        $account->password = md5($password);
        $account->name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $account->status = $this->request->getPost('status');
        $account->created_by = $this->account->id;
        $account->date_joined = Utils::now();

        if (Account::findByUsername($account->username))
            return $this->responseError("Username sudah digunakan");

        if (!Account::isEmailAvailable($email) || !AccountContact::isEmailAvailable($email))
            return $this->responseError("Alamat email sudah digunakan");

        if ($account->save())
        {
            $roleUser = Role::findBySlug(Role::ROLE_GENERIC);
            $accountRole = new AccountRole;
            $accountRole->account_id = $account->id;
            $accountRole->role_id = $roleUser->id;
            $accountRole->save();

            if ($application = Application::findfirst())
            {
                $access = new Access;
                $access->account_id = $account->id;
                $access->application_id = $application->id;
                $access->created = Utils::now();
                $access->save();
            }

            $this->notification->emailTemplate(
                $email,
                "Akun berhasil didaftarkan",
                "akunbaru",
                [
                    'account'       => $account,
                    'email'         => $email,
                    'password'      => $password,
                ]
            );

            // add user contact email, to be verified;
            $contact = new AccountContact;
            $contact->type = AccountContact::CONTACT_EMAIL;
            $contact->account_id = $account->id;
            $contact->value = $email;
            $contact->token = Utils::randomString();
            $emailToken = $contact->token;
            $contact->created = Utils::now();

            if ($contact->save())
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

            return $this->responseSuccess($account->normalizeToArray());
        }

        return $this->responseError("AKun tidak berhasil disimpan");
    }

    public function saveSettingAjax ()
    {
        $account_id = $this->request->getPost('account_id');

        if ($account = Account::findById($account_id))
        {
            $field = $this->request->getPost('field');
            $value = $this->request->getPost('value');

            $account->$field = $value;

            if ($account->save())
                return $this->responseSuccess();

            return $this->responseError("Perubahan Setting Akun tidak berhasil disimpan");
        }

        return $this->responseError("Akun tidak ditemukan");
    }

    public function updatecontactAjax ()
    {
        $account_id = $this->request->getPost('account_id');

        if ($account = Account::findById($account_id))
        {
            $type = $this->request->getPost('type', null, "");
            $value = $this->request->getPost('value', null, "");

            if ($type == AccountContact::CONTACT_EMAIL)
            {
                if (!Account::isEmailAvailable($value, $account->id) || !AccountContact::isEmailAvailable($value, $account->id))
                  return $this->responseError('Email sudah digunakan');
            }
            else
            {
                $value = SMSHelper::normalizeMsisdn($value);
                if (!Account::isPhoneAvailable($value, $account->id) || !AccountContact::isPhoneAvailable($value, $account->id))
                    return $this->responseError('Nomor HP sudah digunakan');
            }

            if ($contact = AccountContact::getContact($account->id, $type))
            {
                $contact->status = 0;
                $contact->data = null;
            }
            else
            {
                $contact = new AccountContact;
                $contact->account_id = $account->id;
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
                            'account'       => $account,
                            'contact'       => $contact,
                            'email'         => $contact->value,
                            'token'         => $contact->token,
                        ]
                    );
                }
                else if ($type == AccountContact::CONTACT_PHONE)
                {
                    list($firstName, $lastName) = explode(" ", $account->name, 2);

                    $this->notification->telegramAfterAdd(
                        $contact->value, $firstName, $lastName, "Kode Verifikasi Nomor Telepon Akun Sahara :\n".$contact->token
                    );
                }

                return $this->responseSuccess($contact);
            }
        }

        return $this->responseError("Akun tidak ditemukan");
    }


    public function resetPasswordAjax ()
    {
        $account_id = $this->request->getPost('account_id');
        if ($account = Account::findById($account_id))
        {
            $password = $this->request->getPost('password')?:Utils::randomString(8);
            $account->password = md5($password);

            if ($account->save())
            {
                $email_sent = false;
                if ($account->email)
                {
                    $this->notification->emailTemplate(
                        $account->email,
                        "Pemulihan Password Akun",
                        "akunresetpassword",
                        [
                            'account'   => $account,
                            'password'  => $password
                        ]
                    );
                    $email_sent = true;
                }
                return $this->responseSuccess(['email_sent' => $email_sent]);
            }
        }

        return $this->responseError("Akun tidak ditemukan");
    }

    public function deleteAjax ()
    {
        $account_id = $this->request->getPost('account_id');
        if ($account = Account::findById($account_id))
        {
            if ($account->delete())
            {
                if ($roleList = AccountRole::findByAccount($account_id))
                    $roleList->delete();

                if ($levelList = Access::findByAccount($account_id))
                    $levelList->delete();

                return $this->responseSuccess();
            }

            return $this->responseError("Akun tidak berhasil dihapus");
        }

        return $this->responseError("Akun tidak ditemukan");
    }

    public function roledatatableAjax ()
    {
        $this->view->disable();

        $account_id = $this->request->getPost('account_id');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.account_roles ar",
            'column'        => ['ar.*', "r.name as role_name", "r.slug"],
            'join'          => ["LEFT JOIN $sso.roles r ON ar.role_id=r.id"],
            'filter'        => ["ar.account_id='$account_id'"],
            'order'         => ["r.id"]
        ];

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];
        foreach ($result['data'] as $row)
        {
            $row['deskripsi'] = "";
            $row['employee'] = false;

            if ($row['slug'] == Role::ROLE_STAFF)
            {
                $employee_name = '';
                $department = '';
                $designation = '';

                if ($record = Employee::findDetailById($row['object_id']))
                {
                    $employee_name = $record->employee->getFullName();
                    $department = $record->department->name;
                    $designation = $record->designation->name;
                }

                $row['employee'] = [
                    'name'          => $employee_name,
                    'department'    => $department,
                    'designation'   => $designation
                ];
            }
            $row['icon'] = Utils::getArrayValue(ApplicationHelper::$roleIcons, $row['slug'],"far fa-user");
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function setRoleAjax ()
    {
        $action = $this->request->getPost('action');
        $account_id = $this->request->getPost('account_id');

        if ($action == 'add')
        {
            $object_id = $this->request->getPost('object_id');
            if ($role = Role::findBySlug($this->request->getPost('role_slug')))
            {
                if (empty($accountRole = AccountRole::findByAccountRole($account_id, $role->id)))
                {
                    $accountRole = new AccountRole;
                    $accountRole->account_id = $account_id;
                    $accountRole->role_id = $role->id;
                }
                $accountRole->object_id = $object_id;
                $accountRole->status = 1;

                if ($accountRole->save())
                    return $this->responseSuccess();

                return $this->responseError("Role AKun tidak berhasil disimpan");
            }

        }
        else if ($action == 'remove')
        {
            $role_id = $this->request->getPost('role_id');
            if ($accountRole = AccountRole::findByAccountRole($account_id, $role_id))
            {
                if ($accountRole->delete())
                    return $this->responseSuccess();
            }
            return $this->responseError("Role AKun tidak ditemukan");
        }

        return $this->responseError("Perintah tidak dikenali");
    }

    public function accessdatatableAjax ()
    {
        $this->view->disable();

        $account_id = $this->request->getPost('account_id');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.access ac",
            'column'        => [
                 "ac.*", "a.type", "a.description", "a.name", "l.name as akses_level_txt", "ac.access_level_id", 'a.appid',
                 "IF(acc.access_level_id IS NOT NULL AND l.id IS NOT NULL, 1, 0) as valid_access"
            ],
            'join'          => [
                "LEFT JOIN $sso.applications a ON a.id=ac.application_id",
                "LEFT JOIN $sso.access_levels l ON ac.access_level_id=l.id",
                "LEFT JOIN $sso.application_access_levels acc ON acc.access_level_id=ac.access_level_id AND ac.application_id=acc.application_id",
            ],
            'filter'        => ["ac.account_id='$account_id'"],
            'sortColumn'    => [
                'name'      => "a.name"
            ]
        ];

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];
        foreach ($result['data'] as $row)
        {
            $row['type_txt'] = Utils::getArrayValue(Application::$typeText, $row['type']);
            $row['status_txt'] = Utils::getArrayValue(Access::$statusText, $row['status']);
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function setAksesAjax ()
    {
        $action = $this->request->getPost('action');
        $account_id = $this->request->getPost('account_id');

        if ($action == 'add')
        {
            $items = $this->request->getPost('items');
            $success = 0;
            foreach ($items as $application_id)
            {
                if (empty($access = Access::findAccess($application_id, $account_id)))
                {
                    $access = new Access;
                    $access->application_id = $application_id;
                    $access->account_id = $account_id;
                    $access->status = Access::STATUS_ACTIVE;
                    $access->created = Utils::now();

                    if ($access->save())
                        $success++;
                }
            }

            if ($success)
                return $this->responseSuccess();

            return $this->responseError("Akses Aplikasi gagal disimpan");
        }

        return $this->responseError("Perintah tidak dikenali");
    }

    public function devicedatatableAjax ()
    {
        $this->view->disable();

        $account_id = $this->request->getPost('account_id');
        $query = $this->request->getPost('query');
        $periode = $this->request->getPost('periode');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.access_devices ad",
            'column'        => ['ad.*', "a.name as nama_aplikasi"],
            'join'          => ["LEFT JOIN $sso.access ac ON ac.id=ad.access_id",
                                "LEFT JOIN $sso.applications a ON a.id=ac.application_id"],
            'filter'        => ["ac.account_id='$account_id'"],
            'searchColumn'  => [
                'application_id'    => ["field" => 'ac.application_id', 'exact' => true],
            ]
        ];

        if ($query)
        {
            $aParams['condition'][] = "(ad.device_name LIKE '%$query%' OR ad.device_os LIKE '%$query%')";
        }

        if ($periode)
        {
            $time = strtotime($periode);
            $month = date("m", $time);
            $year = date("Y", $time);
            $aParams['condition'][] = "(MONTH(ad.modified)='$month' AND YEAR(ad.modified)='$year')";
        }

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];
        foreach ($result['data'] as $row)
        {
            $row['modified_txt'] = Utils::formatTanggal($row['modified'], true, true, true);
            $row['os'] = sprintf("<b>OS</b> %s, <b>Versi</b> %s", $row['device_os']?:"-", $row['device_os_version']?:"-");
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }


}