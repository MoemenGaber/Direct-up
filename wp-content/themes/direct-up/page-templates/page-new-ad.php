<?php
/* Template Name: New Ad */

if(is_user_logged_in()){

$context          = Timber::context();
    $context['page_title']=get_the_title();

$templates        = array( 'views/page-templates/page-new-ad.twig' );

$ad_categories = get_terms( 'ad_categories',array('hide_empty'=>0));

$context['ad_categories'] = $ad_categories;

    $user= wp_get_current_user();
    $user_name=$user->data->user_login;
    $context['user_name']=$user_name;
    $user_phone_number = get_user_meta( $user->data->ID, 'user_phone', true);
    $context['user_phone']= $user_phone_number;
    $context['ad_cities_options']=get_field('ad_cities','options');
    Timber::render( $templates, $context );
}else{
    $login_page=get_permalink(get_page_by_path('login'));
    wp_safe_redirect($login_page);
}