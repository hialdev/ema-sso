<?php

class TelegramBot extends  Phalcon\Mvc\User\Component
{
    static function connect ()
    {
        require BASE_PATH.'/vendor/autoload.php';

        $config = Phalcon\Di::getDefault()->getConfig();
        $mysql_credentials = [
            'host'     => $config->database->host,
            'user'     => $config->database->username,
            'password' => $config->database->password,
            'database' => $config->database->db->bot,
        ];

        $telegram = new Longman\TelegramBot\Telegram($config->bot->apiKey, $config->bot->username);
        $telegram->enableMySql($mysql_credentials);
        $telegram->enableAdmins((array) $config->bot->adminUsers);
        $telegram->enableLimiter();

        return $telegram;
    }

    static function broadcast ($message)
    {
        self::connect ()->runCommands([
            '/sendtoall '.$message
        ]);
    }
}