<?php

namespace Chayka\Email;

require_once 'wp-includes/class-phpmailer.php';

use Chayka\MVC\View;
use PHPMailer;

class EmailHelper {

    protected static $scriptPaths = array();

    /**
     * Add script paths for the View to look for templates;
     *
     * @param $path
     */
    public static function addScriptPath($path){
        if(!in_array($path, self::$scriptPaths)){
            self::$scriptPaths[]=$path;
        }
    }

    /**
     * Get email address where admin notifications should be sent to
     *
     * @return string
     */
    public static function getNotificationEmailAddress(){
        return OptionHelper::getOption('notification_email');
    }

    /**
     * @param string $subject
     * @param string $html
     * @param string $to
     * @param string $from
     * @param string $cc
     * @param string $bcc
     * @return bool
     * @throws \phpmailerException
     */
    public static function send($subject, $html, $to, $from = '', $cc = '', $bcc = ''){
        $mailFrom = OptionHelper::getOption('mail_from', 'postmaser@'.$_SERVER['SERVER_NAME']);
        $mailFromName = OptionHelper::getOption('mail_from', $_SERVER['SERVER_NAME']);
        $smtpHost = OptionHelper::getOption('smtp_host', 'localhost');
        $smtpPort = OptionHelper::getOption('smtp_port', '25');
        $smtpSsl  = OptionHelper::getOption('smtp_ssl', 'none'); // none|tsl|ssl
        $smtpAuth  = OptionHelper::getOption('smtp_auth', false);
        $smtpUser  = OptionHelper::getOption('smtp_user', '');
        $smtpPass  = OptionHelper::getOption('smtp_pass', '');

        $fn = get_template_directory().'/app/views/email/template.phtml';
        if(file_exists($fn)){
            $view = new View();
            $view->addBasePath(get_template_directory().'/app/views');
            $html = str_replace('<!--content-->', $html, $view->render('email/template.phtml'));
        }

        $mail = new PHPMailer();

        $mail->Subject = $subject;

        $mail->isHTML(true);
        $mail->Body = $html;
        $mail->From = $from?$from:$mailFrom;
        $mail->FromName = $mailFromName;
        $mail->addAddress($to);
        if($cc){
            $mail->addCC($cc);
        }
        if($bcc){
            $mail->addBcc($bcc);
        }

        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->Port = $smtpPort;
        $mail->SMTPAuth = $smtpAuth;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = $smtpSsl;

        $res = $mail->send();

        return $res;
    }

    /**
     * Nice function to redefine with:
     *
     * return Plugin::getView();
     *
     * @return View;
     */
    public static function getView(){
        $scriptPath = "app/views";
        $html = new View();
        $html->addBasePath($scriptPath);
        foreach(self::$scriptPaths as $path){
            $html->addBasePath($path);
        }

        return $html;
    }

    /**
     * Send templated message
     *
     * @param string $subject
     * @param string $template
     * @param array $params
     * @param string $to
     * @param string $from
     * @param string $cc
     * @param string $bcc
     * @return bool
     */
    public static function sendTemplate($subject, $template, $params, $to, $from = '', $cc = '', $bcc = ''){

        $html = static::getView();

        foreach($params as $key => $value){
            $html->assign($key, $value);
        }

        $html->enableNls(true);

        $content = $html->render($template);

        return self::send($subject, $content, $to, $from, $cc, $bcc);
    }

}

