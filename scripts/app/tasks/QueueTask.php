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

    const PARAM_STATUS      = 'tm-queue-status';
    const PARAM_STARTING    = 'tm-queue-starting';

    public function runAction()
    {
        echo "Taskman Email Queue Flusher";
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

                    $cc = $email->cc?:[];
                    $bcc = $email->bcc?:[];

                    Timer::start();
                    $result = $this->mailer->send($email->to, $email->subject, $email->body, $cc, $bcc) ? "OK":"NOK";
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

}