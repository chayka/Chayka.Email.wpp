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
     * Set up PhpMailer instance
     *
     * @param PhpMailer $phpMailer
     *
     * @return mixed
     */
    public static function setupPhpMailer($phpMailer){
        $mailFrom = OptionHelper::getOption('mail_from', 'postmaster@'.$_SERVER['SERVER_NAME']);
        $mailFromName = OptionHelper::getOption('mail_from', $_SERVER['SERVER_NAME']);
        $smtpHost = OptionHelper::getOption('smtp_host', 'localhost');
        $smtpPort = OptionHelper::getOption('smtp_port', '25');
        $smtpSsl  = OptionHelper::getOption('smtp_ssl', 'none'); // none|tsl|ssl
        $smtpAuth  = OptionHelper::getOption('smtp_auth', false);
        $smtpUser  = OptionHelper::getOption('smtp_user', '');
        $smtpPass  = OptionHelper::getOption('smtp_pass', '');

        $phpMailer->From = $mailFrom;
        $phpMailer->FromName = $mailFromName;

        $phpMailer->isSMTP();
        $phpMailer->Host = $smtpHost;
        $phpMailer->Port = $smtpPort;
        $phpMailer->SMTPAuth = !!$smtpAuth;
        $phpMailer->Username = $smtpUser;
        $phpMailer->Password = $smtpPass;
        $phpMailer->SMTPSecure = $smtpSsl;

        return $phpMailer;
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

        $fn = get_template_directory().'/app/views/email/template.phtml';

        $fn = apply_filters_ref_array('EmailHelper.htmlTemplate', array($fn));

        if(file_exists($fn)){
            $view = new View();
            $html = str_replace('<!--content-->', $html, $view->render($fn));
        }

        $phpMailer = new PHPMailer();

        self::setupPhpMailer($phpMailer);

        $phpMailer->Subject = $subject;

        $phpMailer->isHTML(true);
        $phpMailer->Body = $html;

        if($from){
            $phpMailer->From = $from;
            $phpMailer->FromName = '';
        }

        $phpMailer->addAddress($to);
        if($cc){
            $phpMailer->addCC($cc);
        }
        if($bcc){
            $phpMailer->addBcc($bcc);
        }

        $res = $phpMailer->send();

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

        $content = apply_filters_ref_array('EmailHelper.sendTemplate', array($content, $template, $params));

        return self::send($subject, $content, $to, $from, $cc, $bcc);
    }

}

