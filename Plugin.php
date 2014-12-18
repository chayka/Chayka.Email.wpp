<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 21.10.14
 * Time: 18:20
 */

namespace Chayka\Email;

use Chayka\WP;

class Plugin extends WP\Plugin{

    public static $instance = null;

    public static function init(){
        self::$instance = $plugin = new self(__FILE__, array(
            'admin-email'
        ));

        $plugin->addSupport_ConsolePages();

    }

    /**
     * Routes are to be added here via $this->addRoute();
     */
    public function registerRoutes(){
        $this->addRoute('default');
    }

    /**
     * Custom post type are to be added here
     */
    public function registerCustomPostTypes(){

    }

    /**
     * Custom Taxonomies are to be added here
     */
    public function registerTaxonomies(){

    }

    /**
     * Custom Sidebars are to be added here via $this->registerSidbar();
     */
    public function registerSidebars(){

    }

    /**
     * Register scripts and styles here using $this->registerScript() and $this->registerStyle()
     *
     * @param bool $minimize
     */
    public function registerResources($minimize = false){
        $this->registerScript('chayka-email-options-form', 'src/ng-modules/chayka-email-options-form.js', array('chayka-options-form', 'chayka-ajax'));
    }

    /**
     * Register your action hooks here using $this->addAction();
     */
    public function registerActions(){

    }

    /**
     * Register your action hooks here using $this->addFilter();
     */
    public function registerFilters(){

    }

    public function registerConsolePages(){
        $this->addConsoleSubPage('chayka-core', 'Email', 'update_core', 'chayka-email', '/admin-email/');
    }
}