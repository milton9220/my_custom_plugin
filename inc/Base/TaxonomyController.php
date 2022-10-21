<?php
/**
 * @package MiltonPlugin
 */
namespace MiltonPlugin\Inc\Base;

use \MiltonPlugin\Inc\Api\Callbacks\AdminCallbacks;
use \MiltonPlugin\Inc\Api\Callbacks\TaxonomyCallbacks;
use \MiltonPlugin\Inc\Api\SettingsApi;
use \MiltonPlugin\Inc\Base\BaseController;

class TaxonomyController extends BaseController {

    public $subpages = array();

    public $callbacks;

    public $settings;

    public $tax_callbacks;

    public $taxonomies = array();

    public function register() {

        if ( !$this->activated( 'taxonomy_manager' ) ) { //jodi activate na hoi ,not true hole return hobe
            return;
        }

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->tax_callbacks = new TaxonomyCallbacks();

        $this->setSubPages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();

        $this->settings->addSubPages( $this->subpages )->register();

        $this->storeCustomTaxonomies();

        if ( !empty( $this->taxonomies ) ) {
            add_action( 'init', array( $this, 'registerCustomTaxonomy' ) );
        }

    }

    public function setSubPages() {
        $this->subpages = array(
            array(
                'parent_slug' => 'milton_plugin',
                'page_title'  => 'Custom Taxonomies',
                'menu_title'  => 'Taxonomy Manager',
                'capability'  => 'manage_options',
                'menu_slug'   => 'milton_taxonomy',
                'callback'    => array( $this->callbacks, 'adminTaxonomy' ),
            ),
        );
    }

    public function setSettings() {
        $args = array(
            array(
                'option_group' => 'milton_plugin_tax_settings',
                'option_name'  => 'milton_plugin_taxonomy', //page name
                'callback' => array( $this->tax_callbacks, 'taxSanitize' ),
            ),
        );

        $this->settings->setSettings( $args );
    }

    public function setSections() {
        $args = array(
            array(
                'id'       => 'milton_tax_index',
                'title'    => 'Custom Taxonomy Manager',
                'callback' => array( $this->tax_callbacks, 'taxSectionManager' ),
                'page'     => 'milton_taxonomy',
            ),
        );
        $this->settings->setSections( $args );

    }

    public function setFields() {

        $args = array(
            array(
                'id'       => 'taxonomy',
                'title'    => 'Custom Taxonomy ID',
                'callback' => array( $this->tax_callbacks, 'textField' ),
                'page'     => 'milton_taxonomy',
                'section'  => 'milton_tax_index',
                'args'     => array(
                    'option_name' => 'milton_plugin_taxonomy',
                    'label_for'   => 'taxonomy',
                    'placeholder' => 'eg. genre',
                ),
            ),
            array(
                'id'       => 'singular_name',
                'title'    => 'Singular Name',
                'callback' => array( $this->tax_callbacks, 'textField' ),
                'page'     => 'milton_taxonomy',
                'section'  => 'milton_tax_index',
                'args'     => array(
                    'option_name' => 'milton_plugin_taxonomy',
                    'label_for'   => 'singular_name',
                    'placeholder' => 'eg. Genre',
                ),
            ),
            array(
                'id'       => 'hierarchical',
                'title'    => 'Hierarchical',
                'callback' => array( $this->tax_callbacks, 'checkboxField' ),
                'page'     => 'milton_taxonomy',
                'section'  => 'milton_tax_index',
                'args'     => array(
                    'option_name' => 'milton_plugin_taxonomy',
                    'label_for'   => 'hierarchical',
                    'class'       => 'ui-toggle',
                ),
            ),
            array(
                'id'       => 'post_types',
                'title'    => 'Post Tpes',
                'callback' => array( $this->tax_callbacks, 'postTypesCheckboxField' ),
                'page'     => 'milton_taxonomy',
                'section'  => 'milton_tax_index',
                'args'     => array(
                    'option_name' => 'milton_plugin_taxonomy',
                    'label_for'   => 'post_types',
                    'class'       => 'ui-toggle',
                ),
            ),
        );

        $this->settings->setFields( $args );
    }

    public function storeCustomTaxonomies() {
        // get the taxonomies array
        $options = get_option( 'milton_plugin_taxonomy' ) ?: array();

// store those info into an array
        foreach ( $options as $option ) {
            $labels = array(
                'name'              => $option['singular_name'],
                'singular_name'     => $option['singular_name'],
                'search_items'      => 'Search ' . $option['singular_name'],
                'all_items'         => 'All ' . $option['singular_name'],
                'parent_item'       => 'Parent ' . $option['singular_name'],
                'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
                'edit_item'         => 'Edit ' . $option['singular_name'],
                'update_item'       => 'Update ' . $option['singular_name'],
                'add_new_item'      => 'Add New ' . $option['singular_name'],
                'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
                'menu_name'         => $option['singular_name'],
            );

            $this->taxonomies[] = array(
                'hierarchical'      => isset( $option['hierarchical'] ) ? true : false,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => $option['taxonomy'] ),
                'post_types'        => isset( $option['post_types'] ) ? $option['post_types'] : null,
            );

        }

    }

    public function registerCustomTaxonomy() {
        foreach ( $this->taxonomies as $taxonomy ) {
            $post_types = isset( $taxonomy['post_types'] ) ? array_keys( $taxonomy['post_types'] ) : null;
            register_taxonomy( $taxonomy['rewrite']['slug'], $post_types, $taxonomy );
        }

    }

}
