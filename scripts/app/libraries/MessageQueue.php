<?php

/**
 * Sends e-mails based on pre-defined templates
 */
class MessageQueue extends Phalcon\Di\Injectable
{
    protected $queue;

    public function connect()
    {
        return $this->queue = Pheanstalk\Pheanstalk::create(
            $this->config->queue->beanstalk->host,
            $this->config->queue->beanstalk->port);
    }

    /**
     * Sends e-mail message
     *
     * @param array $to
     * @param string $subject
     * @param string $body
     * @return bool
     * @throws Exception
     */
    public function email($recipients, $subject, $body, $tube = null)
    {
        if (empty($this->queue))  $this->connect();

        $tube = $tube?:$this->config->queue->tubes->email;

        $data = new stdClass;

        if (is_array($recipients))
        {
            if (isset($recipients['cc']))
            {
                $data->cc = $recipients['cc'];
            }
            else if (isset($recipients['bcc']))
            {
                $data->bcc = $recipients['bcc'];
            }
            else $data->to = $recipients;
        }
        else
        {
            $data->to = $recipients;
        }

        $data->subject = $subject;
        $data->body = $body;

        return $this->queue->useTube($tube)->put(json_encode($data));
    }

    /**
     * Sends e-mail message
     *
     * @param array $to
     * @param string $subject
     * @param string $name
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function emailTemplate($to, $subject, $name, $params, $tube = null)
    {
        $body = $this->mailer->getTemplate($name, $params);
        return $this->email($to, $subject, $body, $tube);
    }

    /**
     * Sends Text message (SMS)
     *
     * @param array $msisdn
     * @param string $message
     * @return bool
     * @throws Exception
     */
    public function sms($msisdn, $message)
    {
        if (empty($this->queue))  $this->connect();

        $tube = $this->config->queue->tubes->sms;

        $data = new stdClass;
        $data->msisdn = $msisdn;
        $data->message = $message;

        return $this->queue->useTube($tube)->put(json_encode($data));
    }

    /**
     * Sends Telegram message
     *
     * @param array $name
     * @param string $message
     * @return bool
     * @throws Exception
     */
    public function telegram($name, $message, $data = [])
    {
        if (empty($this->queue))  $this->connect();

        $tube = $this->config->queue->tubes->telegram;

        $data = new stdClass;
        $data->command = 'msg';
        $data->name = is_array($name) ? $name : [$name];
        $data->message = $message;

        return $this->queue->useTube($tube)->put(json_encode($data));
    }

    /**
     * Add Contact then Send Telegram message
     *
     * @param number $phone
     * @param string $firstName
     * @param string $lastName
     * @param string $message
     * @return bool
     * @throws Exception
     */
    public function telegramAfterAdd($phone, $firstName, $lastName, $message)
    {
        if (empty($this->queue))  $this->connect();

        $tube = $this->config->queue->tubes->telegram;

        $data = new stdClass;
        $data->command = 'add_msg';
        $data->phone = $phone;
        $data->firstname = $firstName;
        $data->lastname = $lastName;
        $data->message = $message;

        return $this->queue->useTube($tube)->put(json_encode($data));
    }

}