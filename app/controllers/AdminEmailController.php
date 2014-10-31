<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 21.10.14
 * Time: 18:24
 */

namespace Chayka\Email;

use Chayka\Helpers\InputHelper;
use Chayka\Helpers\JsonHelper;
use Chayka\Helpers\Util;
use Chayka\MVC\Controller;
use Exception;

class AdminEmailController extends Controller{

    public function indexAction(){
        wp_enqueue_script('chayka-email-options-form');
    }

    public function testAction(){
        InputHelper::checkParam('to')->required()->email();
        InputHelper::checkParam('message')->required();
        InputHelper::captureInput();
        InputHelper::validateInput(true);

        $to = InputHelper::getParam('to');
        $message = InputHelper::getParam('message');
        try{
            EmailHelper::send('Test Message from '.Util::serverName(), $message, $to);
            JsonHelper::respond(null, 0, 'message sent');
        }catch (Exception $e){
            JsonHelper::respondException($e);
        }
    }

} 