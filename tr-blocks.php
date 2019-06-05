<?php
/**
* Plugin Name: Taproot Blocks
* Description: Adds the reusable blocks page to the admin menu and creates a shortcode for adding reusable blocks outside the content area.
* Version: 1.0
* Author: Sky Shabatura
* Author URI: https://github.com/skyshab
**/


class Taproot_Blocks {


    /**
     * Run our plugin on init
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action('init', [ $this, 'boot' ] );
    }


    /**
     * Set up our plugin class
     *
     * @since 1.0.0
     */
    public function boot() {
        add_shortcode( 'block-template', [ $this, 'shortcode' ]);
        add_filter( 'manage_wp_block_posts_columns', [ $this, 'make_column' ] );
        add_action( 'manage_wp_block_posts_custom_column', [ $this, 'add_column_data' ], 10, 2);
        add_action( 'admin_menu', [ $this, 'add_menu_item' ] );
    }


    /**
     * Create shortcode for adding block templates
     *
     * @since 1.0.0
     */
    public function shortcode($atts) {

        $a = shortcode_atts( array(
            'id' => false,
        ), $atts );

        $post_id = $a['id'];
        if( !$post_id ) return;

        $post_content = get_post($post_id);
        $content = $post_content->post_content;
        return do_shortcode( $content );
    }


    /**
     * Create custom column in wp_blocks edit page
     *
     * @since 1.0.0
     */
    public function make_column($columns) {
        $columns['wp_block_shortcode'] = __( 'Shortcode', 'taproot' );
        return $columns;
    }


    /**
     * Add shortcode string to wp_blocks column
     *
     * @since 1.0.0
     */
    public function add_column_data($column, $post_id) {
        switch ( $column ) {
            case 'wp_block_shortcode' :
                printf('[block-template id="%s"]', $post_id );
                break;
        }
    }


    /**
     * Add admin menu item for reusable blocks page
     *
     * @since 1.0.0
     */
    public function add_menu_item() {
        add_menu_page(
            __( 'Blocks Title', 'taproot' ),
            __( 'Blocks', 'taproot' ),
            'manage_options',
            'edit.php?post_type=wp_block',
            '',
            'dashicons-layout',
            50
        );
    }

}

$tr_blocks_plugin = new Taproot_Blocks();
