<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Api\Callbacks;

use \MiltonPlugin\Inc\Base\BaseController;

class ManagerCallbacks extends BaseController{

    public function checkboxSanitize( $input )
	{
		$output = array();

		foreach ( $this->managers as $key => $value ) {
			$output[$key] = ( isset( $input[$key] ) && $input[$key] == 1 ) ? true : false;
		}

		return $output;
	}

    public function adminSectionManager(){
        echo "Manage the Sections and Features of this Plugin by activating the checkboxes from the following list.";
    }

    public function checkboxField($args){

        $name=$args['label_for'];
        $classes=$args['class'];
        $option_name=$args['option_name'];

        $checkbox=get_option($option_name);

        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;
        

        $_name="${option_name}"."["."${name}"."]";      //$option_name[$name] = milton_plugin[cpt_manager]

        printf('<div class="%1$s">
                    <input type="checkbox" name="%2$s" value="1" class="%1$s" %3$s id="%4$s"/>
                    <label for="%4$s"><div></div></label>
                </div>',
                $classes,$_name,($checked ? 'checked':''),$name);
    }

    
    
}