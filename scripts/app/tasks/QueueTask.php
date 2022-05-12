<?php
/*
 *
 *
 *
 *
 * @author: me@tes123.id
 */
class QueueTask extends BaseTask
{
    const QUEUE_RUNNNING    = 'RUNNING';
    const QUEUE_CLOSED      = 'CLOSED';

    const PARAM_STATUS      = 'queue-status';
    const PARAM_STARTING    = 'queue-starting';

    public function runAction()
    {
        echo "Instantama Email Queue Flusher";
        echo PHP_EOL;
        printf("Usage: %s queue:manager start|stop", $_SERVER['argv'][0]);
        echo PHP_EOL;
    }

    public function managerAction($actionCommand = null)
    {
        $_command = $this->config->application->cliManager;
        if ($actionCommand == 'start')
        {
            if ($this->isRunning('queue:send'))
            {
                if ($this->cache->get(self::PARAM_STATUS) == self::QUEUE_CLOSED)
                {
                    echo "Waiting previeous job ...";
                    while ($this->isRunning('queue:send')){
                        usleep("1000");
                    }
                }
                //else $this->showResult("queue still running");
            }

            $this->cache->set(self::PARAM_STARTING, 1, 3);
            exec($_command.' queue:sendemail > /dev/null 2>/dev/null &', $output);
            //exec($_command.' queue:telegram > /dev/null 2>/dev/null &', $output);
            exec($_command.' queue:whatsapp > /dev/null 2>/dev/null &', $output);

            $this->showResult("queue started");
        }
        else if ($actionCommand == 'stop')
        {
            $this->cache->set(self::PARAM_STATUS, self::QUEUE_CLOSED, 3600);
            $this->showResult("queue stopped");
        }
        else
        {
            $this->runAction();
        }
    }

    public function sendemailAction()
    {
        $minProcess = 1;
        if ($this->cache->get(self::PARAM_STARTING) != 1) $minProcess = 0;

        if ($this->isRunning('queue:sendemail', "", $minProcess))
            $this->showErrorResult("Process already running");

        $queue = $this->notification->connect();

        $this->cache->set(self::PARAM_STATUS, self::QUEUE_RUNNNING, 3600);
        $this->logDebug( "SendEmail | Queue is STARTED" );

        $queue->useTube($this->config->queue->tubes->email);

        while (1)
        {
            if ($this->cache->get(self::PARAM_STATUS) == self::QUEUE_RUNNNING)
            {
                if ($job = $queue->peekReady())
                {
                    $email = json_decode($job->getData());
                    Timer::start();
                    $result = $this->mailer->send($email->to, $email->subject, $email->body) ? "OK":"NOK";
                    $_elapsed = Timer::elapsedTime();

                    $this->logInfo(
                        sprintf(
                            "SendEmail | %s | %s | %s - [%s]", json_encode($email->to), $email->subject, $result, $_elapsed
                        )
                    );
                    $queue->delete($job);
                    usleep(10000);
                    continue;
                }

            }
            else
            {
                $this->logDebug( "SendEmail | Queue is CLOSED" );
                exit;
            }

            $this->mailer->stop();
            sleep(5);
        }
    }


    public function telegramAction()
    {
        $minProcess = 1;
        if ($this->cache->get(self::PARAM_STARTING) != 1) $minProcess = 0;

        if ($this->isRunning('queue:telegram', "", $minProcess))
            $this->showErrorResult("Process already running");

        $queue = $this->notification->connect();

        $this->cache->set(self::PARAM_STATUS, self::QUEUE_RUNNNING, 3600);
        $this->logInfo( "Telegram | Queue is STARTED" );

        $queue->useTube($this->config->queue->tubes->telegram);

        $telegram = new TelegramClient($this->config->telegram->client);

        while (1)
        {
            if ($this->cache->get(self::PARAM_STATUS) == self::QUEUE_RUNNNING)
            {
                if ($job = $queue->peekReady())
                {
                    $message = json_decode($job->getData());

                    if (stripos($message->message, 'Kode') !== FALSE)
                        $message->message = 'Tolong ganti verifikasi menggunakan email, Hubungi Admin';

                    if ($message->command == 'msg')
                    {
                        foreach ($message->name as $name)
                        {
                            Timer::start();
                            $result = $telegram->msg($name, $message->message);
                            $_elapsed = Timer::elapsedTime();

                            $errormessage = $result ? "SUCCESS" : $telegram->getErrorMessage();

                            $this->logInfo(
                                sprintf(
                                    "Telegram:Message | %s | %s | %s | %s - [%s]", json_encode($name), $message->message, $result ? "OK":"NOK", $errormessage, $_elapsed
                                )
                            );
                            usleep(10000);
                        }
                    }
                    else if ($message->command == 'add_msg')
                    {
                        Timer::start();
                        $result = $telegram->addContact($message->phone, $message->firstname, $message->lastname);
                        $_elapsed = Timer::elapsedTime();

                        $this->logInfo(
                            sprintf(
                                "Telegram:AddContact | %s | %s | %s - [%s]", $message->phone, $message->firstname.' '.$message->lastname, json_encode($result), $_elapsed
                            )
                        );

                        if ($result)
                        {
                            $contact = $result[0];

                            if ($account = Account::findByPhone($message->phone))
                            {
                                if (empty($account->telegram_id))
                                {
                                    $this->logDebug(
                                        sprintf(
                                            "Telegram:UpdateAccount | %s | %s", $message->phone, $contact->print_name
                                        )
                                    );

                                    $account->telegram_id = $contact->print_name;
                                    $account->save();
                                }
                            }

                            if ($accountContact = AccountContact::findByPhone($message->phone))
                            {
                                $this->logDebug(
                                    sprintf(
                                        "Telegram:SetData | %s | %s", $message->phone, json_encode($contact)
                                    )
                                );

                                $accountContact->data = json_encode($contact);
                                $accountContact->save();
                            }

                            Timer::start();
                            $result = $telegram->msg($contact->print_name, $message->message);
                            $_elapsed = Timer::elapsedTime();

                            $errormessage = $result ? "SUCCESS" : $telegram->getErrorMessage();

                            $this->logInfo(
                                sprintf(
                                    "Telegram:Message | %s | %s | %s | %s - [%s]", $contact->print_name, $message->message, $result? "OK":"NOK", $errormessage, $_elapsed
                                )
                            );
                        }
                    }

                    $queue->delete($job);
                    usleep(10000);
                }

            }
            else
            {
                $this->logInfo( "Telegram | Queue is CLOSED" );
                exit;
            }

            sleep(5);
        }
    }

    public function whatsappAction()
    {
        $minProcess = 1;
        if ($this->cache->get(self::PARAM_STARTING) != 1) $minProcess = 0;

        if ($this->isRunning('queue:whatsapp', "", $minProcess))
            $this->showErrorResult("Process already running");

        $queue = $this->notification->connect();

        $this->cache->set(self::PARAM_STATUS, self::QUEUE_RUNNNING, 3600);
        $this->logInfo( "Whatsapp | Queue is STARTED" );

        $queue->useTube($this->config->queue->tubes->whatsapp);

        $whatsapp = new WhatsappApi();

        while (1)
        {
            if ($this->cache->get(self::PARAM_STATUS) == self::QUEUE_RUNNNING)
            {
                if ($job = $queue->peekReady())
                {
                    $message = json_decode($job->getData());

                    Timer::start();
                    $result = $whatsapp->send($message->msisdn, $message->message);
                    $_elapsed = Timer::elapsedTime();

                    $errormessage = $result ? "SUCCESS" : $whatsapp->getErrorMessage();

                    $this->logInfo(
                        sprintf(
                            "Whatsapp:Message | %s | %s | %s | %s - [%s]", json_encode($message->msisdn), $message->message, $result ? "OK":"NOK", $errormessage, $_elapsed
                        )
                    );

                    $queue->delete($job);
                    usleep(10000);
                }

            }
            else
            {
                $this->logInfo( "Whatsapp | Queue is CLOSED" );
                exit;
            }

            sleep(5);
        }
    }

}