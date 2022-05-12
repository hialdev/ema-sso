<?php


Class Fcm extends   Phalcon\Di\Injectable
{
    const TYPE_NOTIFICATION = 'message';
    const TYPE_ARTICLE      = 'article';
    const TYPE_SYSTEM       = 'system';

    const SENDTO_ALL        = 'all';
    //const SENDTO_SELECTED   = 'selected';

    const FCM_SENDING_URL       = 'https://fcm.googleapis.com/fcm/send';
    const FCM_NOTIFICATION_URL  = 'https://android.googleapis.com/gcm/notification';
    const FCM_SUBSCRIBE_TOPIC   = 'https://iid.googleapis.com/iid/v1:batchAdd';
    const FCM_UNSUBSCRIBE_TOPIC = 'https://iid.googleapis.com/iid/v1:batchRemove';

    private $config;
    private $types          = [self::TYPE_NOTIFICATION, self::TYPE_ARTICLE, self::TYPE_SYSTEM];

    private $authKey        = "";
    private $senderId       = "";
    private $headers        = [];

    public function __construct ()
    {
        $this->config = \Phalcon\Di::getDefault()->getShared('config');
        $this->logInfo( $this->config->google->fcm_auth_key);

        $this->authKey = $this->config->google->fcm_auth_key;
        $this->senderId = $this->config->google->fcm_sender_id;

        $this->headers = [
            'Authorization: key='.$this->authKey,
            'Content-Type: application/json'
        ];
    }

    private function logInfo ($message)
    {
        $this->log->info($message);
    }

    private function _sendRequest ($url, $payload)
    {
        $sent = 0;
        $status = false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $_start = microtime(true);
        $result = curl_exec($ch);
        curl_close($ch);

        $_log = sprintf (
            "FCM Send Request | Url: %s | Headers : %s | Data: %s | Response : %s | [%s]",
            $url, json_encode($this->headers), $payload, $result, (microtime(true) - $_start)
        );

        $this->logInfo($_log);

        return ($result) ?
            json_decode($result) :
            FALSE;

    }

    public function send ($tokens, $title, $message, $type = self::TYPE_NOTIFICATION, $data = [])
    {
        if (!in_array($type, $this->types))
            $type = self::TYPE_NOTIFICATION;

        $status = false;

        $body = [
            "type"      => $type,
            "message"   => $message?: "Terima kasih telah menggunakan Aplikasi Sahara",
            "title"     => $title ?: "Sahara",
            "data"      => $data
        ];

        $payload = [
            "data"  => $body,
        ];

        if (is_array($tokens))
            $payload['registration_ids'] = $tokens;
        else
            $payload['to'] = $tokens;

        if ($oResponse = $this->_sendRequest (self::FCM_SENDING_URL, json_encode($payload)))
        {
            if (!is_array($tokens) && stripos($tokens, '/topics/') !== FALSE)
            {
                $status = isset($oResponse->message_id) && !empty($oResponse->message_id);
            }
            else
            {
                $status = $oResponse->success > 0;
            }
        }

        return $status;
    }

    public function sendTopic ($topic, $title, $message, $type = self::TYPE_NOTIFICATION, $data = [])
    {
        $tokens = '/topics/'.$topic;
        return $this->send($tokens, $title, $message, $type, $data);
    }


    public function createGroup ($tokens, $notificationKeyName)
    {
        $payload = [
            'operation'             => 'create',
            'notification_key_name' => $notificationKeyName,
            'registration_ids'      => $tokens
        ];

        $this->headers[] = 'project_id: '.$this->senderId;

        if ($oResponse = $this->_sendRequest (self::FCM_NOTIFICATION_URL, json_encode($payload)))
        {
            if (isset($oResponse->notification_key))
                return $oResponse->notification_key;
        }

        return FALSE;
    }

    public function addToGroup ($tokens, $notificationKeyName, $notificationKey)
    {
        $payload = [
            'operation'             => 'add',
            'notification_key_name' => $notificationKeyName,
            'notification_key'      => $notificationKey,
            'registration_ids'      => $tokens
        ];

        $this->headers[] = 'project_id: '.$this->senderId;

        if ($oResponse = $this->_sendRequest (self::FCM_NOTIFICATION_URL, json_encode($payload)))
        {
            if (isset($oResponse->notification_key))
                return $oResponse->notification_key;
        }

        return FALSE;
    }

    public function removeFromGroup ($tokens, $notificationKeyName, $notificationKey)
    {
        $payload = [
            'operation'             => 'remove',
            'notification_key_name' => $notificationKeyName,
            'notification_key'      => $notificationKey,
            'registration_ids'      => $tokens
        ];

        $this->headers[] = 'project_id: '.$this->senderId;

        if ($oResponse = $this->_sendRequest (self::FCM_NOTIFICATION_URL, json_encode($payload)))
        {
            if (isset($oResponse->notification_key))
                return $oResponse->notification_key;
        }

        return FALSE;
    }

    public function subscribeTopic ($tokens, $topic)
    {
        $payload = [
            'to'                    => '/topics/'.$topic,
            'registration_tokens'   => $tokens,
        ];

        if ($oResponse = $this->_sendRequest (self::FCM_SUBSCRIBE_TOPIC, json_encode($payload)))
        {
            if (isset($oResponse->error))
                return FALSE;
        }

        return TRUE;
    }

    public function unsubscribeTopic ($token, $topic)
    {
        $payload = [
            'to'                    => '/topics/'.$topic,
            'registration_tokens'   => $token,
        ];

        if ($oResponse =  $this->_sendRequest (self::FCM_UNSUBSCRIBE_TOPIC, json_encode($payload)))
        {
            if (isset($oResponse->error))
                return FALSE;
        }

        return TRUE;
    }

}