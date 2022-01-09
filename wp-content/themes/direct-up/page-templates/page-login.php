<?php
/* Template Name: Login page */
if(!is_user_logged_in()){
$context          = Timber::context();
    $context['page_title']=get_the_title();
$templates        = array( 'views/page-templates/page-login.twig' );
$context['login_page']=get_permalink(get_page_by_path('login'));


$login_errors = (isset($_GET['user-login']) ) ? $_GET['user-login'] : 0;     
   
if ( $_POST['action'] == 'log-in' ) {
   
    # Submit the user login inputs
    $login = wp_login( $_POST['user-name'], $_POST['password'] );
    $login = wp_signon( array( 'user_login' => $_POST['user-name'], 'user_password' => $_POST['password'], 'remember' => $_POST['remember-me'] ), false );
       
    # Redirect to account page after successful login.
    if ( $login->ID ) {
        wp_redirect( home_url('profile') );      
    }  
}


if ( $login_errors === "failed" ) {  
    $context['error']='<p class="input-error" style="color:red;text-align:center">مشكلة في كلمة السر أو اسم المستخدم</p>';
} elseif ( $login_errors === "empty" ) {  
    $context['error']='<p class="input-error" style="color:red;text-align:center">كلمة السر أو اسم المستخدم فارغ</p>';
} elseif ( $login_errors === "false" ) {  
    $context['error']='<p class="input-error" style="color:red;text-align:center">تم تسجيل الخروج</p>';
}

$register_page=get_permalink(get_page_by_path('register'));
$context['register']=$register_page;

Timber::render( $templates, $context );
}else{
    wp_safe_redirect(get_home_url());
}