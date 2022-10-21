<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

class Deactivate{
    public static function deactivate(){
        flush_rewrite_rules();
    }
 }