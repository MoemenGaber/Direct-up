<?php
/* Template Name: Register */

if(is_user_logged_in()){
    wp_safe_redirect(get_home_url());
}

$context          = Timber::context();
$context['page_title']=get_the_title();

$templates        = array( 'views/page-templates/page-register.twig' );

$context['login_page']= get_permalink(get_page_by_path('login'));
$context['register_page']= get_permalink(get_page_by_path('register'));

$register_page=get_permalink(get_page_by_path('register'));

$register_thank_you=get_permalink(get_page_by_path('user-registered'));

$login_errors = (isset($_GET['user-register']) ) ? $_GET['user-register'] : 0;

if ( $login_errors === "empty-password" ) {
    $context['register_error']='<p class="input-error" style="color:red;text-align:center">يجب كتابة كلمة السر مرتين للتأكيد</p>';

}elseif($login_errors === "username-unavailable"){
    $context['register_error']='<p class="input-error" style="color:red;text-align:center">اسم المستخدم غير متاح<br>استخدم اسم آخر مع حروف إنجليزية وبدون                                                            مسافات</p>';

}elseif ($login_errors === "email-unavailable"){
    $context['register_error']='<p class="input-error" style="color:red;text-align:center">البريد الإلكتروني مسجل مسبقاً</p>';
}elseif ($login_errors === "wrong-password") {
    $context['register_error']='<p class="input-error" style="color:red;text-align:center">كلمة سر خاطئة</p>';
}



if(isset($_POST['submit-registered-user'])){
   $username=$_POST['register-username'];
   $email = $_POST['register-email'];
   $user_phone = $_POST['register-phone'];
   $user_pass = $_POST['register-password'];
   $user_pass_confirm = $_POST['confirm-password'];

   if(empty($user_pass_confirm) || empty($user_pass)){
       wp_safe_redirect($register_page."?user-register=empty-password");
       exit();
   }
   if($user_pass !== $user_pass_confirm){
       wp_safe_redirect($register_page."?user-register=wrong-password");
       exit();
   }
   if(username_exists($username) || ! preg_match('/^[a-z0-9 .}, !@#$%^&*()_+|\';?><-]+$/i',  $username)){
       wp_safe_redirect($register_page."?user-register=username-unavailable");
       exit();
   }
    if(email_exists($email)){
        wp_safe_redirect($register_page."?user-register=email-unavailable");
        exit();
    }
    $user_data=array(
        'user_login'    =>  $username,
        'user_email'    =>  $email,
        'user_pass'     =>  $user_pass,
    );
    $id=wp_insert_user($user_data);
    update_user_meta($id,'user_phone',$user_phone);
    wp_update_user( array ('ID' => $id, 'role' => 'subscriber') ) ;
    wp_safe_redirect(get_home_url().'/user-registered'.'?registered=success');

}

Timber::render( $templates, $context );


