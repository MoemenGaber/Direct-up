<?php

class require_assets
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'require_styles'));
        add_action('wp_enqueue_scripts', array($this, 'require_scripts'));

    }

    public function require_styles()
    {
        wp_enqueue_style('plugins', get_template_directory_uri() . '/assets/css/inc/plugins.css', array(), '2.0.0', 'all');
        wp_enqueue_style('directup-style', get_template_directory_uri() . '/assets/css/style.css', array(), '2.0.0', 'all');

    }

    public function require_scripts()
    {
        wp_enqueue_script('jquery-plugins', get_template_directory_uri() . '/assets/js/jquery-plugins.js', array(), '2.0.0', 'true' );
        wp_enqueue_script('directup-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), '2.0.0', 'true' );
        wp_enqueue_script('custom-scripts', get_template_directory_uri() . '/assets/js/custom-scripts.js', array(), '2.0.0', 'true' );

        wp_localize_script( 'custom-scripts', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    }
}

new require_assets();