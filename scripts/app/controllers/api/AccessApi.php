<?php
/*
 *
 *
 *
 *
 * @author: me@tes123.id
 */

Class AccessApi extends BaseApi
{
    public function regToken ()
    {
        $appId = $this->request->getPost('appid');
        $token = $this->request->getPost('token');
        $oldtoken = $this->request->getPost('oldtoken');
        $uid = $this->request->getPost('uid');
        $deviceId = $this->request->getPost('androidId');
        $deviceName = $this->request->getPost('deviceName');

        $accessId = 0;

        if (empty($application = Application::findByAppId($appId)))
            return $this->responseError("Akses aplikasi tidak dikenal");

        if ($uid)
        {
            if ($account = Account::findById($uid))
            {
                if ($access = Access::findAccess($application->id, $uid))
                    $accessId = $access->id;
            }
        }

        $loggedIn = false;

        if ($accessDevice = AccessDevice::findByToken($token))
        {
            if ($uid && $accessDevice->login_status == 0)
                $loggedIn = true;
        }
        else
        {
            $accessDevice = null;
            if ($adev = AccessDevice::findByDevice($deviceId, $accessId))
            {
                if ($accessId == 0 && $adev->device_name  == $deviceName)
                    $accessDevice = $adev;
            }

            if (is_null($accessDevice))
            {
                $accessDevice = new AccessDevice;
                $accessDevice->created = Utils::now();
            }

            $accessDevice->access_token = $token;

            if ($uid) $loggedIn = true;
        }

        if ($loggedIn)
        {
            $accessDevice->last_login = Utils::now();
            $accessDevice->login_status = 1;

            $fcm = new Fcm;
            $fcm->subscribeTopic ([$token], $this->config->google->fcm_topic);
        }

        $accessDevice->access_id = $accessId;
        $accessDevice->device_name = $deviceName;
        $accessDevice->device_id = $deviceId;
        $accessDevice->device_os = $this->request->getPost('os');
        $accessDevice->device_os_version = $this->request->getPost('osVersion');
        $accessDevice->ip_address = $this->request->getPost('ipAddress');

        if ($accessDevice->save())
        {
            if ($oldtoken)
            {
                AccessDevice::deleteByToken($oldtoken);

                $fcm = new Fcm;
                $fcm->unsubscribeTopic ([$oldtoken], $this->config->google->fcm_topic);

            }

            return $this->responseSuccess();
        }

        return $this->responseError("Access Token gagal disimpan". $accessDevice->getErrorMessage());
    }

    public function removeToken ()
    {
        if (AccessDevice::deleteByToken($token))
        {
            $fcm = new Fcm;
            $fcm->unsubscribeTopic ([$token], $this->config->google->fcm_topic);

            return $this->responseSuccess();
        }

        return $this->responseError("Access Token tidak ditemukan");
    }

    public function resetToken ()
    {
        $token = $this->request->getPost('token');
        $uid = $this->request->getPost('uid');

        if ($accessDevice = AccessDevice::findByToken($token))
        {
            $accessDevice->access_id = 0;
            $accessDevice->last_login = null;
            $accessDevice->login_status = 0;

            if ($accessDevice->update())
            {
                return $this->responseSuccess();
            }
        }

        return $this->responseError("Access Token tidak ditemukan");
    }

}
