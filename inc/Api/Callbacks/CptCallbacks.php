<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Api\Callbacks;

class CptCallbacks {

    public function cptSectionManager() {
        echo "Manage the Sections and Features of this Plugin by activating the checkboxes from the following list.";
    }

    public function cptSanitize( $input ) {

        $output = get_option( 'milton_plugin_cpt' );

        /**Delete or unset post before insert database posttypes code start*/

        if ( isset($_POST["remove"]) ) {

			unset($output[$_POST["remove"]]);

			return $output;
		}
        
        /**Delete or unset post before insert database posttypes code end */

        if ( count($output) == 0 ) {
            $output = array( $input['post_type'] => $input );
            return $output;
        }    
        foreach ( $output as $key => $value ) {

            if ( $input['post_type'] === $key ) {
                $output[$key] = $input;
            } else {
                $output[$input['post_type']] = $input;
            }

        }
        
        return $output;

    }

    public function textField( $args ) {

        $name = $args['label_for'];

        $option_name = sanitize_text_field($args['option_name']);

        $_name = "{$option_name}" . "[" . "{$name}" . "]";

        $value='';

        $disable_input='';

        if(isset($_POST['edit_post']) && 'post_type'==$name){
            $disable_input='readonly';
        }

        if(isset($_POST['edit_post'])){
            $input = get_option( $option_name );
            $value=$input[$_POST['edit_post']][$name];
        }

        printf( '<input type="text" required name="%s"  class="regular-text"  id="%1$s" value="%s" placeholder="%s" %4$s />', $_name, $value, $args['placeholder'],$disable_input);
    }

    public function checkboxField( $args ) {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];

        $checkbox = get_option( $option_name );

        $_name = "{$option_name}" . "[" . "{$name}" . "]"; //$option_name[$name] = milton_plugin_cpt[public]

        $checked=false;


        if(isset($_POST['edit_post'])){

            $checkbox=get_option($option_name);

            $checked = isset($checkbox[$_POST['edit_post']][$name]) ?: false;

        }

        printf( '<div class="%1$s">
                    <input type="checkbox" name="%2$s" value="1" class="%1$s"  id="%3$s" %4$s />
                    <label for="%3$s"><div></div></label>
                </div>',
            $classes, $_name, $name,($checked ? 'checked':'') );
    }

}
