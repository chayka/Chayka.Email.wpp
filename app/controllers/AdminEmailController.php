<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 21.10.14
 * Time: 18:24
 */

namespace Chayka\Email;

use Chayka\MVC\Controller;

class AdminEmailController extends Controller{

    public function indexAction(){
        wp_enqueue_script('ng-options-form');
    }

    public function testAction(){

    }

} 