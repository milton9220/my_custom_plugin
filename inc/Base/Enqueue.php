<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

use MiltonPlugin\Inc\Base\BaseController;

class Enqueue extends BaseController{
    public function register(){
        add_action('admin_enqueue_scripts',array($this,'enqueue'));
        add_action('wp_enqueue_scripts',array($this,'public_enqueue'));
    }
    public function enqueue(){
        wp_enqueue_style( 'myplugin-style', $this->plugin_url. 'assets/css/style.css');
        wp_enqueue_script('myplugin-script', $this->plugin_url. 'assets/js/app.js');
    }
    public function public_enqueue(){
        wp_enqueue_style( 'public-style', $this->plugin_url. 'assets/css/public-style.css');
    }
 }