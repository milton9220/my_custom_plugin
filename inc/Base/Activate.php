<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

class Activate {
    public static function activate() {

        flush_rewrite_rules();
        $default = array(); 

        if ( !get_option( 'milton_plugin' )) {
            update_option( 'milton_plugin', $default );
        }

        if ( !get_option( 'milton_plugin_cpt' )) {
            update_option( 'milton_plugin_cpt', $default );
        }

        if ( !get_option( 'milton_plugin_taxonomy' )) {
            update_option( 'milton_plugin_taxonomy', $default );
        }
        
        
    }

}
