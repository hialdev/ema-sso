<?php

class ApplicationController extends BaseAppController
{
    protected $pageTitle = "Aplikasi";

    public function indexAction()
    {
        $this->view->list_tipe_aplikasi = Application::getTypeAsOption();
        $this->view->list_role = Role::getAsOption();
        $this->view->list_status = Utils::getOptionList(Application::$statusText);
    }

    public function datatableAjax ()
    {
        $this->view->disable();

        $sso = $this->config->database->db->sso;
        $query = $this->request->getPost('query');

        $aParams = [
            'from'          => "$sso.applications a",
            'column'        => [
                'a.*', "(SELECT GROUP_CONCAT(ro.name) FROM $sso.application_roles ar LEFT JOIN $sso.roles ro ON ar.role_id=ro.id WHERE ar.application_id=a.id) as roles",
            ],
            'searchColumn'  => [
                'tipe'      => ['exact' => true, 'field' => 'a.type'],
                'status'    => ['exact' => true, 'field' => 'a.status'],
                //'query'     => ['field' => 'a.name'],
                'url'       => ['field' => 'a.url'],
            ],
        ];

        if ($query) $aParams['condition'][] = "(a.name LIKE '%$query%' OR a.description LIKE '%$query%')";

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $aData[] = Application::normalize($row);
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function getAjax()
    {
        $application_id = $this->request->getPost('appid');
        if ($application = Application::findByAppId($application_id))
        {
            return $this->responseSuccess($application->normalizeToArray());
        }

        return $this->responseError("Data tidak ditemukan");
    }

    public function createAjax ()
    {
        $application = new Application;

        $application->appid = sha1(uniqid());
        $application->appkey = password_hash(uniqid(), PASSWORD_DEFAULT);
        $application->name = $this->request->getPost('name');
        $application->type = $this->request->getPost('type');
        $application->url = $application->type != Application::APPLICATION_MOBILE? $this->request->getPost('url') : '';
        $application->status = $this->request->getPost('status');
        $application->description = $this->request->getPost('description');
        $application->created = Utils::now();

        if ($application->save())
        {
            return $this->responseSuccess($application->normalizeToArray());
        }

        return $this->responseError("Aplikasi tidak berhasil disimpan");
    }

    public function saveAjax ()
    {
        $appid = $this->request->getPost('appid');

        if ($application = Application::findByAppId($appid))
        {
            $application->name = $this->request->getPost('name');
            $application->type = $this->request->getPost('type');
            $application->url = $application->type != Application::APPLICATION_MOBILE ? $this->request->getPost('url') : '';
            $application->description = $this->request->getPost('description');

            if ($application->save())
            {
                return $this->responseSuccess($application->normalizeToArray());
            }

            return $this->responseError("Aplikasi tidak berhasil disimpan");
        }

        return $this->responseError("Aplikasi tidak ditemukan");
    }

    public function saveSettingAjax ()
    {
        $appid = $this->request->getPost('appid');

        if ($application = Application::findByAppId($appid))
        {
            $field = $this->request->getPost('field');
            $value = $this->request->getPost('value');

            $application->$field = $value;

            if ($application->save())
                return $this->responseSuccess();

            return $this->responseError("Perubahan Setting Aplikasi tidak berhasil disimpan");
        }

        return $this->responseError("Aplikasi tidak ditemukan");
    }

    public function deleteAjax ()
    {
        $appid = $this->request->getPost('appid');

        if ($application = Application::findByAppId($appid))
        {
            $application_id = $application->id;
            if ($application->delete())
            {
                if ($roleList = ApplicationRole::findByApplication($application_id))
                    $roleList->delete();

                if ($levelList = ApplicationAccessLevel::findByApplication($application_id))
                    $levelList->delete();

                if ($accessList = Access::findByApplication($application_id))
                    $accessList->delete();

                return $this->responseSuccess();
            }

            return $this->responseError("Aplikasi tidak berhasil dihapus");
        }

        return $this->responseError("Aplikasi tidak ditemukan");
    }

    public function datatableRoleAjax ()
    {
        $this->view->disable();

        $appid = $this->request->getPost('appid');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.application_roles ar",
            'column'        => ["r.*"],
            'join'          => [
                "LEFT JOIN $sso.applications a ON ar.application_id=a.id",
                "LEFT JOIN $sso.roles r ON ar.role_id=r.id"
            ],
            'filter'        => ["a.appid='$appid'"],
            'sortColumn'    => [
                'name'      => ["r.name"],
            ],
        ];

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $row['icon'] = Utils::getArrayValue(ApplicationHelper::$roleIcons, $row['slug'],"far fa-user");
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function setRoleAjax ()
    {
        $appid = $this->request->getPost('appid');
        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Data Aplikasi tidak ditemukan");

        $action = $this->request->getPost('action');

        if ($action == 'add')
        {
            $items = $this->request->getPost('items');
            $success = 0;
            foreach ($items as $role_id)
            {
                if (empty($applicationRole = ApplicationRole::findRole($application->id, $role_id)))
                {
                    $applicationRole = new ApplicationRole;
                    $applicationRole->application_id = $application->id;
                    $applicationRole->role_id = $role_id;

                    if ($applicationRole->save())
                        $success++;
                }
            }

            if ($success)
                return $this->responseSuccess();

            return $this->responseError("Role Aplikasi gagal disimpan");
        }
        else if ($action == 'remove')
        {
            $role_id = $this->request->getPost('role_id');

            if ($applicationRole = ApplicationRole::findRole($application->id, $role_id))
            {
                if ($applicationRole->delete())
                    return $this->responseSuccess();

                return $this->responseError("Role Aplikasi gagal dihapus");
            }

            return $this->responseError("Role Aplikasi tidak ditemukan");
        }

        return $this->responseError("Perintah tidak dikenal");
    }

    public function datatableAksesLevelAjax ()
    {
        $this->view->disable();

        $appid = $this->request->getPost('appid');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.application_access_levels al",
            'column'        => ["l.*"],
            'join'          => [
                "LEFT JOIN $sso.applications a ON al.application_id=a.id",
                "LEFT JOIN $sso.access_levels l ON al.access_level_id=l.id"
            ],
            'filter'        => ["a.appid='$appid'"],
            'sortColumn'  => [
                'name'    => ["l.name"],
            ],
        ];

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function setAksesAjax ()
    {
        $appid = $this->request->getPost('appid');
        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Data Aplikasi tidak ditemukan");

        $action = $this->request->getPost('action');

        if ($action == 'add')
        {
            $items = $this->request->getPost('items');
            $success = 0;
            foreach ($items as $access_level_id)
            {
                if (empty($applicationAcessLevel = ApplicationAccessLevel::findAccessLevel($application->id, $access_level_id)))
                {
                    $applicationAcessLevel = new ApplicationAccessLevel;
                    $applicationAcessLevel->application_id = $application->id;
                    $applicationAcessLevel->access_level_id = $access_level_id;

                    if ($applicationAcessLevel->save())
                        $success++;
                }
            }

            if ($success)
                return $this->responseSuccess();

            return $this->responseError("Akses Level gagal disimpan");
        }
        else if ($action == 'remove')
        {
            $access_level_id = $this->request->getPost('access_level_id');

            if ($applicationAcessLevel = ApplicationAccessLevel::findAccessLevel($application->id, $access_level_id))
            {
                if ($applicationAcessLevel->delete())
                    return $this->responseSuccess();

                return $this->responseError("Akses Level gagal dihapus");
            }

            return $this->responseError("Akses Level Aplikasi tidak ditemukan");
        }

        return $this->responseError("Perintah tidak dikenal");
    }

    public function datatableUserAjax ()
    {
        $this->view->disable();

        $appid = $this->request->getPost('appid');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.access c",
            'column'        => [
                'c.id', 'c.account_id', 'c.status', 'a.uid', 'a.username', 'a.name', "l.name as akses_level_txt", "acc.access_level_id",
                "IF(acc.access_level_id IS NOT NULL AND l.id IS NOT NULL, 1, 0) as valid_access"
            ],
            'join'          => [
                "LEFT JOIN $sso.applications ap ON c.application_id=ap.id",
                "LEFT JOIN $sso.accounts a ON c.account_id=a.id",
                "LEFT JOIN $sso.access_levels l ON c.access_level_id=l.id",
                "LEFT JOIN $sso.application_access_levels acc ON acc.access_level_id=c.access_level_id AND c.application_id=acc.application_id",
            ],
            'filter'        => ["ap.appid='$appid'"],
            'searchColumn'  => [
                'access_level_id' => ['exact' => true, 'field' => 'c.access_level_id'],
                'query'     => ['field' => 'a.name'],
            ],
            'sortColumn'  => [
                'name'      => ["a.name"],
            ],
        ];

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $row['status_txt'] = Utils::getArrayValue(Access::$statusText, $row['status']);
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function setUserAjax ()
    {
        $appid = $this->request->getPost('appid');
        if (empty($application = Application::findByAppId($appid)))
            return $this->responseError("Data Aplikasi tidak ditemukan");

        $action = $this->request->getPost('action');

        if ($action == 'add')
        {
            $items = $this->request->getPost('items');
            $success = 0;
            foreach ($items as $account_id)
            {
                if (empty($access = Access::findAccess($application->id, $account_id)))
                {
                    $access = new Access;
                    $access->application_id = $application->id;
                    $access->account_id = $account_id;
                    $access->status = Access::STATUS_ACTIVE;
                    $access->created = Utils::now();

                    if ($access->save())
                        $success++;
                }
            }

            if ($success)
                return $this->responseSuccess();

            return $this->responseError("Akses User gagal disimpan");
        }
        else if (in_array($action, ['set', 'status', 'remove']))
        {
            $account_id = $this->request->getPost('account_id');
            if (empty($account = Account::findById($account_id)))
                return $this->responseError("Akun tidak ditemukan");

            if ($access = Access::findAccess($application->id, $account_id))
            {
                if ($action == 'remove')
                {
                    if ($access->delete())
                        return $this->responseSuccess();

                    return $this->responseError("Akses User gagal dihapus");
                }
                else if ($action == 'set')
                {
                    $access->access_level_id = $this->request->getPost('access_level_id');

                    if ($access->save())
                        return $this->responseSuccess($access);

                    return $this->responseError("Akses User gagal disimpan");
                }
                else if ($action == 'status')
                {
                    $access->status = $this->request->getPost('status');

                    if ($access->save())
                        return $this->responseSuccess();

                    return $this->responseError("Status Akses User gagal disimpan");
                }
            }

            return $this->responseError("Akses User tidak ditemukan");
        }

        return $this->responseError("Perintah tidak dikenal: $action");
    }


    public function listRoleAjax ()
    {
        $this->view->disable();

        $id = $this->request->getPost('id');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.roles r",
            'column'        => ["r.*", 'IF(ar.role_id IS NULL, 0,1) as disabled '],
            'join'          => ["LEFT JOIN $sso.application_roles ar ON ar.application_id='$id' AND ar.role_id=r.id"],
            //'filter'        => ["r.id NOT IN (SELECT role_id FROM $sso.application_roles WHERE application_id='$id')"],
            //'filter'        => ["ar.role_id IS NULL"],
            'searchColumn'  => [
                'query'     => ["field" => "r.name"]
            ],
            'sortColumn'  => [
                'name'    => ["r.name"],
            ],
        ];

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $row['icon'] = Utils::getArrayValue(ApplicationHelper::$roleIcons, $row['slug'],"far fa-user");
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function listAksesAjax ()
    {
        $this->view->disable();

        $id = $this->request->getPost('id');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.access_levels al",
            'column'        => ["al.*", 'IF(aal.access_level_id IS NULL, 0, 1) as disabled'],
            //'filter'        => ["al.id NOT IN (SELECT $sso.access_level_id FROM application_access_levels WHERE application_id='$id')"],
            'join'          => ["LEFT JOIN $sso.application_access_levels aal ON aal.application_id='$id' AND aal.access_level_id=al.id"],
            //'filter'        => ["aal.access_level_id IS NULL"],
            'searchColumn'  => [
                'query'     => ["field" => "al.name"]
            ],
            'sortColumn'  => [
                'name'    => ["al.name"],
            ],
        ];

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function listUserAjax ()
    {
        $this->view->disable();

        $id = $this->request->getPost('id');
        $query = $this->request->getPost('query');
        $roleId = $this->request->getPost('role_id');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.accounts a",
            'column'        => [
                "a.*",
                "IF(ac.id IS NULL,0,1) as disabled",
                "(SELECT GROUP_CONCAT(ro.name) FROM $sso.account_roles ar LEFT JOIN $sso.roles ro ON ar.role_id=ro.id WHERE ar.account_id=a.id) as roles",
                "(SELECT GROUP_CONCAT(ro.slug) FROM $sso.account_roles ar LEFT JOIN $sso.roles ro ON ar.role_id=ro.id WHERE ar.account_id=a.id) as roles_slug",
            ],
            'join'          => ["LEFT JOIN $sso.access ac ON ac.application_id='$id' AND ac.account_id=a.id"],
            //'filter'        => ["ac.id IS NULL"],
            'sortColumn'  => [
                'name'    => ["a.name"],
                'email'   => ["a.email"],
            ],
        ];

        if ($query) $aParams['condition'][] = "(a.name LIKE '%$query%' OR a.email LIKE '%$query%')";

        if ($roleId)
        {
            $aParams['join'][] = "LEFt JOIN $sso.account_roles r ON r.account_id=a.id AND r.role_id='$roleId'";
            $aParams['filter'][] = "r.role_id IS NOT NULL";
        }

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }

    public function listAppAjax ()
    {
        $this->view->disable();

        $query = $this->request->getPost('query');
        $account_id = $this->request->getPost('account_id');
        $sso = $this->config->database->db->sso;

        $aParams = [
            'from'          => "$sso.applications a",
            'column'        => [
                "a.*",
                "IF(ac.id IS NULL,0,1) as disabled",
            ],
            'join'          => ["LEFT JOIN $sso.access ac ON ac.application_id=a.id AND ac.account_id='$account_id'"],
            'sortColumn'  => [
                'name'    => ["a.name"],
            ],
        ];

        if ($query) $aParams['condition'][] = "(a.name LIKE '%$query%' OR a.description LIKE '%$query%')";

        $result = DataTable::getData($this->config->database, $aParams, $this->request->getPost());
        $aData = [];

        foreach ($result['data'] as $row)
        {
            $aData[] = $row;
        }

        $result['data'] = $aData;

        return $this->jsonResponse($result);
    }
}