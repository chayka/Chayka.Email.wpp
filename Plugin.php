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

    const POST_TYPE_CONTENT_FRAGMENT = 'content-fragment';
    const TAXONOMY_CONTENT_FRAGMENT_TAG = 'content-fragment-tag';

    public static function init(){

        self::$instance = $plugin = new self(__FILE__, array(
            'admin-email'
        ));

        $plugin->addSupport_ConsolePages();

    }
    /**
     * Routes are to be added here via $this->addRoute();
     */
    public function registerRoutes()
    {
        // TODO: Implement registerRoutes() method.
        $this->addRoute('default');
    }

    /**
     * Custom post type are to be added here
     */
    public function registerCustomPostTypes()
    {
        // TODO: Implement registerCustomPostTypes() method.
    }

    /**
     * Custom Taxonomies are to be added here
     */
    public function registerTaxonomies()
    {
        // TODO: Implement registerTaxonomies() method.
    }

    /**
     * Custom Sidebars are to be added here via $this->registerSidbar();
     */
    public function registerSidebars()
    {
        // TODO: Implement registerSidebars() method.
    }

    /**
     * Register scripts and styles here using $this->registerScript() and $this->registerStyle()
     *
     * @param bool $minimize
     */
    public function registerResources($minimize = false)
    {
        // TODO: Implement registerResources() method.
    }

    /**
     * Register your action hooks here using $this->addAction();
     */
    public function registerActions()
    {
        // TODO: Implement registerActions() method.
    }

    /**
     * Register your action hooks here using $this->addFilter();
     */
    public function registerFilters()
    {
        // TODO: Implement registerFilters() method.
    }
}