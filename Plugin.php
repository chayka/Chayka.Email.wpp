<?php

namespace Chayka\Email;

use Chayka\WP;

class Plugin extends WP\Plugin{

    /* chayka: constants */
    
    public static $instance = null;

    public static function init(){
        if(!static::$instance){
            static::$instance = $app = new self(__FILE__, array(
                'admin-email'
                /* chayka: init-controllers */
            ));
	        $app->addSupport_UriProcessing();
	        $app->addSupport_ConsolePages();
            /* chayka: init-addSupport */
        }
    }


    /**
     * Register your action hooks here using $this->addAction();
     */
    public function registerActions() {
        $this->addAction('phpmailer_init', array('\\Chayka\\Email\\EmailHelper', 'setupPhpMailer'));
    	/* chayka: registerActions */
    }

    /**
     * Register your action hooks here using $this->addFilter();
     */
    public function registerFilters() {
		/* chayka: registerFilters */
    }

    /**
     * Register scripts and styles here using $this->registerScript() and $this->registerStyle()
     *
     * @param bool $minimize
     */
    public function registerResources($minimize = false) {
        $this->registerBowerResources(true);

        $this->setResSrcDir('src/');
        $this->setResDistDir('dist/');

        $this->registerNgScript('chayka-email-options-form', 'ng-modules/chayka-email-options-form.js', array('chayka-options-form', 'chayka-ajax'));
        $this->registerMinimizedScript('chayka-email', 'ng-modules/chayka-email.min.js', array(
            'chayka-email-options-form',
        ));
		/* chayka: registerResources */
    }

    /**
     * Routes are to be added here via $this->addRoute();
     */
    public function registerRoutes() {
        $this->addRoute('default');
    }

    /**
     * Registering console pages
     */
    public function registerConsolePages(){
        $this->addConsoleSubPage('chayka-core', 'Email', 'update_core', 'chayka-email', '/admin-email/');
        /* chayka: registerConsolePages */
    }
}