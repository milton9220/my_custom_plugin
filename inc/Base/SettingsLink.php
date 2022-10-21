<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

use \MiltonPlugin\Inc\Base\BaseController;
class SettingsLink extends BaseController{
    public function register(){
        add_filter("plugin_action_links_".$this->plugin,array($this,'admin_settings_link'));
    }
    public function admin_settings_link($links){
        $settings_link='<a href="admin.php?page=milton_plugin">Settings</a>';
        array_push($links,$settings_link);
        return $links;
    }
 }