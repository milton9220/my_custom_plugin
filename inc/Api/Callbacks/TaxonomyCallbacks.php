<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Api\Callbacks;

class TaxonomyCallbacks {

    public function taxSectionManager() {
        echo "Create Taxonomies as you want!!!";
    }

    public function taxSanitize( $input ) {


        $output = get_option( 'milton_plugin_taxonomy' );


        /**Delete or unset post before insert database posttypes code start*/

        if ( isset($_POST["remove"]) ) {

			unset($output[$_POST["remove"]]);

			return $output;
		}
        
        /**Delete or unset post before insert database posttypes code end */

        if ( count($output) == 0 ) {
            $output = array( $input['taxonomy'] => $input );
            return $output;
        }    
        foreach ( $output as $key => $value ) {

            if ( $input['taxonomy'] === $key ) {
                $output[$key] = $input;
            } else {
                $output[$input['taxonomy']] = $input;
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

        if(isset($_POST['edit_taxonomy']) && 'taxonomy'==$name){
            $disable_input='readonly';
        }

        if(isset($_POST['edit_taxonomy'])){
            $input = get_option( $option_name );
            $value=$input[$_POST['edit_taxonomy']][$name];
        }

        printf( '<input type="text" required name="%s"  class="regular-text"  id="%1$s" value="%s" placeholder="%s" %4$s />', $_name, $value, $args['placeholder'],$disable_input);
    }

    public function checkboxField( $args ) {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];

        $checkbox = get_option( $option_name );

        $_name = "{$option_name}" . "[" . "{$name}" . "]"; //$option_name[$name] = milton_plugin[cpt_manager]

        $checked=false;


        if(isset($_POST['edit_taxonomy'])){

            $checkbox=get_option($option_name);

            $checked = isset($checkbox[$_POST['edit_taxonomy']][$name]) ?: false;

        }

        printf( '<div class="%1$s">
                    <input type="checkbox" name="%2$s" value="1" class="%1$s"  id="%3$s" %4$s />
                    <label for="%3$s"><div></div></label>
                </div>',
            $classes, $_name, $name,($checked ? 'checked':'') );
    }

    public function postTypesCheckboxField( $args ) {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];

        $checkbox = get_option( $option_name );

        // $_name = "{$option_name}" . "[" . "{$name}" . "]"; //$option_name[$name] = milton_plugin[cpt_manager]

        $checked=false;

        $output='';

        if(isset($_POST['edit_taxonomy'])){

            $checkbox=get_option($option_name);

        }
        $post_types=get_post_types(array( 'show_ui' => true ));

        foreach ($post_types as $post_type) {
            if(isset($_POST['edit_taxonomy'])){
                $checked = isset($checkbox[$_POST['edit_taxonomy']][$name][$post_type]) ?: false;
            }
            $_name = "{$option_name}" . "[" . "{$name}" . "]"."["."{$post_type}"."]";
           $output.=sprintf('<div class="%1$s post-types">
                <input type="checkbox" name="%2$s" value="1" class="%1$s"  id="%3$s" %4$s />
                <label for="%3$s"><div></div></label><strong>' . $post_type . '</strong>
                </div>',$classes, $_name, $_name,($checked ? 'checked':'') );
        }
        echo $output;
    }


}
