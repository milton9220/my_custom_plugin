<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

use \MiltonPlugin\Inc\Api\SettingsApi;
use \MiltonPlugin\Inc\Base\BaseController;
use \MiltonPlugin\Inc\Api\Callbacks\AdminCallbacks;

class AuthController extends BaseController {

    public $subpages = array();

    public $callbacks;

    public $settings;

    public function register() {

        if(! $this->activated('login_manager')){   //jodi activate na hoi ,not true hole return hobe
            return;
        }
        
        $this->settings = new SettingsApi();
        
        $this->callbacks = new AdminCallbacks();
        
        $this->setSubPages();
        
        $this->settings->addSubPages( $this->subpages )->register();

    }
    public function setSubPages() {
        $this->subpages = array(
            array(
                'parent_slug' => 'milton_plugin',
                'page_title'  => 'Login Manager',
                'menu_title'  => 'Login',
                'capability'  => 'manage_options',
                'menu_slug'   => 'milton_auth',
                'callback'    => array( $this->callbacks, 'adminAuth' ),
            ),
        );
    }
}