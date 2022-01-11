<?php
/* Template Name: Approve Ads Admins */

use Timber\Timber;
$current_user_id= get_current_user_id();
$current_user=get_user_by('id',$current_user_id);
$current_user_roles=$current_user->roles;

if(in_array('administrator',$current_user_roles)){
    $args=array(
        'post_type'=>'ads',
        'post_status'=>'draft',
    );
    $draft_ads=Timber::get_posts($args);
    $context          = Timber::context();
    $context['draft_ads']=$draft_ads;
    $templates        = array( 'views/page-templates/page-approve-ads.twig' );

    Timber::render( $templates, $context );
}else{
    wp_safe_redirect(get_home_url());
}
