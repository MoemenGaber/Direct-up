<?php
/* Template Name: Edit Ad */

if(is_user_logged_in()){

$context          = Timber::context();
    $context['page_title']=get_the_title();

$templates        = array( 'views/page-templates/page-edit-ad.twig' );

$ad_id= $_GET['post_id'];

$ad_categories = get_the_terms($ad_id,'ad_categories')[0];
$ad_category_slug=$ad_categories->slug;
$context['ad_category_slug']=$ad_category_slug;
$context['post']=Timber::get_post($ad_id);
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