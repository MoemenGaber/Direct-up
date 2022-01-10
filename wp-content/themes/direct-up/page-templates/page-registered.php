<?php
/* Template Name: Registered */

$registered_user = (isset($_GET['registered']) ) ? $_GET['registered'] : 0;

if ( $registered_user === "success" ) {

    $context          = Timber::context();
    $templates        = array( 'views/page-templates/page-registered.twig' );
    $context['page_title']=get_the_title();

    Timber::render( $templates, $context );

    $login_page=get_permalink(get_page_by_path('login'));
    $homepage=get_home_url();
    header( "refresh:3;url=$homepage" );


}else{
    wp_safe_redirect(get_home_url());
}