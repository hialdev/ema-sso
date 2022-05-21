<?php

class MainTask extends BaseTask
{
    public function runAction()
    {
        echo "Sahara API commands";
        echo PHP_EOL;
        echo "Version ".$this->config->version;
        echo PHP_EOL;
    }

    public function testAction($params = [])
    {
        $this->parseParameters($params, ['media']);

        if ($this->argument->media == 'email'){
            echo $this->notification->email(
                ['me@tes123.id'],
                "subject",
                "body email"
            ) ? "Done" : "Failed";
        }else if ($this->argument->media == 'sms'){
            echo $this->notification->sms(
                '082114566413',
                "tes sms"
            ) ? "Done" : "Failed";
        }
        else
        {
            echo "test apa ?";
        }
    }

    public function fcmAction($params = [])
    {
        $this->parseParameters($params, ['action', 'token']);

        $fcm = new Fcm;
        $topic = $this->config->google->fcm_topic;

        if ($this->argument->action == 'send'){
            //printf("Token: %s", $this->argument->token);
            $fcm->send ([$this->argument->token], "Aplikasi Sahara", "", Fcm::TYPE_NOTIFICATION, [
                'image' => 'https://insantama.sch.id/wp-content/uploads/2018/11/Cover-Brosur-PPDB-2019-1.png'
            ]);
        }else if ($this->argument->action == 'sendartikel'){
            $fcm->send ([$this->argument->token], "Aplikasi Sahara", "", Fcm::TYPE_ARTICLE, [
                'id'        => '3778',
                'title'     => 'Reportase Rihlah Ilmiah"',
                'date'      => '2019-03-11 07:59:57',
                'thumbnail' => 'http://insantama.sch.id/wp-content/uploads/2019/03/Rihlah-SMPIT-4.jpeg',
                'category_id'   => 'headline',
                'category_name' => 'Headline',
            ]);
        }else if ($this->argument->action == 'sendtopic'){
            $fcm->sendTopic ($topic, "Selamat datang di Aplikasi Sahara", "");

        }else if ($this->argument->action == 'subscribe'){
            printf("Token: %s", $this->argument->token);

            $fcm->subscribeTopic ([$this->argument->token], $topic);
        }else if ($this->argument->action == 'unsubscribe'){
            printf("Token: %s", $this->argument->token);

            $fcm->unsubscribeTopic ([$this->argument->token], $topic);
        }else {

            echo "params: send|subscribe <token>|unsubscribe <token>";
        }

        //
    }

}
