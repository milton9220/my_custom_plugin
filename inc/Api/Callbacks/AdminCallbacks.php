<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Api\Callbacks;

use \MiltonPlugin\Inc\Base\BaseController;

class AdminCallbacks extends BaseController{

    public function adminDashboard(){
        return require_once("$this->plugin_path/templates/admin/admin.php");
    }

    public function adminCpt()
	{
		return require_once( "$this->plugin_path/templates/admin/cpt.php" );
	}

    public function adminTaxonomy(){
        return require_once( "$this->plugin_path/templates/admin/taxonomy.php" );
    }

    public function adminAuth(){
        return require_once( "$this->plugin_path/templates/admin/auth.php" );
    }

    public function adminTestimonial(){
        return require_once( "$this->plugin_path/templates/admin/testimonial.php" );
    }
    
    public function adminWidget(){
        return require_once( "$this->plugin_path/templates/admin/widget.php" );
    }

}