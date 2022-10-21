<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

class BaseController {

    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $managers = array();

    public function __construct() {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/milton-plugin.php';

        $this->managers = array(
            'cpt_manager'         => 'Activate CPT Manager',
            'taxonomy_manager'    => 'Activate Taxonomy Manager',
            'media_widget'        => 'Activate Media Widget',
            'gallery_manager'     => 'Activate Gallery',
            'testimonial_manager' => 'Activate Testimonial Manager',
            'login_manager'       => 'Activate Login Manager',
        );
    }

    public function activated(string $key){

        $option=get_option('milton_plugin');
        
        return isset($option[$key]) ? $option[$key]:false;
    }
}