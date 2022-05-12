<?php

class TesTask extends BaseTask
{
    public function runAction()
    {
        echo "Sahara API Tes ";
        echo PHP_EOL;
        echo "Version ".$this->config->version;
        echo PHP_EOL;
    }

    public function autoidAction ($name = null)
    {
        $task = new Task;
        $task->name = $name?:"New Task ".Utils::randomString();

        if ($task->save())
        {
            printf ("SUCCESS".PHP_EOL);
        }
        else
        {
            printf ("ERROR: %s".PHP_EOL, $task->getErrorMessage());
        }
    }

    public function emailAction ($to = null, $subject = null, $body = null)
    {
        if (empty($to))
        {
            $this->showResult (
                "Usage: tes:email <to> [<subject>] [<body>]"
            );
        }

        $recipients = explode(",", $to);
        Timer::start();

        $result = $this->mailer->send($recipients, $subject, $body) ? "OK":"NOK";
        $_elapsed = Timer::elapsedTime();

        printf(
            "Test:Email | %s | %s | %s - [%s]", $to, $subject, $result, $_elapsed
        );
    }
}