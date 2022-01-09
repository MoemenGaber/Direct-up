<?php
/* Template Name: Profile */

$user= wp_get_current_user();

if(is_user_logged_in()){

    if(isset($_POST['submit-profile'])){
        $logo_image=$_FILES['user_profile_pic'];
        if ( !function_exists('wp_handle_upload') ) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        $_FILES = array("upload_attachment" => $logo_image);
        $movefile = wp_handle_upload( $logo_image, array('test_form' => false) );
        if ( $movefile && !isset($movefile['error']) ) {
            $wp_upload_dir = wp_upload_dir();
            $attachment = array(
                'guid' => $wp_upload_dir['url'] . '/' . basename($movefile['file']),
                'post_mime_type' => $movefile['type'],
                'post_title' => preg_replace('/\.[^.]+$/', "", basename($movefile['file'])),
                'post_content' => "",
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment($attachment, $movefile['file']);
            update_user_meta($user->ID,'user_pic',$attach_id);
        }
    }




$context          = Timber::context();
$context['page_title']=get_the_title();

$templates        = array( 'views/page-templates/page-profile.twig' );

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

    $args = array(
        'author'        =>  $user->data->ID,
        'post_status' =>'draft',
        'orderby'       =>  'post_date',
        'order'         =>  'ASC',
        'posts_per_page' => -1,
        'post_type' => 'ads',
    );
    $context['user_draft_ads']= Timber::query_posts($args);



    $args = array(
        'author'        =>  $user->data->ID,
        'post_status' =>'publish',
        'orderby'       =>  'post_date',
        'order'         =>  'ASC',
        'posts_per_page' => -1,
        'post_type' => 'ads',
    );
    $context['user_published_ads']= Timber::query_posts($args);

    $favorite_ids= get_user_meta($user->data->ID,'favorite_ads',true);
    if(($favorite_ids)) {
        $favorite_args = array(
            'author' => $user->data->ID,
            'post__in' => $favorite_ids,
            'post_status' => 'publish',
            'orderby' => 'post_date',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'post_type' => 'ads',
        );
        $context['user_favourite_ads'] = Timber::query_posts($favorite_args);
    }

    $context['user_posts_count'] = count_user_posts($user->ID,'ads');

    $all_user_posts = array(
        'author' => $user->data->ID,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post_type' => 'ads',
    );
    $views_count_posts=Timber::query_posts($all_user_posts);
    foreach ($views_count_posts as $post){
        $views +=(int)get_post_meta($post->ID,'ads_views_count',true);
    }
    $context['views_count']=$views;


}else{
    wp_safe_redirect(get_permalink(get_page_by_path('login')));
}
Timber::render( $templates, $context );