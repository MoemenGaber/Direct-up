<?php

//function get_sub_categories_new_ad(){
//$parent_id=$_POST['category_id'];
//    $termchildren = get_terms( 'ad_categories',array('hide_empty'=>0,'parent'=>$parent_id));
//    echo json_encode($termchildren);
//die();
//}
//
//add_action("wp_ajax_get_sub_categories_new_ad", "get_sub_categories_new_ad");
//add_action("wp_ajax_nopriv_get_sub_categories_new_ad", "get_sub_categories_new_ad");
//



function upload_images_to_ad($post_id){
    $gallery= array();

    if ( !function_exists('wp_handle_upload') ) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    $files= $_FILES['images-gallery'];
    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            $file = array(
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            );

            $_FILES = array("upload_attachment" => $file);
            foreach ($_FILES as $file) {
                    $movefile = wp_handle_upload( $file, array('test_form' => false) );
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
                        array_push($gallery,$attach_id);
                    }
                }
                set_post_thumbnail($post_id,$gallery[0]);
                update_field('images', $gallery, $post_id);
            }
    }
}



// Login

function redirect_login_page() {

    $login_page  = home_url( '/login' );  
    $page_viewed = basename($_SERVER['REQUEST_URI']);  
 
    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {  
        wp_redirect($login_page);  
        exit;  
    }  
}  
add_action('init','redirect_login_page');

function login_failed() {

    $login_page  = home_url( '/login' );  
    wp_redirect( $login_page . '?user-login=failed' );  
    exit;  
}  
add_action( 'wp_login_failed', 'login_failed' );  
 
//function verify_username_password( $user, $username, $password ) {
//
//    $login_page  = home_url( '/login' );
//    if( $username == "" || $password == "" ) {
//        wp_redirect( $login_page . "?user-login=empty" );
//        exit;
//    }
//}
//add_filter( 'authenticate', 'verify_username_password', 1, 3);

function logout_page() {

    $login_page  = home_url( '/login' );  
    wp_redirect( $login_page . "?user-login=false" );  
    exit;  
}  
add_action('wp_logout','logout_page');



function get_user_profile_pic_name( $return_name = false ) {

    $current_user = wp_get_current_user();

    if ( ! ( is_user_logged_in() && $current_user && ( $current_user instanceof WP_User ) ) ) {
        return;
    }

    // get user name
    if ( ! empty( $current_user->user_firstname ) && ! empty( $current_user->user_lastname ) ) {
        $name      = substr( $current_user->user_firstname, 0, 1 ) . substr( $current_user->user_lastname, 0, 1 );
        $full_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
    } else {
        $name      = substr( $current_user->display_name, 0, 2 );
        $full_name = $current_user->display_name;
    }

    // get user avatar
    if ( ! empty( get_avatar_url( $current_user->ID, array( 'size' => '100' ) ) ) ) {
        $output = '<img src="' . get_avatar_url( $current_user->ID, array( 'size' => '100' ) ) . '" alt="">';
    } else {
        $output = $name;
    }

    // return user name instead of avatar
    if ( $return_name ) {
        return $full_name;
    } else {
        // return avatar by default
        return $output;
    }

}

function add_post_to_favorites(){
    $user_id = (int)$_POST['userID'];
    $post_id = (int)$_POST['postID'];
    $old_user_posts = get_user_meta($user_id,'favorite_ads',true);
    if(!$old_user_posts){
        $posts=array();
        array_push($posts,$post_id);
        add_user_meta($user_id,'favorite_ads',$posts);
    }
    if(!empty($old_user_posts) && in_array($post_id,$old_user_posts)){
            $key = array_search($post_id, $old_user_posts);
            unset($old_user_posts[$key]);
            update_user_meta($user_id,'favorite_ads',$old_user_posts);
        }else{
        array_push($old_user_posts,$post_id);
        update_user_meta($user_id,'favorite_ads',$old_user_posts);
    }


    var_dump(get_user_meta($user_id,'favorite_ads',true));
    die();
}
add_action("wp_ajax_add_post_to_favorites", "add_post_to_favorites");
add_action("wp_ajax_nopriv_add_post_to_favorites", "add_post_to_favorites");
