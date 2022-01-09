<?php
/* Template Name: Thank you */

$context = Timber::context();
$context['page_title']=get_the_title();

$templates = array('views/page-templates/page-thank-you.twig');


if(isset($_POST['submit'])){

// car ad

$ad_title = $_POST['ad-title'];
$ad_description = $_POST['ad-description'];
$ad_price = $_POST['ad-price'];
$ad_city = $_POST['ad-city'];

$car_type= $_POST['car-type'];
$car_model= $_POST['car-model'];
$car_year= $_POST['car-year'];
$car_status= $_POST['car-status'];
$car_kilometers=$_POST['car-kilometers'];
$car_gear=$_POST['car-gear'];
$car_petrol_type=$_POST['car-petrol-type'];
$car_addons=$_POST['car-addons'];
$car_payment=$_POST['car-payment'];

// Mobile Ad

$mobile_brand=$_POST['mobile-type'];
$mobile_model = $_POST['mobile-model'];
$mobile_year = $_POST['mobile-year'];
$mobile_status = $_POST['mobile-status'];
$mobile_storage = $_POST['mobile-storage'];
$mobile_color = $_POST['mobile-color'];
$mobile_payment = $_POST['mobile-payment'];

// General
    $parent_category = $_POST['final_parent_cat'];
    $sub_category = $_POST['final_sub_cat'];

$hierarchical_tax = array($parent_category,$sub_category); // Array of tax ids.

$post_arr = array(
    'post_title'   => $ad_title,
    'post_status'  => 'draft',
    'post_author'  => get_current_user_id(),
    'post_type' => 'ads',
    'tax_input'    => array(
        'ad_categories'     => $hierarchical_tax,
    ),
);
$post_id = wp_insert_post( $post_arr );

// Car Fields
update_field('car_type',$car_type,$post_id);
update_field('car_sub_model',$car_model,$post_id);
update_field('car_year',$car_year,$post_id);
update_field('car_status',$car_status,$post_id);
update_field('car_kilometers',$car_kilometers,$post_id);
update_field('car_gear_type',$car_gear,$post_id);
update_field('car_petrol_type',$car_petrol_type,$post_id);
update_field('car_extra_addons',$car_addons,$post_id);
update_field('car_payment',$car_payment,$post_id);
update_field('ad_price',$ad_price,$post_id);
update_field('ad_city',$ad_city,$post_id);

// Mobile Fields
update_field('mobile_brand',$mobile_brand,$post_id);
update_field('mobile_city',$ad_city,$post_id);
update_field('mobile_model',$mobile_model,$post_id);
update_field('mobile_storage',$mobile_storage,$post_id);
update_field('mobile_color',$mobile_color,$post_id);
update_field('mobile_status',$mobile_status,$post_id);

// Ganeral

update_field('ad_description',$ad_description,$post_id);
$user_phone=get_user_meta(get_current_user_id(),'user_phone',true);
update_field('contact_number',$user_phone,$post_id);


    upload_images_to_ad($post_id);


}else{
    wp_safe_redirect(get_home_url());
}

Timber::render($templates, $context);