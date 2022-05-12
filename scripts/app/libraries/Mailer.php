<?php
use Swift_Message as Message;
use Swift_SmtpTransport as Smtp;
use Phalcon\Mvc\View;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP as SMTPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Sends e-mails based on pre-defined templates
 */
class Mailer extends Phalcon\Di\Injectable
{
    const SWIFT_MAILER          = 'swiftmailer';
    const PHP_MAILER            = 'phpmailer';

    protected $transport;
    protected $errorCode = 0;

    /**
     * Applies a template to be used in the e-mail
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function getTemplate($name, $params)
    {
        $parameters = array_merge([
            'baseUrl' => $this->config->application->baseUri
        ], $params);
        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });
    }
    /**
     * Sends e-mails on predefined templates
     *
     * @param array $to
     * @param string $subject
     * @param string $name
     * @param array $params
     * @return bool|int
     * @throws Exception
     */
    public function sendTemplate($to, $subject, $name, $params)
    {
        $template = $this->getTemplate($name, $params);
        return $this->send($to, $subject, $template);
    }

    /**
     * Sends e-mails
     *
     * @param array $to
     * @param string $subject
     * @param string $body
     * @return bool|int
     * @throws Exception
     */
    public function sendLegacy($to, $subject, $body, $cc = [], $attachments = [], $setting = null)
    {
        // Settings
        $mailSettings = $setting?:$this->config->email;

        if (isset($mailSettings->disabled) && $mailSettings->disabled === true)
        {
            $this->log->debug(
                sprintf(
                   "Mailer Canceled | Email Config is disabled"
                )
            );
            return false;
        }

        $this->errorCode = 0;
        $result = false;

        // Create the message
        try {

            $message = (new Message())
                ->setSubject($subject)
                ->setTo($to)
                ->setFrom([
                    $mailSettings->senderEmail => $mailSettings->senderName
                ])
                ->setBody($body, 'text/html');

                if ($cc) $message->setCc($cc);

            if ($attachments)
            {
                foreach ($attachments as $attachment)
                {
                    $message->attach(
                        Swift_Attachment::fromPath($attachment['filepath'])->setFilename($attachment['name'])
                    );
                }
            }

        }catch(\Exception $e){
            $this->log->error($e->getCode()."|".$e->getMessage());
            $this->errorCode = $e->getCode();
            $this->log->debug(
                sprintf(
                    "Mailer | %s | %s | %s | %s", $mailSettings->senderEmail, $to, $subject, "NOK:Email Message Error - ". $e->getMessage()
                )
            );

            return false;
        }

        if (isset($mailSettings) && isset($mailSettings->smtp))
        {
            if (!$this->transport)
            {
                $this->transport = (new Smtp(
                    $mailSettings->smtp->server,
                    $mailSettings->smtp->port,
                    $mailSettings->smtp->security
                ))
                ->setUsername($mailSettings->smtp->username)
                ->setPassword($mailSettings->smtp->password)
                ->setStreamOptions([
                    'ssl'   => [
                        'allow_self_signed' => true,
                        'verify_peer' => false
                    ]
                ]);
            }

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($this->transport);

            Timer::start();
            try{
                $result = $mailer->send($message);
            }catch(\Swift_TransportException $e){
                $this->log->error($e->getCode()."|".$e->getMessage());
                $this->errorCode = $e->getCode();
            }

            $_elapsed = Timer::elapsedTime();

            $this->log->debug(
                sprintf(
                    "Mailer | %s | %s | %s | %s - [%s]", $mailSettings->senderEmail, implode(",", $to), $subject, $result?"OK":"NOK:".$this->errorCode, $_elapsed
                )
            );
        }

        return $result;
    }

    /**
     * Sends e-mails
     *
     * @param array $to
     * @param string $subject
     * @param string $body
     * @return bool|int
     * @throws Exception
     */
    public function send($to, $subject, $body, $cc = [], $attachments = [], $setting = null)
    {
        $mailer = isset($this->config->email->mailer) ? $this->config->email->mailer : self::PHP_MAILER;

        $this->log->debug(
            sprintf(
                "%s | Mailer ", $mailer
            )
        );

        $this->errorCode = 0;

        if ($mailer == self::SWIFT_MAILER)
            return $this->SwiftMailerSend($to, $subject, $body, $cc, $attachments, $setting);

        return $this->PhpMailerSend($to, $subject, $body, $cc, $attachments, $setting);
    }

    public function SwiftMailerSend ($to, $subject, $body, $cc = [], $attachments = [], $setting = null)
    {
        // Settings
        $mailSettings = $setting?:$this->config->email;

        if (isset($mailSettings->disabled) && $mailSettings->disabled === true)
        {
            $this->log->debug(
                sprintf(
                   "Mailer Canceled | Email Config is disabled"
                )
            );
            return false;
        }

        $this->errorCode = 0;
        $result = false;

        // Create the message
        try
        {
            $message = (new Message())
                ->setSubject($subject)
                ->setTo($to)
                ->setFrom([
                    $mailSettings->senderEmail => $mailSettings->senderName
                ])
                ->setBody($body, 'text/html');

            if ($cc) $message->setCc($cc);

            if ($attachments)
            {
                foreach ($attachments as $attachment)
                {
                    $message->attach(
                        Swift_Attachment::fromPath($attachment)->setFilename(basename($attachment))
                    );
                }
            }

        }
        catch(\Exception $e)
        {
            $this->log->error($e->getCode()."|".$e->getMessage());
            $this->errorCode = $e->getCode();
            $this->log->debug(
                sprintf(
                    "Mailer | %s | %s | %s | %s", $mailSettings->senderEmail, $to, $subject, "NOK:Email Message Error - ". $e->getMessage()
                )
            );

            return false;
        }

        if (isset($mailSettings) && isset($mailSettings->smtp))
        {
            if (!$this->transport)
            {
                $this->transport = (new Smtp(
                    $mailSettings->smtp->server,
                    $mailSettings->smtp->port,
                    $mailSettings->smtp->security
                ))
                ->setUsername($mailSettings->smtp->username)
                ->setPassword($mailSettings->smtp->password)
                ->setStreamOptions([
                    'ssl'   => [
                        'allow_self_signed' => true,
                        'verify_peer' => false
                    ]
                ]);
            }

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($this->transport);

            Timer::start();
            try
            {
                $result = $mailer->send($message);
            }
            catch(\Swift_TransportException $e)
            {
                $this->log->error($e->getCode()."|".$e->getMessage());
                $this->errorCode = $e->getCode();
            }

            $_elapsed = Timer::elapsedTime();

            $this->log->debug(
                sprintf(
                    "%s | %s | %s | %s | %s - [%s]", $mailSettings->mailer, $mailSettings->senderEmail, is_array($to) ? implode(",", $to) : $to, $subject, $result?"OK":"NOK:".$this->errorCode, $_elapsed
                )
            );
        }

        return $result;
    }

    public function PHPMailerSend ($to, $subject, $body, $cc = [], $attachments = [], $setting = null)
    {
        $mailSettings = $setting?:$this->config->email;

        if (isset($mailSettings->disabled) && $mailSettings->disabled === true)
        {
            $this->log->debug(
                sprintf(
                   "Mailer Canceled | Email Config is disabled"
                )
            );
            return false;
        }

        $result = false;

        $mail = new PHPMailer(true);

        Timer::start();
        try {
            //Server settings
            $mail->SMTPDebug = SMTPMailer::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();

                // Send using SMTP
            $mail->Host       = $mailSettings->smtp->server;                    // Set the SMTP server to send through
            $mail->Port       = $mailSettings->smtp->port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $mailSettings->smtp->username;                     // SMTP username
            $mail->Password   = $mailSettings->smtp->password;                               // SMTP password
            if ($mailSettings->smtp->security == 'tls')
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            else
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            $mail->SMTPOptions = array(
                'ssl' => [
                    'verify_peer' => false,
                    'allow_self_signed' => true,
                ],
            );

            //Recipients
            $mail->setFrom($mailSettings->senderEmail, $mailSettings->senderName);
            $mail->addAddress($to);     // Add a recipient
            //$mail->addReplyTo('info@example.com', 'Information');

            if ($cc)
            {
                if (is_array($cc))
                {
                    foreach ($cc as $c) $mail->addCC($c);
                }
                else $mail->addCC($cc);
            }

            // Attachments
            if ($attachments)
            {
                if (is_array($attachments))
                {
                    foreach ($attachments as $attachment) $mail->addAttachment($attachment);
                }
                else $mail->addAttachment($attachments);
            }

            // Content
            // Set email format
            if (Utils::is_html($body)) $mail->isHTML(true);

            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $result = $mail->send();
        } catch (Exception $e) {
            $this->errorCode = $e->getCode()."|".$mail->ErrorInfo;
        }

        $_elapsed = Timer::elapsedTime();

        $this->log->debug(
            sprintf(
                "%s | %s | %s | %s | %s - [%s]", $mailSettings->mailer, $mailSettings->senderEmail, is_array($to) ? implode(",", $to) : $to, $subject, $result?"OK":"NOK:".$this->errorCode, $_elapsed
            )
        );

        return $result;
    }

    public function stop ()
    {
        if ($this->transport)
        {
            $this->transport->stop();
            $this->transport = null;
        }

    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}