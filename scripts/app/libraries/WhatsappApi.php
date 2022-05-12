<?php


Class WhatsappApi extends Phalcon\Di\Injectable
{
    private $config;
    private $apiUrl;

    /**
     * If telegram-cli returns an error, the error-message gets stored here.
     *
     * @var string
     */
    protected $_errorMessage = null;

    public function __construct ()
    {
        $this->config = $this->getDI()->getShared('config');
        $this->apiUrl = $this->config->whatsapp->server->local;
    }

    private function logInfo ($message)
    {
        $this->log->info($message);
    }

    private function _sendRequest ($payload)
    {
        $sent = 0;
        $status = false;

        $_url = $this->apiUrl.'/send-message';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $_start = microtime(true);
        $result = curl_exec($ch);
        curl_close($ch);

        $_log = sprintf (
            "Whatsapp Send Message Request | Url: %s | Data: %s | Response : %s | [%s]",
            $_url, json_encode($payload), $result, (microtime(true) - $_start)
        );

        $this->logInfo($_log);

        return ($result) ?
            json_decode($result) :
            FALSE;

    }

    public function send ($number, $message)
    {
        $payload = [
            'number'    => $number,
            'message'   => $message
        ];

        $status = false;
        $this->_errorMessage = 'Request Failed';

        if ($oResponse = $this->_sendRequest ($payload))
        {
            if (!$oResponse->status && $oResponse->message)
                $this->_errorMessage = $oResponse->message;

            return $oResponse->status;
        }

        return $status;
    }

    public function getErrorMessage ()
    {
        return $this->_errorMessage;
    }
}