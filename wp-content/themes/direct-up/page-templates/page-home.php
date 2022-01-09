<?php
/* Template Name: Homepage */

use Timber\Timber;

$context          = Timber::context();
$context['page_title']=get_the_title();
$templates        = array( 'views/page-templates/page-home.twig' );
$context['page_add_new_ad'] = get_permalink(get_page_by_path('add-new-ad'));

// Loop Ads

$featured_args= array(
  'post_type'=>'ads',
  'posts_per_page'=>-1,
'meta_key'		=> 'up_ad',
'meta_value'	=> true,
);
$ads_posts=Timber::get_posts($featured_args);

$context['features_ads_posts'] = $ads_posts;

// Loop categories for services

$services_categories = get_terms( 'services_categories',array('hide_empty'=>0));

$context['services_categories'] = $services_categories;
$args = array(
    'post_type' => 'services',
    'posts_per_page' => -1,
);
$context['services']=Timber::get_posts($args);

$args = array(
    'post_status' =>'publish',
    'orderby'       =>  'post_date',
    'order'         =>  'DESC',
    'posts_per_page' => -1,
    'post_type' => 'ads',
);
$context['ads']= Timber::query_posts($args);


$user= wp_get_current_user();
$user_name=$user->data->user_login;
$first_name=get_user_meta( $user->data->ID, 'first_name', true);
$second_name=get_user_meta( $user->data->ID, 'last_name', true);
$user_email = $user->data->user_email;
$user_membership_status = 'عضوية مجانية';
$user_phone_number = get_user_meta( $user->data->ID, 'user_phone', true);
$user_profile_pic = get_user_meta( $user->data->ID, 'user_pic', true);

$context['user_data']=array(
    'ID'=>$user->ID,
    'user_name'=>$user_name,
    'first_name'=>$user_name,
    'user_email'=>$user_email,
    'user_membership_status'=>$user_membership_status,
    'user_phone_number'=>$user_phone_number,
    'user_profile_pic'=>$user_profile_pic,
);

Timber::render( $templates, $context );