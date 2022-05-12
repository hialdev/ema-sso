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

    function insertFileIntoFolder($service, $folderId, $fileId)
    {
        $newChild = new Google_Service_Drive_ChildReference();
        $newChild->setId($fileId);
        try {
          return $service->children->insert($folderId, $newChild);
        } catch (Exception $e) {
          print "An error occurred: " . $e->getMessage();
        }
        return false;
      }


    public function driveAction($params = [])
    {

        $client = new Google_Client();

        //$credentialsFile = __DIR__ . '/credentials/service_account.json';
        $credentialsFile = '/windows/data/Documents/Google/ServiceAccount/service_account_gdrive_tes123-7f9ec056aa18.json';

        if (!file_exists($credentialsFile)) {
            throw new RuntimeException('Service account credentials Not Found!');
        }
        $tokenCode = '4/1wFMrzecKWOfAvjlB-SEdnqrfcdjuiGSSe5xXyei_Pf-e7BItY3VdSo';

        $clientId = '113221221816471917328';
        //$client->setAccessToken($tokenCode);
        //$client->setClientId($clientId);
        $client->setAuthConfig($credentialsFile);
        $client->setApplicationName("Service Account Example");
        $client->setScopes(Google_Service_Drive::DRIVE);
        $client->setAccessType('offline');
        $client->setSubject('app.tes123.id@gmail.com');


        $tokenJsonFile = '/windows/data/Documents/Google/Token/client_secret.json';
        if (file_exists($tokenJsonFile)) {
            $accessToken = file_get_contents($tokenJsonFile);
        } else {
            $accessToken = "MUST BE CREATED!!!"; // real creation process must be placed here
        }
        $client->setAccessToken($accessToken);

        $service = new Google_Service_Drive($client);
        $folderId = '1HPzoSbiEHGAtLxluSnY9TcmWuzlkwae8';

        //Insert a file
        $file = new Google_Service_Drive_DriveFile([
            'parents' => [$folderId]
        ]);
        //$file->setName(uniqid().'.zip');
        $file->setName('test123.zip');
        $file->setDescription('Testing document ZIP backups');
        $file->setMimeType('application/zip');
        //$file->setParents(['id' =>  $folderId]);

        $data = file_get_contents('/home/azies/Downloads/Source_Sans_Pro.zip');

        $createdFile = $service->files->create($file, array(
            'data' => $data,
            'mimeType' => 'application/zip',
            'uploadType' => 'multipart'
            ));

            echo $createdFile->getId();
        //print_r($createdFile);





        // Print the names and IDs for up to 10 files.
        $optParams = array(
            'pageSize' => 10,
            'fields' => 'nextPageToken, files(id, name)'
        );
        $results = $service->files->listFiles($optParams);

        if (count($results-> getFiles()) == 0)
        {
            print "No files found.\n";
        }
        else
        {
            print "Files:\n";
            foreach($results->getFiles() as $file)
            {
                $fileId = $file->getId();
                $fileName = $file-> getName();
                printf("%s (%s)\n", $file-> getName(), $file-> getId());


                //$this->insertFileIntoFolder($service, $folderId, $fileId);
            }
        }

        /* $content = $service->files->get($fileId, array("alt" => "media"));
        $outHandle = fopen("/tmp/".$fileName, "w+");
        while (!$content->getBody()->eof()) {
                fwrite($outHandle, $content->getBody()->read(1024));
        }
        fclose($outHandle); */

    }

    public function fcmAction($params = [])
    {
        $this->parseParameters($params, ['action', 'token']);

        $fcm = new Fcm;
        $topic = $this->config->google->fcm_topic;

        if ($this->argument->action == 'send'){
            //printf("Token: %s", $this->argument->token);
            $fcm->send ([$this->argument->token], "Sahara", "", Fcm::TYPE_NOTIFICATION, [
                'image' => 'https://insantama.sch.id/wp-content/uploads/2018/11/Cover-Brosur-PPDB-2019-1.png'
            ]);
        }else if ($this->argument->action == 'sendartikel'){
            $fcm->send ([$this->argument->token], "Aplikasi Sahara", "", Fcm::TYPE_ARTICLE, [
                'id'        => '3778',
                'title'     => 'Reportase Rihlah',
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

    public function emailAction ($to, $subject, $body)
    {
        Timer::start();
        $result = $this->mailer->send($to, $subject, $body) ? "OK":"NOK";
        $_elapsed = Timer::elapsedTime();

        printf(
            "Test:Email | %s | %s | %s - [%s]", $to, $subject, $result, $_elapsed
        );
    }
}