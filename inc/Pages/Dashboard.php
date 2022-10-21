<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Pages;

use \MiltonPlugin\Inc\Api\Callbacks\AdminCallbacks;
use \MiltonPlugin\Inc\Api\Callbacks\ManagerCallbacks;
use \MiltonPlugin\Inc\Api\SettingsApi;
use \MiltonPlugin\Inc\Base\BaseController;

class Dashboard extends BaseController {
    public $settings;

    public $callbacks;

    public $sanitizeCallbacks;

    public $managerCallbacks;

    public $pages = array();

    public function register() {
        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->managerCallbacks = new ManagerCallbacks();

        $this->setPages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();

        $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();
    }

    public function setPages() {
        $this->pages = array(
            array(
                'page_title' => 'Milton Plugin',
                'menu_title' => 'Milton',
                'capability' => 'manage_options',
                'menu_slug'  => 'milton_plugin',
                'callback'   => array( $this->callbacks, 'adminDashboard' ),
                'icon_url'   => 'dashicons-smiley',
                'position'   => '60',
            ),
        );
    }

    public function setSettings() {

        $args = array(
            array(
                'option_group' => 'milton_plugin_settings',
                'option_name'  => 'milton_plugin',         //page name
                'callback'     => array( $this->managerCallbacks, 'checkBoxSanitize' ),
            )
        );

        $this->settings->setSettings( $args );
    }

    public function setSections() {
        $args = array(
            array(
                'id'       => 'milton_admin_index',
                'title'    => 'Settings Manager',
                'callback' => array( $this->managerCallbacks, 'adminSectionManager' ),
                'page'     => 'milton_plugin',

            ),
        );
        $this->settings->setSections( $args );
    }

    public function setFields() {
        $args = array();

        foreach ( $this->managers as $key => $value ) {
            $args[] = array(
                'id'       => $key,
                'title'    => $value,
                'callback' => array( $this->managerCallbacks, 'checkboxField' ),
                'page'     => 'milton_plugin',
                'section'  => 'milton_admin_index',
                'args'     => array(
                    'option_name' =>'milton_plugin',
                    'label_for' => $key,
                    'class'     => 'ui-toggle',
                ),
            );
        }

        $this->settings->setFields( $args );
    }

}
