<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

use \MiltonPlugin\Inc\Api\SettingsApi;
use \MiltonPlugin\Inc\Base\BaseController;
use \MiltonPlugin\Inc\Api\Callbacks\TestimonialCallbacks;

class TestimonialController extends BaseController {

    public $subpages = array();

    public $callbacks;

    public function register() {

        if ( !$this->activated( 'testimonial_manager' ) ) { //jodi activate na hoi ,not true hole return hobe
            return;
        }

        $this->settings = new SettingsApi();

        $this->callbacks = new TestimonialCallbacks();

        add_action( 'init', array( $this, 'testimonial_cpt' ) );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

        add_action( 'save_post', array( $this, 'save_testimonial_metabox' ) );

        add_action( 'manage_testimonial_posts_columns', array( $this, 'set_custom_columns' ) );

        add_action( 'manage_testimonial_posts_custom_column', array( $this, 'set_custom_columns_data' ),10,2 );

        add_filter( 'manage_edit-testimonial_sortable_columns', array( $this, 'set_custom_columns_sortable' ) );

        $this->setShortCodePages();

        add_shortcode('testimonial-form',array($this,'testimonial_form'));

        add_action('wp_ajax_submit_testimonial',array($this,'submit_testimonial'));

        add_action('wp_ajax_nopriv_submit_testimonial',array($this,'submit_testimonial'));

    }

    public function testimonial_cpt() {
        $labels = array(
            'name'          => 'Testimonials',
            'singular_name' => 'Testimonial',
            'menu_name'     => 'Testimonials',
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'menu_icon'           => 'dashicons-testimonial',
            'show_ui'             => true,
            'rewrite'             => array( 'slug' => 'testimonial', 'with_front' => false ),
            'supports'            => array( 'title', 'editor' ),
        );
        register_post_type( 'testimonial', $args );
    }

    public function add_meta_boxes() {
        add_meta_box( 'testimonial_fields', 'Testimonial Options', array( $this, 'render_testimonial_box' ), 'testimonial', 'side', 'default' );
    }

    public function save_testimonial_metabox( $post_id ) {

        if ( !isset( $_POST['milton_testimonial_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['milton_testimonial_nonce'];

        if ( !wp_verify_nonce( $nonce, 'milton_testimonial' ) ) {
            return $post_id;
        }

        if ( define( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        $data = array(
            'name'     => sanitize_text_field( $_POST['milton_testimonial_author'] ),
            'email'    => sanitize_text_field( $_POST['milton_testimonial_email'] ),
            'rating'    => sanitize_text_field( $_POST['milton_testimonial_rating'] ),
            'approved' => isset( $_POST['milton_testimonial_approved'] ) ? 1 : 0,
            'featured' => isset( $_POST['milton_testimonial_featured'] ) ? 1 : 0,
        );

        update_post_meta( $post_id, '_milton_testimonial_key', $data );
    }

    public function render_testimonial_box( $post ) {

        wp_nonce_field( 'milton_testimonial', 'milton_testimonial_nonce' );

        $data = get_post_meta( $post->ID, '_milton_testimonial_key', true );

        $name = isset( $data['name'] ) ? $data['name'] : '';

        $email = isset( $data['email'] ) ? $data['email'] : '';

        $rating = isset( $data['rating'] ) ? $data['rating'] : '';

        $approved = isset( $data['approved'] ) ? $data['approved'] : false;

        $featured = isset( $data['featured'] ) ? $data['featured'] : false;


        ?>
        <p>
			<label class="meta-label" for="milton_testimonial_author">Author Name</label>
			<input type="text" id="milton_testimonial_author" name="milton_testimonial_author" class="widefat" value="<?php echo esc_attr( $name ); ?>">
		</p>
		<p>
			<label class="meta-label" for="milton_testimonial_email">Author Email</label>
			<input type="email" id="milton_testimonial_email" name="milton_testimonial_email" class="widefat" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<div class="meta-container">
			<label class="meta-label w-50 text-left" for="milton_testimonial_approved">Approved</label>
			<div class="text-right w-50 inline">
				<div class="ui-toggle inline"><input type="checkbox" id="milton_testimonial_approved" name="milton_testimonial_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
					<label for="milton_testimonial_approved"><div></div></label>
				</div>
			</div>
		</div>
		<div class="meta-container">
			<label class="meta-label w-50 text-left" for="milton_testimonial_featured">Featured</label>
			<div class="text-right w-50 inline">
				<div class="ui-toggle inline"><input type="checkbox" id="milton_testimonial_featured" name="milton_testimonial_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
					<label for="milton_testimonial_featured"><div></div></label>
				</div>
			</div>
		</div>
        <p>
			<label class="meta-label" for="milton_testimonial_email">Author Rating</label>
            <select name="milton_testimonial_rating" id="milton_testimonial_rating">
                <option value="">Select a Rating</option> 
                <?php 
                    for ($i=1; $i <=5; $i++) {
                        $selected='';
                        $selected= $rating==$i ? 'selected':''; 
                        printf('<option value="%s" %s>%s Star</option>',$i,$selected,$i);
                    }
                ?>
            </select>
		</p>
        <?php
    }

    public function set_custom_columns( $column ) {
        $title = $column['title'];
        $date = $column['date'];

        unset( $column['title'], $column['date'] );

        $column['name'] = 'Author Name';
        $column['title'] = $title;
        $column['rating'] = 'Author Rating';
        $column['approved'] = 'Approved';
        $column['featured'] = 'Featured';
        $column['date'] = 'Date';

        return $column;
    }
    public function set_custom_columns_data($column,$post_id){

        $data = get_post_meta( $post_id, '_milton_testimonial_key', true );

        $name = isset( $data['name'] ) ? $data['name'] : '';

        $email = isset( $data['email'] ) ? $data['email'] : '';

        $rating = isset( $data['rating'] ) ? intval($data['rating']) : '';

        $approved = isset( $data['approved'] ) && $data['approved']===1 ? '<strong>Yes</strong>' : 'No';

        $featured = isset( $data['featured'] ) && $data['featured'] ===1 ? '<strong>Yes</strong>' : 'No';

        switch ($column) {
            case 'name':
                echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
				break;
                break;
            case 'rating':
                echo $rating;
                break;
            case 'approved':
                echo $approved;
                break;
    
            case 'featured':
                echo $featured;
                break;
        }
    }
    public function set_custom_columns_sortable($columns)
	{
		$columns['name'] = 'name';
		$columns['approved'] = 'approved';
		$columns['featured'] = 'featured';

		return $columns;
	}
    public function setShortCodePages(){
        $subpage = array(
			array(
				'parent_slug' => 'edit.php?post_type=testimonial',
				'page_title' => 'Shortcodes',
				'menu_title' => 'Shortcodes',
				'capability' => 'manage_options',
				'menu_slug' => 'alecaddd_testimonial_shortcode',
				'callback' => array( $this->callbacks, 'shortcodePage' )
			)
		);

		$this->settings->addSubPages( $subpage )->register();
    }
    public function testimonial_form(){

        ob_start();

        require_once("$this->plugin_path/templates/public/contact-form.php");

        echo "<script src=\"$this->plugin_url/assets/js/form.js\"></script>";

        return ob_get_clean();
    }
    public function submit_testimonial(){

        if(! DOING_AJAX || ! check_ajax_referer('testimonial-nonce','nonce')){

            return $this->return_json('error');
        }

        $name = sanitize_text_field($_POST['name']);
		$email = sanitize_email($_POST['email']);
		$rating = sanitize_text_field($_POST['rating']);
		$message = sanitize_textarea_field($_POST['message']);

		$data = array(
			'name' => $name,
			'email' => $email,
            'rating'=>intval($rating),
			'approved' => 0,
			'featured' => 0,
		);

        $args = array(
			'post_title' => 'Testimonial from ' . $name,
			'post_content' => $message,
			'post_author' => 1,
			'post_status' => 'publish',
			'post_type' => 'testimonial',
			'meta_input' => array(
				'_milton_testimonial_key' => $data
			)
		);

        $postID = wp_insert_post($args);
 
		if ($postID) {
			return $this->return_json('success');
		}

		return $this->return_json('error');

    }
    public function return_json($status){

        $return = array(
            'status' =>$status
        );

        wp_send_json($return);

        die();
    }
}
