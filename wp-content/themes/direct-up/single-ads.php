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
$templates        = array( 'views/single-ads.twig' );
$context['user_data_id']=get_current_user_id();

$post_categories= get_the_terms($timber_post->ID,'ad_categories');
if(has_term('cars','ad_categories',$timber_post)){
    $context['ad_type'] = 'cars';
}elseif (has_term('akar','ad_categories',$timber_post)){
    $context['ad_type'] = 'akar';
}elseif (has_term('equipment','ad_categories',$timber_post)){
    $context['ad_type'] = 'equipment';
}elseif(has_term('mobile','ad_categories',$timber_post)){
    $context['ad_type']='mobile';
}elseif(has_term('video-games','ad_categories',$timber_post)){
    $context['ad_type']='equipment';
}
if($context['ad_type'] == 'cars'){
    $ad_description=get_field('ad_description',$timber_post->ID);
    $car_type=get_field('car_type',$timber_post->ID);
    $car_sub_model=get_field('car_sub_model',$timber_post->ID);
    $car_year=get_field('car_year',$timber_post->ID);
    $car_status=get_field('car_status',$timber_post->ID);
    $car_kilometers=get_field('car_kilometers',$timber_post->ID);
    $car_gear_type=get_field('car_gear_type',$timber_post->ID);
    $car_petrol_type=get_field('car_petrol_type',$timber_post->ID);
    $car_extra_addons=get_field('car_extra_addons',$timber_post->ID);
    $car_payment=get_field('car_payment',$timber_post->ID);
    $car_price=get_field('ad_price',$timber_post->ID);
    $car_city=get_field('ad_city',$timber_post->ID);
    $images=get_field('images',$timber_post->ID);
    $ad_details=array(
        'ad_description'=>$ad_description,
        'car_type'=>$car_type,
        'car_sub_model'=>$car_sub_model,
        'car_year'=>$car_year,
        'car_status'=>$car_status,
        'car_killometers'=>$car_kilometers,
        'car_gear'=>$car_gear_type,
        'car_petrol'=>$car_petrol_type,
        'car_addons'=>$car_extra_addons,
        'car_payment'=>$car_payment,
        'ad_price'=>$car_price,
        'ad_city'=>$car_city,
        'images_gallery'=>$images,

    );
}elseif($context['ad_type'] == 'akar'){
    $ad_description=get_field('ad_description',$timber_post->ID);
    $akar_city=get_field('ad_city',$timber_post->ID);
    $akar_type=get_field('akar_type',$timber_post->ID);
    $akar_size=get_field('akar_size',$timber_post->ID);
    $akar_price=get_field('ad_price',$timber_post->ID);
    $akar_payment_type=get_field('akar_payment_type',$timber_post->ID);

    $images=get_field('images',$timber_post->ID);
    $ad_details=array(
        'ad_description'=>$ad_description,
        'ad_city'=> $akar_city,
        'akar_type'=>$akar_type,
        'akar_size'=>$akar_size,
        'akar_price'=>$akar_price,
        'akar_payment_type'=>$akar_payment_type,
        'images_gallery'=>$images,

    );
}elseif($context['ad_type'] == 'equipment') {
    $ad_description=get_field('ad_description',$timber_post->ID);
    $akar_city=get_field('ad_city',$timber_post->ID);
    $equipment_type=get_field('equipment_type',$timber_post->ID);
    $equipment_storage=get_field('equipment_storage',$timber_post->ID);
    $equipment_price=get_field('ad_price',$timber_post->ID);
    $equipment_payment_type=get_field('equipment_payment_type',$timber_post->ID);
    $contact_number=get_field('contact_number',$timber_post->ID);
    $whatsapp_number=get_field('whatsapp_number',$timber_post->ID);
    $ad_price = get_field('ad_price',$timber_post->ID);
    $images=get_field('images',$timber_post->ID);
    $ad_details=array(
        'ad_description'=>$ad_description,
        'ad_price'=>$ad_price,
        'ad_city'=> $akar_city,
        'equipment_type'=>$equipment_type,
        'equipment_storage'=>$equipment_storage,
        'equipment_price'=>$equipment_price,
        'equipment_payment_type'=>$equipment_payment_type,
        'images_gallery'=>$images,

    );
}elseif($context['ad_type']=='mobile'){
    $ad_description=get_field('ad_description',$timber_post->ID);
    $ad_city=get_field('ad_city',$timber_post->ID);
    $ad_price = get_field('ad_price',$timber_post->ID);
    $ad_contact_number=get_field('ad_contact_number',$timber_post->ID);
    $ad_whatsapp_number=get_field('ad_whatsapp_number',$timber_post->ID);
    $mobile_brand=get_field('mobile_brand',$timber_post->ID);
    $mobile_model=get_field('mobile_model',$timber_post->ID);
    $mobile_storage=get_field('mobile_storage',$timber_post->ID);
    $mobile_color=get_field('mobile_color',$timber_post->ID);
    $mobile_status=get_field('mobile_status',$timber_post->ID);
    $images=get_field('images',$timber_post->ID);
    $ad_details=array(
        'ad_description'=>$ad_description,
        'ad_price'=>$ad_price,
        'ad_whatsapp_number'=>$ad_whatsapp_number,
        'mobile_brand'=>$mobile_brand,
        'mobile_model'=>$mobile_model,
        'mobile_color'=>$mobile_color,
        'mobile_status'=>$mobile_status,
        'mobile_storage'=>$mobile_storage,
        'images_gallery'=>$images,

    );
}
$context['contact_number']=get_field('ad_contact_number');
$key = 'ads_views_count';
$post_id = get_the_ID();
$count = (int) get_post_meta( $post_id, $key, true );
$count++;
update_post_meta( $post_id, $key, $count );

$context['views_count']=get_post_meta(get_the_ID(),'ads_views_count',true);

$context['ad_details']=$ad_details;

Timber::render( $templates, $context );
