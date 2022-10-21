<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Api\Callbacks;

use \MiltonPlugin\Inc\Base\BaseController;

class TestimonialCallbacks extends BaseController{

    public function shortcodePage(){
        return require_once("$this->plugin_path/templates/admin/testimonial.php");
    }


}