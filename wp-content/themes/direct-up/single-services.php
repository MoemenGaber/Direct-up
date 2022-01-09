<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

$post_categories= get_the_terms($timber_post->ID,'ad_categories');

$car_ad_description=get_field('car_ad_description',$timber_post->ID);
$images=get_field('images',$timber_post->ID);
$ad_details=array(
    'ad_description'=>$car_ad_description,
    'images_gallery'=>$images,

);

$context['ad_details']=$ad_details;

$templates        = array( 'views/single-services.twig' );


Timber::render( $templates, $context );
