<?php

class custom_post_types
{

    public function __construct()
    {
        add_action('init', array($this, 'ads_custom_post_type'));
        add_action('init', array($this, 'services_custom_post_type'));

    }

    public function ads_custom_post_type()
    {
// Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'Ads', 'Post Type General Name', 'directup' ),
            'singular_name'       => _x( 'Ad', 'Post Type Singular Name', 'directup' ),
            'menu_name'           => __( 'Ads', 'directup' ),
            'parent_item_colon'   => __( 'Parent Ad', 'directup' ),
            'all_items'           => __( 'All Ads', 'directup' ),
            'view_item'           => __( 'View Ad', 'directup' ),
            'add_new_item'        => __( 'Add New Ad', 'directup' ),
            'add_new'             => __( 'Add New', 'directup' ),
            'edit_item'           => __( 'Edit Ad', 'directup' ),
            'update_item'         => __( 'Update Ad', 'directup' ),
            'search_items'        => __( 'Search Ad', 'directup' ),
            'not_found'           => __( 'Not Found', 'directup' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'directup' ),
        );

// Set other options for Custom Post Type

        $args = array(
            'label'               => __( 'ads', 'twentytwenty' ),
            'description'         => __( 'Movie news and reviews', 'twentytwenty' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail','author'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => true,

        );

        // Registering your Custom Post Type
        register_post_type( 'ads', $args );

    }

    public function services_custom_post_type()
    {
// Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'Services', 'Post Type General Name', 'directup' ),
            'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'directup' ),
            'menu_name'           => __( 'Services', 'directup' ),
            'parent_item_colon'   => __( 'Parent Service', 'directup' ),
            'all_items'           => __( 'All Services', 'directup' ),
            'view_item'           => __( 'View Service', 'directup' ),
            'add_new_item'        => __( 'Add New Service', 'directup' ),
            'add_new'             => __( 'Add New', 'directup' ),
            'edit_item'           => __( 'Edit Service', 'directup' ),
            'update_item'         => __( 'Update Service', 'directup' ),
            'search_items'        => __( 'Search Service', 'directup' ),
            'not_found'           => __( 'Not Found', 'directup' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'directup' ),
        );

// Set other options for Custom Post Type

        $args = array(
            'label'               => __( 'services', 'twentytwenty' ),
            'description'         => __( 'Services post type', 'twentytwenty' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => true,

        );

        // Registering your Custom Post Type
        register_post_type( 'services', $args );

    }
}

new custom_post_types();